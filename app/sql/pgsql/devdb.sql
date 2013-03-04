--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Name: argomento_id_argomento_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE argomento_id_argomento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.argomento_id_argomento_seq OWNER TO universibo;

--
-- Name: argomento_set_id_argomento__seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE argomento_set_id_argomento__seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.argomento_set_id_argomento__seq OWNER TO universibo;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: canale; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE canale (
    id_canale integer NOT NULL,
    tipo_canale integer NOT NULL,
    nome_canale character varying(200),
    immagine character varying(50),
    visite integer NOT NULL,
    ultima_modifica integer,
    permessi_groups integer,
    files_attivo character(1),
    news_attivo character(1),
    forum_attivo character(1),
    id_forum integer,
    group_id integer,
    links_attivo character(1),
    files_studenti_attivo character(1)
);


ALTER TABLE public.canale OWNER TO universibo;

--
-- Name: canale_id_canale_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE canale_id_canale_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.canale_id_canale_seq OWNER TO universibo;

--
-- Name: canale_id_canale_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE canale_id_canale_seq OWNED BY canale.id_canale;


--
-- Name: canale_noforum; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW canale_noforum AS
    SELECT canale.id_canale, canale.tipo_canale, canale.nome_canale, canale.immagine, canale.visite, canale.ultima_modifica, canale.permessi_groups, canale.files_attivo, canale.news_attivo, canale.forum_attivo, canale.id_forum, canale.group_id, canale.links_attivo, canale.files_studenti_attivo FROM canale WHERE (canale.forum_attivo = 'N'::bpchar);


ALTER TABLE public.canale_noforum OWNER TO universibo;

--
-- Name: classi_corso; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE classi_corso (
    cod_corso character varying(4) NOT NULL,
    desc_corso character varying(150) NOT NULL,
    id_canale integer,
    cat_id integer,
    cod_doc character varying(6),
    cod_fac character varying(4),
    categoria integer
);


ALTER TABLE public.classi_corso OWNER TO universibo;

--
-- Name: classi_materie; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE classi_materie (
    cod_materia character varying(5) NOT NULL,
    desc_materia character varying(200) NOT NULL
);


ALTER TABLE public.classi_materie OWNER TO universibo;

--
-- Name: collaboratore_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE collaboratore_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.collaboratore_id_seq OWNER TO universibo;

--
-- Name: collaboratore; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE collaboratore (
    id_utente integer,
    intro text,
    recapito character varying(255),
    obiettivi text,
    foto character varying(255),
    ruolo character varying(255),
    show character varying(1) DEFAULT 'N'::bpchar NOT NULL,
    id integer DEFAULT nextval('collaboratore_id_seq'::regclass) NOT NULL
);


ALTER TABLE public.collaboratore OWNER TO universibo;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contacts_id_seq OWNER TO universibo;

--
-- Name: contacts; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE contacts (
    id integer DEFAULT nextval('contacts_id_seq'::regclass) NOT NULL,
    user_id integer,
    value character varying(255) NOT NULL,
    verification_token character varying(128),
    verification_sent_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    verified_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.contacts OWNER TO universibo;

--
-- Name: docente; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE docente (
    id_utente integer NOT NULL,
    cod_doc character varying(6) NOT NULL,
    nome_doc character varying(150),
    docente_contattato integer DEFAULT 0,
    id_mod integer
);


ALTER TABLE public.docente OWNER TO universibo;

--
-- Name: docente2; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE docente2 (
    cod_doc character varying(6) NOT NULL,
    email character varying(255),
    nome_doc character varying(150)
);


ALTER TABLE public.docente2 OWNER TO universibo;

--
-- Name: docente_contatti; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE docente_contatti (
    cod_doc character varying(6) NOT NULL,
    stato integer DEFAULT 1 NOT NULL,
    id_utente_assegnato integer,
    ultima_modifica integer,
    report text DEFAULT ''::text NOT NULL,
    eliminato character(1) DEFAULT 'N'::bpchar NOT NULL
);


ALTER TABLE public.docente_contatti OWNER TO universibo;

--
-- Name: COLUMN docente_contatti.eliminato; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON COLUMN docente_contatti.eliminato IS 'Eliminazione logica';


--
-- Name: facolta; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE facolta (
    cod_fac character varying(4) NOT NULL,
    desc_fac character varying(150) NOT NULL,
    url_facolta character varying(80),
    id_canale integer,
    cod_doc character(6)
);


ALTER TABLE public.facolta OWNER TO universibo;

--
-- Name: file; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file (
    id_file integer DEFAULT nextval(('"file_id_file_seq"'::text)::regclass) NOT NULL,
    permessi_download integer NOT NULL,
    permessi_visualizza integer NOT NULL,
    id_utente integer NOT NULL,
    titolo character varying(150) NOT NULL,
    descrizione text,
    data_inserimento integer NOT NULL,
    data_modifica integer NOT NULL,
    dimensione integer NOT NULL,
    download integer NOT NULL,
    nome_file character varying(256) NOT NULL,
    id_categoria integer NOT NULL,
    id_tipo_file integer NOT NULL,
    hash_file character varying(40) NOT NULL,
    password character varying(40),
    eliminato character(1) NOT NULL
);


ALTER TABLE public.file OWNER TO universibo;

--
-- Name: file_canale; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_canale (
    id_file integer NOT NULL,
    id_canale integer NOT NULL
);


ALTER TABLE public.file_canale OWNER TO universibo;

--
-- Name: file_categoria; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_categoria (
    id_file_categoria integer NOT NULL,
    descrizione character varying(128) NOT NULL
);


ALTER TABLE public.file_categoria OWNER TO universibo;

--
-- Name: file_categoria_id_file_categoria_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE file_categoria_id_file_categoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.file_categoria_id_file_categoria_seq OWNER TO universibo;

--
-- Name: file_categoria_id_file_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE file_categoria_id_file_categoria_seq OWNED BY file_categoria.id_file_categoria;


--
-- Name: file_id_file_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE file_id_file_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.file_id_file_seq OWNER TO universibo;

--
-- Name: file_inseriti_giorno; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW file_inseriti_giorno AS
    SELECT max(date_part('dow'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval)))) AS giornos, date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))) AS giorno, date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))) AS mese, date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))) AS anno, count(file.id_file) AS totale_file FROM file GROUP BY date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))), date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))), date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))) ORDER BY date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))), date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval))), date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((file.data_inserimento)::double precision * '00:00:01'::interval)));


ALTER TABLE public.file_inseriti_giorno OWNER TO universibo;

--
-- Name: VIEW file_inseriti_giorno; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW file_inseriti_giorno IS 'Seleziona i files inseriti in ogni giorno';


--
-- Name: file_inseriti_mese; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW file_inseriti_mese AS
    SELECT file_inseriti_giorno.mese, file_inseriti_giorno.anno, sum(file_inseriti_giorno.totale_file) AS file_mese FROM file_inseriti_giorno GROUP BY file_inseriti_giorno.anno, file_inseriti_giorno.mese ORDER BY file_inseriti_giorno.anno DESC, file_inseriti_giorno.mese DESC;


ALTER TABLE public.file_inseriti_mese OWNER TO universibo;

--
-- Name: VIEW file_inseriti_mese; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW file_inseriti_mese IS 'Seleziona i file inseriti in ogni mese in tutte le sezioni';


--
-- Name: file_keywords; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_keywords (
    id_file integer NOT NULL,
    keyword character varying(50) NOT NULL
);


ALTER TABLE public.file_keywords OWNER TO universibo;

--
-- Name: file_studente_canale; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_studente_canale (
    id_file integer NOT NULL,
    id_canale integer NOT NULL
);


ALTER TABLE public.file_studente_canale OWNER TO universibo;

--
-- Name: file_studente_commenti; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_studente_commenti (
    id_commento integer NOT NULL,
    id_file integer NOT NULL,
    id_utente integer NOT NULL,
    commento text NOT NULL,
    voto integer NOT NULL,
    eliminato character(1) NOT NULL
);


ALTER TABLE public.file_studente_commenti OWNER TO universibo;

--
-- Name: file_studente_commenti_id_commento_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE file_studente_commenti_id_commento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.file_studente_commenti_id_commento_seq OWNER TO universibo;

--
-- Name: file_studente_commenti_id_commento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE file_studente_commenti_id_commento_seq OWNED BY file_studente_commenti.id_commento;


--
-- Name: file_tipo; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE file_tipo (
    id_file_tipo integer NOT NULL,
    descrizione character varying(128) NOT NULL,
    pattern_riconoscimento character varying(128) NOT NULL,
    icona character varying(256) NOT NULL,
    info_aggiuntive text
);


ALTER TABLE public.file_tipo OWNER TO universibo;

--
-- Name: file_tipo_id_file_tipo_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE file_tipo_id_file_tipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.file_tipo_id_file_tipo_seq OWNER TO universibo;

--
-- Name: file_tipo_id_file_tipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE file_tipo_id_file_tipo_seq OWNED BY file_tipo.id_file_tipo;


--
-- Name: forums_auth_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE forums_auth_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.forums_auth_id_seq OWNER TO universibo;

--
-- Name: fos_group_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE fos_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fos_group_id_seq OWNER TO universibo;

