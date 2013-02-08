
-- modifiche phpbb 2.0.6
CREATE TABLE phpbb_confirm (
    confirm_id character(32) DEFAULT '' NOT NULL,
    session_id character(32) DEFAULT '' NOT NULL,
    code character(6) DEFAULT '' NOT NULL
);

ALTER TABLE ONLY phpbb_confirm
    ADD CONSTRAINT phpbb_confirm_pkey PRIMARY KEY (session_id, confirm_id);

INSERT INTO "phpbb_config" ("config_name", "config_value") VALUES ('enable_confirm', '0');
INSERT INTO "phpbb_config" ("config_name", "config_value") VALUES ('sendmail_fix', '0');
UPDATE "phpbb_config" SET config_value = '.0.10' WHERE config_name='version';

-- modifiche tabella utente livello -> groups
ALTER TABLE "utente" ADD "groups" int4 ;

UPDATE "utente" SET "groups" = 2 WHERE "livello" = 100;
UPDATE "utente" SET "groups" = 4 WHERE "livello" = 200;
UPDATE "utente" SET "groups" = 8 WHERE "livello" = 300;
UPDATE "utente" SET "groups" = 16 WHERE "livello" = 400;
UPDATE "utente" SET "groups" = 32 WHERE "livello" = 600;
UPDATE "utente" SET "groups" = 64 WHERE "livello" = 500;

ALTER TABLE "utente" DROP COLUMN "livello";
-- creazione tabella canale   (si poteva fare in modo molto pi� semplice... vedi utente_canale)
CREATE TABLE "canale" (
"id_canale" SERIAL, 
"tipo_canale" int4 NOT NULL, 
"nome_canale" varchar (60) , 
"immagine" varchar (50) , 
"visite" int4 NOT NULL, 
"ultima_modifica" int4 , 
"permessi_groups" int4 , 
"files_attivo" char (1) , 
"news_attivo" char (1), 
"forum_attivo" char (1), 
"id_forum" int4 , 
"group_id" int4 ,
PRIMARY KEY ("id_canale"), UNIQUE ("id_canale"));
CREATE INDEX "canale_id_canale_key" ON "canale"("id_canale"); 
-- importa dati argomento->canale
UPDATE argomento SET visite=0 WHERE visite IS NULL;
INSERT INTO "canale" ( "id_canale" ,"tipo_canale" , "nome_canale" , "immagine" , "visite" , "ultima_modifica" , "permessi_groups" , "files_attivo" , "news_attivo" , "forum_attivo" , "id_forum" , "group_id" ) 
    SELECT "id_argomento" ,0 , "nome_argomento" , "immagine" , "visite" , "ultima_modifica" , 0, "files_attivo" , "news_attivo" , "forum_attivo" , "id_forum" , "group_id" FROM argomento;

UPDATE canale SET permessi_groups = permessi_groups+1 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE  '1_______');
UPDATE canale SET permessi_groups = permessi_groups+2 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE  '__1_____');
UPDATE canale SET permessi_groups = permessi_groups+4 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE  '___1____');
UPDATE canale SET permessi_groups = permessi_groups+8 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE  '____1___');
UPDATE canale SET permessi_groups = permessi_groups+16 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE '_____1__');
UPDATE canale SET permessi_groups = permessi_groups+32 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE '_______1');
UPDATE canale SET permessi_groups = permessi_groups+64 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE diritti_visualizzazione LIKE '______1_');

UPDATE canale SET tipo_canale = 1 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE tipo_argomento='A' );
UPDATE canale SET tipo_canale = 2 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE tipo_argomento='H' );
UPDATE canale SET tipo_canale = 3 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE tipo_argomento='F' );
UPDATE canale SET tipo_canale = 4 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE tipo_argomento='C' );
UPDATE canale SET tipo_canale = 5 WHERE canale.id_canale IN ( SELECT id_argomento FROM argomento WHERE tipo_argomento='E' );
UPDATE canale SET tipo_canale = 6 WHERE canale.id_canale IN ( SELECT a.id_argomento FROM argomento a, esami_attivi b, classi_corso c WHERE a.id_argomento=b.id_argomento AND b.cod_corso=c.cod_corso AND c.cod_fac='0054' );
-- modifica utente_argomento
ALTER TABLE "utente_argomento" RENAME "id_argomento" TO "id_canale"; ALTER TABLE "utente_argomento" ALTER "id_canale" DROP DEFAULT ;
ALTER TABLE "utente_argomento" ADD "ruolo" int4 ;
ALTER TABLE "utente_argomento" ADD "my_universibo" char (1) ;
ALTER TABLE "utente_argomento" RENAME TO "utente_canale";

UPDATE utente_canale SET my_universibo='S' WHERE 1=1;
UPDATE utente_canale SET ruolo=1 WHERE diritti='M';
UPDATE utente_canale SET ruolo=2 WHERE diritti='R';

ALTER TABLE "utente_canale" DROP COLUMN "diritti";
-- nuovi campi in utente_argomento
ALTER TABLE "utente_canale" ADD "notifica" int4 ;
ALTER TABLE "utente_canale" ADD "nome" char (60) ;

-- nuovi campi in canale
ALTER TABLE "canale" ADD "links_attivo" char (1) ;     

SELECT setval('canale_id_canale_seq', nextval('argomento_id_argomento_seq'));
UPDATE canale SET nome_canale = 'Homepage', permessi_groups=127 WHERE id_canale=1;

ALTER TABLE "facolta" RENAME "id_argomento" TO "id_canale"; 

ALTER TABLE "classi_corso" RENAME "id_argomento" TO "id_canale"; 
ALTER TABLE "classi_corso" ADD "categoria" int4;

UPDATE classi_corso SET categoria=2 WHERE cod_corso='0067';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0023';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0044';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0045';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0049';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0050';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0051';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0052';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0053';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0055';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0057';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0221';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0231';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0232';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0234';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2141';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2142';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2143';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2145';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2146';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2147';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2148';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2149';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2150';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2163';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2151';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0054';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0022';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0218';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='2140';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='5402';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0048';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0047';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0046';
UPDATE classi_corso SET categoria=2 WHERE cod_corso='0233';
UPDATE classi_corso SET categoria=1 WHERE cod_corso='0025';
UPDATE classi_corso SET categoria=3 WHERE cod_corso='5407';

ALTER TABLE "classi_corso" DROP COLUMN "menu_corso";


ALTER TABLE "facolta" DROP COLUMN "menu_facolta";
ALTER TABLE "facolta" DROP COLUMN "abbr_facolta";
DROP TABLE "argomento";

UPDATE "canale" SET "permessi_groups"=127 WHERE tipo_canale=4 OR tipo_canale=5 OR tipo_canale=6;

--------16-10-2003
UPDATE canale SET permessi_groups=127 WHERE tipo_canale=3;

--------21-10-2003
ALTER TABLE "esami_attivi" DROP COLUMN "cod_attivita";

--------22-10-2003

ALTER TABLE "news" RENAME TO "news2";

ALTER TABLE news2
 DROP CONSTRAINT news_pkey;

CREATE TABLE "news" (
   "id_news" int4 DEFAULT nextval('"news_id_news_seq"'::text) NOT NULL,
   "titolo" varchar(150) NOT NULL,
   "data_inserimento" int4 NOT NULL,
   "data_scadenza" int4,
   "notizia" text,
   "id_utente" int4 NOT NULL,
   "eliminata" char(1) DEFAULT 'N' NOT NULL,
   "flag_urgente" char(1) DEFAULT 'N' NOT NULL,
   CONSTRAINT "news_pkey" PRIMARY KEY ("id_news")
);

INSERT INTO news ( "id_news" , "titolo" , "data_inserimento", "data_scadenza", "notizia" , "id_utente", "eliminata") 
SELECT "id_news" , "titolo" , "data_inserimento", "data_scadenza", "notizia" , "id_utente", "eliminata" FROM news2 ;

CREATE TABLE "news_canale" (
"id_news" int4 NOT NULL, 
"id_canale" int4 NOT NULL ,
PRIMARY KEY ("id_news", "id_canale"));
CREATE INDEX "news_canale_id_news_key" ON "news_canale"("id_news"); 
CREATE INDEX "news_canale_id_canale_key" ON "news_canale"("id_canale");

------30-10-2003

INSERT INTO news_canale ( "id_news" , "id_canale") 
SELECT "id_news" ,  "id_argomento" FROM news2 ;

---query per configurarvi il path del forum
--UPDATE phpbb_config SET config_value='localhost' WHERE config_name='server_name';
--UPDATE phpbb_config SET config_value='localhost' WHERE config_name='cookie_domain';
--UPDATE phpbb_config SET config_value='/universibo2/htmls/forum/' WHERE config_name='script_path';
--UPDATE phpbb_config SET config_value='0' WHERE config_name='cookie_secure';

-----03-11-2003
ALTER TABLE "utente" ADD "ban" char (1) ;
ALTER TABLE "utente" ALTER "ban" SET DEFAULT 'N';
UPDATE "utente" SET ban='N' WHERE 1=1;

-----06-11-2003
CREATE TABLE "collaboratore" (
 "id_utente" int4 PRIMARY KEY,
 "intro" text ,
 "ruolo" varchar(50) ,
 "recapito" varchar(255),
 "obiettivi" text 
);
 
 
 ----13-11-2003

ALTER TABLE "collaboratore" ADD "foto" varchar (255) ;

ALTER TABLE "collaboratore" DROP COLUMN "ruolo";
ALTER TABLE "collaboratore" ADD "ruolo" varchar (255) ;
 

---eliminazione della tabella studente

DROP TABLE "studente";

---eliminazione degli attributi non utilizzati della tabella docente

ALTER TABLE "docente"   DROP COLUMN "email";
ALTER TABLE "docente"   DROP COLUMN "nome";
ALTER TABLE "docente"   DROP COLUMN "cognome";
ALTER TABLE "docente"   DROP COLUMN "qualifica";
ALTER TABLE "docente"   DROP COLUMN "sesso";
ALTER TABLE "docente"   DROP COLUMN "data_nascita";
ALTER TABLE "docente"   DROP COLUMN "telefono_1";
ALTER TABLE "docente"   DROP COLUMN "telefono_2";
ALTER TABLE "docente"   DROP COLUMN "ufficio";
ALTER TABLE "docente"   DROP COLUMN "icq";
ALTER TABLE "docente"   DROP COLUMN "homepage";

----04-12-2003

----eliminazione attributi non utilizzati in questionario

ALTER TABLE "questionario" DROP COLUMN "win";
ALTER TABLE "questionario" DROP COLUMN "linux";
ALTER TABLE "questionario" DROP COLUMN "html";
ALTER TABLE "questionario" DROP COLUMN "php";
ALTER TABLE "questionario" DROP COLUMN "javascript";
ALTER TABLE "questionario" DROP COLUMN "xml";
ALTER TABLE "questionario" DROP COLUMN "java";
ALTER TABLE "questionario" DROP COLUMN "photoshop";
ALTER TABLE "questionario" DROP COLUMN "gimp";
ALTER TABLE "questionario" DROP COLUMN "sql";


----09-12-2003
--aggiunta timestamp ultima modifica della notizia
ALTER TABLE "news" ADD "data_modifica" int4 ;
UPDATE news SET data_modifica = data_inserimento;
--correzione dati tabella
UPDATE utente_canale SET ruolo=0 WHERE ruolo is NULL;

----11-12-2003
--aggiunta possibilit� di nascondere la visualizzazione di un ruolo/contatto
ALTER TABLE "utente_canale" ADD "nascosto" char (1) ;
ALTER TABLE "utente_canale" ALTER "nascosto" SET DEFAULT 'N';
UPDATE utente_canale SET nascosto = 'N';
--aggiunto preside alle tabelle
ALTER TABLE "facolta" ADD "cod_doc" char (6) ;   ---bisogna ancora inserire manualmente i dati dei presidi di facolta



-----29-01-04
CREATE TABLE help(
id_help int4 PRIMARY KEY,
titolo varchar(255) NOT NULL,
contenuto text NOT NULL,
ultima_modifica int4 NOT NULL,
indice int4 NOT NULL);

CREATE TABLE help_riferimento(
riferimento varchar(32) PRIMARY KEY,
id_help int4 NOT NULL
);


-----04-02-04

DROP TABLE help;

CREATE TABLE help(
id_help serial PRIMARY KEY,
titolo varchar(255) NOT NULL,
contenuto text NOT NULL,
ultima_modifica int4 NOT NULL,
indice int4 NOT NULL);

------14-02-04
DROP TABLE help_riferimento;

CREATE TABLE help_riferimento(
riferimento varchar(32) ,
id_help int4,
PRIMARY KEY(riferimento, id_help)
);

------01-03-04
CREATE TABLE "help_topic" (
"riferimento" varchar (32) NOT NULL, 
"titolo" varchar (256) NOT NULL ,
PRIMARY KEY ("riferimento"));

------15-03-04
ALTER TABLE "esami_attivi" RENAME TO "prg_insegnamento";
ALTER TABLE "sdoppiamenti_attivi" RENAME TO "prg_sdoppiamento";
ALTER TABLE "esami_attivi2" RENAME TO "prg_insegnamento2";
ALTER TABLE "sdoppiamenti_attivi2" RENAME TO "prg_sdoppiamento2";
ALTER TABLE "prg_insegnamento" RENAME "id_argomento" TO "id_canale"; 

