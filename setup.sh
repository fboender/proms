#!/bin/sh
#
# PROMS setup/installation tool
#
# Copyright (C), 2003-2005 Ferry Boender. Released under the General Public
# License For more information, see the COPYING file supplied with this
# program.
#

#----------------------------------------------------------------------------
# input and output functions
#----------------------------------------------------------------------------
function input_line() {
	if [ "$HAS_DIALOG" == "1" ]; then
		LINE=`dialog --no-cancel --title "${1}" --stdout --inputbox "${2}" 0 0 "${4}"`;
	else
		clear
		echo -e "${1}\n---------------------------------------------------------------------------\n"
		if [ "$HAS_FMT" == "1" ]; then
			echo -e "${2}\n" | fmt
		else
			echo -e "${2}\n"
		fi
		read -p"[${4}]" LINE
		if [ -z $LINE ]; then
			LINE=${4}
		fi
	fi

	eval $3='$LINE'
}

function input_password() {
	if [ "$HAS_DIALOG" == "1" ]; then
		LINE=`dialog --no-cancel --title "${1}" --stdout --passwordbox "${2}" 0 0 "${4}"`;
		read
	else
		clear
		echo -e "${1}\n---------------------------------------------------------------------------\n"
		if [ "$HAS_FMT" == "1" ]; then
			echo -e "${2}\n" | fmt
		else
			echo -e "${2}\n"
		fi
		read -s -p"[${4}]" LINE
		if [ -z $LINE ]; then
			LINE=${4}
		fi
	fi
	
	eval $3='$LINE'
}

function input_yesno() {
	if [ "$HAS_DIALOG" == "1" ]; then
		dialog --title "${1}" --yesno "${2}" 0 0;
		eval $3='$?';
	else
		clear
		echo -e "${1}\n---------------------------------------------------------------------------\n"
		if [ "$HAS_FMT" == "1" ]; then
			echo -e "${2}\n" | fmt
		else
			echo -e "${2}\n"
		fi
		read -n1 -p"[Y/N]" YESNO
		echo -e "\n"
		if [ "$YESNO" == "n" -o "$YESNO" == "N" ]; then
			eval $3='1';
		else
			eval $3='0';
		fi
	fi
}

function output_text() {
	if [ "$HAS_DIALOG" == "1" ]; then
		dialog --title "${1}" --msgbox "$2" 0 0;
	else 
		clear
		echo -e "${1}\n---------------------------------------------------------------------------\n"
		if [ "$HAS_FMT" == "1" ]; then
			echo -e "${2}\n" | fmt
		else
			echo -e "${2}\n"
		fi
		read -p"-- press enter to continue --"
	fi
}

#----------------------------------------------------------------------------
# Menus
#----------------------------------------------------------------------------
function actions_menu() {
	TMP_FILE=`tempfile`

	DONE_TMP=$DONE
	DONE=0
	while [ "$DONE" == "0" ]; do # Settings check 
		while [ "$DONE" == "0" ]; do
			if [ "$HAS_DIALOG" == "1" ]; then
				dialog --ok-label Select --title "PROMS Setup" --menu "Please choose an action" 22 60 15 \
					1 "Read settings from previous installation" \
					2 "View / Change settings" \
					3 "Change database and webserver settings for setup" \
					0 "Done (start installation)" 2>$TMP_FILE
				if [ "$?" == "1" ]; then
					rm $TMP_FILE
					exit
				else
					ACTION=`cat $TMP_FILE`
				fi

			else
				clear
				echo -e "PROMS Setup\n---------------------------------------------------------------------------\n${2}\n"
				echo "1 - Read settings from previous installation"
				echo "2 - View / Change settings"
				echo "3 - Change database and webserver settings for setup"
				echo
				echo "0 - Done (start installation)"
				echo "C - Cancel (stop installation)"
				echo
				read -p"Choice: " ACTION
			fi

			case "$ACTION" in
				1) settings_get_upgrade ;;
				2) settings_change_menu ;;
				3) setup_db_change_menu ;;
				C) rm $TMP_FILE; exit ;;
				c) rm $TMP_FILE; exit ;;
				0) ACTION=""; DONE=1 ;;
			esac

		done

		settings_check # Sets DONE to 0 if some settings are missing.
	done

	DONE=$DONE_TMP

	rm $TMP_FILE
}