--
-- Name: fos_group; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE fos_group (
    id integer DEFAULT nextval('fos_group_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL,
    roles text NOT NULL
);


ALTER TABLE public.fos_group OWNER TO universibo;

--
-- Name: COLUMN fos_group.roles; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON COLUMN fos_group.roles IS '(DC2Type:array)';


--
-- Name: fos_user; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE fos_user (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    username_canonical character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_canonical character varying(255) NOT NULL,
    enabled boolean NOT NULL,
    salt character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    last_login timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    locked boolean NOT NULL,
    expired boolean NOT NULL,
    expires_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    confirmation_token character varying(255) DEFAULT NULL::character varying,
    password_requested_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    roles text NOT NULL,
    credentials_expired boolean NOT NULL,
    credentials_expire_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    phone character varying(15),
    notifications integer NOT NULL,
    groups integer DEFAULT 0 NOT NULL,
    person_id integer,
    username_locked boolean DEFAULT true NOT NULL,
    encoder_name character varying(15) DEFAULT NULL::character varying,
    forum_id integer
);


ALTER TABLE public.fos_user OWNER TO universibo;

--
-- Name: COLUMN fos_user.roles; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON COLUMN fos_user.roles IS '(DC2Type:array)';


--
-- Name: fos_user_group; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE fos_user_group (
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.fos_user_group OWNER TO universibo;

--
-- Name: fos_user_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE fos_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fos_user_id_seq OWNER TO universibo;

--
-- Name: fos_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE fos_user_id_seq OWNED BY fos_user.id;


--
-- Name: fos_user_ismemberof; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE fos_user_ismemberof (
    user_id integer NOT NULL,
    ismemberof_id integer NOT NULL
);


ALTER TABLE public.fos_user_ismemberof OWNER TO universibo;

--
-- Name: help; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE help (
    id_help integer NOT NULL,
    titolo character varying(255) NOT NULL,
    contenuto text NOT NULL,
    ultima_modifica integer NOT NULL,
    indice integer NOT NULL
);


ALTER TABLE public.help OWNER TO universibo;

--
-- Name: help_id_help_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE help_id_help_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.help_id_help_seq OWNER TO universibo;

--
-- Name: help_id_help_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE help_id_help_seq OWNED BY help.id_help;


--
-- Name: help_riferimento; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE help_riferimento (
    riferimento character varying(32) NOT NULL,
    id_help integer NOT NULL
);


ALTER TABLE public.help_riferimento OWNER TO universibo;

--
-- Name: help_topic; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE help_topic (
    riferimento character varying(32) NOT NULL,
    titolo character varying(256) NOT NULL,
    indice integer NOT NULL
);


ALTER TABLE public.help_topic OWNER TO universibo;

--
-- Name: info_didattica; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE info_didattica (
    id_canale integer NOT NULL,
    programma text DEFAULT ''::text NOT NULL,
    programma_link character varying(256) DEFAULT ''::character varying NOT NULL,
    testi_consigliati text DEFAULT ''::text NOT NULL,
    testi_consigliati_link character varying(256) DEFAULT ''::character varying NOT NULL,
    modalita text DEFAULT ''::text NOT NULL,
    modalita_link character varying(256) DEFAULT ''::character varying NOT NULL,
    obiettivi_esame text DEFAULT ''::text NOT NULL,
    obiettivi_esame_link character varying(256) DEFAULT ''::character varying NOT NULL,
    appelli text DEFAULT ''::text NOT NULL,
    appelli_link character varying(256) DEFAULT ''::character varying NOT NULL,
    homepage_alternativa_link character varying(256) DEFAULT ''::character varying NOT NULL,
    orario_ics_link character varying(256) DEFAULT ''::character varying NOT NULL
);


ALTER TABLE public.info_didattica OWNER TO universibo;

--
-- Name: informativa; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE informativa (
    id_informativa integer DEFAULT nextval(('"informativa_id_informativa_seq"'::text)::regclass) NOT NULL,
    data_pubblicazione integer NOT NULL,
    data_fine integer,
    testo text NOT NULL
);


ALTER TABLE public.informativa OWNER TO universibo;

--
-- Name: informativa_id_informativa_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE informativa_id_informativa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.informativa_id_informativa_seq OWNER TO universibo;

--
-- Name: input_esami_attivi; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE input_esami_attivi (
    anno_accademico numeric(4,0) NOT NULL,
    cod_corso character varying(4) NOT NULL,
    cod_ind character varying(3) NOT NULL,
    cod_ori character varying(3) NOT NULL,
    cod_materia character varying(5) NOT NULL,
    anno_corso character varying(1) NOT NULL,
    cod_materia_ins character varying(5) NOT NULL,
    anno_corso_ins character varying(1) NOT NULL,
    cod_ril character varying(3) NOT NULL,
    cod_modulo character varying(5) NOT NULL,
    cod_attivita character varying(5) DEFAULT 0 NOT NULL,
    prog_cronologico smallint DEFAULT 0 NOT NULL,
    cod_doc character varying(6) NOT NULL,
    flag_titolare_modulo character(1) NOT NULL,
    id_canale integer,
    cod_orario smallint,
    tipo_ciclo character(1) NOT NULL,
    cod_ate character varying(3) DEFAULT '010'::character varying,
    anno_corso_universibo character varying(1)
);


ALTER TABLE public.input_esami_attivi OWNER TO universibo;

--
-- Name: prg_insegnamento; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE prg_insegnamento (
    anno_accademico numeric(4,0) NOT NULL,
    cod_corso character varying(4) NOT NULL,
    cod_ind character varying(3) NOT NULL,
    cod_ori character varying(3) NOT NULL,
    cod_materia character varying(5) NOT NULL,
    anno_corso character varying(1) NOT NULL,
    cod_materia_ins character varying(5) NOT NULL,
    anno_corso_ins character varying(1) NOT NULL,
    cod_ril character varying(3) NOT NULL,
    cod_modulo character varying(5) NOT NULL,
    cod_doc character varying(6) NOT NULL,
    flag_titolare_modulo character(1) NOT NULL,
    id_canale integer,
    cod_orario smallint,
    tipo_ciclo character(1) NOT NULL,
    cod_ate character varying(3) DEFAULT '010'::character varying,
    anno_corso_universibo character varying(1)
);


ALTER TABLE public.prg_insegnamento OWNER TO universibo;

--
-- Name: prg_sdoppiamento; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE prg_sdoppiamento (
    cod_ril character varying(3) NOT NULL,
    anno_accademico numeric(4,0) NOT NULL,
    cod_corso character varying(4) NOT NULL,
    cod_ind character varying(3) NOT NULL,
    cod_ori character varying(3) NOT NULL,
    cod_materia character varying(5) NOT NULL,
    anno_corso character varying(1) NOT NULL,
    cod_materia_ins character varying(5) NOT NULL,
    anno_corso_ins character varying(1) NOT NULL,
    flag_mutuato character(1) NOT NULL,
    flag_comune character(1) NOT NULL,
    tipo_ciclo character(1) NOT NULL,
    anno_accademico_fis numeric(4,0),
    cod_corso_fis character varying(4),
    cod_ind_fis character varying(3),
    cod_ori_fis character varying(3),
    cod_materia_fis character varying(5),
    anno_corso_fis character varying(1),
    cod_materia_ins_fis character varying(5),
    anno_corso_ins_fis character varying(1),
    cod_ril_fis character varying(3),
    cod_ate character varying(3) DEFAULT '010'::character varying,
    cod_ate_fis character varying(3) DEFAULT '010'::character varying,
    anno_corso_universibo character varying(1),
    id_sdop integer DEFAULT nextval(('prg_sdop_id_sdop_seq'::text)::regclass) NOT NULL
);


ALTER TABLE public.prg_sdoppiamento OWNER TO universibo;

--
-- Name: insegnamenti; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW insegnamenti AS
    SELECT prg_insegnamento.anno_accademico, prg_insegnamento.cod_corso, prg_insegnamento.cod_ind, prg_insegnamento.cod_ori, prg_insegnamento.cod_materia, prg_insegnamento.anno_corso, prg_insegnamento.cod_materia_ins, prg_insegnamento.anno_corso_ins, prg_insegnamento.cod_ril, prg_insegnamento.cod_modulo, prg_insegnamento.cod_doc, prg_insegnamento.flag_titolare_modulo, prg_insegnamento.id_canale, prg_insegnamento.cod_orario, prg_insegnamento.tipo_ciclo, prg_insegnamento.cod_ate, prg_insegnamento.anno_corso_universibo, 'N'::bpchar AS flag_mutuato, 'N'::bpchar AS flag_comune FROM prg_insegnamento UNION SELECT s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, s.anno_corso, s.cod_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, i.flag_titolare_modulo, i.id_canale, i.cod_orario, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, s.flag_mutuato, s.flag_comune FROM prg_insegnamento i, prg_sdoppiamento s WHERE ((((((((((s.anno_accademico = i.anno_accademico) AND ((s.cod_corso_fis)::text = (i.cod_corso)::text)) AND ((s.cod_ind_fis)::text = (i.cod_ind)::text)) AND ((s.cod_ori_fis)::text = (i.cod_ori)::text)) AND ((s.cod_materia_fis)::text = (i.cod_materia)::text)) AND ((s.anno_corso_fis)::text = (i.anno_corso)::text)) AND ((s.cod_materia_ins_fis)::text = (i.cod_materia_ins)::text)) AND ((s.anno_corso_ins_fis)::text = (i.anno_corso_ins)::text)) AND ((s.cod_ril_fis)::text = (i.cod_ril)::text)) AND ((s.cod_ate_fis)::text = (i.cod_ate)::text));


ALTER TABLE public.insegnamenti OWNER TO universibo;

--
-- Name: VIEW insegnamenti; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW insegnamenti IS 'Seleziona gli insegnamenti considerando anche il join con i comuni e i mutuati';


--
-- Name: ismemberof; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE ismemberof (
    id integer NOT NULL,
    name character varying(20) NOT NULL
);


ALTER TABLE public.ismemberof OWNER TO universibo;

--
-- Name: ismemberof_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE ismemberof_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ismemberof_id_seq OWNER TO universibo;

--
-- Name: link; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE link (
    id_link integer NOT NULL,
    id_canale integer NOT NULL,
    id_utente integer NOT NULL,
    uri character varying(255) NOT NULL,
    label character varying(128),
    description text
);


ALTER TABLE public.link OWNER TO universibo;

--
-- Name: link_id_link_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE link_id_link_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.link_id_link_seq OWNER TO universibo;

--
-- Name: link_id_link_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE link_id_link_seq OWNED BY link.id_link;


--
-- Name: loggati_168h; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_168h AS
    SELECT fos_user.id, fos_user.username, fos_user.username_canonical, fos_user.email, fos_user.email_canonical, fos_user.enabled, fos_user.salt, fos_user.password, fos_user.last_login, fos_user.locked, fos_user.expired, fos_user.expires_at, fos_user.confirmation_token, fos_user.password_requested_at, fos_user.roles, fos_user.credentials_expired, fos_user.credentials_expire_at, fos_user.phone, fos_user.notifications, fos_user.groups, fos_user.person_id, fos_user.username_locked, fos_user.encoder_name FROM fos_user WHERE (fos_user.last_login >= (SELECT max((fos_user.last_login - '168:00:00'::interval)) AS max FROM fos_user));


ALTER TABLE public.loggati_168h OWNER TO universibo;

--
-- Name: loggati_168h_count; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_168h_count AS
    SELECT count(*) AS count FROM loggati_168h;


ALTER TABLE public.loggati_168h_count OWNER TO universibo;

--
-- Name: loggati_24h; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_24h AS
    SELECT fos_user.id, fos_user.username, fos_user.username_canonical, fos_user.email, fos_user.email_canonical, fos_user.enabled, fos_user.salt, fos_user.password, fos_user.last_login, fos_user.locked, fos_user.expired, fos_user.expires_at, fos_user.confirmation_token, fos_user.password_requested_at, fos_user.roles, fos_user.credentials_expired, fos_user.credentials_expire_at, fos_user.phone, fos_user.notifications, fos_user.groups, fos_user.person_id, fos_user.username_locked, fos_user.encoder_name FROM fos_user WHERE (fos_user.last_login >= (SELECT max((fos_user.last_login - '24:00:00'::interval)) AS max FROM fos_user));


ALTER TABLE public.loggati_24h OWNER TO universibo;

--
-- Name: loggati_24h_count; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_24h_count AS
    SELECT count(*) AS count FROM loggati_24h;


ALTER TABLE public.loggati_24h_count OWNER TO universibo;

--
-- Name: loggati_mese; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_mese AS
    SELECT fos_user.id, fos_user.username, fos_user.username_canonical, fos_user.email, fos_user.email_canonical, fos_user.enabled, fos_user.salt, fos_user.password, fos_user.last_login, fos_user.locked, fos_user.expired, fos_user.expires_at, fos_user.confirmation_token, fos_user.password_requested_at, fos_user.roles, fos_user.credentials_expired, fos_user.credentials_expire_at, fos_user.phone, fos_user.notifications, fos_user.groups, fos_user.person_id, fos_user.username_locked, fos_user.encoder_name FROM fos_user WHERE (fos_user.last_login >= (SELECT max((fos_user.last_login - '30 days'::interval)) AS max FROM fos_user)) ORDER BY fos_user.last_login DESC;


ALTER TABLE public.loggati_mese OWNER TO universibo;

--
-- Name: loggati_mese_count; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW loggati_mese_count AS
    SELECT count(*) AS count FROM loggati_mese;


ALTER TABLE public.loggati_mese_count OWNER TO universibo;

--
-- Name: migration_versions; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE migration_versions (
    version character varying(255) NOT NULL
);


ALTER TABLE public.migration_versions OWNER TO universibo;

--
-- Name: news; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE news (
    id_news integer DEFAULT nextval(('"news_id_news_seq"'::text)::regclass) NOT NULL,
    titolo character varying(150) NOT NULL,
    data_inserimento integer NOT NULL,
    data_scadenza integer,
    notizia text,
    id_utente integer NOT NULL,
    eliminata character(1) DEFAULT 'N'::bpchar NOT NULL,
    flag_urgente character(1) DEFAULT 'N'::bpchar NOT NULL,
    data_modifica integer
);


ALTER TABLE public.news OWNER TO universibo;

--
-- Name: news_canale; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE news_canale (
    id_news integer NOT NULL,
    id_canale integer NOT NULL
);


ALTER TABLE public.news_canale OWNER TO universibo;

--
-- Name: news_id_news_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE news_id_news_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_id_news_seq OWNER TO universibo;

--
-- Name: news_inserite_giorno; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW news_inserite_giorno AS
    SELECT max(date_part('dow'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval)))) AS giornos, date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))) AS giorno, date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))) AS mese, date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))) AS anno, count(news.id_news) AS totale_news FROM news GROUP BY date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))), date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))), date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))) ORDER BY date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))), date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval))), date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((news.data_inserimento)::double precision * '00:00:01'::interval)));


