CREATE TABLE todo_statuses (
  id mediumint(9) NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

insert into todo_statuses values ('','Pending');
insert into todo_statuses values ('','Done');
insert into todo_statuses values ('','Closed');


