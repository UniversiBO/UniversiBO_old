--LastHope 27-08-2005

ALTER TABLE "info_didattica" ADD "orario_ics_link" character varying(256);
UPDATE "info_didattica" SET orario_ics_link = ' ';
ALTER TABLE "info_didattica" ALTER COLUMN "orario_ics_link" SET NOT NULL;


--evaimitico 22/09/2005
UPDATE canale SET links_attivo = 'S' WHERE id_canale = '25';

--03-02-2006 LastHope
--Questa query non esiste sul CVS, ma puo' essere che il vostro db sia gia' sistemato...vedete voi
--CREATE TABLE docente_contatti (
--    cod_doc character varying(6) NOT NULL,
--    stato integer DEFAULT 1 NOT NULL,
--    id_utente_assegnato integer,
--    ultima_modifica integer,
--    report text DEFAULT ''::text NOT NULL
--);
--ALTER TABLE ONLY docente_contatti
--    ADD CONSTRAINT docente_contatti_pkey PRIMARY KEY (cod_doc);
    
--07-02-2006 LastHope
--Altra query che non esiste sul CVS

--create table rb_docente(
--IDUtente int primary key,
--NomeOggetto char(9),
--GuidUser char(32),
--LogonName varchar(50),
--Nome varchar(30),
--Cognome varchar(40),
--PrefissoNome varchar(15),
--SuffissoNome varchar(10),
--Sesso smallint,
--Email varchar(50),
--cod_doc char(6),
--NomeOggettoStruttura char(9),
--DescrizioneStruttura varchar(100) 
--);

--create table rb_email(
--IDUtente int,
--SeqEmailUtente smallint,
--DescrizioneEMailUtente varchar(50),
--primary key(IDUtente,SeqEmailUtente)
--);

--create table rb_telefono(
--IDUtente int,
--SeqTelUtente smallint,
--DescrizioneTelUtente varchar(28),
--Voice smallint,
--Fax smallint,
--TipoTel varchar(15),
--primary key(IDUtente, SeqTelUtente)
--);

--------------------------------

--create table rub_docente(
--cod_doc char(6),  --codice docente del cesia e della nostra tabella "docente"
--Nome varchar(30), --nome docente
--Cognome varchar(40),  --cognome docente
--PrefissoNome varchar(15),  --prefisso tipo "dott." "prof."
--Sesso smallint,  --sesso  1=maschile 2=femminile
--Email varchar(50),  --email 
--DescrizioneStruttura varchar(100), --descrizione del dipartimento o struttura al quale il docente afferisce
--flag_origine smallint ); --origine dei dati: 1=inseriti da DSA, 0=inseriti manualmente

--create table rub_email(
--cod_doc char(6),  --codice docente del cesia e della nostra tabella "docente"
--SeqEmailUtente smallint,  --numero di sequenza dell'e-mail (nel caso il docente abbia pi� e-mail)
--DescrizioneEMailUtente varchar(50) --e-mail
--);

--create table rub_telefono(
--cod_doc char(6),  --codice docente del cesia e della nostra tabella "docente"
--SeqTelUtente smallint,  --numero di sequnza del telefono (nel caso il docente abbi� pi� numeri di telefono)
--DescrizioneTelUtente varchar(28), --telefono
--Voice smallint, --servizio voce 1=si 0=no
--Fax smallint  --servizio fax 1=si 0=no
--);

---------------------------------------
--insert into rub_docente (select cod_doc, Nome, Cognome, PrefissoNome, Sesso, Email, DescrizioneStruttura, 1
--                         from rb_docente);

--insert into rub_email (select d.cod_doc, e.SeqEmailUtente, e.DescrizioneEmailUtente
--                        from rb_docente d, rb_email e
--                        where d.IDUtente=e.IDUtente);

--insert into rub_telefono (select d.cod_doc, t.SeqTelUtente, t.DescrizioneTelUtente, t.Voice, t.Fax
--                          from rb_docente d, rb_telefono t
--                          where d.IDUtente=t.IDUtente);
                          
   
-- evaimitico  12/05/2006
CREATE TABLE "informativa" (
"id_informativa" integer DEFAULT nextval('"informativa_id_informativa_seq"'::text) NOT NULL,
"data_pubblicazione" integer NOT NULL,
"data_fine" integer,
"testo" text NOT NULL,
PRIMARY KEY ("id_informativa"));


CREATE SEQUENCE "informativa_id_informativa_seq" INCREMENT 1 MINVALUE 1 START 1 CACHE 1;


