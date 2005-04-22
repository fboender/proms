ALTER TABLE todos ADD todo_nr mediumint(9) NOT NULL;
UPDATE todos SET todo_nr=todos.id;
ALTER TABLE project_parts ADD maint_user_id mediumint(9);
CREATE TABLE `files` (
  `id` mediumint(9) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `title` varchar(80) NOT NULL default '',
  `version` varchar(20) NOT NULL default '',
  `description` text NOT NULL default '',
  `adddate` int(11) NOT NULL,
  `category_id` mediumint(9) NOT NULL,
  `contenttype` varchar(100) NOT NULL default '',
  `project_id` mediumint(9) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;
CREATE TABLE `file_categories` (
  `id` mediumint(9) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `project_id` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=InnoDB;
ALTER TABLE project_parts ADD maint_user_id mediumint(9);
ALTER TABLE projects ADD desc_short VARCHAR(255);