function settings_change_menu() {
	TMP_FILE=`tempfile`

	DONE_TMP=$DONE
	DONE=0

	while [ "$DONE" == "0" ]; do
		if [ "$HAS_DIALOG" == "1" ]; then
			dialog --cancel-label Done --ok-label Change --title "Change settings" --menu "Please choose a setting to change" 22 60 16 \
				"Install location" "$S_SETUP_PATH" \
				"DB hostname" "$S_PROMS_DB_HOSTNAME" \
				"DB username" "$S_PROMS_DB_USERNAME" \
				"DB password" "$S_PROMS_DB_PASSWORD"\
				"DB name" "$S_PROMS_DB_NAME"\
				"E-mail address" "$S_PROMS_LIST_EMAIL" \
				"SMTP hostname" "$S_PROMS_SMTP_HOSTNAME" \
				"SMTP port" "$S_PROMS_SMTP_PORT" \
				2>$TMP_FILE
			if [ "$?" == "1" ]; then
				DONE=1
				ACTION=""
			else 
				ACTION=`cat $TMP_FILE`
			fi
			
		else
			clear
			echo -e "Change settings\n---------------------------------------------------------------------------\n${2}\n"
			echo "1 - Install location : $S_SETUP_PATH"
			echo "2 - DB hostname      : $S_PROMS_DB_HOSTNAME"
			echo "3 - DB username      : $S_PROMS_DB_USERNAME"
			echo "4 - DB password      : $S_PROMS_DB_PASSWORD"
			echo "5 - DB name          : $S_PROMS_DB_NAME"
			echo "6 - E-mail address   : $S_PROMS_LIST_EMAIL"
			echo "7 - SMTP hostname    : $S_PROMS_SMTP_HOSTNAME"
			echo "8 - SMTP port        : $S_PROMS_SMTP_PORT"
			echo
			echo "0 - Done"
			echo
			read -p"Choice: " ACTION
			
			case "$ACTION" in
				1) ACTION="Install location";;
				2) ACTION="DB hostname"     ;;
				3) ACTION="DB username"     ;;
				4) ACTION="DB password"     ;;
				5) ACTION="DB name"         ;;
				6) ACTION="E-mail address"  ;;
				7) ACTION="SMTP hostname"   ;;
				8) ACTION="SMTP port"       ;;
				0) ACTION=""; DONE=1 ;;
			esac
		fi

		case "$ACTION" in
			"Install location" ) settings_change_installpath ;;
			"DB hostname"      ) settings_change_db_hostname ;;
			"DB username"      ) settings_change_db_username ;;
			"DB password"      ) settings_change_db_password ;;
			"DB name"          ) settings_change_db_name ;;
			"E-mail address"   ) settings_change_email ;;
			"SMTP hostname"    ) settings_change_smtp_hostname ;;
			"SMTP port"        ) settings_change_smtp_port ;;
			*) ;; 
		esac
	done

	DONE=$DONE_TMP

	rm $TMP_FILE
}