ALTER TABLE "prg_insegnamento"   DROP COLUMN "prog_cronologico";

------23-03-04
UPDATE "canale" SET "tipo_canale"=5 WHERE tipo_canale=6;

------07-09-04

INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('727', 'studentessa di ingegneria delle telecomunicazioni', '3295432013', 'Mi sono unita per collaborare alla realizzazione di un servizio valido e utile come Universibo e per cercare di ampliare le mie scarse conoscenze informatiche', NULL, 'collaboro alle attivit\340 off-line,scrittura contenuti e moderazione');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('96', 'Bukowski non si sarebbe mai arreso alla settima ripresa. Tra il dire e il fare, chi visse sperando. Piove. ', '3496692919', 'Cerco di dare una mano, quando posso. Non \350 facile gestire la vita universitaria, gli esami, le donne e il vino, ma sono un gestionale, del resto. E'' uno sporco lavoro, ma qualcuno deve pur farlo, non credete ?', 'budwhite.jpg', 'moderatore - lesto tuttofare');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('110', 'Studentessa di Ingegneria Gestionale. Nel progetto si occupa insieme a Matteo della grafica del sito e della stesura di pagine HTML', NULL, 'Ho iniziato a collaborare con Andrea e Matteo alla moderazione della mailing list per i gestionali e quando \350 iniziato il progetto di UniversiBO \350 stato praticamente impossibile non farmi coinvolgere. Ho sempre avuto il pallino del computer ma non ho mai avuto modo di approfondire... ora non solo ho l''opportunit\340 di imparare tante cose ma quel che faccio mi piace e mi da soddisfazione. Ogni giorno ho modo di confrontarmi, di imparare e sperimentare cosa vuol dire lavorare in gruppo... se a tutto questo aggiungiamo che questo gruppo di matti \350 straordinario... il gioco \350 fatto!', NULL, 'admin - responsabile della grafica');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('105', 'Ciao, sono Francesco! Sono studente di Ingegneria dei Processi Gestionali, amo lo sport (praticato!) e il "far baracca"! Mi occupo principalmente dell''Analisi dei Servizi e della Logistica.', '3283030235', '\310 senz''altro una possibilit\340 pi\371 unica che rara per fare esperienza diretta in un team di progetto! [...]\r\n\r\nBla bla bla... Avevo scritto un profilo molto serio e barboso, e rileggendolo sembrava scritto da un ingegnere e ho preso paura... quindi tabula rasa e se volete sapere qualcosa in pi\371 su di me non avete che da chiamarmi. No, gli insulti non sono graditi...\r\nP.S.\r\nNon ho ancora ben capito che animale sia un Ingegnere dei Processi Gestionali, ma arriver\362 in fondo a questa storia! Ciau!', NULL, 'admin - gestione, analisi dei servizi, logistica');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('115', 'Responsabile informatico del Cieg, con la passione per il software Open Source.', '0512093946', 'I miei obiettivi sono "istituzionali" nel senso che fanno parte del mio ruolo lavorativo presso il Cieg e principalmente comprendono l''amministrazione dell''infrastruttura informatica di una parte della sede di via Saragozza n.8. Questo progetto si discosta leggermente rispetto alle situazioni standard incontrate fino ad ora e non so se inserirlo nella lista dei compiti "istituzionali", ma comunque ha ricevuto il mio appoggio fin dal principio. Lavorare con gli altri membri dello staff permette di creare un circolo virtuoso di scambio di idee e quindi ognuno di noi pu\362 imparare qualcosa dagli altri.', NULL, 'admin di sistema e addetto alla sicurezza');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('79', 'Studente di Ingegneria Gestionale super esperto in informatica. Nel progetto si occupa dell''amministrazione di sistema e del design grafico.', '3288311503', 'Le motivazioni che mi spingono a lavorare su questo progetto sono strutturate, nel senso che si sono ampliate e modificate col tempo. Alla radice c''\350 sicuramente il desiderio di creare qualcosa di utile, poi mi piace l''idea di applicare quello che studio a qualcosa di concreto. L''esperienza di gestione della mailing list ha contribuito ad accrescere la voglia di implementare un servizio creato dagli studenti e per gli studenti. Queste forse sono le motivazioni a pi\371 basso livello, quelle che c''erano fin dall''inizio e che si sono mantenute; a rinforzo di queste, con l''avanzare del progetto il gruppo che si \350 formato e le gratificazioni ricevute da professori e compagni mi hanno spinto e mi spingono ad andare avanti ben consapevole dell''importanza del servizio che andremo a offrire.', NULL, 'admin - sistemista e designer');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('517', 'Studentessa di ingegneria informatica al 2 anno. Sono di Ravenna e tra qua e l\340 mi piace lavorare al computer, imparare sempre cose nuove a riguardo; mi piace leggere, informarmi e... farmi i fatti degli altri... Nel progetto mi occupo di gestire i collaboratori, vecchi e nuovi, e vedere di farli rigare dritto :P . ', '3393626294 ', 'Mi piace poter essere utile agli altri, fare qualcosa che \350 contemporaneamente un aiuto e un incentivo per gli studenti, ed istruttivo per me (per le mie capacit\340 informatiche e non). Penso che sia un ottimo progetto e sono convinta della sua utilit\340 quindi, anche se inizialmente la mia partecipazione al progetto era solo marginale, ora sta prendendo sempre pi\371 piede nella mia vita e nel mio tempo libero. ', NULL, 'admin - gestione collaboratori');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('92', 'Studente di Ingegneria dei Processi Gestionali. Si occupa di tutto ci\362 che riguarda le attivit\340 non gestibili via computer: contatto con i docenti, studenti, presentazioni, volantinaggio, manifestazioni, corsi, etc....', '3392888793', 'Ho iniziato a collaborare a UniversiBO perch\351 mi dava la possibilit\340 di imparare cose che sui libri o in un aula universitaria non \350 possibile imparare. Con UniversiBO ho conosciuto molti amici, che mi hanno aiutato ad imparare e crescere le mie conoscenze tecniche e non. ', NULL, 'admin - attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('650', 'ing. mi piace per le persone, non certo per quello che si studia. chi pensa che analisi sia utile per creare un array alzi la mano. a parte questo, sono un fanatico dei computer, scout, e pazzo! (nonch\350 figlio di ferroviere, e quindi non pago lu treno!)', '3402246549', 'arrivare ad un numero di macchine amministrate talmente alto che scorder\362 gli ip di tutte, far capire agli utenti che la password "pippo" non \350 la pi\371 sicura, installare macchine da 200$ con linux e far capire a chi compra super-server-strafichi-con-windows-xp a 20000$ che le mie macchine vanno meglio e sono pi\371 sicure, essere lo zefram cochrane dei computer... ', NULL, 'sys-admin, sviluppatore sw, attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('88', 'Studente di Ingegneria Gestionale, proveniente da Macerata, tifoso del Milan', '3392517183', 'Aiutare il gruppo a realizzare un sito ben fatto e specialmente utile e di facile comprensione. E poi la gratificazione di un lavoro ben fatto \350 il massimo che si possa chiedere.. ', NULL, 'moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('491', 'studentessa di ingegneria delle telecomunicazioni', '3478005673', 'Mi sono unita al progetto con la speranza di incrementare le mie (ridotte) capacit\340 informatiche e contemporaneamente di poter collaborare alla progressiva realizzazione di un''idea senz''altro valida, quale UniversiBo mi era sembrata fin dall''inizio.', NULL, 'moderatore - collaboratrice progettazione grafica');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('520', 'Mi chiamo Daniele Tiles, studio Ingegneria Informatica e sono di Bologna...basta cos\354 O volete anche il numero di scarpe?', '3284139075', 'diventare un ingegnere, capire tutto il possibile su computer e affini...cosa volete di pi\371? UniversiBO m''interessa tantissimo...quando avr\362 le capacit\340 adatte, entrer\362 anch''io nel ramo della progettazione. Il mio obbiettivo principale in UniversiBO \350 aiutare al massimo gli studenti che arrivano...non \350 facile abituarsi a questo nuovo ambiente, ed UniversiBO secondo me \350 lo strumento ideale!', NULL, 'moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('342', 'studente di Ingegneria Gestionale ', '3289760725', NULL, NULL, 'collaboratore attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('167', 'studente di ing gestionale patito di informatica e tutto ci\362 che ruota intorno ad internet ', '3478005673', 'Aumentare la mia conoscenza dei vari software nella progettazione di carattere web, conoscere le basi di un server, dalla sua realizzazione al suo mantenimento! Ma soprattutto divertirmi con un gruppo di amici!!! ', NULL, 'collaboratore nelle sezioni Test, Progettazione, Benchmarking - moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('83', '23 anni, iscritta al IV anno di Ingegneria Gestionale, spero di uscire presto dall''Universit\340, ma nel frattempo vale la pena di dedicarsi un po'' al progetto UniversiBo ', '3382305493', 'Mi occupo quasi esclusivamente dell''attivit\340 OffLine, contatto con i docenti in aula e in dipartimento. Mi sono trovata bene con le persone che lavorano al progetto, ho stima dell''impegno che ci stanno mettendo e condivido le loro speranze su quello che ne verr\340 fuori!', NULL, 'moderatore - attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('577', 'sono uno studente di ingegneria delle telecomunicazioni con l''hobby per l''informatica (e sopratutto per gli anime)', '3331553398', 'ci\362 che mi ha spinto a collaborare \350 stato prima di tutto la curiosit\340 per qualcosa di nuovo, poi l''interesse di fare quasi da tramite tra professori e studenti, con la voglia di partecipare a qualcosa di utile', NULL, 'faccio da referente e moderatore per alcuni esami del mio cdl e provo a realizzare qualcosa per la grafica e la stesura contenuti');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('746', NULL, '3475214764', 'a dire il vero non so bene neanch''io perch\350 abbia deciso di collaborare a universibo, forse perch\350 sar\340 un buono stimolo per applicare ci\362 che studio a un progetto concreto. Visto poi l''entusiasmo che ci mettono tutti gli altri ragazzi....beh....mi sono lasciato coinvolgere e sono convinto che questo coinvolgimento aumenter\340 col tempo.', NULL, 'collaboratore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('99', 'Non trovo nulla di interessante da dire a mio proposito\r\n', '3282213798', 'Non riesco a trovare nulla che non sia terribilmente demagogico x riempire questo campo...', NULL, 'moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('113', NULL, NULL, 'Molti hanno gi\340 detto molto e forse anche troppo; cosa potrei mai aggiungere io di rilevante? Come gi\340 osservai con qualcuno, avrei dovuto elaborare questo scritto ai miei esordi nel progetto - evento che ormai si perde nel tempo e nella memoria - e non ora che ormai l''entusiasmo iniziale va scemando. Concludo quindi questa mia divagazione gi\340 fin troppo lunga, ricordando che le persone che pensano a farsi notare, in realt\340 non sono quelle che contano veramente: quindi basta chiacchiere! perch\350 appoggio incondizionatamente la filosofia di un mio conterraneo: fatti e non pugn...', NULL, 'diffusione del LaTeX quale strumento di condivisione tra gli studenti di appunti e materiale didattico in forma elettronica... pi\371 tutto il resto (ie. integrazione tra grafica e software: sviluppo del sistema dei template)');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('593', 'partito dalla solare citt\340 di Pescara, sto provando ad immedesimarmi nel ruolo di studente di Ingegneria Meccanica nella bella Bologna. Appena posso coltivo le mie passioni:calcio,computer e tutto ci\362 che \350 tecnologia', '3497630035', 'appena saputo di Universibo mi \350 subito piaciuto lo spirito del progetto: studenti che con passione cercano di mettere la tecnologia al servizio di altri studenti per far s\354 che si aiutino l''uno con l''altro! come non farne parte...e magari imparer\362 anche qualcosa!', NULL, 'collaboratore nella sezione grafica-moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('93', 'studente, cercatore d''oro...', '051392346', 'costruzione di un portale web, applicazione di tecnologie orientate al web, comunione dello scibile fra gli studenti... ', NULL, 'moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('150', NULL, NULL, 'dare il mio contributo ad un sito che io giudico all\222avanguardia nell\222ambito dello scambio di notizie e materiale didattico tra docenti e studenti, uno strumento indispensabile per una moderna universit\340 che proprio mancava.\r\n', 'gasp.jpg', 'moderatore-attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('157', NULL, NULL, 'trovo l''iniziativa estremamente utile.. sarebbe un peccato farla \r\nmorire ;-)  e poi contattare professori, parlare in aule rappresentano \r\nsicuramente un bel allenamento per il futuro.\r\n', 'jarod82.jpg', 'collaboratore attivit\340 off-line, moderatore corsi ');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('91', 'Studio ingegneria gestionale al 3 anno, sono studente fuori sede, proveniente da Fabriano. Tra un esame e l''altro i miei hobbyes sono l''informatica ed il netgaming mentre gli sports che seguo di pi\371 sono il Basket, lo Sci e la F1. Amo fare passeggiate in mountain bike quando tra le colline marchigianea e quass\371 a bologna mi orgnanizzo per le "partitelle con gli amici". In un certo senso apprezzo tutti i generi musicali senza distinzione, prediligo cmq pop e ska, ascolto molto la radio (105 4ever) e colleziono le musiche degli spots publicitari... \r\n', '3493940611', 'cercare di coltivare questa community perch\350, come si sa, l''unione fa la forza.... ', 'jolly82.jpg', 'moderatore - collaboratore nella sezione grafica');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('106', 'Studente di Ingegneria Gestionale con tante piccole passioni.All''interno di UniversiBo sono responsabile per alcuni esami e moderatore per altri. Mi occuper\362 inoltre della sezione Erasmus(col sogno di riuscire ad andarci anch''io un giorno ;-D). ', '3394489656', 'Sono tanti e sicuramente ne dimenticher\362 qualcuno. Tra di essi c''\350 sicuramente quello di capire ed usare (un passo per volta magari)i diversi linguaggi utilizzati nelle pagine del sito; la volont\340 di partecipare ad un progetto impossibile da realizzare alle superiori e che mi ha subito affascinato e trovato d''accordo; la voglia come sempre di contribuire a creare e fornire un aiuto per tutti i compagni di Universit\340 col pensiero che "insieme le difficolt\340 si superano meglio" e quindi con la speranza che tutti contribuiscano nel darsi una mano(anche se purtroppo ci sar\340 anche chi "sfrutter\340" solo il servizio vedendo nell''amico solo un "avversario"... mah...). ', 'dexter.jpg', 'moderatore');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('252', 'Antares \350 uno studente di Ingegneria Informatica', '3385403745 ', 'Il mio obiettivo \350 trovarmi una donna ad Ingegneria. Come un ago in un pagliaio.', NULL, 'Amministratore Attivit\340 Offline/Stesura Contenuti');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('81', 'brain \350 un caparbio studente di Ingegneria Informatica. Nel progetto si occupa del lavoro pi\371 importante: la progettazione del software e di buona parte della grafica.', '3381407176', 'Obiettivo iniziale del mio progetto era semplicemente mettere in pratica le prime conoscenze acquisite riguardo HTML, PHP e la realizzazione di applicazioni Web.\r\nAver portato avanti il mio primo progetto mi ha sicuramente fatto piacevolmente imparare molte cose e insieme al lato didattico sono arrivate enormi gratificazioni personali da altri amici, studenti e anche docenti, per aver creato nel mio piccolo qualcosa di grosso aiuto per gli altri... e queste gratificazioni mi hanno spinto ad impegnarmi ancora di pi\371 ad accrescere le mie conoscenze... un circolo virtuoso...\r\nAppena ho conosciuto altri due matti con un progetto simile al mio... beh, quale migliore occasione per aprire un manicomio... e subito altri matti hanno risposto all''appello \r\nOra c''\350 il piacere di poter imparare a lavorare in gruppo, dividere e condividere il lavoro pur mantenendo ognuno la libert\340 di fare quel che gli pare!!!\r\nNuove conoscenze... Progettazione e strutture grafiche web, Basi di Dati, Amministrazione e configurazione del web Server, Gestione di del Progetto in gruppo.... e ad approfondire quelle di prima (...\350 proprio vero che non si finisce mai di imparare!!!)', 'brain.jpg', 'admin - progettista software');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('307', NULL, '3470856915 ', 'so fare poco o nulla: io e i pc non siamo buoni amici, ma collaborare \r\nper universibo mi sembra una bella perdita di tempo : aiuto me, gli altri, \r\nimparo un sacco di cose, conosco ed imparo a lavorare con un sacco di gente simpatica... ', 'bulbis.jpg', 'attivit\340 offline');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('78', 'Studente di Ingegneria Gestionale da sempre fissato con l''informatica. Nel progetto si occupa in particolar modo della gestione e della documentazione.', '3394323246', 'Grazie all''iniziativa di matteo di fondare la mailing list dei gestionali, ho avuto l''opportunit\340 di gestire una comunit\340 di studenti. Gi\340 tale attivit\340 comporta una notevole spesa di tempo e sicuramente il carico del progetto sar\340 molto pi\371 pesante, ma so che quando il sito sar\340 realizzato la soddisfazione ripagher\340 tutte le fatiche. Il motivo forte che mi spinge ogni giorno a lavorare per il progetto \350 che ci\362 che faccio proprio mi piace: mi diverto a programmare, ma soprattutto mi piace dedicare tempo alla gestione del progetto e questo proprio perch\351 diventare direttore di progetti di innovazione(in particolar modo di software) \350 ci\362 che aspiro per il mio futuro. Quale miglior occasione per fare esperienza di questa?', 'eagleone.jpg', 'admin - gestione progetto e documentazione');
INSERT INTO "collaboratore" ("id_utente", "intro", "recapito", "obiettivi", "foto", "ruolo") VALUES ('87', 'Guapoz intorta le donnine ad Ingegneria.', NULL, 'L''obiettivo di Guapoz \350 inserirsi in quanti pi\371 pertugi possibili prima di diventare impotente, la notte del 25 dicembre del 2020.', 'guapoz.jpg', 'Fotti-zio.');






ALTER TABLE "file" RENAME TO "file2";

ALTER TABLE "file2" DROP CONSTRAINT "file_pkey";

--ATTENZIONE qui bisogna fare a mano il drop della costrain sull'indice della tabella files2!!

CREATE TABLE "file" (
"id_file" int4 DEFAULT nextval('"file_id_file_seq"'::text) NOT NULL,
"permessi_download" int4 NOT NULL,
"permessi_visualizza" int4 NOT NULL,
"id_utente" int4 NOT NULL,
"titolo" varchar (150) NOT NULL,
"descrizione" text NULL,
"data_inserimento" int4 NOT NULL,
"data_modifica" int4 NOT NULL,
"dimensione" int4 NOT NULL,
"download" int4 NOT NULL,
"nome_file" varchar (256) NOT NULL,
"id_categoria" int4 NOT NULL,
"id_tipo_file" int4 NOT NULL,
"hash_file" varchar (40) NOT NULL,
"password" varchar (40) ,
"eliminato" char (1) NOT NULL ,
PRIMARY KEY ("id_file"));


CREATE TABLE "file_canale" (
"id_file" int4 NOT NULL, 
"id_canale" int4 NOT NULL ,
PRIMARY KEY ("id_file", "id_canale"));
CREATE INDEX "file_canale_id_file_key" ON "file_canale"("id_file"); 
CREATE INDEX "file_canale_id_canale_key" ON "file_canale"("id_canale");


CREATE TABLE "file_tipo" (
"id_file_tipo" SERIAL NOT NULL,
"descrizione" varchar (128) NOT NULL,
"pattern_riconoscimento" varchar (128) NOT NULL,
"icona" varchar (256) NOT NULL,
"info_aggiuntive" text,
PRIMARY KEY ("id_file_tipo"));


CREATE TABLE "file_categoria" (
"id_file_categoria" SERIAL NOT NULL,
"descrizione" varchar (128) NOT NULL,
PRIMARY KEY ("id_file_categoria"));

INSERT INTO file ( "id_file", "permessi_download", "permessi_visualizza", "id_utente",
"titolo", "descrizione", "data_inserimento", "data_modifica", "dimensione", "download",
"nome_file", "id_categoria", "id_tipo_file", "hash_file", "password", "eliminato" ) 
SELECT "id_file" , '127' , '127', "id_autore",
'' , "descrizione", data, data, dimensione, contatore,
nome_file, 0 , 0 , '', NULL, 'N' FROM file2;

--se in v1 un file era stato eliminato da tutti gli argomenti allora viene 
--impostato come eliminato
UPDATE file SET eliminato = 'S' WHERE id_file IN (
  SELECT id_file from file_riguarda_argomento WHERE eliminato = 'S' AND id_file NOT IN 
  (
    SELECT id_file from file_riguarda_argomento WHERE eliminato = 'N'
  )
  GROUP BY id_file 
);

UPDATE file SET titolo = substring(descrizione from 1 for 100);

-- eseguire lo script: v2.php?do=ScriptUpdateFileHash



INSERT INTO file_categoria (id_file_categoria, descrizione) VALUES (
1, 'dispense');
INSERT INTO file_categoria (id_file_categoria, descrizione) VALUES (
2, 'esercitazioni');
INSERT INTO file_categoria (id_file_categoria, descrizione) VALUES (
3, 'lucidi');
INSERT INTO file_categoria (id_file_categoria, descrizione) VALUES (
4, 'appunti');
INSERT INTO file_categoria (id_file_categoria, descrizione) VALUES (
5, 'altro');

SELECT setval('file_categoria_id_file_categoria_seq', 5);
UPDATE file SET id_categoria = 5;


------08-09-04


INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
1, 'altro', '', 'formato_.gif', '');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
2, 'pdf', '\.pdf$', 'formato_pdf.gif', 'Adobe Portable Document Format');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
3, 'doc', '\.doc$', 'formato_doc.gif', 'Microsoft Word');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
4, 'gif', '\.gif$', 'formato_gif.gif', 'Graphic Interchange Format');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
5, 'html', '\.(html|htm)$', 'formato_html.gif', 'HyperText Mark-Up Language');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
6, 'jpeg', '\.(jpeg|jpg)$', 'formato_jpg.gif', 'Joint Photographic Experts Group');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
7, 'mp3', '\.mp3$', 'formato_mp3.gif', 'Mpeg1 Layer 3');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
8, 'sxw', '\.sxw$', 'formato_sxw.gif', 'Open Office Writer');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
9, 'sxc', '\.sxc$', 'formato_sxc.gif', 'Open Office Calc');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
10, 'sxi', '\.sxi$', 'formato_sxi.gif', 'Open Office Impress');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
11, 'ppt', '\.ppt$', 'formato_ppt.gif', 'Microsoft Power Point');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
12, 'rtf', '\.rtf$', 'formato_rtf.gif', 'Rich Text Format');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
13, 'tex', '\.tex$', 'formato_tex.gif', 'TeX Document');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
14, 'txt', '\.txt$', 'formato_txt.gif', 'File di testo');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
15, 'xls', '\.xls$', 'formato_xls.gif', 'Microsoft Excel');
INSERT INTO file_tipo (id_file_tipo, descrizione, pattern_riconoscimento, icona, info_aggiuntive) VALUES (
16, 'bmp', '\.bmp$', 'formato_bmp.gif', 'Bitmap');