insert into "informativa" ("data_pubblicazione","testo")
VALUES (1147431647,'INFORMATIVA AI SENSI DELLA LEGGE 31 DICEMBRE 1996 N. 675/96
Ai sensi e per gli effetti dell\'art.13 L.675/96 informiamo di quanto segue:

1. In relazione al trattamento di dati personali l\'interessato ha diritto:   a) di conoscere, mediante accesso gratuito al registro di cui all\'articolo 31, comma 1, lettera a), l\'esistenza di trattamenti di dati che possono riguardarlo;
  b) di essere informato su quanto indicato all\'articolo 7, comma 4, lettere a), b) e h);
  c) di ottenere, a cura del titolare o del responsabile, senza ritardo:
    1) la conferma dell\'esistenza o meno di dati personali che lo riguardano, anche se non ancora registrati, e la comunicazione in forma intelligibile dei medesimi dati e della loro origine, nonch� della logica e delle finalit� su cui si basa il trattamento; la richiesta pu� essere rinnovata, salva l\'esistenza di giustificati motivi, con intervallo non minore di novanta giorni;
    2) la cancellazione, la trasformazione in forma anonima o il blocco dei dati trattati in violazione di legge, compresi quelli di cui non � necessaria la conservazione in relazione agli scopi per i quali i dati sono stati raccolti o successivamente trattati;
    3) l\'aggiornamento, la rettificazione ovvero, qualora vi abbia interesse, l\'integrazione dei dati;
    4) l\'attestazione che le operazioni di cui ai numeri 2) e 3) sono state portate a conoscenza, anche per quanto riguarda il loro contenuto, di coloro ai quali i dati sono stati comunicati o diffusi, eccettuato il caso in cui tale adempimento si riveli impossibile o comporti un impiego di mezzi manifestamente sproporzionato rispetto al diritto tutelato;
  d) di opporsi, in tutto o in parte, per motivi legittimi, al trattamento dei dati personali che lo riguardano, ancorch� pertinenti allo scopo della raccolta;
  e) di opporsi, in tutto o in parte, al trattamento di dati personali che lo riguardano, previsto a fini di informazione commerciale o di invio di materiale pubblicitario o di vendita diretta ovvero per il compimento di ricerche di mercato o di comunicazione commerciale interattiva e di essere informato dal titolare, non oltre il momento in cui i dati sono comunicati o diffusi, della possibilit� di esercitare gratuitamente tale diritto.

2. Per ciascuna richiesta di cui al comma 1, lettera c), numero 1), pu� essere chiesto all\'interessato, ove non risulti confermata l?esistenza di dati che lo riguardano, un contributo spese, non superiore ai costi effettivamente sopportati, secondo le modalit� ed entro i limiti stabiliti dal regolamento di cui all\'articolo 33, comma 3.

3. I diritti di cui al comma 1 riferiti ai dati personali concernenti persone decedute possono essere esercitati da chiunque vi abbia interesse.

4. Nell\'esercizio dei diritti di cui al comma 1 l\'interessato pu� conferire, per iscritto, delega o procura a persone fisiche o ad associazioni.

5. Restano ferme le norme sul segreto professionale degli esercenti la professione di giornalista, limitatamente alla fonte della notizia
');


--tabelle necessarie per capire lo stato delle interazione dell'utente con i vari InteractiveCommand

CREATE TABLE "step_log" (
"id_step" integer DEFAULT nextval('"step_id_step_seq"'::text) NOT NULL,
"id_utente" integer NOT NULL,
"data_ultima_interazione" integer NOT NULL,
"nome_classe" varchar(255) NOT NULL,
"esito_positivo" char(1),
PRIMARY KEY ("id_step"));

CREATE SEQUENCE "step_id_step_seq" INCREMENT 1 MINVALUE 1 START 1 CACHE 1;


CREATE TABLE "step_parametri" (
"id_step" integer NOT NULL,
"callback_name" varchar(255) NOT NULL,
"param_name" varchar(255) NOT NULL,
"param_value" varchar(255) NOT NULL     -- andr� bene come dimensione? forse � meglio un altro tipo
);


-- 24/05/06  evaimitico
ALTER TABLE utente ADD sospeso char(1);
UPDATE utente SET sospeso = 'N' WHERE 1=1;
ALTER TABLE utente ALTER COLUMN sospeso SET NOT NULL;
ALTER TABLE utente ALTER COLUMN sospeso SET DEFAULT 'N';