ALTER TABLE public.news_inserite_giorno OWNER TO universibo;

--
-- Name: VIEW news_inserite_giorno; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW news_inserite_giorno IS 'Seleziona le news inserite per giorno';


--
-- Name: news_inserite_mese; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW news_inserite_mese AS
    SELECT news_inserite_giorno.mese, news_inserite_giorno.anno, sum(news_inserite_giorno.totale_news) AS news_mese FROM news_inserite_giorno GROUP BY news_inserite_giorno.anno, news_inserite_giorno.mese ORDER BY news_inserite_giorno.anno DESC, news_inserite_giorno.mese DESC;


ALTER TABLE public.news_inserite_mese OWNER TO universibo;

--
-- Name: VIEW news_inserite_mese; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW news_inserite_mese IS 'Seleziona le news inserite in ogni mese';


--
-- Name: nome_insegnamenti; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW nome_insegnamenti AS
    SELECT i.id_canale, m.desc_materia, i.cod_ril, d.nome_doc, i.cod_corso, i.anno_accademico FROM insegnamenti i, classi_materie m, docente d WHERE (((i.cod_materia_ins)::text = (m.cod_materia)::text) AND ((i.cod_doc)::text = (d.cod_doc)::text));


ALTER TABLE public.nome_insegnamenti OWNER TO universibo;

--
-- Name: notifica; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE notifica (
    id_notifica integer NOT NULL,
    urgente character(1) NOT NULL,
    messaggio text NOT NULL,
    titolo character varying(200) NOT NULL,
    "timestamp" integer NOT NULL,
    destinatario character varying(200) NOT NULL,
    eliminata character(1) NOT NULL
);


ALTER TABLE public.notifica OWNER TO universibo;

--
-- Name: notifica_id_notifica_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE notifica_id_notifica_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notifica_id_notifica_seq OWNER TO universibo;

--
-- Name: notifica_id_notifica_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: universibo
--

ALTER SEQUENCE notifica_id_notifica_seq OWNED BY notifica.id_notifica;


--
-- Name: people_id_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE people_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.people_id_seq OWNER TO universibo;

--
-- Name: people; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE people (
    id integer DEFAULT nextval('people_id_seq'::regclass) NOT NULL,
    unibo_id integer,
    given_name character varying(160) NOT NULL,
    surname character varying(160) DEFAULT NULL::character varying
);


ALTER TABLE public.people OWNER TO universibo;

--
-- Name: prg_sdop_id_sdop_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE prg_sdop_id_sdop_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.prg_sdop_id_sdop_seq OWNER TO universibo;

--
-- Name: prg_sdoppiamento_r_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE prg_sdoppiamento_r_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.prg_sdoppiamento_r_seq OWNER TO universibo;

--
-- Name: prg_sdoppiamento_rr_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE prg_sdoppiamento_rr_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.prg_sdoppiamento_rr_seq OWNER TO universibo;

--
-- Name: questionario; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE questionario (
    id_questionario integer DEFAULT nextval(('"questionario_id_questionari_seq"'::text)::regclass) NOT NULL,
    data integer NOT NULL,
    nome character varying(50) NOT NULL,
    cognome character varying(50) NOT NULL,
    mail character varying(50) NOT NULL,
    telefono character varying(50) NOT NULL,
    tempo_disp smallint NOT NULL,
    tempo_internet smallint NOT NULL,
    attiv_offline character(1) DEFAULT 'N'::bpchar NOT NULL,
    attiv_moderatore character(1) DEFAULT 'N'::bpchar NOT NULL,
    attiv_contenuti character(1) DEFAULT 'N'::bpchar NOT NULL,
    attiv_test character(1) DEFAULT 'N'::bpchar NOT NULL,
    attiv_grafica character(1) DEFAULT 'N'::bpchar NOT NULL,
    attiv_prog character(1) DEFAULT 'N'::bpchar NOT NULL,
    altro text NOT NULL,
    id_utente integer NOT NULL,
    cdl character varying(50) NOT NULL
);


ALTER TABLE public.questionario OWNER TO universibo;

--
-- Name: questionario_id_questionari_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE questionario_id_questionari_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.questionario_id_questionari_seq OWNER TO universibo;

--
-- Name: rub_docente; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE rub_docente (
    cod_doc character varying(6) NOT NULL,
    nome character varying(30),
    cognome character varying(40),
    prefissonome character varying(15),
    sesso smallint,
    email character varying(50),
    descrizionestruttura character varying(100),
    flag_origine smallint
);


ALTER TABLE public.rub_docente OWNER TO universibo;

--
-- Name: sms_inviati; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW sms_inviati AS
    SELECT notifica."timestamp", date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))) AS giorno, date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))) AS mese, date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))) AS anno, count(notifica."timestamp") AS count FROM notifica WHERE ((notifica.urgente = 'S'::bpchar) AND ("substring"((notifica.destinatario)::text, 1, 3) = 'sms'::text)) GROUP BY notifica."timestamp" ORDER BY date_part('year'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))), date_part('month'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))), date_part('day'::text, ('1970-01-01 00:00:00'::timestamp without time zone + ((notifica."timestamp")::double precision * '00:00:01'::interval))), notifica."timestamp";


ALTER TABLE public.sms_inviati OWNER TO universibo;

--
-- Name: VIEW sms_inviati; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW sms_inviati IS 'Visualizza gli sms inviati ogni giorno';


--
-- Name: stat_accessi_id_accesso_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE stat_accessi_id_accesso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stat_accessi_id_accesso_seq OWNER TO universibo;

--
-- Name: stat_canale_file; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW stat_canale_file AS
    SELECT c.id_canale, count(fic.id_file) AS canale_files, sum(fi.dimensione) AS canale_dimensione, sum(fi.download) AS canale_download FROM file_canale fic, canale c, file fi WHERE ((fic.id_canale = c.id_canale) AND (fi.id_file = fic.id_file)) GROUP BY c.id_canale;


ALTER TABLE public.stat_canale_file OWNER TO universibo;

--
-- Name: VIEW stat_canale_file; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW stat_canale_file IS 'seleziona le statistiche sui files dei canali';


--
-- Name: stat_canale_info; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW stat_canale_info AS
    SELECT info_didattica.id_canale, 1 AS flag_info FROM info_didattica WHERE (((((((((info_didattica.programma <> ''::text) OR ((info_didattica.programma_link)::text <> ''::text)) OR (info_didattica.testi_consigliati <> ''::text)) OR ((info_didattica.testi_consigliati_link)::text <> ''::text)) OR (info_didattica.modalita <> ''::text)) OR ((info_didattica.modalita_link)::text <> ''::text)) OR (info_didattica.obiettivi_esame <> ''::text)) OR ((info_didattica.obiettivi_esame_link)::text <> ''::text)) OR (info_didattica.appelli <> ''::text));


ALTER TABLE public.stat_canale_info OWNER TO universibo;