function setup_db_change_menu() {
	TMP_FILE=`tempfile`

	DONE_TMP=$DONE
	DONE=0

	while [ "$DONE" == "0" ]; do
		while [ "$DONE" == "0" ]; do
			if [ "$HAS_DIALOG" == "1" ]; then
				dialog --cancel-label Done --ok-label Change --title "Change DB & Web Access" --menu "Please choose a setting to change" 22 60 16 \
					"DB Admin hostname" "$S_SETUP_DB_HOSTNAME" \
					"DB Admin username" "$S_SETUP_DB_USERNAME" \
					"DB Admin password" "$S_SETUP_DB_PASSWORD"\
					"Webserver Username" "$S_SETUP_WEB_USERNAME"\
					"Webserver Groupname" "$S_SETUP_WEB_GROUPNAME"\
					2>$TMP_FILE
				if [ "$?" == "1" ]; then
					DONE=1
					ACTION=""
				else 
					ACTION=`cat $TMP_FILE`
				fi
			else
				clear
				echo -e "Change DB & Web Access\n---------------------------------------------------------------------------\n${2}\n"
				echo "1 - DB Admin hostname   : $S_SETUP_DB_HOSTNAME"
				echo "2 - DB Admin username   : $S_SETUP_DB_USERNAME"
				echo "3 - DB Admin password   : $S_SETUP_DB_PASSWORD"
				echo "4 - Webserver Username  : $S_SETUP_WEB_USERNAME"
				echo "5 - Webserver Groupname : $S_SETUP_WEB_GROUPNAME"
				echo
				echo "0 - Done"
				echo
				read -p"Choice: " ACTION
				case "$ACTION" in
					1) ACTION="DB Admin hostname"   ;;
					2) ACTION="DB Admin username"   ;;
					3) ACTION="DB Admin password"   ;;
					4) ACTION="Webserver Username"  ;;
					5) ACTION="Webserver Groupname" ;;
					0) ACTION=""; DONE=1 ;;
				esac
			fi

			case "$ACTION" in
				"DB Admin hostname"   ) setup_change_db_hostname ;;
				"DB Admin username"   ) setup_change_db_username ;;
				"DB Admin password"   ) setup_change_db_password ;;
				"Webserver Username"  ) setup_change_web_username;;
				"Webserver Groupname" ) setup_change_web_groupname;;
				*) ;; 
			esac
		done

		settings_check_mysql # Modifies $DONE
	done

	DONE=$DONE_TMP

	rm $TMP_FILE
}

#----------------------------------------------------------------------------
# Special function related to settings
#----------------------------------------------------------------------------
function settings_check_mysql() {
	mysql -u"$S_SETUP_DB_USERNAME" --password="$S_SETUP_DB_PASSWORD" -h "$S_SETUP_DB_HOSTNAME" -e"exit"

	if [ "$?" == "1" ]; then
		input_yesno "Database test" "Testing the database connection...\n\nDatabase connection FAILED\n\nDo you want to change the information?" "DONE";
	else
		output_text "Database test" "Testing the database connection...\n\nDatabase connection succesful."
		DONE=1
	fi
}

function settings_check() {
	ERRORS=""
	
	if [ -z "$S_PROMS_DB_HOSTNAME" ]; then
		ERRORS="$ERRORS\nNo database hostname was specified for PROMS to connect to."
	fi
	if [ -z "$S_PROMS_DB_USERNAME" ]; then
		ERRORS="$ERRORS\nNo database username was specified for PROMS to connect with."
	fi
	if [ -z "$S_PROMS_DB_PASSWORD" ]; then
		ERRORS="$ERRORS\nNo database password was specified for PROMS to connect with."
	fi
	if [ -z "$S_PROMS_DB_NAME" ]; then
		ERRORS="$ERRORS\nNo database name was specified for PROMS to connect to."
	fi
	if [ -z "$S_PROMS_SMTP_HOSTNAME" ]; then
		ERRORS="$ERRORS\nNo SMTP hostname was specified for PROMS to sent mail through."
	fi
	if [ -z "$S_PROMS_SMTP_PORT" ]; then
		ERRORS="$ERRORS\nNo SMTP port was specified for PROMS to sent mail through"
	fi
	if [ -z "$S_PROMS_LIST_EMAIL" ]; then
		ERRORS="$ERRORS\nNo E-mail address for PROMS was specified."
	fi

	if [ -z "$S_SETUP_PATH" ]; then
		ERRORS="$ERRORS\nNo installation location was specified."
	fi
	if [ -z "$S_SETUP_DB_HOSTNAME" ]; then
		ERRORS="$ERRORS\nNo hostname was specified for the setup database connection."
	fi
	if [ -z "$S_SETUP_DB_USERNAME" ]; then
		ERRORS="$ERRORS\nNo username was specified for the setup database connection."
	fi
	if [ -z "$S_SETUP_DB_PASSWORD" ]; then
		ERRORS="$ERRORS\nNo password was specified for the setup database connection."
	fi
	if [ -z "$S_SETUP_WEB_USERNAME" ]; then
		ERRORS="$ERRORS\nThe webserver's username was not specified."
	fi
	if [ -z "$S_SETUP_WEB_GROUPNAME" ]; then
		ERRORS="$ERRORS\nThe webserver's groupname was not specified."
	fi

	if [ -n "$ERRORS" ]; then 
		output_text "Incomplete setup" "Some settings are not complete:\n$ERRORS\n\nPlease go back and correct them"
		DONE=0
	fi
}