INSERT INTO file_canale (id_file, id_canale) SELECT id_file, id_argomento FROM file_riguarda_argomento;

-- 10-09-2004

UPDATE file SET id_tipo_file = 1 WHERE id_file IN ( SELECT id_file FROM file2 where estensione IS NULL);
UPDATE file SET id_tipo_file = 2 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'pdf');
UPDATE file SET id_tipo_file = 3 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'doc');
UPDATE file SET id_tipo_file = 4 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'gif');
UPDATE file SET id_tipo_file = 5 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'htm');
UPDATE file SET id_tipo_file = 6 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'jpg');
UPDATE file SET id_tipo_file = 7 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'mp3');
UPDATE file SET id_tipo_file = 8 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'sxw');
UPDATE file SET id_tipo_file = 9 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'sxc');
UPDATE file SET id_tipo_file = 10 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'sxi');
UPDATE file SET id_tipo_file = 11 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'ppt');
UPDATE file SET id_tipo_file = 12 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'rtf');
UPDATE file SET id_tipo_file = 13 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'tex');
UPDATE file SET id_tipo_file = 14 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'txt');
UPDATE file SET id_tipo_file = 15 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'xls');
UPDATE file SET id_tipo_file = 16 WHERE id_file IN ( SELECT id_file FROM file2 where estensione = 'bmp');

ALTER TABLE "help_topic" ADD "indice" integer;
UPDATE "help_topic" SET indice = 0;
ALTER TABLE "help_topic" ALTER COLUMN "indice" SET NOT NULL;

-- 11-09-2004

CREATE TABLE "file_keywords" (
    "id_file" integer NOT NULL,
    "keyword" character varying(50) NOT NULL,
    CONSTRAINT "file_keywords_pkey" PRIMARY KEY (id_file, keyword)
) WITH OIDS;

DELETE FROM "help" WHERE 1=1;

INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('8', 'Alla registrazione sul Portale ho inserito l''username nome.cognome, ma il sito non me lo accetta, perche''?', 'Evidentemente siete alla presenza di un caso di omonimia.  Per trovare il vostro username presso il Portale allora andate all''indirizzo [url]https://www.unibo.it/Portale/Il+mio+Portale/FindStudentUsername.htm[/url] e nei rispettivi campi inserite nome e cognome e numero di matricola.  Poi selezionate invia, vi verra'' dato il vostro username effettivo.', '111111', '60');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('7', 'Come faccio a registrarmi presso il Portale Unibo?', 'Entrate sul sito del Portale ([url]http://www.unibo.it[/url]) e seguite in alto a destra il link ''login''.\r\nRiempite il campo username con il vostro (di solito del tipo nome.cognome).\r\nInserite come password il PIN del vostro badge.\r\nVi verr� chiesto di cambiare password. Il nostro consiglio � di sceglierne una di almeno 8 caratteri:[list][*] inserite la vecchia password;[*]inserite la nuova password;[*]reinserite la nuova password per conferma;[*]inserite una domanda e la risposta di cui siete certamente a conoscenza: in caso di smarrimento della password dovrete rispondere a questa domanda;[*]cliccate su INVIA.[/list]Ora siete iscritti su Unibo.', '1', '50');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('3', 'Come faccio a navigare nel sito?', 'Le pagine del sito sono strutturate cosi'':[list][*] in alto vi � l''intestazione di UniversiBo con una piccola barra di navigazione rapida;[*]il menu di sinistra di navigazione rimane pi� o meno lo stesso per tutte le pagine e vi guida anche all''esterno del sito;[*]la parte centrale corrisponde alla pagina attualmente navigata;[*]il menu di destra contiene il form per il login degli utenti, alcuni servizi riferiti alla parte centrale(ad esempio il calendario) e le statistiche.[/list]Quando entrerete nel sito vi ritroverete nella homepage in cui vi saranno le informazioni riguardanti le novit� sul sito.\r\n\r\nLa struttura delle pagine interne del sito � pi� o meno sempre la stessa e si basa sul concetto di argomento. I servizi e le informazioni vengono ricostruiti intorno all''argomento che pu&#242; essere un esame, un corso di laurea, oppure la biblioteca.\r\nNel corpo centrale verranno visualizzati alcuni contenuti, i link principali interni all''argomento, le ultime news relative a quell''argomento, il forum (se presente in quell''argomento) e le news.\r\nNel menu di destra verr� visualizzato il calendario di quel particolare argomento.', '10111111', '10');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('4', 'Cos''e'' la mail d''ateneo?', 'E'' una casella di posta elettronica fornita [b]gratuitamente[/b] dall''Universita'' ad ogni studente.  Vi si puo'' accedere dal sito [url]https://posta.studio.unibo.it/horde/[/url].', '19000', '20');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('6', 'Come faccio ad attivare la mia mail d''Ateneo?', 'Bisogna [url=v2.php?do=ShowHelp#7]registrarsi sul sito del Portale[/url] dell''Universita'' di Bologna ([url]http://www.unibo.it[/url]): La casella di posta elettronica verra'' attivata automaticamente [b]24 ore dopo[/b] la prima modifica della password d''accesso alla vostra pagina personale, e sara'' del tipo nome.cognome@studio.unibo.it.', '12345', '40');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('10', 'Come faccio a loggarmi al sito?', 'Il login (procedura di identificazione e accesso al sistema) non � obbligatorio: � possibile navigare all''interno delle sezioni del sito anche senza essere loggati; tuttavia si acceder� come esterni e quindi molte funzionalit� saranno ridotte. Naturalmente possono effettuarlo solo gli utenti iscritti.\r\nPer effettuare il login[list][*]inserite il vostro username e la vostra password nel blocco in alto a destra;[*]quindi premete sul pulsante ''Entra''.[/list] Il sistema a questo punto vi riconoscera'' e leggera'' i vostri diritti di accesso in base ai quali creera'' dinamicamente le pagine e vi appariranno i relativi links.\r\n\r\nNB: [b]si consiglia di eseguire il logout[/b] prima di abbandonare il sito.', '111', '80');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('11', 'Come faccio a modificare la password?', 'Per cambiare la password dovrete innanzitutto essere loggati. [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowSettings]Andate poi nella pagina delle Impostazioni[/url] (link nel menu in alto a sinistra). Poi cliccate su ''Modifica password''. Vi si aprir� una finestra in cui dovrete inserire:[list][*]Username: il vostro username (serve come controllo);[*]Vecchia password: inserite la password che avete usato per loggarvi fino ad ora;[*]Nuova password: nel scegliere una nuova password si consiglia di:\r\n- utilizzare una composizione casuale di lettere maiuscole/minuscole, cifre e segni di punteggiatura;\r\n- utilizzare almeno sei/otto caratteri: pi� � lunga e pi� � difficile individuarla;\r\n- che sia per te facilmente memorizzabile senza doverla scrivere da qualche parte;\r\n- di non riutilizzare una password gi� usata per altri servizi;\r\n- di non utilizzare mai come password il nickname, o un suo anagramma;\r\n- evita di utilizzare lettere o numeri riconducibili a qualcosa che ti riguarda: data di nascita, targa dell''auto, hobby, codice fiscale, ecc...;\r\n- ricordati di cambiarla periodicamente.[*]Conferma nuova password: ridigitate la nuova password.[/list]A questo punto cliccate su ''Modifica'' e attendete che il popup si aggiorni e vi confermi l''aggiornamento.\r\nSe avete perso la vostra password scrivete a: [email]info_universibo@calvin.ing.unibo.it[/email].', '11111', '90');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('14', 'Come faccio ad inserire un esame tra i preferiti?', 'Per inserire un esame tra i [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowHelp#13]preferiti[/url] � necessario cercare l''esame stesso nel corso di laurea ad esso corrispondente e, una volta entrati nella pagina relativa, cliccare sull''iconcina che trovate sotto le informazioni dell''esame: ''Aggiungi l''esame ai preferiti''.\r\nSi aprir� un popup nel quale verr� segnalata l''avvenuta Modifica.\r\nCos� facendo, ad ogni accesso al sito, l''esame in questione comparir� nel vostro men� di sinistra alla voce ''preferiti'', rendendo cos� pi� immediato e veloce l''accesso alla sezione relativa (vi sar� inoltre una scritta ''NEW'' ad indicarvi se vi sono aggiornamenti dall''ultimo vostro accesso alla pagina).\r\nA modifica effettuata, nella pagina da cui siete partiti, l''esame non comparir� ancora tra i ''preferiti'': questo perch� dovete riaggiornare la pagina. Per farlo potete o cliccare sull''apposito pulsante del browser (Aggiorna, Reload...) oppure premere il tasto di funzione F5 della tastiera.', '12632212', '110');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('16', 'A cosa serve il servizio di news di UniversiBO?', 'Il servizio di news di UniversiBO permette di essere avvisati in tempo reale via e-mail/sms di ogni notizia che viene inserita nella pagina dell''esame d''interesse: � [b]necessario[/b] [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowHelp#14]inserire l''esame tra i preferiti[/url] per potere sfruttare questo servizio.', '113274632', '130');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('13', 'Cosa sono i preferiti?', 'I preferiti sono lo strumento fondamentale di personalizzazione del sito; sono infatti [b]fondamentali[/b] per lo sfruttamento del servizio di [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowHelp#16]news[/url]: [color=red]si possono ricevere le notizie [b]esclusivamente[/b] degli esami inseriti tra i preferiti[/color]!!!!', '1982198217', '100');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('19', 'Le news vengono spedite sulla mia mail d''ateneo, come faccio a cambiare l''indirizzo in cui ricevere la posta?', 'Bisogna andare nella pagina delle [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowSettings]Impostazioni[/url], cliccare su ''Impostazioni Personali'' e, nel popup che vi comparir�, andare a modificare il campo ''Indirizzo e-mail'' con quello desiderato. ', '125632367', '150');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('15', 'Come faccio a rimuovere un esamte dai preferiti?', 'Si accede alla pagina dell''esame d''interesse ed, usando l''iconcina ''Togli l''esame dai preferiti'', l''esame verr� tolto dal men� di sinistra e per accedere alla pagina sar� necessario passare attraverso il corso di laurea corrispondente.\r\nA modifica effettuata, l''esame non sar� ancora scomparso dai ''preferiti'': questo perch� dovete riaggiornare la pagina. Per farlo potete o cliccare sull''apposito pulsante del browser (Aggiorna, Reload...) oppure premere il tasto di funzione F5 della tastiera.', '1231231321', '120');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('26', 'Chi puo'' inserire un file su UniversiBO?', 'Questa e'' una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.', '2132131', '160');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('21', 'Come faccio a modificare un file gia'' inserito nella pagina?', 'Per Modificare un file gi� presente sul sito bisogna cliccare su link ''Modifica'' che vi viene visualizzato al di sotto del file (ovviamente l''opzione comparira'' solo se l''utente ha i diritti necessari, ovvero solo se e'' un collaboratore o un professore).\r\nSi aprir� un popup analogo a quello utilizzato per [url=v2.php?do=ShowHelp#20]caricare il file on line[/url] con le informazioni che sono gi� state inserite. Effettuate le modifiche necessarie, e'' sufficiente cliccare sul pulsante ''Modifica''.\r\nSe la modifica ha avuto successo, comparira'' una notifica.', '123719263', '180');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('22', 'Come faccio ad eliminare un file?', 'Per eliminare un file (sempre che se ne abbia i diritti, ovvero se si e'' un collaboratore o un professore) bisogna cliccare sul link ''Elimina''.  Apparir� una finestra (popup) per confermare la cancellazione. Se si e'' sicuri di volerlo cancellare, cliccare su ''Elimina''.\r\nA questo punto nessuno potr� pi� accedere al file al di fuori degli amministratori. Infatti, per ragioni di [b]sicurezza[/b], verr� conservata una copia del file [color=red]non accessibile dal web[/color].', '182791', '190');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('23', 'Come faccio a scaricare un file da UniversiBO?', 'Una volta giunti alla pagina in cui e'' contenuto il file, bisogna cliccare sull''icona che rappresenta il contenuto del file: si aprira'' una nuova finestra (popup) con segnato il collegamento al file.  A questo puinto si deve cliccare sul link [b][color=red]col tasto destro del mouse[/color][/b]: comparira'' un menu a cascata.  Selezionare ''Salva Oggetto Con Nome...'' (o ''Save Link Target As...'') e salvare il file.  Questa procedura e'' dovuta al fatto che alcuni files sono apribili direttamente col browser, e quindi cliccando col tasto sinistro verrebbero automaticamente visualizzati da web.', '21786278', '200');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('9', 'Come faccio ad iscrivermi ad UniversiBO?', 'Dopo [url=v2.php?do=ShowHelp#6]esservi procurati la mail d''Ateneo[/url] ed aver atteso le 24 ore prima della sua attivazione:[list][*]entrate nel nostro sito;[*]andate sul collegamento evidenziato in rosso [color=red]''Registrazione Studenti''[/color];[*]nella finestra che vi apparira'' riempite i campi col vostro username all''interno del portale e la password della vostra mail d''Ateneo (che [b]non[/b] sara'' la password che utilizzerete per UniversiBO: la trasmissione dei vostri dati avverra'' attraverso un canale cifrato e sicuro, la password della vostra casella e-mail non viene in alcun modo conservata all''interno di UniversiBO e il sistema � progettato in modo che nessuno possa leggerla, ma e'' necessaria come strumento di autenticazione di UniversiBO);[*]inserite l''username che desiderate utilizzare su UniversiBO (attenzione: una volta inserito [b]non[/b] e'' possibile modificarlo);[*]leggete attentamente il regolamento e per accettarlo, spuntate la casella ''Confermo di aver letto il regolamento'';[*]andate su ''REGISTRA''.[/list]Se tutto e'' stato fatto correttamente, vi comparira'' una notifica in cui vi verra'' detto che la registrazione e'' stata effettuata con successo.\r\nEntro qualche minuto riceverete nella vostra casella e-mail d''Ateneo un messaggio contenente il vostro username e la vostra password per fare il primo [url=v2.php?do=ShowHelp#10]login[/url] ad UniversiBO.  E'' consigliabile [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowSettings]andare alla pagina delle Impostazioni[/url] (link in alto a sinistra) e modificare la password con una a vostra scelta.  E'' consigliabile anche andare a inserire tra i propri preferiti gli esami che si e'' interessati a seguire.', '100101', '70');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('20', 'Come faccio ad inserire un file su UniversiBO?', 'Il procedimento per caricare un file sul sito e'' questo:[list=1][*][url=v2.php?do=ShowHelp#10]Accedere al sito col proprio nome utente[/url] (username)[*][url=v2.php?do=ShowHelp#3]Andare nella pagina[/url] in cui si desidera che compaia il file: scorrendo verso il basso si potranno anche vedere i files che sono gia'' stati caricati in quella pagina.[*]Cliccare su ''Invia nuovo file''.  Cosi'' facendo vi comparira'' una nuova finestra ([b]popup[/b]) con un certo numero di campi da compilare:[list=A][*]Data e ora d''inserimento.  Questo campo apparira'' gia'' compilato con la data e l''ora correnti.  L''utente ha comunque la possibilita'' di modificarli, nel caso desideri che essi compaiano in un secondo momento: data e ora infatti determinano il momento in cui [b]diverranno visibili agli utenti[/b] i dati caricati.[*]File.  In questo campo bisogna selezionare il percorso sul computer dell''utente per raggiungere il file che si desidera caricare.  Una procedura molto semplice per farlo e'' quella di premere il pulsante ''Sfoglia'' (o ''Browse'') e cercare la cartella del pc in cui e'' contenuto il file, selezionatelo, e cliccate su ''Apri'' (o ''Open'', a seconda del sistema operativo utilizzato).  Per rendere piu'' veloce il processo di upload (e conseguentemente di download da parte degli utenti che desidereranno scaricare il file sul loro computer) e'' consigliabile comprimere il file con un programma apposito installato sul proprio pc.\r\nNB: si puo'' caricare [b]un solo file alla volta[/b], quindi per mettere on line piu'' files bisogna ripetere la procedura, oppure servirsi dello stesso programma utilizzato per comprimere tutti i files desiderati utilizzando l''opzione che consente di comprimerli tutti in un unico file.[*]Data e ora di creazione.  Anche questo campo sara'' gia'' compilato all''apertura del popup con la data e l''ora correnti.  Se si desidera, si puo'' modificarli manualmente, ma questo non incidera'' sul momento in cui il file verra'' visualizzato nella pagina web.[*]Formato file.  Serve a [i]selezionare il tipo di file[/i] che si desidera caricare.  I formati attualmente supportati sono:[list][*]pdf - Adobe&#174; Portable Document Format (Adobe Acrobat Reader&#174;)[*]doc - documento Microsoft Word&#174;;[*]txt - file di testo;[*]rtf - Rich Text Format (Microsof&#174;);[*]bmp - immagine Bitmap;[*]fla - animazione Macromedia Flash&#174;;[*]gif - Graphic Image Format (Compuserve&#174;);[*]htm (html) - HyperText Markup Language;[*]mp3 - MPEG Layer-3 (Fraunhofer);[*]ppt - presentazione Microsoft PowerPoint&#174;;[*]pps;[*]xls - Foglio elettronico Microsoft Excel&#174;.[/list]\r\nSe il file e'' di un formato diverso da questi, selezionate altro.\r\nNB: con questa opzione [b][color=red]il file non verra'' modificato in alcun modo[/color][/b], in quanto la sua unica funzione e'' quella di far comparire un''icona che permettera'' capire all''utente che dovra'' scaricare il file quale programma bisogna avere installato per utilizzarlo (e dunque sapere in anticipo se potra'' utilizzarlo o meno) nel caso che il file sia compresso (in quanto non si puo'' conoscere l''estensione di un file di quel tipo prima di averlo scaricato e decompresso).[*]Descrizione.  Serve per dare una descrizione esauriente del contenuto del file.[/list][*]Cliccare su invia.[/list]Se la procedura e'' stata completata con successo, un nuovo popup confermera'' che il file e'' stato salvato, e verra'' visualizzato come comparira'' nella pagina; se c''e'' qualche errore, per poterlo correggere e'' sufficiente cliccare su ''Modifica''.\r\nNella pagina in cui si desiderava inserire il file non comparira'' ancora la modifica apportata; per poterla visualizzare e'' sufficiente cliccare sull''apposito pulsante del browser (''Aggiorna'', ''Reload'',...), oppure premere F5.', '1276378', '170');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('24', 'Chi puo'' inserire una notizia on line?', 'Questa e'' una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.', '282828', '210');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('28', 'Come faccio a modificare una notizia?', 'Per modificare una notizia gi� presente sul sito bisogna cliccare su link ''Modifica'' che vi viene visualizzato al di sotto del file (ovviamente l''opzione comparira'' solo se l''utente ha i diritti necessari, ovvero solo se e'' un collaboratore o un professore).\r\nSi aprir� un popup analogo a quello utilizzato per [url=v2.php?do=ShowHelp#27]inserire la notizia[/url] con le informazioni che sono gi� state scritte. Effettuate le modifiche necessarie, e'' sufficiente cliccare sul pulsante ''Modifica''.\r\nSe la modifica ha avuto successo, comparira'' una notifica.', '132123123', '230');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('29', 'Come faccio ad eliminare una notizia?', 'Per eliminare una notizia (sempre che se ne abbia i diritti, ovvero se si e'' un collaboratore o un professore) bisogna cliccare sul link ''Elimina''. Apparir� una finestra (popup) per confermare la cancellazione. Se si e'' sicuri di volerla cancellare, cliccare su ''Elimina''.', '1231231', '240');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('30', 'Posso avere accesso alle notizie scadute?', 'Si'': le news scadute sono raggiungibili dagli aventi diritto cliccando sul link ''Visualizza notizie scadute''.  Cosi'' facendo si possono vedere le vecchie news e anche modificarle (ad esempio per prolungare la visualizzazione della notizia per ancora alcuni giorni).', '1312321', '250');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('27', 'Come faccio ad inserire una notizia?', ' Per inserire una nuova new andate nell''argomento per la quale volete inserirla. Se volete pubblicarla in pi� argomenti avete due strade a disposizione: la prima � inserirla in una sezione pi� generica (ad esempio il corso di laurea o la facolt�); la seconda � caricarla direttamente in pi� argomenti.\r\nQuesto e'' comunque il procedimento standard inserire una new''[list=1][*][url=v2.php?do=ShowHelp#10]Accedere al sito con il proprio nome utente[/url] (username).[*][url=v2.php?do=ShowHelp#3]Andare nella pagina[/url]in cui si desidera che compaia la notizia: scorrendo verso il basso si potranno anche vedere le news che sono gia'' stati caricate in quella pagina.[*]Cliccare su ''Scrivi una nuova notizia''. Cosi'' facendo vi comparira'' una nuova finestra ([b]popup[/b]) con un certo numero di campi da compilare:[list][*]Titolo: inserire un titolo significativo per la notizia.[*]Data e Ora di inserimento: questi campi vengono completati automaticamente; lo si desidera, si puo'' cambiarle: ci&#242; pu&#242; essere molto utile nel caso in cui si voglia che la notizia non compaia prima di una certa data e ora in quanto [b]le news vengono visualizzate solo se la data attuale � posteriore a quella di inserimento[/b].[*]Attiva scadenza: selezionando questo campo e compilando quelli che indicano la data e l''ora di scadenza, si fissera'' il momento in cui la notizia non sara'' piu'' visualizzata nella pagina.  E'' consigliabile inserire sempre una scadenza, se possibile: in tal modo si semplifica la manutenzione del sito che cos� diventa automatica, e la pagina non verra'' appesantita da notizie che risulteranno inutili dopo una certa data e dunque non sara'' piu'' utile leggere.[*]Data e Ora di scadenza: nel caso sia stato attivato il servizio di scadenza, bisogna riempire questi campi per fissare il momento in cui la notizia diverra'' inutile.[*]Notizia: bisogna inserire qui il testo della notizia cercando di essere il pi� sintetici possibile.[/list][*]Cliccare su ''Inserisci''.[/list]Se la procedura e'' stata completata con successo, verr� visualizzato un popup che confermer� che la notizia � stata inserita.  Verr� anche visualizzata la notizia cos� come la si vedr� all''interno della pagina, in modo da poter controllare che le informazioni siano corrette.\r\nSe tutto va bene allora si puo'' chiudere la finestra, altrimenti cliccare su ''Modifica''.\r\nNella pagina in cui si desiderava inserire la notizia non comparira'' ancora la modifica apportata; per poterla visualizzare e'' sufficiente cliccare sull''apposito pulsante del browser (''Aggiorna'', ''Reload'',...), oppure premere F5.', '12321314', '220');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('32', 'Come posso inserire un messaggio in una discussione?', 'Per inserire un messaggio in una discussione gia'' avviata e'' sufficiente entrare nel thread e cliccare sul pulsante ''Reply''.  Comparira'' una nuova finestra in cui, oltre ad un form per scrivere il nuovo post, scorrendo verso il basso si potranno vedere i messaggi che sono gia'' stati inseriti nel thread.  Oltre a diverse opzioni utili per ''abbellire'' il messaggio, per limitarsi ad inserirlo e'' sufficiente scrivere il testo e cliccare su ''Invia''.  A fianco del pulsante ''Invia'' c''e'' l''opzione ''Anteprima'', con la quale e'' possibile vedere come apparira'' il messaggio prima di spedirlo effettivamente.\r\nUna volta cliccato su ''Invia'', comparira'' una nuova finestra in cui verra'' notificato che la procedura e'' stata effettuata con successo, e comparira'' la possibilita'' di essere inviati alla discussione in cui si e'' intervenuti, nel punto in cui si e'' inserito il proprio messaggio, oppure di essere reindirizzati alla pagina generale della sezione del forum cui appartiene la discussione in cui si e'' scritto.', '13421312', '270');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('31', 'Che cos''e'' un forum?', 'Un forum e'' una bacheca virtuale in cui ogni utente puo'' inserire ([b]postare[/b]) un messaggio ([b]post[/b]).\r\nUn forum e'' organizzato in sezioni, a loro volta suddivise in discussioni ([b]threads[/b] o [b]topic[/b]).\r\nN.B.: per poter interagire attivamente nel forum [url=v2.php?do=ShowHelp#10]bisogna essere loggati[/url].', '23412314', '260');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('5', 'Perche'' devo avere la mail di Ateneo per iscrivermi ad UniversiBO?', 'Perche'' [url=v2.php?do=ShowHelp#4]la mail di Ateneo[/url] viene assegnata univocamente dall''Universita'' ad ogni singolo studente iscritto, e'' dunque un metodo d''identificazione di ogni utente registrato al sito UniversiBO.', '1000', '30');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('17', 'Come faccio a personalizzare il servizio di news?', 'Una volta [url=v2.php?do=ShowHelp#14]aggiunto l''esame ai preferiti[/url], viene impostata automaticamente dal sistema la possibilit� di ricevere tutte le [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowHelp#16]news[/url] che vengono inserite nella pagina dell''esame d''interesse.  Una volta [url=v2.php?do=ShowHelp#10]loggati[/url], andando nella pagina [url=v2.php?do=ShowSettings]''Impostazioni''[/url] (link in alto a sinistra) e  cliccando su ''Impostazioni Personali'', troverete un campo in cui � segnato il vostro indirizzo e-mail a cui verranno spedite le news, e la possibilit� di scegliere quali notizie ricevere e quali no.[list][*]Nessun messaggio: non vi verr� inviato nessun messaggio, nemmeno quelli urgenti.[*]Solo messaggi urgenti: solo le news indicate dal docente come urgenti vi verranno segnalate con una e-mail; [*]Tutti i messaggi: tutte le news inserite nei vostri esami preferiti vi verranno anche segnalate via e-mail.[/list]\r\nL''opzione di default � la terza.  Una volta selezionata la preferenza, cliccando su ''Invia'' vi verr� notificato se la modifica ha avuto successo e a questo punto si potr� chiudere la finestra.\r\n&#200; comunque sempre possibile modificare la scelta di notifica effettuata, ripetendo le operazioni sopra indicate.', '1231232', '140');

