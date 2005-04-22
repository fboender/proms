alter table forum add column notify char(1) NOT NULL;
alter table accounts add column disabled char(1) NOT NULL;
update todos set done=2 where done=1;
update todos set done=1 where done=0;
