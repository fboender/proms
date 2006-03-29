-- MySQL dump 9.11
--
-- Host: sharky    Database: proms_dev
-- ------------------------------------------------------
-- Server version	3.23.49-log

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` mediumint(9) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `fullname` varchar(40) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `rights` smallint(6) NOT NULL default '0',
  `private` char(1) NOT NULL default '',
  `disabled` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM;

--
-- Table structure for table `bugs`
--

CREATE TABLE `bugs` (
  `id` mediumint(9) NOT NULL auto_increment,
  `bug_nr` mediumint(9) NOT NULL default '0',
  `project_id` mediumint(9) NOT NULL default '0',
  `user_id` mediumint(9) NOT NULL default '0',
  `severity` tinyint(4) NOT NULL default '0',
  `part` smallint(6) NOT NULL default '0',
  `subject` varchar(150) NOT NULL default '',
  `description` text NOT NULL,
  `reproduction` text NOT NULL,
  `patch` text NOT NULL,
  `version` varchar(20) NOT NULL default '',
  `software` text NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  `lastmoddate` int(11) NOT NULL default '0',
  `reportdate` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `bugs_severity`
--

CREATE TABLE `bugs_severity` (
  `id` mediumint(9) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `bugs_severity`
--

INSERT INTO `bugs_severity` VALUES (1,'Feature request','');
INSERT INTO `bugs_severity` VALUES (2,'Minor','');
INSERT INTO `bugs_severity` VALUES (3,'Normal','');
INSERT INTO `bugs_severity` VALUES (4,'Important','');
INSERT INTO `bugs_severity` VALUES (5,'Serious','');
INSERT INTO `bugs_severity` VALUES (6,'Critical','');

--
-- Table structure for table `bugs_statuses`
--

CREATE TABLE `bugs_statuses` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `bugs_statuses`
--

INSERT INTO `bugs_statuses` VALUES (1,'Unconfirmed','');
INSERT INTO `bugs_statuses` VALUES (2,'Confirmed','');
INSERT INTO `bugs_statuses` VALUES (3,'Unreproducable','');
INSERT INTO `bugs_statuses` VALUES (4,'Closed','');
INSERT INTO `bugs_statuses` VALUES (5,'In progress','');
INSERT INTO `bugs_statuses` VALUES (6,'Fixed','');

--
-- Table structure for table `file_categories`
--

CREATE TABLE `file_categories` (
  `id` mediumint(9) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `project_id` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` mediumint(9) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL default '',
  `title` varchar(80) NOT NULL default '',
  `version` varchar(20) NOT NULL default '',
  `description` text NOT NULL,
  `adddate` int(11) NOT NULL default '0',
  `category_id` mediumint(9) NOT NULL default '0',
  `contenttype` varchar(100) NOT NULL default '',
  `project_id` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `forum`
--

CREATE TABLE `forum` (
  `id` int(11) NOT NULL auto_increment,
  `reply_to` int(11) NOT NULL default '0',
  `project_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `contents` text NOT NULL,
  `postdate` int(11) NOT NULL default '0',
  `lastpostdate` int(11) NOT NULL default '0',
  `notify` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `licenses`
--

CREATE TABLE `licenses` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `licenses`
--

INSERT INTO `licenses` VALUES (4,'GPL (General Public License','');
INSERT INTO `licenses` VALUES (5,'LGPL (Lesser General Public License)','');
INSERT INTO `licenses` VALUES (6,'PAL (Perl Artistic License)','');

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL auto_increment,
  `project_id` int(11) NOT NULL default '0',
  `account_id` int(11) NOT NULL default '0',
  `rights` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `project_parts`
--

CREATE TABLE `project_parts` (
  `id` mediumint(9) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `project_id` mediumint(9) NOT NULL default '0',
  `maint_user_id` mediumint(9) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `project_releases`
--

CREATE TABLE `project_releases` (
  `id` mediumint(9) NOT NULL auto_increment,
  `project_id` mediumint(9) NOT NULL default '0',
  `version` varchar(10) NOT NULL default '',
  `changes` text NOT NULL,
  `url_source` tinytext NOT NULL,
  `url_bin` tinytext NOT NULL,
  `url_deb` tinytext NOT NULL,
  `url_rpm` tinytext NOT NULL,
  `release_focus_id` mediumint(9) NOT NULL default '0',
  `status_id` mediumint(9) NOT NULL default '0',
  `codename` varchar(50) NOT NULL default '',
  `url_changelog` tinytext NOT NULL,
  `url_releasenotes` tinytext NOT NULL,
  `date` int(11) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` mediumint(9) NOT NULL auto_increment,
  `shortname` varchar(30) NOT NULL default '',
  `fullname` varchar(80) NOT NULL default '',
  `owner` mediumint(9) NOT NULL default '0',
  `description` text NOT NULL,
  `progress` tinyint(4) NOT NULL default '0',
  `homepage` varchar(255) NOT NULL default '',
  `license` tinyint(4) NOT NULL default '0',
  `desc_short` varchar(255) default NULL,
  `private` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `release_focus`
--

CREATE TABLE `release_focus` (
  `id` mediumint(9) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `release_focus`
--

INSERT INTO `release_focus` VALUES (1,'Misc');
INSERT INTO `release_focus` VALUES (2,'Small bugfixes');
INSERT INTO `release_focus` VALUES (3,'Major bugfixes');
INSERT INTO `release_focus` VALUES (4,'Small feature enhancements');
INSERT INTO `release_focus` VALUES (5,'Major feature enhancements');

--
-- Table structure for table `release_status`
--

CREATE TABLE `release_status` (
  `id` mediumint(9) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `release_status`
--

INSERT INTO `release_status` VALUES (1,'Stable');
INSERT INTO `release_status` VALUES (2,'Unstable');

--
-- Table structure for table `subs_releases`
--

CREATE TABLE `subs_releases` (
  `id` mediumint(9) NOT NULL auto_increment,
  `project_id` mediumint(9) NOT NULL default '0',
  `user_id` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Table structure for table `todo_priority`
--

CREATE TABLE `todo_priority` (
  `id` mediumint(9) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `todo_priority`
--

INSERT INTO `todo_priority` VALUES (1,'Feature request','');
INSERT INTO `todo_priority` VALUES (2,'Minor','');
INSERT INTO `todo_priority` VALUES (3,'Normal','');
INSERT INTO `todo_priority` VALUES (4,'Important','');
INSERT INTO `todo_priority` VALUES (5,'Serious','');
INSERT INTO `todo_priority` VALUES (6,'Critical','');

--
-- Table structure for table `todo_statuses`
--

CREATE TABLE `todo_statuses` (
  `id` mediumint(9) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- Dumping data for table `todo_statuses`
--

INSERT INTO `todo_statuses` VALUES (1,'Pending');
INSERT INTO `todo_statuses` VALUES (2,'Done');
INSERT INTO `todo_statuses` VALUES (3,'Closed');

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` mediumint(9) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `user_id` mediumint(9) NOT NULL default '0',
  `done` tinyint(3) default '0',
  `project_id` int(11) NOT NULL default '0',
  `part` smallint(6) NOT NULL default '0',
  `priority` tinyint(4) NOT NULL default '0',
  `lastmoddate` int(11) NOT NULL default '0',
  `todo_nr` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Todos for projects';