DELETE FROM "help_riferimento" WHERE 1=1;
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('Preferiti', '14');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('Preferiti', '15');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('Preferiti', '13');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '24');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '27');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '28');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '29');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '30');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '16');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '17');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '19');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filesutenti', '26');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filesutenti', '23');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '20');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '21');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '22');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '9');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '11');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '4');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '5');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '6');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '7');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '8');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('suggerimentinav', '10');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('suggerimentinav', '3');


DELETE FROM "help_topic" WHERE 1=1;
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('Preferiti', 'Come personalizzare UniversiBO.', '40');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('iscrizione', 'Come faccio ad iscrivermi ad UniversiBO?', '10');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('suggerimentinav', 'Navigazione nel sito: i primi passi.', '30');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newsutenti', 'Cos''e'' e come gestire il servizio di News di UniversiBO.', '50');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newscollabs', 'Voglio inserire una notizia su UniversiBo: come posso fare?', '80');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filescollabs', 'Voglio mettere un file on line su UniversiBO: come posso fare?', '70');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filesutenti', 'Come faccio a scaricare i files da UniversiBO?', '60');

-- 13/9/2004

CREATE TABLE "notifica" (
    "id_notifica" serial NOT NULL,
    "urgente" char(1) NOT NULL,
    "messaggio" text NOT NULL,
    "titolo" varchar(200) NOT NULL,
    "timestamp" int NOT NULL,
    "destinatario" varchar(200) NOT NULL,
    "eliminata" char(1) NOT NULL,
    CONSTRAINT "notifica_pkey" PRIMARY KEY (id_notifica)
) WITH OIDS;

-- 16/9/2004

ALTER TABLE "utente" ADD "notifica" integer;
UPDATE "utente" SET notifica = 0;
ALTER TABLE "utente" ALTER COLUMN "notifica" SET NOT NULL;

UPDATE "utente" SET notifica = 1 WHERE inoltro_email = 'U';
UPDATE "utente" SET notifica = 2 WHERE inoltro_email = 'T';

-- LastHope 16/9/2004

ALTER TABLE "questionario" ADD "cdl" character varying(50);
UPDATE "questionario" SET cdl = 0;
ALTER TABLE "questionario" ALTER COLUMN "cdl" SET NOT NULL;

--- 17/9/2004

update help_riferimento set riferimento='myuniversibo' where 
riferimento='Preferiti';

update help_topic set riferimento='myuniversibo' where 
riferimento='Preferiti';

--- 18/9/2004

UPDATE utente_canale SET notifica = 0 WHERE id_utente IN (SELECT id_utente FROM utente WHERE inoltro_email = 'N');
UPDATE utente_canale SET notifica = 1 WHERE id_utente IN (SELECT id_utente FROM utente WHERE inoltro_email = 'U');
UPDATE utente_canale SET notifica = 2 WHERE id_utente IN (SELECT id_utente FROM utente WHERE inoltro_email = 'T');

