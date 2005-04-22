<?php
/***************************************************************************
 *                              smtp.php
 *                       -------------------
 *   begin                : Wed May 09 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *   modified by          : fboender (Mar 11, 2005: Adapted to PROMS style)
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('SMTP_INCLUDED', 1);

function server_parse($socket, $response, $line = __LINE__) { 
	while (substr($server_response, 3, 1) != ' ') {
		if (!($server_response = fgets($socket, 256))) { 
			return("Couldn't get mail server response codes");
		} 
	} 

	if (!(substr($server_response, 0, 3) == $response)) { 
		return("Ran into problems sending Mail. Response: $server_response");
	} 

	return("");
}

// Replacement or substitute for PHP's mail command
function smtpmail($mail_to, $subject, $message, $headers = '') {
	// Fix any bare linefeeds in the message to make it RFC821 Compliant.
	$message = preg_replace("#(?<!\r)\n#si", "\r\n", $message);

	if ($headers != '') {
		if (is_array($headers)) {
			if (sizeof($headers) > 1) {
				$headers = join("\n", $headers);
			} else {
				$headers = $headers[0];
			}
		}
		$headers = chop($headers);

		// Make sure there are no bare linefeeds in the headers
		$headers = preg_replace('#(?<!\r)\n#si', "\r\n", $headers);

		// Ok this is rather confusing all things considered,
		// but we have to grab bcc and cc headers and treat them differently
		// Something we really didn't take into consideration originally
		$header_array = explode("\r\n", $headers);
		@reset($header_array);

		$headers = '';
		while(list(, $header) = each($header_array)) {
			if (preg_match('#^cc:#si', $header)) {
				$cc = preg_replace('#^cc:(.*)#si', '\1', $header);
			} else 
			if (preg_match('#^bcc:#si', $header)) {
				$bcc = preg_replace('#^bcc:(.*)#si', '\1', $header);
				$header = '';
			}
			$headers .= ($header != '') ? $header . "\r\n" : '';
		}

		$headers = chop($headers);
		$cc = explode(',', $cc);
		$bcc = explode(',', $bcc);
	}

	if (trim($subject) == '') {
		return("No email Subject specified");
	}

	if (trim($message) == '') {
		return("Email message was blank");
	}

	$mail_to_array = explode(',', $mail_to);

	// Ok we have error checked as much as we can to this point let's get on
	// it already.
	if( !$socket = fsockopen(SMTP_HOST, 25, $errno, $errstr, 20) ) {
		return("Could not connect to smtp host : $errno : $errstr");
	}

	// Wait for reply
	$error = server_parse($socket, "220", __LINE__);
	if (!empty($error)) {
		return($error);
	}

	// Do we want to use AUTH?, send RFC2554 EHLO, else send RFC821 HELO
	// This improved as provided by SirSir to accomodate
	//if(!empty(constant(SMTP_USERNAME)) && !empty(constant(SMTP_PASSWORD)) ) { 
	if( defined(SMTP_USERNAME) && defined(SMTP_PASSWORD) ) { 
		fputs($socket, "EHLO " . SMTP_HOST . "\r\n");
		$error = server_parse($socket, "250", __LINE__);
		if (!empty($error)) {
			return($error);
		}

		fputs($socket, "AUTH LOGIN\r\n");
		$error = server_parse($socket, "334", __LINE__);
		if (!empty($error)) {
			return($error);
		}

		fputs($socket, base64_encode(SMTP_USERNAME) . "\r\n");
		$error = server_parse($socket, "334", __LINE__);
		if (!empty($error)) {
			return($error);
		}

		fputs($socket, base64_encode(SMTP_PASSWORD) . "\r\n");
		$error = server_parse($socket, "235", __LINE__);
		if (!empty($error)) {
			return($error);
		}
	} else {
		fputs($socket, "HELO " . SMTP_HOST . "\r\n");
		$error = server_parse($socket, "250", __LINE__);
		if (!empty($error)) {
			return($error);
		}
	}

	// From this point onward most server response codes should be 250
	// Specify who the mail is from....
	fputs($socket, "MAIL FROM: <" . PROMS_EMAIL . ">\r\n");
	$error = server_parse($socket, "250", __LINE__);
	if (!empty($error)) {
		return($error);
	}

	// Specify each user to send to and build to header.
	@reset($mail_to_array);
	while(list(, $mail_to_address) = each($mail_to_array)) {
		// Add an additional bit of error checking to the To field.
		$mail_to_address = trim($mail_to_address);
		if (preg_match('#[^ ]+\@[^ ]+#', $mail_to_address)) {
			fputs($socket, "RCPT TO: $mail_to_address\r\n");
			$error = server_parse($socket, "250", __LINE__);
			if (!empty($error)) {
				return($error);
			}
		}
		$to_header .= (($to_header !='') ? ', ' : '') . "$mail_to_address";
	}

	// Ok now do the CC and BCC fields...
	@reset($bcc);
	while(list(, $bcc_address) = each($bcc)) {
		// Add an additional bit of error checking to bcc header...
		$bcc_address = trim($bcc_address);
		if (preg_match('#[^ ]+\@[^ ]+#', $bcc_address))
		{
			fputs($socket, "RCPT TO: $bcc_address\r\n");
			$error = server_parse($socket, "250", __LINE__);
			if (!empty($error)) {
				return($error);
			}
		}
	}

	@reset($cc);
	while(list(, $cc_address) = each($cc)) {
		// Add an additional bit of error checking to cc header
		$cc_address = trim($cc_address);
		if (preg_match('#[^ ]+\@[^ ]+#', $cc_address)) {
			fputs($socket, "RCPT TO: $cc_address\r\n");
			$error =server_parse($socket, "250", __LINE__);
			if (!empty($error)) {
				return($error);
			}
		}
	}

	// Ok now we tell the server we are ready to start sending data
	fputs($socket, "DATA\r\n");

	// This is the last response code we look for until the end of the message.
	$error = server_parse($socket, "354", __LINE__);
	if (!empty($error)) {
		return($error);
	}

	// Send the Subject Line...
	fputs($socket, "Subject: $subject\r\n");

	// Now the To Header.
	fputs($socket, "To: $to_header\r\n");

	// Now any custom headers....
	fputs($socket, "$headers\r\n\r\n");

	// Ok now we are ready for the message...
	fputs($socket, "$message\r\n");

	// Ok the all the ingredients are mixed in let's cook this puppy...
	fputs($socket, ".\r\n");
	$error = server_parse($socket, "250", __LINE__);
	if (!empty($error)) {
		return($error);
	}

	// Now tell the server we are done and close the socket...
	fputs($socket, "QUIT\r\n");
	fclose($socket);

	return TRUE;
}

?>