function settings_get_upgrade() {
	DONE_TMP=$DONE
	DONE=0
		
	while [ "$DONE" == "0" ]; do
		input_line "Previous settings" "If you already have a version of PROMS installed, the settings from that\ninstallation can be read and re-used.\n\nPlease enter the full path where the previous version of PROMS is installed." "S_PREV_PATH" "$S_PREV_PATH"
		S_PREV_PATH=`echo $S_PREV_PATH| sed -e 's/\/$//'`
		S_SETUP_PATH="$S_PREV_PATH"

		if [ -e "$S_PREV_PATH/settings.php" ]; then
			# Get settings from previous settings.php
			S_PREV_VERSION=`cat $S_PREV_PATH/settings.php | grep PROMS_VERSION | grep -v REF| cut -d"\"" -f4`
			S_PROMS_LIST_EMAIL=`cat $S_PREV_PATH/settings.php | grep PROMS_EMAIL | grep -v REF| cut -d"\"" -f4`
			S_PROMS_DB_HOSTNAME=`cat $S_PREV_PATH/settings.php | grep DB_HOSTNAME | grep -v REF| cut -d"\"" -f4`
			S_PROMS_DB_USERNAME=`cat $S_PREV_PATH/settings.php | grep DB_USERNAME | grep -v REF| cut -d"\"" -f4`
			S_PROMS_DB_PASSWORD=`cat $S_PREV_PATH/settings.php | grep DB_PASSWORD | grep -v REF| cut -d"\"" -f4`
			S_PROMS_DB_NAME=`cat $S_PREV_PATH/settings.php | grep DB_DATABASE | grep -v REF| cut -d"\"" -f4`
			S_PROMS_SMTP_HOSTNAME=`cat $S_PREV_PATH/settings.php | grep SMTP_HOST | grep -v REF| cut -d"\"" -f4`
			S_PROMS_SMTP_PORT=`cat $S_PREV_PATH/settings.php | grep SMTP_PORT | grep -v REF| cut -d"\"" -f4`

			output_text "Previous settings" "The settings from the previous installation have been read. Please review these settings to see if any need to be changed.";
			DONE="1"
		elif [ -e "$INSTALL_PATH/inc_common.php" ]; then
			# Get settings from previous inc_common.php (PROMS v0.8)
			S_PREV_VERSION="0.8"
			S_PROMS_DB_HOSTNAME=`cat $INSTALL_PATH/inc_common.php | grep "\\\$db_hostname" | grep -v "(" | cut -d"\"" -f2`
			S_PROMS_DB_USERNAME=`cat $INSTALL_PATH/inc_common.php | grep "\\\$db_username" | grep -v "(" | cut -d"\"" -f2`
			S_PROMS_DB_PASSWORD=`cat $INSTALL_PATH/inc_common.php | grep "\\\$db_password" | grep -v "(" | cut -d"\"" -f2`
			S_PROMS_DB_NAME=`cat $INSTALL_PATH/inc_common.php | grep "\\\$db_database" | grep -v "(" | cut -d"\"" -f2`
			# SMTP Stuff wasn't present in 0.8 yet, so we can safely skip this here.

			output_text "Previous settings" "The settings from the previous installation have been read. Please review these settings to see if any need to be changed.";
			DONE="1"
		else 
			input_yesno "Previous settings" "No previous installation of PROMS was found at the path specified. Do you want to specify a different path?" "DONE";
		fi
	done

	DONE=$DONE_TMP
}