--- 20/9/2004

CREATE TABLE "info_didattica" (
    "id_canale" integer NOT NULL,
    "programma" text NOT NULL,
    "programma_link" varchar(256) NOT NULL,
    "testi_consigliati" text NOT NULL,
    "testi_consigliati_link" varchar(256) NOT NULL,
    "modalita" text NOT NULL,
    "modalita_link" varchar(256) NOT NULL,
    "obiettivi_esame" text NOT NULL,
    "obiettivi_esame_link" varchar(256) NOT NULL,
	"appelli" text NOT NULL,
	"appelli_link" varchar(256) NOT NULL,
	"homepage_alternativa_link" varchar(256)  NOT NULL,   
    CONSTRAINT "info_didattica_pkey" PRIMARY KEY (id_canale)
) WITH OIDS;

INSERT INTO info_didattica (id_canale, programma, programma_link, testi_consigliati, 
testi_consigliati_link, modalita, modalita_link, obiettivi_esame, obiettivi_esame_link,
appelli, appelli_link, homepage_alternativa_link) 
SELECT id_argomento, programma_esame, programma_esame_link, testi_consigliati, 
testi_consigliati_link, modalita_esame, modalita_esame_link, obiettivi_esame, 
obiettivi_esame_link, appelli_esame, appelli_esame_link, homepage_esame_link 
FROM esami_info;

-- 22-09-2004 meco

INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:k]','face_azz.gif','azz');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:b]','face_beer.gif','beer');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[o|]','face_climb.gif','climb');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:<]','face_cry.gif','cry');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[7)]','face_devil.gif','devil');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[|7]','face_guns.gif','guns');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[?(]','face_help.gif','help');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[o)]','face_idea.gif','idea');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[|9]','face_lode.gif','lode');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[3)]','face_love.gif','love');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:j]','face_ok.gif','ok');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[|(]','face_ot.gif','OT');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:s]','face_sick.gif','sick');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[|x]','face_spam.gif','spam');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[o(]','face_wall.gif','wall');
INSERT into phpbb_smilies (code,smile_url,emoticon) values('[:7]','face_whistle.gif','whistle');


UPDATE phpbb_config set config_value='la community degli studenti dell\'Universit� di Bologna'
WHERE  config_name='site_desc';

DELETE FROM utente_canale WHERE id_canale NOT IN (SELECT id_canale FROM canale WHERE 1=1);

-- forum

DELETE FROM "phpbb_themes" WHERE 1=1;
INSERT INTO "phpbb_themes" ("themes_id", "style_name", "template_name", "head_stylesheet", "body_background", "body_bgcolor", "body_text", "body_link", "body_vlink", "body_alink", "body_hlink", "tr_color1", "tr_color2", "tr_color3", "tr_class1", "tr_class2", "tr_class3", "th_color1", "th_color2", "th_color3", "th_class1", "th_class2", "th_class3", "td_color1", "td_color2", "td_color3", "td_class1", "td_class2", "td_class3", "fontface1", "fontface2", "fontface3", "fontsize1", "fontsize2", "fontsize3", "fontcolor1", "fontcolor2", "fontcolor3", "span_class1", "span_class2", "span_class3", "img_size_poll", "img_size_privmsg") VALUES ('7', 'BlackSoul', 'BlackSoul', 'BlackSoul.css', '', 'E5E5E5', '000000', '006699', '5493B4', '      ', 'DD6900', 'EFEFEF', 'DEE3E7', 'D1D7DC', '', '', '', '98AAB1', '006699', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '      ', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, ''Courier New'', sans-serif', '10', '11', '12', '444444', '006600', '008800', '', '', '', '0', '0');
INSERT INTO "phpbb_themes" ("themes_id", "style_name", "template_name", "head_stylesheet", "body_background", "body_bgcolor", "body_text", "body_link", "body_vlink", "body_alink", "body_hlink", "tr_color1", "tr_color2", "tr_color3", "tr_class1", "tr_class2", "tr_class3", "th_color1", "th_color2", "th_color3", "th_class1", "th_class2", "th_class3", "td_color1", "td_color2", "td_color3", "td_class1", "td_class2", "td_class3", "fontface1", "fontface2", "fontface3", "fontsize1", "fontsize2", "fontsize3", "fontcolor1", "fontcolor2", "fontcolor3", "span_class1", "span_class2", "span_class3", "img_size_poll", "img_size_privmsg") VALUES ('1', 'subSilver', 'subSilver', 'subSilver.css', '', 'eff3fc', '000000', '386dce', '5493B4', '      ', '386dce', 'eff3fc', 'd8e9f8', 'd0dcf8', '', '', '', '98AAB1', '386dce', 'd0dcf8', '', '', '', 'FAFAFA', 'ffffff', '      ', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, ''Courier New'', sans-serif', '10', '11', '12', '444444', '006600', 'FFFFFF', '', '', '', NULL, NULL);

--23-9-04
update canale set permessi_groups = 64 where id_canale = 73;

UPDATE canale SET files_attivo = 'S' WHERE tipo_canale IN (1,5);
UPDATE canale SET files_attivo = 'N' WHERE tipo_canale IN (2,3,4);
UPDATE canale SET news_attivo = 'S' WHERE 1 = 1;

--24-9-04
ALTER TABLE "utente" ADD "phone" varchar(15);
ALTER TABLE "utente" ALTER COLUMN "phone" SET DEFAULT '';
UPDATE "utente" SET  "phone" = '' WHERE 1=1;
ALTER TABLE "utente" ADD "default_style" varchar(10);
ALTER TABLE "utente" ALTER COLUMN "default_style" SET DEFAULT '';
UPDATE "utente" SET  "default_style" = '' WHERE 1=1;


UPDATE phpbb_config set config_value='1' WHERE config_name='default_style';

UPDATE phpbb_users set user_style='1' WHERE 1=1;

--24-9-04 ...post risveglio
DELETE FROM help WHERE 1=1;
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('8', 'Alla registrazione sul Portale ho inserito l''username nome.cognome, ma il sito non me lo accetta, perche''?', 'Evidentemente siete alla presenza di un caso di omonimia.  Per trovare il vostro username presso il Portale allora andate all''indirizzo [url]https://www.unibo.it/Portale/Il+mio+Portale/FindStudentUsername.htm[/url] e nei rispettivi campi inserite nome e cognome e numero di matricola.  Poi selezionate invia, vi verra'' dato il vostro username effettivo.', '111111', '60');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('7', 'Come faccio a registrarmi presso il Portale Unibo?', 'Entrate sul sito del Portale ([url]http://www.unibo.it[/url]) e seguite in alto a destra il link ''login''.\r\nRiempite il campo username con il vostro (di solito del tipo nome.cognome).\r\nInserite come password il PIN del vostro badge.\r\nVi verr\340 chiesto di cambiare password. Il nostro consiglio \350 di sceglierne una di almeno 8 caratteri:[list][*] inserite la vecchia password;[*]inserite la nuova password;[*]reinserite la nuova password per conferma;[*]inserite una domanda e la risposta di cui siete certamente a conoscenza: in caso di smarrimento della password dovrete rispondere a questa domanda;[*]cliccate su INVIA.[/list]Ora siete iscritti su Unibo.', '1', '50');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('4', 'Cos''e'' la mail d''ateneo?', 'E'' una casella di posta elettronica fornita [b]gratuitamente[/b] dall''Universita'' ad ogni studente.  Vi si puo'' accedere dal sito [url]https://posta.studio.unibo.it/horde/[/url].', '19000', '20');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('10', 'Come faccio a loggarmi al sito?', 'Il login (procedura di identificazione e accesso al sistema) non \350 obbligatorio: \350 possibile navigare all''interno delle sezioni del sito anche senza essere loggati; tuttavia si acceder\340 come esterni e quindi molte funzionalit\340 saranno ridotte. Naturalmente possono effettuarlo solo gli utenti iscritti.\r\nPer effettuare il login[list][*]inserite il vostro username e la vostra password nel blocco in alto a destra;[*]quindi premete sul pulsante ''Entra''.[/list] Il sistema a questo punto vi riconoscera'' e leggera'' i vostri diritti di accesso in base ai quali creera'' dinamicamente le pagine e vi appariranno i relativi links.\r\n\r\nNB: [b]si consiglia di eseguire il logout[/b] prima di abbandonare il sito.', '111', '80');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('24', 'Chi puo'' inserire una notizia on line?', 'Questa e'' una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.', '282828', '210');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('32', 'Come posso inserire un messaggio in una discussione?', 'Per inserire un messaggio in una discussione gia'' avviata e'' sufficiente entrare nel thread e cliccare sul pulsante ''Reply''.  Comparira'' una nuova finestra in cui, oltre ad un form per scrivere il nuovo post, scorrendo verso il basso si potranno vedere i messaggi che sono gia'' stati inseriti nel thread.  Oltre a diverse opzioni utili per ''abbellire'' il messaggio, per limitarsi ad inserirlo e'' sufficiente scrivere il testo e cliccare su ''Invia''.  A fianco del pulsante ''Invia'' c''e'' l''opzione ''Anteprima'', con la quale e'' possibile vedere come apparira'' il messaggio prima di spedirlo effettivamente.\r\nUna volta cliccato su ''Invia'', comparira'' una nuova finestra in cui verra'' notificato che la procedura e'' stata effettuata con successo, e comparira'' la possibilita'' di essere inviati alla discussione in cui si e'' intervenuti, nel punto in cui si e'' inserito il proprio messaggio, oppure di essere reindirizzati alla pagina generale della sezione del forum cui appartiene la discussione in cui si e'' scritto.', '13421312', '270');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('30', 'Posso avere accesso alle notizie scadute?', 'S\355: le news scadute sono raggiungibili dagli aventi diritto cliccando sul link ''Visualizza notizie scadute''.  Cos\355 facendo si possono vedere le vecchie news e anche modificarle (ad esempio per prolungare la visualizzazione della notizia per ancora alcuni giorni).', '1312321', '250');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('19', 'Le news vengono spedite sulla mia mail d''ateneo, come faccio a cambiare l''indirizzo in cui ricevere la posta?', 'Bisogna andare nella pagina delle  [url=v2.php?do=ShowSettings]Impostazioni[/url], cliccare su ''Impostazioni Personali'' e, nel popup che vi comparir\340, andare a modificare il campo ''Indirizzo e-mail'' con quello desiderato. ', '125632367', '150');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('15', 'Come faccio a rimuovere una pagina dalla mia pagina My UniversiBO?', 'Si accede alla pagina d''interesse e si clicca sul link sotto al titolo della pagina "Rimuovi questa pagina da My UniversiBO". La pagina verr\340 tolta dal men\371 di sinistra e per accedere alla pagina sar\340 necessario passare attraverso il corso di laurea corrispondente o attraverso il men\372 Servizi.', '1231231321', '120');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('23', 'Come faccio a scaricare un file da UniversiBO?', 'Una volta giunti alla pagina in cui e'' contenuto il file, bisogna cliccare sull''icona che rappresenta il download del file, a fianco del titolo del file: si aprir\341 la finestra con cui salvare sul proprio computer il file desiderato.\r\nAlcuni files sono scaricabili solo se iscritti ad UniversiBO, altri solo tramite l''apposita password comunicata dal professore.', '21786278', '200');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('26', 'Chi puo'' inserire un file su UniversiBO?', 'Questa \351 una prerogativa dei professori nelle loro pagine d''esame, dei collaboratori nelle pagine d''esame di cui sono referenti, e dei responsabili di certe sezioni del sito nelle aree di loro competenza.\r\nGli utenti del sito hanno a disposizione uno spazio ove caricare i propri files (appunti, esercizi svolti).\r\n [b]\311 severamente proibito caricare files con contenuto inadeguato (pornografico, razzista, pedofilo...): la responsabilit\341 dei files caricati \351 dell''utente, ma i files vengolo controllati a campione e periodicamente. UniversiBO si riserva il diritto di cancellare quelli non conformi alle regole e di bannare l''utente.[/b]', '2132131', '160');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('29', 'Come faccio ad eliminare una notizia?', 'Per eliminare una notizia (sempre che se ne abbia i diritti, ovvero se si \351 un collaboratore o un professore) bisogna cliccare sul link ''Elimina''. Si passer\340 ad una finestra dove verr\341 mostrata la notizia e dove si potranno selezionare le pagine da cui si pu\363 cancellarla. Una volta selezionato almeno una pagina, se si \351 sicuri di volere cancellare la notizia, cliccare su ''Elimina''.', '1231231', '240');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('31', 'Che cos''e'' un forum?', 'Un forum e'' una bacheca virtuale in cui ogni utente puo'' inserire ([b]postare[/b]) un messaggio ([b]post[/b]).\r\nUn forum e'' organizzato in sezioni, a loro volta suddivise in discussioni ([b]threads[/b] o [b]topic[/b]).\r\nN.B.: per poter interagire attivamente nel forum [url=v2.php?do=ShowHelp#id10]bisogna essere loggati[/url].', '23412314', '260');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('5', 'Perche'' devo avere la mail di Ateneo per iscrivermi ad UniversiBO?', 'Perche'' [url=v2.php?do=ShowHelp#id4]la mail di Ateneo[/url] viene assegnata univocamente dall''Universita'' ad ogni singolo studente iscritto, e'' dunque un metodo d''identificazione di ogni utente registrato al sito UniversiBO.', '1000', '30');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('6', 'Come faccio ad attivare la mia mail d''Ateneo?', 'Bisogna [url=v2.php?do=ShowHelp#id7]registrarsi sul sito del Portale[/url] dell''Universita'' di Bologna ([url]http://www.unibo.it[/url]): La casella di posta elettronica verra'' attivata automaticamente [b]24 ore dopo[/b] la prima modifica della password d''accesso alla vostra pagina personale, e sara'' del tipo nome.cognome@studio.unibo.it.', '12345', '40');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('11', 'Come faccio a modificare la password?', 'Per cambiare la password dovrete innanzitutto essere loggati. [url=v2.php?do=ShowSettings]Andate poi nella pagina delle Impostazioni[/url] (link nel menu in alto a sinistra). Poi cliccate su ''Modifica password''. Vi si aprir\340 una finestra in cui dovrete inserire:[list][*]Username: il vostro username (serve come controllo);[*]Vecchia password: inserite la password che avete usato per loggarvi fino ad ora;[*]Nuova password: nel scegliere una nuova password si consiglia di:\r\n- utilizzare una composizione casuale di lettere maiuscole/minuscole, cifre e segni di punteggiatura;\r\n- utilizzare almeno sei/otto caratteri: pi\371 \350 lunga e pi\371 \350 difficile individuarla;\r\n- che sia per te facilmente memorizzabile senza doverla scrivere da qualche parte;\r\n- di non riutilizzare una password gi\340 usata per altri servizi;\r\n- di non utilizzare mai come password il nickname, o un suo anagramma;\r\n- evita di utilizzare lettere o numeri riconducibili a qualcosa che ti riguarda: data di nascita, targa dell''auto, hobby, codice fiscale, ecc...;\r\n- ricordati di cambiarla periodicamente.[*]Conferma nuova password: ridigitate la nuova password.[/list]A questo punto cliccate su ''Modifica'' e attendete che il popup si aggiorni e vi confermi l''aggiornamento.\r\nSe avete perso la vostra password scrivete a: [email]info_universibo@calvin.ing.unibo.it[/email].', '11111', '90');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('13', 'Cosa \351 My UniversiBO?', 'My UniversiBO \351 lo strumento fondamentale di personalizzazione del sito; \351 infatti [b]fondamentale[/b] per lo sfruttamento del servizio di [url=v2.php?do=ShowHelp#id16]news[/url]: [b]si possono ricevere le notizie esclusivamente degli esami/servizi inseriti all''interno della propria pagina My UniversiBO[/b]!!!!', '1982198217', '100');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('14', 'Come faccio ad inserire un esame/servizio all''interno del My UniversiBO?', 'Per inserire un esame all''interno del [url=v2.php?do=ShowHelp#id13]My UniversiBO[/url] \350 necessario cercare l''esame stesso nel corso di laurea ad esso corrispondente, o il servizio interessato nell''apposito men\372 sulla sinistra, e una volta entrati nella pagina relativa, cliccare sul link che trovate sotto le informazioni dell''esame: ''Aggiungi questa pagina a My UniversiBO''.\r\nSi passer\340 a una pagina nella quale verr\340 richiesta il livello di notifica desiderato per le news (Tutti, Solo Urgenti o Nessuna: indica la tipologia di news che desideri ricevere nella tua casella di posta) ed eventualmente un nome personalizzato per la pagina, massimo 60 caratteri.\r\nCos\354 facendo, ad ogni accesso al sito, l''esame in questione/il servizio desiderato comparir\340 nel vostro men\371 di sinistra alla voce ''My UniversiBO'', rendendo cos\354 pi\371 immediato e veloce l''accesso alla sezione relativa (vi sar\340 inoltre una scritta ''NEW'' ad indicarvi se vi sono aggiornamenti dall''ultimo vostro accesso alla pagina).\r\nInoltre, cliccando sull''immagine in alto "My UniversiBO" accederete alla vostra pagina MyUniversiBO, dove potrete visualizzare le ultime 5 news e gli ultimi 5 files inseriti nelle pagine all''interno del vostro My UniversiBO, e i files che voi avete caricato su UniversiBO.', '12632212', '110');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('21', 'Come faccio a modificare un file gia'' inserito nella pagina?', 'Per Modificare un file gi\340 presente sul sito bisogna cliccare sull''apposita icona, posta di fianco al titolo del file (ovviamente l''icona comparir\341 solo se l''utente ha i diritti necessari, ovvero solo se \351 un collaboratore o un professore).\r\nSi passer\340 a una pagina analoga a quella utilizzata per [url=v2.php?do=ShowHelp#id20]caricare il file on line[/url] con le informazioni che sono gi\340 state inserite.\r\nEffettuate le modifiche necessarie, \351 sufficiente cliccare sul pulsante ''Modifica''.\r\nLe modifiche verranno apportate a tutte le pagine  in cui \351 stato inserito il file.\r\nPer inserire una password, selezionare ''Abilita password'' e scrivere la password negli appositi campi: ricordate che [b]i campi password sono riservati solo ai professori o ai file che i professori chiedono al moderatore di caricare sulla pagina[/b].\r\nSe le modifiche avranno successo,si passer\341 ad una pagina dove saranno presenti i link per tornare alla scheda del file o all''insegnamento in cui era stato inserito.', '123719263', '180');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('27', 'Come faccio ad inserire una notizia?', 'Il procedimento per caricare una notizia sul sito \351 questo:[list=1][*][url=v2.php?do=ShowHelp#id10]Accedere al sito con il proprio nome utente[/url] (username).[*][url=v2.php?do=ShowHelp#id3]Andare nella pagina[/url] in cui si desidera che compaia la notizia: scorrendo verso il basso si potranno anche vedere le news che sono gi\341 state caricate in quella pagina.[*]Cliccare su ''Scrivi una nuova notizia''. Cos\355 facendo si passer\341 a una pagina con il form per spedire una notizia. I campi da compilare sono:[list][*]Titolo: inserire un titolo significativo per la notizia.[*]Data e Ora di inserimento: questi campi vengono completati automaticamente; se lo si desidera, si pu\363 cambiarle: ci\363 pu\363 essere molto utile nel caso in cui si voglia che la notizia non compaia prima di una certa data e ora in quanto [b]le news vengono visualizzate solo se la data attuale \350 posteriore a quella di inserimento[/b].\r\n[*]Notizia: bisogna inserire qui il testo della notizia cercando di essere il pi\371 sintetici possibile.[*]Attiva scadenza: selezionando questo campo e compilando quelli che indicano la data e l''ora di scadenza, si fisser\341 il momento in cui la notizia non sar\341 pi\372 visualizzata nella pagina.  \311 consigliabile inserire sempre una scadenza, se possibile: in tal modo si semplifica la manutenzione del sito che cos\354 diventa automatica, e la pagina non verr\341 appesantita da notizie che risulteranno inutili dopo una certa data e dunque non sar\341 pi\372 utile leggere.[*]Data e Ora di scadenza: nel caso sia stato attivato il servizio di scadenza, bisogna riempire questi campi per fissare il momento in cui la notizia diverr\341 inutile.\r\n[*]Invia il messaggio come urgente: selezionando questa opzione, la notizia giunger\341 a un maggior numero di persone che hanno inserito l''insegnamento nei loro preferiti.\r\n[*]La notizia verr\340 inserita negli argomenti: qui vengono selezionati tutti gli insegnamenti/corsi in cui l''utente pu\363 inserire una notizia. Selezionando pi\372 di una casella, la notizia verr\341 inserita in tutte le pagine corrispondenti. [b]Attenzione! [url=v2.php?do=ShowHelp#id28]Se la notizia viene modificata[/url], la modifica comparir\341 in tutte le pagine dove \351 presente![/b]\r\n[/list][*]Cliccare su ''Inserisci''.[/list]Se la procedura \351 stata completata con successo, verr\340 visualizzata una pagina che confermer\340 che la notizia \350 stata inserita, quindi si pu\363 cliccare sul link "Torna a..." con cui si ritorner\341 alla pagina dove \351 stata inserita la notizia, che sar\341 presente in cima. Altrimenti si torner\341 al form d''inserimento della notizia.', '12321314', '220');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('28', 'Come faccio a modificare una notizia?', 'Per modificare una notizia gi\340 presente sul sito bisogna cliccare su link ''Modifica'' che vi viene visualizzato al di sotto della notizia (ovviamente l''opzione comparira'' solo se l''utente ha i diritti necessari, ovvero solo se \351 un collaboratore o un professore).\r\nSi passer\340 a una pagina analoga a quella per [url=v2.php?do=ShowHelp#id27]l''inserimento della notizia[/url] con la notizia come \351 ancora memorizzata in alto, e sotto il form con le informazioni che sono gi\340 state scritte. Effettuate le modifiche necessarie, \351 sufficiente cliccare sul pulsante ''Modifica''.\r\nSe la modifica ha avuto successo, comparir\341 una notifica. La modifica comparir\341 in tutte quelle pagine in cui era stata inserita precedentemente la notizia.', '132123123', '230');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('20', 'Come faccio ad inserire un file su UniversiBO?', 'Il procedimento per caricare un file sul sito \351 questo:[list=1][*][url=v2.php?do=ShowHelp#id10]Accedere al sito col proprio nome utente[/url] (username)[*][url=v2.php?do=ShowHelp#id3]Andare nella pagina[/url] in cui si desidera che compaia il file: scorrendo verso il basso si potranno anche vedere i files che sono gi\341 stati caricati in quella pagina.[*]Cliccare su ''Invia nuovo file''.  Cos\355 facendo vi comparir\341 una nuova finestra con un certo numero di campi da compilare:[list][*]File.  In questo campo bisogna selezionare il percorso sul computer dell''utente per raggiungere il file che si desidera caricare.  Una procedura molto semplice per farlo \351 quella di premere il pulsante ''Sfoglia'' (o ''Browse'') e cercare la cartella del pc in cui \351 contenuto il file, selezionatelo, e cliccate su ''Apri'' (o ''Open'', a seconda del sistema operativo utilizzato).  Per rendere pi\372 veloce il processo di upload (e conseguentemente di download da parte degli utenti che desidereranno scaricare il file sul loro computer) \351 consigliabile comprimere il file con un programma apposito installato sul proprio pc.\r\nNB: si pu\363 caricare [b]un solo file alla volta[/b], quindi per mettere on line pi\372 files bisogna ripetere la procedura, oppure servirsi dello stesso programma utilizzato per comprimere tutti i files desiderati utilizzando l''opzione che consente di comprimerli tutti in un unico file.\r\n[*]Titolo: Il titolo serve per distinguere il file, ed \351 il testo che comparir\341 nella pagina dove sono presenti tutti i files, quindi deve essere significativo.\r\n[*]Descrizione.  Serve per dare una descrizione esauriente del contenuto del file.\r\n[*]Parole chiave: si possono inserire al massimo 4 parole chiave, separate da un Enter/Invio. Tramite le parole chiave, si facilita la ricerca del file.\r\n[*]Categoria: Tramite questo men\372 a discesa si pu\363 specificare se il file appartiene ad una determinata categoria (appunti/lucidi/esercitazioni...). Se non appartiene a nessuna di quelle presenti, lasciare "altro".\r\n[*]Data e ora d''inserimento. Questo campo apparira'' gia'' compilato con la data e l''ora correnti.  L''utente ha comunque la possibilit\341 di modificarli, nel caso desideri che essi compaiano in un secondo momento: data e ora infatti determinano il momento in cui [b]diverranno visibili agli utenti[/b] i dati caricati.\r\n[*]Permessi download: Si pu\363 scegliere se il file \351 scaricabile da chiunque, o solo da persone iscritte al sito UniversiBO.\r\n[*]Password e conferma password: questi campi sono [b]riservati solo ai professori o ai file che i professori chiedono al moderatore di caricare sulla pagina[/b]. Attivandoli, il file sar\341 scaricabile solo se si \351 a conoscenza della password corretta.\r\n[*]Il file verr\340 inserito negli argomenti: qui vengono selezionati tutti gli insegnamenti/corsi in cui l''utente pu\363 inserire un file. Selezionando pi\372 di una casella, il file verr\341 inserito in tutte le pagine corrispondenti.[b] Attenzione!  [url=v2.php?do=ShowHelp#id21]Se il file viene modificato[/url], la modifica comparir\341 in tutte le pagine dove \351 presente![/b]\r\n[/list]\r\n[*]Cliccare su invia.\r\n[/list]Se la procedura \351 stata completata con successo, si passer\341 a un''ulteriore pagina che confermer\341 che il file \351 stato salvato. Cliccando sul link "Torna a..." si torner\341 alla pagina dove \351 stato inserito: il file comparir\341 nel posto pi\372 in alto della categoria selezionata precedentemente. Per visualizzare se tutti i dati sono stati inseriti correttamente, basta cliccare sul titolo del file: si passer\341 a una pagina dove vengono riassunte tutte le caratteristiche del file.', '1276378', '170');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('16', 'A cosa serve il servizio di news di UniversiBO?', 'Il servizio di news di UniversiBO permette di essere avvisati in tempo reale via e-mail/sms di ogni notizia che viene inserita nella pagina dell''esame d''interesse: \350 [b]necessario[/b]  [url=v2.php?do=ShowHelp#id14]inserire l''esame nel proprio MyUniversiBO[/url] per potere sfruttare questo servizio.', '113274632', '130');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('9', 'Come faccio ad iscrivermi ad UniversiBO?', 'Dopo [url=v2.php?do=ShowHelp#id6]esservi procurati la mail d''Ateneo[/url] ed aver atteso le 24 ore prima della sua attivazione:[list][*]entrate nel nostro sito;[*]andate sul collegamento evidenziato in rosso ''Registrazione Studenti'';[*]nella finestra che vi apparira'' riempite i campi col vostro username all''interno del portale e la password della vostra mail d''Ateneo (che [b]non[/b] sara'' la password che utilizzerete per UniversiBO: la trasmissione dei vostri dati avverra'' attraverso un canale cifrato e sicuro, la password della vostra casella e-mail non viene in alcun modo conservata all''interno di UniversiBO e il sistema \350 progettato in modo che nessuno possa leggerla, ma e'' necessaria come strumento di autenticazione di UniversiBO);[*]inserite l''username che desiderate utilizzare su UniversiBO (attenzione: una volta inserito [b]non[/b] e'' possibile modificarlo);[*]leggete attentamente il regolamento e per accettarlo, spuntate la casella ''Confermo di aver letto il regolamento'';[*]andate su ''REGISTRA''.[/list]Se tutto e'' stato fatto correttamente, vi comparira'' una notifica in cui vi verra'' detto che la registrazione e'' stata effettuata con successo.\r\nEntro qualche minuto riceverete nella vostra casella e-mail d''Ateneo un messaggio contenente il vostro username e la vostra password per fare il primo [url=v2.php?do=ShowHelp#id10]login[/url] ad UniversiBO.  E'' consigliabile [url=https://uni147.ing.unibo.it/~evaimitico/universibo2/htmls/v2.php?do=ShowSettings]andare alla pagina delle Impostazioni[/url] (link in alto a sinistra) e modificare la password con una a vostra scelta.  E'' consigliabile anche andare a inserire tra i propri preferiti gli esami che si e'' interessati a seguire.', '100101', '70');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('3', 'Come faccio a navigare nel sito?', 'Le pagine del sito sono strutturate cosi'':[list][*] in alto vi \350 l''intestazione di UniversiBo con una piccola barra di navigazione rapida;[*]il menu di sinistra di navigazione rimane pi\371 o meno lo stesso per tutte le pagine e vi guida anche all''esterno del sito;[*]la parte centrale corrisponde alla pagina attualmente navigata;[*]il menu di destra contiene il form per il login degli utenti, alcuni servizi riferiti alla parte centrale(ad esempio il calendario) e le statistiche.[/list]Quando entrerete nel sito vi ritroverete nella homepage in cui vi saranno le informazioni riguardanti le novit\340 sul sito.\r\n\r\nLa struttura delle pagine interne del sito \350 pi\371 o meno sempre la stessa e si basa sul concetto di argomento. I servizi e le informazioni vengono ricostruiti intorno all''argomento che pu\363 essere un esame, un corso di laurea, oppure la biblioteca.\r\nNel corpo centrale verranno visualizzati alcuni contenuti, i link principali interni all''argomento, le ultime news relative a quell''argomento, il forum (se presente in quell''argomento) e le news.\r\nNel menu di destra verr\340 visualizzato il calendario di quel particolare argomento.', '10111111', '10');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('2', 'Come faccio a cercare un utente?', 'Oltre alla ricerca per username ed email esatte, si pu\363 cercare mediante l''uso di [b]caratteri speciali[/b]:\r\n[list]\r\n[*]%: Inserendo un simbolo di percentuale, ricerca un qualunque usernamente/email che inizia con i caratteri inseriti prima, e con una qualsiasi serie di caratteri che seguono.\r\n[*]_: tramite l''underscore, dopo la parte note, si ricerca qualunque username/email che proseguono con X caratteri di un qualunque tipo, con X il numero di underscore.\r\n[/list]\r\nEsempi:\r\n[list]\r\n[*]L%: ricerca tutti gli username/email che iniziano con L\r\n[*]b____: ricerca tutti gli username/email che iniziano con b e sono lunghi 5 caratteri\r\n[/list]', '111111', '300');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('22', 'Come faccio ad eliminare un file?', 'Per eliminare un file (sempre che se ne abbia i diritti, ovvero se si \351 un collaboratore o un professore) bisogna cliccare sull''apposita icona, che si trova tra l''icona del modifica e quella per scaricare il file, di fianco al titolo del file.  Si passer\340 a una finestra per confermare la cancellazione. \r\nSi potr\341 scegliere da quali insegnamenti cancellare il file (almeno uno).\r\nSe si \351 sicuri di volerlo cancellare, cliccare su ''Elimina''.\r\nA questo punto nessuno potr\340 pi\371 accedere al file al di fuori degli amministratori. Infatti, per ragioni di [b]sicurezza[/b], verr\340 conservata una copia del file non accessibile dal web.', '182791', '190');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('1', 'Come faccio a cambiare i diritti di un utente?', 'Prima di tutto, dovete andare nel corso in cui intendete inserire/cancellare/modificare   i diritti di un utente. Quindi cliccate nel riquadro a destra "Contatti" il link ''Modifica diritti''. Passerete ad una pagina dove   potrete [url=v2.php?do=ShowHelp#id2]cercare[/url] l''utente desiderato e dove verranno visualizzati gi\341 gli utenti con dei diritti su quella pagina. Cliccate sul nome, scegliete i diritti richiesti e cliccate sul bottone ''Modifica''.', '111111', '290');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('17', 'Come faccio a personalizzare il servizio di news?', 'Una volta [url=v2.php?do=ShowHelp#id14]aggiunto l''esame al proprio MyUniversiBO[/url], viene impostata automaticamente dal sistema la possibilit\340 di ricevere tutte le  [url=v2.php?do=ShowHelp#id16]news[/url] che vengono inserite nella pagina dell''esame d''interesse.  Una volta [url=v2.php?do=ShowHelp#id10]loggati[/url], andando nella pagina [url=v2.php?do=ShowSettings]''Impostazioni''[/url] (link in alto a sinistra) e  cliccando su ''Impostazioni Personali'', troverete un campo in cui \350 segnato il vostro indirizzo e-mail a cui verranno spedite le news, e la possibilit\340 di scegliere quali notizie ricevere e quali no.[list][*]Nessun messaggio: non vi verr\340 inviato nessun messaggio, nemmeno quelli urgenti.[*]Solo messaggi urgenti: solo le news indicate dal docente come urgenti vi verranno segnalate con una e-mail; [*]Tutti i messaggi: tutte le news inserite nei vostri esami preferiti vi verranno anche segnalate via e-mail.[/list]\r\nL''opzione di default \350 la terza.  Una volta selezionata la preferenza, cliccando su ''Invia'' vi verr\340 notificato se la modifica ha avuto successo e a questo punto si potr\340 chiudere la finestra.\r\n\311 comunque sempre possibile modificare la scelta di notifica effettuata, ripetendo le operazioni sopra indicate.', '1231232', '140');



DELETE FROM help_riferimento WHERE 1=1;
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '24');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '27');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '28');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '29');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newscollabs', '30');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '16');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '17');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('newsutenti', '19');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filesutenti', '26');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filesutenti', '23');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '20');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '21');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('filescollabs', '22');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '9');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '11');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '4');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '5');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '6');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '7');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('iscrizione', '8');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('suggerimentinav', '10');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('suggerimentinav', '3');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('myuniversibo', '14');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('myuniversibo', '15');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('myuniversibo', '13');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('ruoliadmin', '1');
INSERT INTO "help_riferimento" ("riferimento", "id_help") VALUES ('ruoliadmin', '2');