--
-- Name: VIEW stat_canale_info; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW stat_canale_info IS 'Pone un flag_info=1 se vi sono informazioni nell''insegnamento';


--
-- Name: stat_canale_news; Type: VIEW; Schema: public; Owner: universibo
--

CREATE VIEW stat_canale_news AS
    SELECT c.id_canale, count(nc.id_news) AS canale_news FROM canale c, news_canale nc WHERE (c.id_canale = nc.id_canale) GROUP BY c.id_canale ORDER BY count(nc.id_news) DESC;


ALTER TABLE public.stat_canale_news OWNER TO universibo;

--
-- Name: VIEW stat_canale_news; Type: COMMENT; Schema: public; Owner: universibo
--

COMMENT ON VIEW stat_canale_news IS 'Seleziona il totale di news inserite in ogni canale';


--
-- Name: stat_download_id_download_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE stat_download_id_download_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stat_download_id_download_seq OWNER TO universibo;

--
-- Name: stat_login_id_login_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE stat_login_id_login_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stat_login_id_login_seq OWNER TO universibo;

--
-- Name: stat_visite_id_visita_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE stat_visite_id_visita_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stat_visite_id_visita_seq OWNER TO universibo;

--
-- Name: step_id_step_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE step_id_step_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.step_id_step_seq OWNER TO universibo;

--
-- Name: step_log; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE step_log (
    id_step integer DEFAULT nextval(('"step_id_step_seq"'::text)::regclass) NOT NULL,
    id_utente integer NOT NULL,
    data_ultima_interazione integer NOT NULL,
    nome_classe character varying(255) NOT NULL,
    esito_positivo character(1)
);


ALTER TABLE public.step_log OWNER TO universibo;

--
-- Name: step_parametri; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE step_parametri (
    id_step integer NOT NULL,
    callback_name character varying(255) NOT NULL,
    param_name character varying(255) NOT NULL,
    param_value character varying(255) NOT NULL
);


ALTER TABLE public.step_parametri OWNER TO universibo;

--
-- Name: studente_richi_id_argomento_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE studente_richi_id_argomento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.studente_richi_id_argomento_seq OWNER TO universibo;

--
-- Name: studente_richiede_id_utente_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE studente_richiede_id_utente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.studente_richiede_id_utente_seq OWNER TO universibo;

--
-- Name: utente_canale; Type: TABLE; Schema: public; Owner: universibo; Tablespace: 
--

CREATE TABLE utente_canale (
    id_utente integer NOT NULL,
    id_canale integer NOT NULL,
    ultimo_accesso integer,
    ruolo integer,
    my_universibo character(1),
    notifica integer,
    nascosto character(1) DEFAULT 'N'::bpchar,
    nome character varying(60)
);


ALTER TABLE public.utente_canale OWNER TO universibo;

--
-- Name: utente_richied_id_argomento_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE utente_richied_id_argomento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.utente_richied_id_argomento_seq OWNER TO universibo;

--
-- Name: utente_richiede_a_id_utente_seq; Type: SEQUENCE; Schema: public; Owner: universibo
--

CREATE SEQUENCE utente_richiede_a_id_utente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.utente_richiede_a_id_utente_seq OWNER TO universibo;

--
-- Name: id_canale; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY canale ALTER COLUMN id_canale SET DEFAULT nextval('canale_id_canale_seq'::regclass);


--
-- Name: id_file_categoria; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY file_categoria ALTER COLUMN id_file_categoria SET DEFAULT nextval('file_categoria_id_file_categoria_seq'::regclass);


--
-- Name: id_commento; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY file_studente_commenti ALTER COLUMN id_commento SET DEFAULT nextval('file_studente_commenti_id_commento_seq'::regclass);