function settings_get_defaults() {
	S_PREV_VERSION=""
	S_PREV_PATH=""

	S_SETUP_PATH="`grep -ih "documentroot" /etc/apache/* 2>/dev/null | grep -v "^[	 ]*#" | tail -n 1 | sed -e "s/^[	 ]*DocumentRoot[	 ]*//" -e "s/ $//" | sed -e "s/\/$//"`/proms"
	S_SETUP_DB_HOSTNAME="localhost"
	S_SETUP_DB_USERNAME="root"
	S_SETUP_DB_PASSWORD=""
	S_SETUP_WEB_USERNAME="`grep -ih -e "user[	 ]" /etc/apache/* 2>/dev/null | grep -v "^[	 ]*#" | tail -n 1 | sed -e "s/^[	 ]*User[	 ]*//" | sed -e "s/ $//" | sed -e "s/\/$//"`"
	S_SETUP_WEB_GROUPNAME="`grep -ih -e "group[	 ]" /etc/apache/* 2>/dev/null | grep -v "^[	 ]*#" | tail -n 1 | sed -e "s/^[	 ]*Group[	 ]*//" | sed -e "s/ $//" | sed -e "s/\/$//"`"

	S_PROMS_DB_HOSTNAME="localhost"
	S_PROMS_DB_USERNAME="proms"
	S_PROMS_DB_PASSWORD=""
	S_PROMS_DB_NAME="proms"
	S_PROMS_SMTP_HOSTNAME="localhost"
	S_PROMS_SMTP_PORT="25"
	S_PROMS_LIST_EMAIL="proms@`dnsdomainname`"

	if [ "$S_PROMS_LIST_EMAIL" == "proms@" ]; then
		S_PROMS_LIST_EMAIL="proms@`dnsdomainname -f`"
	fi
}

#----------------------------------------------------------------------------
# Setting manipulation functions
#----------------------------------------------------------------------------
function settings_change_installpath() {
	input_line "Installation path" "At which path do you want to install PROMS?" "S_SETUP_PATH" "$S_SETUP_PATH"
}

function settings_change_db_hostname() {
	input_line "Database" "PROMS needs to connect to the database server where PROMS' database is kept. For this it needs some information about the server.\n\nPlease enter the HOSTNAME of the server where the database is running." "S_PROMS_DB_HOSTNAME" "$S_PROMS_DB_HOSTNAME"
}

function settings_change_db_username() {
	input_line "Database" "PROMS needs to connect to the database server where PROMS' database is kept. For this it needs some information about the server.\n\nPlease enter the USERNAME with which you want the PROMS code to connect. " "S_PROMS_DB_USERNAME" "$S_PROMS_DB_USERNAME"
}

function settings_change_db_password() {
	input_line "Database" "PROMS needs to connect to the database server where PROMS' database is kept. For this it needs some information about the server.\n\nPlease enter the PASSWORD with which you want the PROMS code to connect. If you are upgrading, and the previous password was correct, you can simply press enter here." "S_PROMS_DB_PASSWORD" "$S_PROMS_DB_PASSWORD"
}

function settings_change_db_name() {
	input_line "Database" "PROMS needs to connect to the database server where PROMS' database is kept. For this it needs some information about the server.\n\nPlease enter the DATABASE NAME in which you want to put the PROMS data." "S_PROMS_DB_NAME" "$S_PROMS_DB_NAME"
}

function settings_change_email() {
	input_line "Settings" "PROMS has the ability to send out notifications of new bugs, todo's and releases via e-mails. PROMS needs an email address which can be used as a return address as well as the primary e-mail recipient.\n\nPlease check the README to find out how to set up this e-mail account on your system.\n\nPlease enter the e-mail address you want to use for PROMS." "S_PROMS_LIST_EMAIL" "$S_PROMS_LIST_EMAIL"
}

