Create database Bacula_History;
USE database Bacula_history;
CREATE TABLE daystats(  id int(11) auto_increment NOT NULL,  date date,  server text,  bytes bigint,  files bigint,  clients bigint,  databasesize bigint,  CONSTRAINT daystats_pkey PRIMARY KEY (id)) TYPE=MyISAM;
CREATE TABLE hoursstats(  id int(11) auto_increment NOT NULL,  date date,  server text,  starttime timestamp,  endtime timestamp,  bytes bigint,  hoursdiff bigint,  hourbytes double precision,  timediff time,  CONSTRAINT hoursstats_pkey PRIMARY KEY (id)) TYPE=MyISAM;