-- 6-9-07 evaimitico
CREATE SEQUENCE prg_sdop_id_sdop_seq;
ALTER TABLE prg_sdoppiamento ADD COLUMN id_sdop INTEGER;
UPDATE prg_sdoppiamento SET id_sdop = nextval('prg_sdop_id_sdop_seq');
ALTER TABLE prg_sdoppiamento ALTER COLUMN id_sdop SET DEFAULT nextval('prg_sdop_id_sdop_seq');
ALTER TABLE prg_sdoppiamento ALTER COLUMN id_sdop SET NOT NULL;




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
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('33', 'Cos\'� "modifica didattica"?','"Modifica didattica" � un tool che permette di modificare le informazioni su anno, ciclo, docente per ogni insegnamento e sdoppiamento di UniversiBO.\nNella tabella "Insegnamento selezionato" sono presenti le informazioni riguardanti l\'insegnamento/sdoppiamento che si sta per modificare. Se si tratta di uno sdoppiamento, in fondo alla tabella � presente "status: sdoppiato", altrimenti non compare.','1117109715','310');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('34', 'Cos\'� uno sdoppiamento?','E\' un insegnamento comune a pi� corsi di laurea (stesso docente, stesso orario, ecc. ). Avremo un insegnamento "padre" (nel corso di laurea che attiva l\'insegnamento) e i relativi "figli" (gli sdoppiati, negli altri corsi di laurea).\nEsempio:\nSISTEMI DI TELECOMUNICAZIONI L-A � attivato da INGEGNERIA DELLE TELECOMUNICAZIONI specialistica (insegnamento padre).\nMa � presente anche nel corso di laurea INGEGNERIA INFORMATICA specialistica (insegnamento figlio).\nSe vi sono insegnamenti/sdoppiamenti correlati a quello che si sta modificando, viene visualizzata una lista dopo i box di modifica. Se nella lista � presente il "padre", sar� evidenziato in azzurro (se non c\'� significa che il padre � quello selezionato inizialmente).\nE\' possibile applicare le modifiche anche agli insegnamenti/sdoppiamenti correlati selezionando quelli di interesse.\nE\' possibile spostarsi negli insegnamenti/sdoppiamenti correlati, cliccando su un insegnamento della lista.','1117109715','320');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('35', 'Come posso modificare il ciclo e l\'anno di corso','Basta inserire il dato corretto nell\'apposita casella di testo (i valori possibili si trovano in basso nel box di correzione).','1117109715','330');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('36', 'Come posso modificare il docente?','Inserendo il codice docente nell\'apposita casella di testo: il docente pu� essere modificato solo nel caso in cui non sia ancora stato creato il forum dell\'insegnamento e solo dall\'insegnamento padre. Quindi se siete su uno sdoppiamento, cliccate sull\'insegnamento evidenziato in azzurro per effettuare la modifica del docente. Il docente modificato, sar� cambiato anche per tutti gli insegnamenti correlati (padre/figli).\nNel caso in cui non si conosca il codice del docente � possibile effettuare una ricerca per username o per e-mail. Nella ricerca si possono utilizzare dei caratteri jolly:[list][*] % sostituisce un qualsiasi numero di caratteri (esempio: una ricerca e% nel campo username, trova tutti gli username che iniziano con la lettera e)[*] _ sostituisce un singolo carattere (esempio: una ricerca ner_ nel campo username, trova tutti gli username che iniziano per ner e terminano con qualsiasi lettera)[/list] ','1117109715','350');
INSERT INTO "help" ("id_help", "titolo", "contenuto", "ultima_modifica", "indice") VALUES ('37', 'Come rendo effettive le modifiche?','Cliccando sul tasto "Esegui". Una volta premuto, non � possibile interrompere l\'operazione di modifica quindi prima di mandare in esecuzione una modifica, controllate due volte per non fare errori.','1117109715','360');

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
-- in didatticagestione si pu� riusare l'help che riguarda la ricerca di username
INSERT INTO help_riferimento (riferimento, id_help) VALUES ('didatticagestione', 33); 
INSERT INTO help_riferimento (riferimento, id_help) VALUES ('didatticagestione', 34); 
INSERT INTO help_riferimento (riferimento, id_help) VALUES ('didatticagestione', 35); 
INSERT INTO help_riferimento (riferimento, id_help) VALUES ('didatticagestione', 36);
INSERT INTO help_riferimento (riferimento, id_help) VALUES ('didatticagestione', 37);  

DELETE FROM help_topic WHERE 1=1;
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('iscrizione', 'Come faccio ad iscrivermi ad UniversiBO?', '10');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('suggerimentinav', 'Navigazione nel sito: i primi passi.', '30');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filescollabs', 'Voglio mettere un file on line su UniversiBO: come posso fare?', '70');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('filesutenti', 'Come faccio a scaricare i files da UniversiBO?', '60');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('myuniversibo', 'Come personalizzare My UniversiBO.', '40');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newsutenti', 'Cos''\351 e come gestire il servizio di News di UniversiBO.', '50');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('ruoliadmin', 'Cercare un utente e cambiare i diritti (solo Admin)', '90');
INSERT INTO "help_topic" ("riferimento", "titolo", "indice") VALUES ('newscollabs', 'Voglio inserire una notizia su UniversiBO: come posso fare?', '80');
INSERT INTO help_topic (riferimento, titolo, indice) VALUES ('didatticagestione','Modificare un insegnamento e cercare un codice docente (solo admin e collaboratori)',100);