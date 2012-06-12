DROP TABLE IF EXISTS utente;
DROP SEQUENCE IF EXISTS utente_id_utente_seq;

CREATE SEQUENCE utente_id_utente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE utente (
    id_utente integer DEFAULT nextval(('"utente_id_utente_seq"'::text)::regclass) NOT NULL,
    username character varying(25) NOT NULL,
    password character varying(40) NOT NULL,
    email character varying(255),
    ultimo_login integer,
    inoltro_email character(1) DEFAULT 'N'::bpchar,
    ad_username character varying(255) DEFAULT ''::character varying,
    groups integer,
    ban character(1) DEFAULT 'N'::bpchar,
    notifica integer NOT NULL,
    phone character varying(15) DEFAULT ''::character varying,
    default_style character varying(10) DEFAULT ''::character varying,
    sospeso character(1) DEFAULT 'N'::bpchar NOT NULL,
    algoritmo character varying(8) DEFAULT 'md5'::character varying NOT NULL,
    salt character varying(8) DEFAULT ''::character varying NOT NULL,
    lower character varying(25)
);

    
ALTER TABLE ONLY utente
    ADD CONSTRAINT utente_pkey PRIMARY KEY (id_utente);
    
DROP TABLE IF EXISTS collaboratore;
CREATE TABLE collaboratore (
    id_utente integer NOT NULL,
    intro text,
    recapito character varying(255),
    obiettivi text,
    foto character varying(255),
    ruolo character varying(255),
    show character(1) DEFAULT 'N'::bpchar NOT NULL
);

ALTER TABLE ONLY collaboratore ADD CONSTRAINT collaboratore_pkey PRIMARY KEY (id_utente);

DROP TABLE IF EXISTS step_log;
CREATE TABLE step_log (
    id_step integer DEFAULT nextval(('"step_log_id_step_seq"'::text)::regclass) NOT NULL,
    id_utente integer NOT NULL,
    data_ultima_interazione integer NOT NULL,
    nome_classe character varying(255) NOT NULL,
    esito_positivo character(1)
);

DROP SEQUENCE IF EXISTS step_log_id_step_seq;
DROP SEQUENCE IF EXISTS step_id_step_seq;
CREATE SEQUENCE step_id_step_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ONLY step_log
    ADD CONSTRAINT step_log_pkey PRIMARY KEY (id_step);
    

DROP TABLE IF EXISTS informativa;
CREATE TABLE informativa (
    id_informativa integer DEFAULT nextval(('"informativa_id_informativa_seq"'::text)::regclass) NOT NULL,
    data_pubblicazione integer NOT NULL,
    data_fine integer,
    testo text NOT NULL
);

DROP SEQUENCE IF EXISTS informativa_id_informativa_seq;
CREATE SEQUENCE informativa_id_informativa_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

    
INSERT INTO utente (username, password, notifica, groups) VALUES ('brain', md5('padrino'), 2, 64);
INSERT INTO utente (username, password, notifica, groups) VALUES ('fgiardini', md5('padrino'), 2, 64);
INSERT INTO utente (username, password, notifica, groups) VALUES ('Dece', md5('padrino'), 2, 16);
INSERT INTO utente (username, password, notifica, groups) VALUES ('dtiles', md5('padrino'), 2, 8);
INSERT INTO utente (username, password, notifica, groups) VALUES ('edenti', md5('padrino'), 2, 16);
INSERT INTO utente (username, password, notifica, groups) VALUES ('maurizio.zani', md5('padrino'), 2, 32);
INSERT INTO utente (username, password, notifica, groups) VALUES ('ossistyl', md5('padrino'), 2, 4);

INSERT INTO collaboratore (id_utente, intro, recapito, obiettivi, foto, ruolo, show) VALUES (1, 'hello world', '9999999999', 'lorem ipsum', 'brain.jpg', 'fondatore - progettista software', 'N');

INSERT INTO informativa (data_pubblicazione, testo) VALUES (2000, 'Lorem ipsum');