--
-- Name: id_file_tipo; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY file_tipo ALTER COLUMN id_file_tipo SET DEFAULT nextval('file_tipo_id_file_tipo_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user ALTER COLUMN id SET DEFAULT nextval('fos_user_id_seq'::regclass);


--
-- Name: id_help; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY help ALTER COLUMN id_help SET DEFAULT nextval('help_id_help_seq'::regclass);


--
-- Name: id_link; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY link ALTER COLUMN id_link SET DEFAULT nextval('link_id_link_seq'::regclass);


--
-- Name: id_notifica; Type: DEFAULT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY notifica ALTER COLUMN id_notifica SET DEFAULT nextval('notifica_id_notifica_seq'::regclass);


--
-- Name: argomento_id_argomento_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('argomento_id_argomento_seq', 1, false);


--
-- Name: argomento_set_id_argomento__seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('argomento_set_id_argomento__seq', 1, false);


--
-- Data for Name: canale; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO canale VALUES (1, 2, 'Home', NULL, 19, NULL, 127, ' ', NULL, 'N', NULL, NULL, 'S', 'S');


--
-- Name: canale_id_canale_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('canale_id_canale_seq', 1, true);


--
-- Data for Name: classi_corso; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: classi_materie; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: collaboratore; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: collaboratore_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('collaboratore_id_seq', 1, false);


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO contacts VALUES (1, 2, 'moderator@example.org', NULL, NULL, '2013-01-30 14:48:47');


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('contacts_id_seq', 1, true);


--
-- Data for Name: docente; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: docente2; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: docente_contatti; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: facolta; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: file; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: file_canale; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: file_categoria; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: file_categoria_id_file_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('file_categoria_id_file_categoria_seq', 1, false);


--
-- Name: file_id_file_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('file_id_file_seq', 1, false);


--
-- Data for Name: file_keywords; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: file_studente_canale; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: file_studente_commenti; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: file_studente_commenti_id_commento_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('file_studente_commenti_id_commento_seq', 1, false);


--
-- Data for Name: file_tipo; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: file_tipo_id_file_tipo_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('file_tipo_id_file_tipo_seq', 1, false);


--
-- Name: forums_auth_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('forums_auth_id_seq', 1, false);


--
-- Data for Name: fos_group; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: fos_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('fos_group_id_seq', 1, false);


--
-- Data for Name: fos_user; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO fos_user VALUES (1, 'admin', 'admin', 'admin@example.org', 'admin@example.org', true, 'w4frwrfdpsvm4''3r0iefwpocmzxoHwkrwer0', 'oQJgi8BRCtV60Iq7+g3iXr34Wn86i/VwpLG/LCw1MXpprs8gcxpHF+OWa6IzFzROdaD0sR+y9jmLhVZzzI1pmg==', '2013-01-30 14:41:24', false, false, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', false, NULL, NULL, 0, 64, NULL, true, NULL, NULL);
INSERT INTO fos_user VALUES (3, 'professor', 'professor', 'professor@example.org', 'professor@example.org', true, 'XfXwfjsd,.mwre.WwAervBuZatBhax', 'hy+pZG2Gs6MG6cRmtod1uPGP2CmuC2UByhFG0ttl7eV4ujfmCbxYl5IG05CYUaMbioD84LCe3llcwz6QPG/PNw==', NULL, false, false, NULL, NULL, NULL, 'a:1:{i:0;s:14:"ROLE_PROFESSOR";}', false, NULL, NULL, 0, 16, NULL, true, NULL, NULL);
INSERT INTO fos_user VALUES (2, 'moderator', 'moderator', 'moderator@example.org', 'moderator@example.org', true, 'WEFcdcdsv4252::edcqr32.exX,4534,m,', 'k69027W0fKS8GRwFd/AhVP1vs3yg53FPTxutplIwGFQeydHAjPk8l+5jxMvBh3paEVXIOa8j7YBu7SZZwAxFfA==', '2013-01-30 14:57:05', false, false, NULL, NULL, NULL, 'a:1:{i:0;s:14:"ROLE_MODERATOR";}', false, NULL, NULL, 0, 4, NULL, true, NULL, NULL);
INSERT INTO fos_user VALUES (4, 'tutor', 'tutor', 'tutor@example.org', 'tutor@example.org', true, 'CcXcvwe#TewtX,.crtwet8342,'',', '82Q1V3vZQhDZEp83YGH0GiPwHisCcl8IILq0JNvelOcrrnUu24Ipp1VpB3O9dcnjZ+rgwXSHcoBIu7IfSEvquA==', NULL, false, false, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_TUTOR";}', false, NULL, NULL, 0, 8, NULL, true, NULL, NULL);
INSERT INTO fos_user VALUES (6, 'student', 'student', 'student@example.org', 'student@example.org', true, 'sdf"324##[]Ccd', 'pfA4ZXpBVJDQBIba3PkNuqbWyrMlMmUapjxLIRZLFnX9+NibweGVaCSLA/UE+587Fr9k+8pz873g1viEs8HhtQ==', NULL, false, false, NULL, NULL, NULL, 'a:1:{i:0;s:12:"ROLE_STUDENT";}', false, NULL, NULL, 0, 2, NULL, true, NULL, NULL);
INSERT INTO fos_user VALUES (5, 'staff', 'staff', 'staff@example.org', 'staff@example.org', true, 'sdfSD4"323432#@][', 'C+NTFvThOFzAB21i7Pz+nhpeIPFpW3xRQTOqigCv4aPD93/Q1TRXiYEmTJrWfWvEZRS/wV/YtQRiZ4OGhJ8SwA==', NULL, false, false, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_STAFF";}', false, NULL, NULL, 0, 32, NULL, true, NULL, NULL);


--
-- Data for Name: fos_user_group; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: fos_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('fos_user_id_seq', 6, true);


--
-- Data for Name: fos_user_ismemberof; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: help; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO help VALUES (7, 'Come faccio a registrarmi presso il Portale Unibo?', 'La registrazione al portale è automatica per tutti gli studenti. Se non sei ancora studente ma vuoi iscriverti all''università puoi farlo all''indirizzo [url]http://studenti.unibo.it/preregistrazione[/url]', 1, 50);
INSERT INTO help VALUES (13, 'Cosa é My UniversiBO?', 'My UniversiBO è lo strumento fondamentale di personalizzazione del sito; è infatti [b]fondamentale[/b] per lo sfruttamento del servizio di [url=/help/#id16]news[/url]: [b]si possono ricevere le notizie esclusivamente degli esami/servizi inseriti all''interno della propria pagina My UniversiBO[/b]!!!!', 1982198217, 100);
INSERT INTO help VALUES (35, 'Come posso modificare il ciclo e l''anno di corso', 'Basta inserire il dato corretto nell''apposita casella di testo (i valori possibili si trovano in basso nel box di correzione).', 1117109715, 330);
INSERT INTO help VALUES (4, 'Cos''e'' la mail d''ateneo?', 'E'' una casella di posta elettronica fornita [b]gratuitamente[/b] dall''Università ad ogni studente.  Vi si puo'' accedere dal sito [url]https://www.dsa.unibo.it/AccessoPostaStudenti/[/url].', 19000, 20);
INSERT INTO help VALUES (10, 'Come faccio a loggarmi al sito?', 'Il login (procedura di identificazione e accesso al sistema) non è obbligatorio: è possibile navigare all''interno delle sezioni del sito anche senza essere loggati; tuttavia si accederà come esterni e quindi molte funzionalità saranno ridotte. Naturalmente possono effettuarlo solo gli utenti in possesso di una mail @unibo.it / @studio.unibo.it o @esterni.unibo.it.
Per effettuare il login[list][*]cliccate sul link login in alto a destra a fianco del lucchetto;[*]quindi inserite, se richieste le vostre credenziali di ateneo.[/list] Il sistema a questo punto vi riconoscerà e leggerà i vostri diritti di accesso in base ai quali creerù dinamicamente le pagine e vi appariranno i relativi links.', 111, 80);
INSERT INTO help VALUES (24, 'Chi puo'' inserire una notizia on line?', 'Questa e'' una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.', 282828, 210);
INSERT INTO help VALUES (32, 'Come posso inserire un messaggio in una discussione?', 'Per inserire un messaggio in una discussione gia'' avviata e'' sufficiente entrare nel thread e cliccare sul pulsante ''Reply''.  Comparira'' una nuova finestra in cui, oltre ad un form per scrivere il nuovo post, scorrendo verso il basso si potranno vedere i messaggi che sono gia'' stati inseriti nel thread.  Oltre a diverse opzioni utili per ''abbellire'' il messaggio, per limitarsi ad inserirlo e'' sufficiente scrivere il testo e cliccare su ''Invia''.  A fianco del pulsante ''Invia'' c''e'' l''opzione ''Anteprima'', con la quale e'' possibile vedere come apparira'' il messaggio prima di spedirlo effettivamente.
Una volta cliccato su ''Invia'', comparira'' una nuova finestra in cui verra'' notificato che la procedura e'' stata effettuata con successo, e comparira'' la possibilita'' di essere inviati alla discussione in cui si e'' intervenuti, nel punto in cui si e'' inserito il proprio messaggio, oppure di essere reindirizzati alla pagina generale della sezione del forum cui appartiene la discussione in cui si e'' scritto.', 13421312, 270);
INSERT INTO help VALUES (19, 'Le news vengono spedite sulla mia mail d''ateneo, come faccio a cambiare l''indirizzo in cui ricevere la posta?', 'Bisogna andare nella pagina delle  [url=/my/settings/]Impostazioni[/url], cliccare su ''Impostazioni Personali'' e, nel popup che vi comparirà, andare a modificare il campo ''Indirizzo e-mail'' con quello desiderato. ', 125632367, 150);
INSERT INTO help VALUES (15, 'Come faccio a rimuovere una pagina dalla mia pagina My UniversiBO?', 'Si accede alla pagina d''interesse e si clicca sul link sotto al titolo della pagina "Rimuovi questa pagina da My UniversiBO". La pagina verrà tolta dal menù di sinistra e per accedere alla pagina sarà necessario passare attraverso il corso di laurea corrispondente o attraverso il menù Servizi.', 1231231321, 120);
INSERT INTO help VALUES (23, 'Come faccio a scaricare un file da UniversiBO?', 'Una volta giunti alla pagina in cui e'' contenuto il file, bisogna cliccare sull''icona che rappresenta il download del file, a fianco del titolo del file: si aprirà la finestra con cui salvare sul proprio computer il file desiderato.
Alcuni files sono scaricabili solo se iscritti ad UniversiBO, altri solo tramite l''apposita password comunicata dal professore.', 21786278, 200);
INSERT INTO help VALUES (26, 'Chi puo'' inserire un file su UniversiBO?', 'Questa è una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.
Gli utenti del sito hanno a disposizione uno spazio ove caricare i propri files (appunti, esercizi svolti).
 [b]E'' severamente proibito caricare files con contenuto inadeguato (pornografico, razzista, pedofilo...): la responsabilità dei files caricati è dell''utente, ma i files vengolo controllati a campione e periodicamente. UniversiBO si riserva il diritto di cancellare quelli non conformi alle regole e di bannare l''utente.[/b]', 2132131, 160);
INSERT INTO help VALUES (29, 'Come faccio ad eliminare una notizia?', 'Per eliminare una notizia (sempre che se ne abbia i diritti, ovvero se si è un collaboratore o un professore) bisogna cliccare sul link ''Elimina''. Si passerà ad una finestra dove verrà mostrata la notizia e dove si potranno selezionare le pagine da cui si può cancellarla. Una volta selezionato almeno una pagina, se si è sicuri di volere cancellare la notizia, cliccare su ''Elimina''.', 1231231, 240);
INSERT INTO help VALUES (31, 'Che cos''e'' un forum?', 'Un forum e'' una bacheca virtuale in cui ogni utente puo'' inserire ([b]postare[/b]) un messaggio ([b]post[/b]).
Un forum e'' organizzato in sezioni, a loro volta suddivise in discussioni ([b]threads[/b] o [b]topic[/b]).
N.B.: per poter interagire attivamente nel forum [url=/help/#id10]bisogna essere loggati[/url].', 23412314, 260);
INSERT INTO help VALUES (5, 'Perche'' devo avere la mail di Ateneo per iscrivermi ad UniversiBO?', 'Perche'' [url=/help/#id4]la mail di Ateneo[/url] viene assegnata univocamente dall''Universita'' ad ogni singolo studente iscritto, e'' dunque un metodo d''identificazione di ogni utente registrato al sito UniversiBO.', 1000, 30);
INSERT INTO help VALUES (11, 'Come faccio a modificare la password?', 'È possibile cambiare la propria password attraverso il [url=https://www.dsa.unibo.it/]Directory Service d''Ateneo[/url].', 11111, 90);
INSERT INTO help VALUES (14, 'Come faccio ad inserire un esame/servizio all''interno del My UniversiBO?', 'Per inserire un esame all''interno del [url=/help/#id13]My UniversiBO[/url] è necessario cercare l''esame stesso nel corso di laurea ad esso corrispondente, o il servizio interessato nell''apposito menù sulla sinistra, e una volta entrati nella pagina relativa, cliccare sul link che trovate sotto le informazioni dell''esame: ''Aggiungi questa pagina a My UniversiBO''.
Si passerà a una pagina nella quale verrà richiesta il livello di notifica desiderato per le news (Tutti, Solo Urgenti o Nessuna: indica la tipologia di news che desideri ricevere nella tua casella di posta) ed eventualmente un nome personalizzato per la pagina, massimo 60 caratteri.
Così facendo, ad ogni accesso al sito, l''esame in questione/il servizio desiderato comparirà nel vostro menù di sinistra alla voce ''My UniversiBO'', rendendo così più immediato e veloce l''accesso alla sezione relativa (vi sarà inoltre una scritta ''NEW'' ad indicarvi se vi sono aggiornamenti dall''ultimo vostro accesso alla pagina).
Inoltre, cliccando sull''immagine in alto ""My UniversiBO"" accederete alla vostra pagina MyUniversiBO, dove potrete visualizzare le ultime 5 news e gli ultimi 5 files inseriti nelle pagine all''interno del vostro My UniversiBO, e i files che voi avete caricato su UniversiBO.', 12632212, 110);
INSERT INTO help VALUES (21, 'Come faccio a modificare un file gia'' inserito nella pagina?', 'Per Modificare un file già presente sul sito bisogna cliccare sull''apposita icona, posta di fianco al titolo del file (ovviamente l''icona comparirà solo se l''utente ha i diritti necessari, ovvero solo se è un collaboratore o un professore).
Si passerà a una pagina analoga a quella utilizzata per [url=/help/#id20]caricare il file on line[/url] con le informazioni che sono già state inserite.
Effettuate le modifiche necessarie, è sufficiente cliccare sul pulsante ''Modifica''.
Le modifiche verranno apportate a tutte le pagine  in cui è stato inserito il file.
Per inserire una password, selezionare ''Abilita password'' e scrivere la password negli appositi campi: ricordate che [b]i campi password sono riservati solo ai professori o ai file che i professori chiedono al moderatore di caricare sulla pagina[/b].
Se le modifiche avranno successo,si passerà ad una pagina dove saranno presenti i link per tornare alla scheda del file o all''insegnamento in cui era stato inserito.', 123719263, 180);
INSERT INTO help VALUES (27, 'Come faccio ad inserire una notizia?', 'Il procedimento per caricare una notizia sul sito è questo:[list=1][*][url=/help/#id10]Accedere al sito con il proprio nome utente[/url] (username).[*][url=/help/#id3]Andare nella pagina[/url] in cui si desidera che compaia la notizia: scorrendo verso il basso si potranno anche vedere le news che sono già state caricate in quella pagina.[*]Cliccare su ''Scrivi una nuova notizia''. Così facendo si passerà a una pagina con il form per spedire una notizia. I campi da compilare sono:[list][*]Titolo: inserire un titolo significativo per la notizia.[*]Data e Ora di inserimento: questi campi vengono completati automaticamente; se lo si desidera, si puòcambiarle: ciò può essere molto utile nel caso in cui si voglia che la notizia non compaia prima di una certa data e ora in quanto [b]le news vengono visualizzate solo se la data attuale è posteriore a quella di inserimento[/b].
[*]Notizia: bisogna inserire qui il testo della notizia cercando di essere il più sintetici possibile.[*]Attiva scadenza: selezionando questo campo e compilando quelli che indicano la data e l''ora di scadenza, si fisserà il momento in cui la notizia non sarà più visualizzata nella pagina.  è consigliabile inserire sempre una scadenza, se possibile: in tal modo si semplifica la manutenzione del sito che così diventa automatica, e la pagina non verrà appesantita da notizie che risulteranno inutili dopo una certa data e dunque non sarà più utile leggere.[*]Data e Ora di scadenza: nel caso sia stato attivato il servizio di scadenza, bisogna riempire questi campi per fissare il momento in cui la notizia diverrà inutile.
[*]Invia il messaggio come urgente: selezionando questa opzione, la notizia giungerà ad un maggior numero di persone che hanno sia inserito l''insegnamento nei loro preferiti, sia inserito il proprio numero di cellulare nel profilo.
[*]La notizia verrà inserita negli argomenti: qui vengono selezionati tutti gli insegnamenti/corsi in cui l''utente può inserire una notizia. Selezionando più di una casella, la notizia verrà inserita in tutte le pagine corrispondenti. [b]Attenzione! [url=/help/#id28]Se la notizia viene modificata[/url], la modifica comparirà in tutte le pagine dove è presente![/b]
[/list][*]Cliccare su ''Inserisci''.[/list]Se la procedura è stata completata con successo, verrà visualizzata una pagina che confermerà che la notizia è stata inserita, quindi si può cliccare sul link ""Torna a..."" con cui si ritornerà alla pagina dove è stata inserita la notizia, che sarà presente in cima. Altrimenti si tornerà al form d''inserimento della notizia.', 12321314, 220);
INSERT INTO help VALUES (28, 'Come faccio a modificare una notizia?', 'Per modificare una notizia già presente sul sito bisogna cliccare su link ''Modifica'' che vi viene visualizzato al di sotto della notizia (ovviamente l''opzione comparira'' solo se l''utente ha i diritti necessari, ovvero solo se è un collaboratore o un professore).
Si passerà a una pagina analoga a quella per [url=/help/#id27]l''inserimento della notizia[/url] con la notizia come è ancora memorizzata in alto, e sotto il form con le informazioni che sono già state scritte. Effettuate le modifiche necessarie, è sufficiente cliccare sul pulsante ''Modifica''.
Se la modifica ha avuto successo, comparirà una notifica. La modifica comparirà in tutte quelle pagine in cui era stata inserita precedentemente la notizia.', 132123123, 230);
INSERT INTO help VALUES (20, 'Come faccio ad inserire un file su UniversiBO?', 'Il procedimento per caricare un file sul sito è questo:[list=1][*][url=/help/#id10]Accedere al sito col proprio nome utente[/url] (username)[*][url=/help/#id3]Andare nella pagina[/url] in cui si desidera che compaia il file: scorrendo verso il basso si potranno anche vedere i files che sono già stati caricati in quella pagina.[*]Cliccare su ''Invia nuovo file''.  Così facendo vi comparirà una nuova finestra con un certo numero di campi da compilare:[list][*]File.  In questo campo bisogna selezionare il percorso sul computer dell''utente per raggiungere il file che si desidera caricare.    Per rendere più veloce il processo di upload (e conseguentemente di download da parte degli utenti che desidereranno scaricare il file sul loro computer) è consigliabile comprimere il file.
NB: si può caricare [b]un solo file alla volta[/b], quindi per mettere on line più files bisogna ripetere la procedura
[*]Titolo: Il titolo serve per distinguere il file, ed è il testo che comparirà nella pagina dove sono presenti tutti i files, quindi deve essere significativo.
[*]Descrizione.  Serve per dare una descrizione esauriente del contenuto del file.
[*]Parole chiave: si possono inserire al massimo 4 parole chiave, separate da un Enter/Invio. Tramite le parole chiave, si facilita la ricerca del file.
[*]Categoria: Tramite questo menù a discesa si può specificare se il file appartiene ad una determinata categoria (appunti/lucidi/esercitazioni...). Se non appartiene a nessuna di quelle presenti, lasciare ""altro"".
[*]Data e ora d''inserimento. Questo campo apparira'' gia'' compilato con la data e l''ora correnti.  L''utente ha comunque la possibilità di modificarli, nel caso desideri che essi compaiano in un secondo momento: data e ora infatti determinano il momento in cui [b]diverranno visibili agli utenti[/b] i dati caricati.
[*]Permessi download: Si può scegliere se il file è scaricabile da chiunque, o solo da persone iscritte al sito UniversiBO.
[*]Password e conferma password: questi campi sono [b]riservati solo ai professori o ai file che i professori chiedono al moderatore di caricare sulla pagina[/b]. Attivandoli, il file sarà scaricabile solo se si è a conoscenza della password corretta.
[*]Il file verrà inserito negli argomenti: qui vengono selezionati tutti gli insegnamenti/corsi in cui l''utente può inserire un file. Selezionando più di una casella, il file verrà inserito in tutte le pagine corrispondenti.[b] Attenzione!  [url=/help/#id21]Se il file viene modificato[/url], la modifica comparirà in tutte le pagine dove è presente![/b]
[/list]
[*]Cliccare su invia.
[/list]Se la procedura è stata completata con successo, si passerà a un''ulteriore pagina che confermerà che il file è stato salvato. Cliccando sul link ""Torna a..."" si tornerà alla pagina dove è stato inserito', 1276378, 170);
INSERT INTO help VALUES (16, 'A cosa serve il servizio di news di UniversiBO?', 'Il servizio di news di UniversiBO permette di essere avvisati in tempo reale via e-mail/sms di ogni notizia che viene inserita nella pagina dell''esame d''interesse: è [b]necessario[/b]  [url=/help/#id14]inserire l''esame nel proprio MyUniversiBO[/url] per potere sfruttare questo servizio.', 113274632, 130);
INSERT INTO help VALUES (33, 'Cos''è "modifica didattica"?', '""Modifica didattica"" è un tool che permette di modificare le informazioni su anno, ciclo, docente per ogni insegnamento e sdoppiamento di UniversiBO.
Nella tabella ""Insegnamento selezionato"" sono presenti le informazioni riguardanti l''insegnamento/sdoppiamento che si sta per modificare. Se si tratta di uno sdoppiamento, in fondo alla tabella è presente ""status: sdoppiato"", altrimenti non compare.', 1117109715, 310);
INSERT INTO help VALUES (9, 'Come faccio ad iscrivermi ad UniversiBO?', 'È sufficiente essere in possesso di una mail @unibo.it / @studio.unibo.it / @esterni.unibo.it ed effettuare la procedura di login. All''accettazione del regolamento e dell''informativa sulla privacy l''iscrizione è completata.', 100101, 70);
INSERT INTO help VALUES (3, 'Come faccio a navigare nel sito?', 'Le pagine del sito sono strutturate cosi'':[list][*] in alto vi è l''intestazione di UniversiBO con una piccola barra di navigazione rapida;[*]il menu di sinistra di navigazione rimane più o meno lo stesso per tutte le pagine e vi guida anche all''esterno del sito;[*]la parte centrale corrisponde alla pagina attualmente navigata;[*]il menu di destra contiene il form per il login degli utenti, alcuni servizi riferiti alla parte centrale(ad esempio il calendario).[/list]Quando entrerete nel sito vi ritroverete nella homepage in cui vi saranno le informazioni riguardanti le novità sul sito.

La struttura delle pagine interne del sito è più o meno sempre la stessa e si basa sul concetto di argomento. I servizi e le informazioni vengono ricostruiti intorno all''argomento che può essere un esame o un corso di laurea.
Nel corpo centrale verranno visualizzati alcuni contenuti, i link principali interni all''argomento, le ultime news relative a quell''argomento, il forum (se presente in quell''argomento) e le news.
Nel menu di destra verrà visualizzato il calendario di quel particolare argomento.', 10111111, 10);
INSERT INTO help VALUES (2, 'Come faccio a cercare un utente?', 'Oltre alla ricerca per username ed email esatte, si può cercare mediante l''uso di [b]caratteri speciali[/b]:
[list]
[*]%: Inserendo un simbolo di percentuale, ricerca un qualunque usernamente/email che inizia con i caratteri inseriti prima, e con una qualsiasi serie di caratteri che seguono.
[*]_: tramite l''underscore, dopo la parte note, si ricerca qualunque username/email che proseguono con X caratteri di un qualunque tipo, con X il numero di underscore.
[/list]
Esempi:
[list]
[*]L%: ricerca tutti gli username/email che iniziano con L
[*]b____: ricerca tutti gli username/email che iniziano con b e sono lunghi 5 caratteri
[/list]', 111111, 300);
INSERT INTO help VALUES (22, 'Come faccio ad eliminare un file?', 'Per eliminare un file (sempre che se ne abbia i diritti, ovvero se si è un collaboratore o un professore) bisogna cliccare sull''apposita icona, che si trova tra l''icona del modifica e quella per scaricare il file, di fianco al titolo del file.  Si passerà a una finestra per confermare la cancellazione. 
Si potrà scegliere da quali insegnamenti cancellare il file (almeno uno).
Se si è sicuri di volerlo cancellare, cliccare su ''Elimina''.
A questo punto nessuno potrà più accedere al file al di fuori degli amministratori. Infatti, per ragioni di [b]sicurezza[/b], verrà conservata una copia del file non accessibile dal web.', 182791, 190);
INSERT INTO help VALUES (34, 'Cos''è uno sdoppiamento?', 'E'' un insegnamento comune a più corsi di laurea (stesso docente, stesso orario, ecc. ). Avremo un insegnamento ""padre"" (nel corso di laurea che attiva l''insegnamento) e i relativi ""figli"" (gli sdoppiati, negli altri corsi di laurea).
Esempio:
SISTEMI DI TELECOMUNICAZIONI L-A è attivato da INGEGNERIA DELLE TELECOMUNICAZIONI specialistica (insegnamento padre).
Ma è presente anche nel corso di laurea INGEGNERIA INFORMATICA specialistica (insegnamento figlio).
Se vi sono insegnamenti/sdoppiamenti correlati a quello che si sta modificando, viene visualizzata una lista dopo i box di modifica. Se nella lista è presente il ""padre"", sarà evidenziato in azzurro (se non c''è significa che il padre è quello selezionato inizialmente).
E'' possibile applicare le modifiche anche agli insegnamenti/sdoppiamenti correlati selezionando quelli di interesse.
E'' possibile spostarsi negli insegnamenti/sdoppiamenti correlati, cliccando su un insegnamento della lista.', 1117109715, 320);
INSERT INTO help VALUES (37, 'Come rendo effettive le modifiche?', 'Cliccando sul tasto "Esegui". Una volta premuto, non è possibile interrompere l''operazione di modifica quindi prima di mandare in esecuzione una modifica, controllate due volte per non fare errori.', 1117109715, 360);
INSERT INTO help VALUES (36, 'Come posso modificare il docente?', 'Inserendo il codice docente nell''apposita casella di testo: il docente può essere modificato solo nel caso in cui non sia ancora stato creato il forum dell''insegnamento e solo dall''insegnamento padre. Quindi se siete su uno sdoppiamento, cliccate sull''insegnamento evidenziato in azzurro per effettuare la modifica del docente. Il docente modificato, sarà cambiato anche per tutti gli insegnamenti correlati (padre/figli).
Nel caso in cui non si conosca il codice del docente è possibile effettuare una ricerca per username o per e-mail. Nella ricerca si possono utilizzare dei caratteri jolly:[list][*] % sostituisce un qualsiasi numero di caratteri (esempio: una ricerca e% nel campo username, trova tutti gli username che iniziano con la lettera e)[*] _ sostituisce un singolo carattere (esempio: una ricerca ner_ nel campo username, trova tutti gli username che iniziano per ner e terminano con qualsiasi lettera)[/list] ', 1117109715, 350);
INSERT INTO help VALUES (17, 'Come faccio a personalizzare il servizio di news?', 'Una volta [url=/help/#id14]aggiunto l''esame (o il servizio) al proprio MyUniversiBO[/url], viene impostata automaticamente dal sistema la possibilità di ricevere tutte le  [url=/help/#id16]news[/url] che vengono inserite nella pagina dell''esame d''interesse.  Una volta [url=/help/#id10]loggati[/url], andando nella pagina [url=/my/settings/]''Impostazioni personali''[/url] (link in alto a sinistra) e  cliccando su ''Profilo'', troverete un campo in cui è segnato il vostro indirizzo e-mail a cui verranno spedite le news, e la possibilità di scegliere quali notizie ricevere e quali no. E'' inoltre possibile inserire il proprio numero di cellulare per ricevere gratuitamente le notifiche anche sul proprio telefonino.
E'' possibile scegliere tra i seguenti livelli di notifica [list][*] Tutti: tutte le news arriveranno sia via mail che via sms; [*] Solo urgenti: riceverete solo gli sms; [*] Nessuna: non riceverete nessuna notifica né via mail, né via sms [/list]
è comunque sempre possibile modificare la scelta di notifica effettuata, ripetendo le operazioni sopra indicate.', 1231232, 140);


--
-- Name: help_id_help_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('help_id_help_seq', 40, true);


--
-- Data for Name: help_riferimento; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO help_riferimento VALUES ('newsutenti', 24);
INSERT INTO help_riferimento VALUES ('newscollabs', 27);
INSERT INTO help_riferimento VALUES ('newscollabs', 28);
INSERT INTO help_riferimento VALUES ('newscollabs', 29);
INSERT INTO help_riferimento VALUES ('newscollabs', 30);
INSERT INTO help_riferimento VALUES ('newsutenti', 16);
INSERT INTO help_riferimento VALUES ('newsutenti', 17);
INSERT INTO help_riferimento VALUES ('newsutenti', 19);
INSERT INTO help_riferimento VALUES ('filesutenti', 26);
INSERT INTO help_riferimento VALUES ('filesutenti', 23);
INSERT INTO help_riferimento VALUES ('filescollabs', 20);
INSERT INTO help_riferimento VALUES ('filescollabs', 21);
INSERT INTO help_riferimento VALUES ('filescollabs', 22);
INSERT INTO help_riferimento VALUES ('iscrizione', 9);
INSERT INTO help_riferimento VALUES ('iscrizione', 11);
INSERT INTO help_riferimento VALUES ('iscrizione', 4);
INSERT INTO help_riferimento VALUES ('iscrizione', 5);
INSERT INTO help_riferimento VALUES ('iscrizione', 6);
INSERT INTO help_riferimento VALUES ('iscrizione', 7);
INSERT INTO help_riferimento VALUES ('iscrizione', 8);
INSERT INTO help_riferimento VALUES ('suggerimentinav', 10);
INSERT INTO help_riferimento VALUES ('suggerimentinav', 3);
INSERT INTO help_riferimento VALUES ('myuniversibo', 14);
INSERT INTO help_riferimento VALUES ('myuniversibo', 15);
INSERT INTO help_riferimento VALUES ('myuniversibo', 13);
INSERT INTO help_riferimento VALUES ('ruoliadmin', 1);
INSERT INTO help_riferimento VALUES ('ruoliadmin', 2);
INSERT INTO help_riferimento VALUES ('didatticagestione', 33);
INSERT INTO help_riferimento VALUES ('didatticagestione', 34);
INSERT INTO help_riferimento VALUES ('didatticagestione', 35);
INSERT INTO help_riferimento VALUES ('didatticagestione', 36);
INSERT INTO help_riferimento VALUES ('didatticagestione', 37);
INSERT INTO help_riferimento VALUES ('filestudenti', 20);
INSERT INTO help_riferimento VALUES ('filestudenti', 21);
INSERT INTO help_riferimento VALUES ('filestudenti', 22);


--
-- Data for Name: help_topic; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO help_topic VALUES ('iscrizione', 'Come faccio ad iscrivermi ad UniversiBO?', 10);
INSERT INTO help_topic VALUES ('suggerimentinav', 'Navigazione nel sito: i primi passi.', 30);
INSERT INTO help_topic VALUES ('filescollabs', 'Voglio mettere un file on line su UniversiBO: come posso fare?', 70);
INSERT INTO help_topic VALUES ('filesutenti', 'Come faccio a scaricare i files da UniversiBO?', 60);
INSERT INTO help_topic VALUES ('myuniversibo', 'Come personalizzare My UniversiBO.', 40);
INSERT INTO help_topic VALUES ('newsutenti', 'Cos''é e come gestire il servizio di News di UniversiBO.', 50);
INSERT INTO help_topic VALUES ('ruoliadmin', 'Cercare un utente e cambiare i diritti (solo Admin)', 90);
INSERT INTO help_topic VALUES ('newscollabs', 'Voglio inserire una notizia su UniversiBO: come posso fare?', 80);
INSERT INTO help_topic VALUES ('didatticagestione', 'Modificare un insegnamento e cercare un codice docente (solo admin e collaboratori)', 100);


--
-- Data for Name: info_didattica; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: informativa; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: informativa_id_informativa_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('informativa_id_informativa_seq', 1, false);


--
-- Data for Name: input_esami_attivi; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: ismemberof; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: ismemberof_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('ismemberof_id_seq', 1, false);


--
-- Data for Name: link; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: link_id_link_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('link_id_link_seq', 1, false);


--
-- Data for Name: migration_versions; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO migration_versions VALUES ('20120609002753');
INSERT INTO migration_versions VALUES ('20120919204917');
INSERT INTO migration_versions VALUES ('20120919210230');
INSERT INTO migration_versions VALUES ('20120921151004');
INSERT INTO migration_versions VALUES ('20120924000212');
INSERT INTO migration_versions VALUES ('20120924165745');
INSERT INTO migration_versions VALUES ('20121011120358');
INSERT INTO migration_versions VALUES ('20121011154244');
INSERT INTO migration_versions VALUES ('20121011175005');
INSERT INTO migration_versions VALUES ('20121026171424');
INSERT INTO migration_versions VALUES ('20121026172131');
INSERT INTO migration_versions VALUES ('20121030112258');
INSERT INTO migration_versions VALUES ('20121030125240');
INSERT INTO migration_versions VALUES ('20121030140048');
INSERT INTO migration_versions VALUES ('20121030222900');
INSERT INTO migration_versions VALUES ('20121031130458');
INSERT INTO migration_versions VALUES ('20121104114246');
INSERT INTO migration_versions VALUES ('20121104132749');
INSERT INTO migration_versions VALUES ('20121109124047');
INSERT INTO migration_versions VALUES ('20121113220954');
INSERT INTO migration_versions VALUES ('20121221214920');
INSERT INTO migration_versions VALUES ('20130102154033');
INSERT INTO migration_versions VALUES ('20130125021448');


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Data for Name: news_canale; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: news_id_news_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('news_id_news_seq', 1, false);


--
-- Data for Name: notifica; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: notifica_id_notifica_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('notifica_id_notifica_seq', 1, false);


--
-- Data for Name: people; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: people_id_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('people_id_seq', 1, false);


--
-- Data for Name: prg_insegnamento; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: prg_sdop_id_sdop_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('prg_sdop_id_sdop_seq', 1, false);


--
-- Data for Name: prg_sdoppiamento; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: prg_sdoppiamento_r_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('prg_sdoppiamento_r_seq', 1, false);


--
-- Name: prg_sdoppiamento_rr_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('prg_sdoppiamento_rr_seq', 1, false);


--
-- Data for Name: questionario; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: questionario_id_questionari_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('questionario_id_questionari_seq', 1, false);


--
-- Data for Name: rub_docente; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: stat_accessi_id_accesso_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('stat_accessi_id_accesso_seq', 1, false);


--
-- Name: stat_download_id_download_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('stat_download_id_download_seq', 1, false);


--
-- Name: stat_login_id_login_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('stat_login_id_login_seq', 1, false);


--
-- Name: stat_visite_id_visita_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('stat_visite_id_visita_seq', 1, false);


--
-- Name: step_id_step_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('step_id_step_seq', 2, true);


--
-- Data for Name: step_log; Type: TABLE DATA; Schema: public; Owner: universibo
--

INSERT INTO step_log VALUES (1, 1, 1359556896, 'Universibo\Bundle\LegacyBundle\Command\InteractiveCommand\InformativaPrivacyInteractiveCommand', 'S');
INSERT INTO step_log VALUES (2, 2, 1359557762, 'Universibo\Bundle\LegacyBundle\Command\InteractiveCommand\InformativaPrivacyInteractiveCommand', 'S');


--
-- Data for Name: step_parametri; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: studente_richi_id_argomento_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('studente_richi_id_argomento_seq', 1, false);


--
-- Name: studente_richiede_id_utente_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('studente_richiede_id_utente_seq', 1, false);


--
-- Data for Name: utente_canale; Type: TABLE DATA; Schema: public; Owner: universibo
--



--
-- Name: utente_richied_id_argomento_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('utente_richied_id_argomento_seq', 1, false);


--
-- Name: utente_richiede_a_id_utente_seq; Type: SEQUENCE SET; Schema: public; Owner: universibo
--

SELECT pg_catalog.setval('utente_richiede_a_id_utente_seq', 1, false);


--
-- Name: canale_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY canale
    ADD CONSTRAINT canale_pkey PRIMARY KEY (id_canale);


--
-- Name: classi_corsi_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY classi_corso
    ADD CONSTRAINT classi_corsi_pkey PRIMARY KEY (cod_corso);


--
-- Name: classi_materie_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY classi_materie
    ADD CONSTRAINT classi_materie_pkey PRIMARY KEY (cod_materia);


--
-- Name: collaboratore_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY collaboratore
    ADD CONSTRAINT collaboratore_pkey PRIMARY KEY (id);


--
-- Name: contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: docente2_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY docente2
    ADD CONSTRAINT docente2_pkey PRIMARY KEY (cod_doc);


--
-- Name: docente_contatti_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY docente_contatti
    ADD CONSTRAINT docente_contatti_pkey PRIMARY KEY (cod_doc);


--
-- Name: docente_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY docente
    ADD CONSTRAINT docente_pkey PRIMARY KEY (cod_doc);


--
-- Name: facolta_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY facolta
    ADD CONSTRAINT facolta_pkey PRIMARY KEY (cod_fac);


--
-- Name: file_canale_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_canale
    ADD CONSTRAINT file_canale_pkey PRIMARY KEY (id_file, id_canale);


--
-- Name: file_categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_categoria
    ADD CONSTRAINT file_categoria_pkey PRIMARY KEY (id_file_categoria);


--
-- Name: file_keywords_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_keywords
    ADD CONSTRAINT file_keywords_pkey PRIMARY KEY (id_file, keyword);


--
-- Name: file_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file
    ADD CONSTRAINT file_pkey PRIMARY KEY (id_file);


--
-- Name: file_studente_canale_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_studente_canale
    ADD CONSTRAINT file_studente_canale_pkey PRIMARY KEY (id_file);


--
-- Name: file_studente_commenti_id_file_key; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_studente_commenti
    ADD CONSTRAINT file_studente_commenti_id_file_key UNIQUE (id_file, id_utente, id_commento);


--
-- Name: file_studente_commenti_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_studente_commenti
    ADD CONSTRAINT file_studente_commenti_pkey PRIMARY KEY (id_commento);


--
-- Name: file_tipo_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY file_tipo
    ADD CONSTRAINT file_tipo_pkey PRIMARY KEY (id_file_tipo);


--
-- Name: fos_group_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY fos_group
    ADD CONSTRAINT fos_group_pkey PRIMARY KEY (id);


--
-- Name: fos_user_ismemberof_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY fos_user_ismemberof
    ADD CONSTRAINT fos_user_ismemberof_pkey PRIMARY KEY (user_id, ismemberof_id);


--
-- Name: fos_user_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY fos_user
    ADD CONSTRAINT fos_user_pkey PRIMARY KEY (id);


--
-- Name: fos_user_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY fos_user_group
    ADD CONSTRAINT fos_user_user_group_pkey PRIMARY KEY (user_id, group_id);


--
-- Name: help_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY help
    ADD CONSTRAINT help_pkey PRIMARY KEY (id_help);


--
-- Name: help_riferimento_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY help_riferimento
    ADD CONSTRAINT help_riferimento_pkey PRIMARY KEY (riferimento, id_help);


--
-- Name: help_topic_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY help_topic
    ADD CONSTRAINT help_topic_pkey PRIMARY KEY (riferimento);


--
-- Name: info_didattica_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY info_didattica
    ADD CONSTRAINT info_didattica_pkey PRIMARY KEY (id_canale);


--
-- Name: informativa_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY informativa
    ADD CONSTRAINT informativa_pkey PRIMARY KEY (id_informativa);


--
-- Name: ismemberof_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY ismemberof
    ADD CONSTRAINT ismemberof_pkey PRIMARY KEY (id);


--
-- Name: link_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY link
    ADD CONSTRAINT link_pkey PRIMARY KEY (id_link);


--
-- Name: migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY migration_versions
    ADD CONSTRAINT migration_versions_pkey PRIMARY KEY (version);


--
-- Name: news_canale_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY news_canale
    ADD CONSTRAINT news_canale_pkey PRIMARY KEY (id_news, id_canale);


--
-- Name: news_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id_news);


--
-- Name: notifica_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY notifica
    ADD CONSTRAINT notifica_pkey PRIMARY KEY (id_notifica);


--
-- Name: people_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY people
    ADD CONSTRAINT people_pkey PRIMARY KEY (id);


--
-- Name: questionario_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY questionario
    ADD CONSTRAINT questionario_pkey PRIMARY KEY (id_questionario);


--
-- Name: rub_docente_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY rub_docente
    ADD CONSTRAINT rub_docente_pkey PRIMARY KEY (cod_doc);


--
-- Name: sdoppiamenti_attivi_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY prg_sdoppiamento
    ADD CONSTRAINT sdoppiamenti_attivi_pkey PRIMARY KEY (anno_accademico, anno_corso, anno_corso_ins, cod_corso, cod_ind, cod_materia, cod_materia_ins, cod_ori, cod_ril);


--
-- Name: step_log_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY step_log
    ADD CONSTRAINT step_log_pkey PRIMARY KEY (id_step);


--
-- Name: step_parametri_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY step_parametri
    ADD CONSTRAINT step_parametri_pkey PRIMARY KEY (id_step, callback_name, param_name);


--
-- Name: utente_argomento_pkey; Type: CONSTRAINT; Schema: public; Owner: universibo; Tablespace: 
--

ALTER TABLE ONLY utente_canale
    ADD CONSTRAINT utente_argomento_pkey PRIMARY KEY (id_utente, id_canale);


--
-- Name: canale_id_canale_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX canale_id_canale_key ON canale USING btree (id_canale);


--
-- Name: classi_materie_cod_materia_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX classi_materie_cod_materia_key ON classi_materie USING btree (cod_materia);


--
-- Name: docente2_cod_doc_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX docente2_cod_doc_key ON docente2 USING btree (cod_doc);


--
-- Name: docente_cod_doc_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX docente_cod_doc_key ON docente USING btree (cod_doc);


--
-- Name: file_canale_id_canale_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX file_canale_id_canale_key ON file_canale USING btree (id_canale);


--
-- Name: file_canale_id_file_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX file_canale_id_file_key ON file_canale USING btree (id_file);


--
-- Name: file_studente_canale_id_file_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX file_studente_canale_id_file_key ON file_studente_canale USING btree (id_file);


--
-- Name: idx_33401573a76ed395; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_33401573a76ed395 ON contacts USING btree (user_id);


--
-- Name: idx_957a6479217bbb47; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_957a6479217bbb47 ON fos_user USING btree (person_id);


--
-- Name: idx_b3c77447a76ed395; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_b3c77447a76ed395 ON fos_user_group USING btree (user_id);


--
-- Name: idx_b3c77447fe54d947; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_b3c77447fe54d947 ON fos_user_group USING btree (group_id);


--
-- Name: idx_e352958d45e18be7; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_e352958d45e18be7 ON fos_user_ismemberof USING btree (ismemberof_id);


--
-- Name: idx_e352958da76ed395; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX idx_e352958da76ed395 ON fos_user_ismemberof USING btree (user_id);


--
-- Name: news_canale_id_canale_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX news_canale_id_canale_key ON news_canale USING btree (id_canale);


--
-- Name: news_canale_id_news_key; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX news_canale_id_news_key ON news_canale USING btree (id_news);


--
-- Name: notifica_eliminata; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX notifica_eliminata ON notifica USING btree (eliminata);


--
-- Name: notifica_timestamp; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX notifica_timestamp ON notifica USING btree ("timestamp");


--
-- Name: questionario_id_questionario_ke; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE INDEX questionario_id_questionario_ke ON questionario USING btree (id_questionario);


--
-- Name: uniq_28166a266c57f6ed; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_28166a266c57f6ed ON people USING btree (unibo_id);


--
-- Name: uniq_4b019ddb5e237e06; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_4b019ddb5e237e06 ON fos_group USING btree (name);


--
-- Name: uniq_957a647992fc23a8; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_957a647992fc23a8 ON fos_user USING btree (username_canonical);


--
-- Name: uniq_957a6479a0d96fbf; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_957a6479a0d96fbf ON fos_user USING btree (email_canonical);


--
-- Name: uniq_b6092a05f872060d; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_b6092a05f872060d ON collaboratore USING btree (id_utente);


--
-- Name: uniq_df975e575e237e06; Type: INDEX; Schema: public; Owner: universibo; Tablespace: 
--

CREATE UNIQUE INDEX uniq_df975e575e237e06 ON ismemberof USING btree (name);


--
-- Name: classi_corso_id_canale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY classi_corso
    ADD CONSTRAINT classi_corso_id_canale_fkey FOREIGN KEY (id_canale) REFERENCES canale(id_canale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: docente_id_utente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY docente
    ADD CONSTRAINT docente_id_utente_fkey FOREIGN KEY (id_utente) REFERENCES fos_user(id) ON DELETE SET NULL;


--
-- Name: facolta_id_canale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY facolta
    ADD CONSTRAINT facolta_id_canale_fkey FOREIGN KEY (id_canale) REFERENCES canale(id_canale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_33401573a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT fk_33401573a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user(id);


--
-- Name: fk_957a6479217bbb47; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user
    ADD CONSTRAINT fk_957a6479217bbb47 FOREIGN KEY (person_id) REFERENCES people(id);


--
-- Name: fk_b3c77447a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user_group
    ADD CONSTRAINT fk_b3c77447a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user(id);


--
-- Name: fk_b3c77447fe54d947; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user_group
    ADD CONSTRAINT fk_b3c77447fe54d947 FOREIGN KEY (group_id) REFERENCES fos_group(id);


--
-- Name: fk_b6092a05f872060d; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY collaboratore
    ADD CONSTRAINT fk_b6092a05f872060d FOREIGN KEY (id_utente) REFERENCES fos_user(id);


--
-- Name: fk_e352958d45e18be7; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user_ismemberof
    ADD CONSTRAINT fk_e352958d45e18be7 FOREIGN KEY (ismemberof_id) REFERENCES ismemberof(id);


--
-- Name: fk_e352958da76ed395; Type: FK CONSTRAINT; Schema: public; Owner: universibo
--

ALTER TABLE ONLY fos_user_ismemberof
    ADD CONSTRAINT fk_e352958da76ed395 FOREIGN KEY (user_id) REFERENCES fos_user(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