function settings_change_smtp_hostname() {
	input_line "SMTP" "PROMS need as SMTP server to relay email notifications.\n\nPlease enter the HOSTNAME of the SMTP server you want to use for PROMS." "S_PROMS_SMTP_HOSTNAME" "$S_PROMS_SMTP_HOSTNAME"
}

function settings_change_smtp_port() {
	input_line "SMTP" "PROMS need as SMTP server to relay email notifications.\n\nPlease enter the PORT of the SMTP server you want to use for PROMS." "S_PROMS_SMTP_PORT" "$S_PROMS_SMTP_PORT"
}

function setup_change_db_hostname() {
	input_line "DB Admin hostname" "Please enter the HOSTNAME of the database on which PROMS will run." "S_SETUP_DB_HOSTNAME" "$S_SETUP_DB_HOSTNAME"
}

function setup_change_db_username() {
	input_line "DB Admin username" "Please enter the ADMIN USERNAME of the database on which PROMS will run." "S_SETUP_DB_USERNAME" "$S_SETUP_DB_USERNAME"
}

function setup_change_db_password() {
	input_line "DB Admin password" "Please enter the ADMIN PASSWORD of the database on which PROMS will run." "S_SETUP_DB_PASSWORD" "$S_SETUP_DB_PASSWORD"
}

function setup_change_web_username() {
	input_line "Webserver username" "Please enter the Username under which the webserver runs" "S_SETUP_WEB_USERNAME" "$S_SETUP_WEB_USERNAME"
}

function setup_change_web_groupname() {
	input_line "Webserver groupname" "Please enter the Groupname under which the webserver runs" "S_SETUP_WEB_GROUPNAME" "$S_SETUP_WEB_GROUPNAME"
}

#----------------------------------------------------------------------------
# Script initialisation
#----------------------------------------------------------------------------
if [ -x /usr/bin/dialog ]; then
	HAS_DIALOG=1
else
	HAS_DIALOG=0
fi

if [ -x /usr/bin/fmt ]; then
	HAS_FMT=1
else
	HAS_FMT=0
fi

if [ `id -u` != 0 ]; then 
	HAS_ROOT=0
else
	HAS_ROOT=1
fi

output_text "PROMS" "PROMS v$VERSION setup\n\nPROMS is copyright by Ferry Boender. Released under the General Public License (GPL). See the COPYING file for more information.\n\nThis setup script will install PROMS on your system. In order to be of any help with installing, this script will need certain information from you. Please walk through the options in the menu to set this information. If default values are available they will be given.\n\nThis setup tool is UNSTABLE and BETA. It might very well DESTROY your database all together. Make backups.";

if [ "$HAS_ROOT" == "0" ]; then
	input_yesno "Not root" "In order to properly install PROMS you will have to be root. You are not root. You may proceed with this setup script, but you will not be able to use the default installation values.\n\nDo you want to continue?" CONT_NONROOT;
	if [ "$CONT_NONROOT" == "1" ]; then
		exit;
	fi
fi 

settings_get_defaults;
actions_menu

#----------------------------------------------------------------------------
# Deploy / Upgrade database
#----------------------------------------------------------------------------
if [ ! -z $S_PREV_VERSION ]; then
	#
	# Upgrade database
	#
	
	# Create backups before doing anything
	echo "Backing up previous database into backup.sql";
	mysqldump -u$S_SETUP_DB_USERNAME -h$S_SETUP_DB_HOSTNAME --password="$S_SETUP_DB_PASSWORD" $S_PROMS_DB_NAME > backup.sql

	# Upgrade database
	echo "Upgrading database";

	for DB_UPDATE in `grep -x -A1000 "$S_PREV_VERSION" ./sql/db_upgrade_path`; do
		mysql -u$S_SETUP_DB_USERNAME -h$S_SETUP_DB_HOSTNAME --password="$S_SETUP_DB_PASSWORD" $S_PROMS_DB_NAME < sql/$DB_UPDATE.sql
	done