DELETE FROM help_topic WHERE 1=1;
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('iscrizione', 'Come faccio ad iscrivermi ad UniversiBO?', '10');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('suggerimentinav', 'Navigazione nel sito: i primi passi.', '30');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filescollabs', 'Voglio mettere un file on line su UniversiBO: come posso fare?', '70');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filesutenti', 'Come faccio a scaricare i files da UniversiBO?', '60');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('myuniversibo', 'Come personalizzare My UniversiBO.', '40');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newsutenti', 'Cos''\351 e come gestire il servizio di News di UniversiBO.', '50');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('ruoliadmin', 'Cercare un utente e cambiare i diritti (solo Admin)', '90');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newscollabs', 'Voglio inserire una notizia su UniversiBO: come posso fare?', '80');

DELETE FROM "file_tipo" WHERE 1=1;

INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('1', 'altro', '', 'formato_.gif', '');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('11', 'ppt', '\.ppt$', 'formato_ppt.gif', 'Microsoft Power Point\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software propietario [url=http://office.microsoft.com/]Microsoft Office[/url]\r\nAlternativamente potete utilizzare il software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('7', 'mp3', '\.mp3$', 'formato_mp3.gif', 'MPEG-1 Audio Layer III - tecnologia per la compressione/decompressione di file audio\r\nPer ascoltare questo formato audio potete utilizzare il software libero [url=http://www.videolan.org/]Videolan[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('4', 'gif', '\.gif$', 'formato_gif.gif', 'Graphic Interchange Format');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('6', 'jpeg', '\.jpg$', 'formato_jpg.gif', 'Joint Photographic Experts Group');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('13', 'tex', '\.tex$', 'formato_tex.gif', 'TeX Document');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('14', 'txt', '\.txt$', 'formato_txt.gif', 'File di testo');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('16', 'bmp', '\.bmp$', 'formato_bmp.gif', 'Bitmap');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('3', 'doc', '\.doc$', 'formato_doc.gif', 'Microsoft Word\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software propietario [url=http://office.microsoft.com/]Microsoft Office[/url]\r\nAlternativamente potete utilizzare il software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('15', 'xls', '\.xls$', 'formato_xls.gif', 'Microsoft Excel\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software propietario [url=http://office.microsoft.com/]Microsoft Office[/url]\r\nAlternativamente potete utilizzare il software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('2', 'pdf', '\.pdf$', 'formato_pdf.gif', 'Adobe Portable Document Format\r\nPer visualizzare questo formato di file avrete bisogno di [url=http://www.adobe.com/products/acrobat/readermain.html]Adobe Reader[/url]\r\nAlternativamente potete utilizzare il software libero [url=http://www.cs.wisc.edu/~ghost/]Ghostview o GSview[/url] basati su Ghostscript');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('12', 'rtf', '\.rtf$', 'formato_rtf.gif', 'Rich Text Format - Formato aperto sviluppato da Microsoft.\r\nPotrete visualizzare questo tipo di file utilizzando un word processor come il software propietario [url=http://office.microsoft.com/]Microsoft Word[/url]\r\nAlternativamente potete utilizzare il software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('5', 'html', '\.(html|htm)$', 'formato_html.gif', 'HyperText Mark-Up Language - Formato aperto definito dal [url=http://www.w3c.org/]World Wide Web Consortium (W3C)[/url]\r\nPotete visualizzarlo utilizzando qualsiasi browser web come [url=http://www.mozilla.org/products/firefox/]Mozilla Firefox[/url] o [url=http://www.microsoft.com/windows/ie/]Microsoft Internet Explorer[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('8', 'sxw', '\.sxw$', 'formato_sxw.gif', 'Open Office Writer\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('10', 'sxi', '\.sxi$', 'formato_sxi.gif', 'Open Office Impress\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software libero [url=http://www.openoffice.org/]OpenOffice[/url]');
INSERT INTO "file_tipo" ("id_file_tipo", "descrizione", "pattern_riconoscimento", "icona", "info_aggiuntive") VALUES ('9', 'sxc', '\.sxc$', 'formato_sxc.gif', 'Open Office Calc\r\nPer visualizzare correttamente questo tipo di file avrete bisogno del software libero [url=http://www.openoffice.org/]OpenOffice[/url]');

--LastHope 28-9-2004

UPDATE "utente" SET "default_style" = 'unibo' WHERE 1=1;
 
-- brain 1-10-2004
 
UPDATE phpbb_config SET config_value='0' WHERE config_name='override_user_style';

-- brain 2-10-2004
 

ALTER TABLE "utente_canale" RENAME "nome" TO "nome_errato";
ALTER TABLE "utente_canale" ADD "nome" varchar(60) ;
UPDATE "utente_canale" SET nome = nome_errato;
ALTER TABLE "utente_canale" DROP COLUMN "nome_errato";

-- 26-12-2004 brain
-- sposto la chiave primaria su cod_doc invece che id_utente
ALTER TABLE ONLY docente DROP CONSTRAINT docente_pkey;
ALTER TABLE "docente" ADD PRIMARY KEY ("cod_doc");
