-- Database: bacula_history

-- DROP DATABASE bacula_history;

CREATE DATABASE bacula_history
  WITH OWNER = bacula
       ENCODING = 'LATIN1'
       TABLESPACE = pg_default
       LC_COLLATE = 'pt_PT'
       LC_CTYPE = 'pt_PT'
       CONNECTION LIMIT = -1;

-- Table: daystats

-- DROP TABLE daystats;

CREATE TABLE daystats
(
  id serial NOT NULL,
  date date,
  server text,
  bytes bigint,
  files bigint,
  clients bigint,
  databasesize bigint,
  CONSTRAINT daystats_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE daystats OWNER TO bacula;

-- Table: hoursstats

-- DROP TABLE hoursstats;

CREATE TABLE hoursstats
(
  id serial NOT NULL,
  date date,
  server text,
  starttime timestamp without time zone,
  endtime timestamp without time zone,
  bytes bigint,
  hoursdiff bigint,
  hourbytes double precision,
  timediff time without time zone,
  CONSTRAINT hoursstats_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE hoursstats OWNER TO bacula;