else
	# Deploy new database
	echo "Deploying database";
	mysql -u$S_SETUP_DB_USERNAME -h$S_SETUP_DB_HOSTNAME --password="$S_SETUP_DB_PASSWORD" -e"CREATE DATABASE $S_PROMS_DB_NAME"
	mysql -u$S_SETUP_DB_USERNAME -h$S_SETUP_DB_HOSTNAME --password="$S_SETUP_DB_PASSWORD" -e"GRANT ALL PRIVILEGES ON $S_PROMS_DB_NAME.* TO $S_PROMS_DB_USERNAME IDENTIFIED BY '$S_PROMS_DB_PASSWORD' WITH GRANT OPTION"
	mysql -u$S_SETUP_DB_USERNAME -h$S_SETUP_DB_HOSTNAME --password="$S_SETUP_DB_PASSWORD" $S_PROMS_DB_NAME < sql/proms.sql
fi

#----------------------------------------------------------------------------
# Prepare codebase for new settings
#----------------------------------------------------------------------------
echo "Preparing settings.php";
REPLACE=""
REPLACE="${REPLACE}s|^define (\"REF001_.*|define (\"PROMS_VERSION\", \"$VERSION\");|;"
REPLACE="${REPLACE}s|^define (\"REF002_.*|define (\"PROMS_EMAIL\", \"$S_PROMS_LIST_EMAIL\");|;"
REPLACE="${REPLACE}s|^define (\"REF003_.*|define (\"DB_DATABASE\", \"$S_PROMS_DB_NAME\");|;"
REPLACE="${REPLACE}s|^define (\"REF004_.*|define (\"DB_USERNAME\", \"$S_PROMS_DB_USERNAME\");|;"
REPLACE="${REPLACE}s|^define (\"REF005_.*|define (\"DB_PASSWORD\", \"$S_PROMS_DB_PASSWORD\");|;"
REPLACE="${REPLACE}s|^define (\"REF006_.*|define (\"DB_HOSTNAME\", \"$S_PROMS_DB_HOSTNAME\");|;"
REPLACE="${REPLACE}s|^define (\"REF007_.*|define (\"SMTP_HOSTNAME\", \"$S_PROMS_SMTP_HOSTNAME\");|;"
REPLACE="${REPLACE}s|^define (\"REF008_.*|define (\"SMTP_PORT\", \"$S_PROMS_SMTP_PORT\");|;"
cat src/settings.php | sed "${REPLACE}" > temp.settings.php

#----------------------------------------------------------------------------
# Deploy codebase
#----------------------------------------------------------------------------
echo "Deploying codebase";
mkdir -p $S_SETUP_PATH
cp -R src/* $S_SETUP_PATH
mv temp.settings.php $S_SETUP_PATH/settings.php

if [ "$HAS_ROOT" == "1" ]; then
	echo "Setting rights";
	chown $S_SETUP_WEB_USERNAME:$S_SETUP_WEB_GROUPNAME $S_SETUP_PATH -R
	chmod 550 $S_SETUP_PATH 
	chmod 640 $S_SETUP_PATH/*.php
	chmod 550 $S_SETUP_PATH/images
	chmod 750 $S_SETUP_PATH/files
fi

#----------------------------------------------------------------------------
# Clean up
#----------------------------------------------------------------------------

output_text "Done" "The installation/upgrade of PROMS is done. If this is the first time you have installed PROMS, you must point your webbrowser to whatever directory it is that you installed PROMS in and use the 'Signup' feature to create a new account for yourself. The first account you create will be the superuser of PROMS. After that you can begin to add new projects, etc."
output_text "Notices" "Please make sure you have have Magic Quotes enabled in your installation of PHP. If you do not have this enabled, your installation of PROMS will be VULNERABLE to SQL INJECTIONS!\n\nIf you are running a different web server than Apache, please use your web server's configuration to prevent people from uploading scripts to the files/ directory! If you do not, people might be able to upload PHP or PERL files and execute from within PROMS, which opens up major security holes.\n\n"
