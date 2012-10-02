<?php

/***************************************************************************
 *                            lang_admin.php [English]
 *                              -------------------
 *     begin                : Sat Dec 16 2000
 *     copyright            : (C) 2001 The phpBB Group
 *     email                : support@phpbb.com
 *
 *     $Id: lang_admin.php,v 1.35.2.17 2006/02/05 15:59:48 grahamje Exp $
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/* CONTRIBUTORS
	01-01-2007  phpbb.it - http://www.phpbb.it - info@phpbb.it
		Fixed many minor grammatical mistakes
*/

//
// Format is same as lang_main
//

//
// Modules, this replaces the keys used
// in the modules[][] arrays in each module file
//
$lang['General'] = 'Generale';
$lang['Users'] = 'Utenti';
$lang['Groups'] = 'Gruppi';
$lang['Forums'] = 'Forum';
$lang['Styles'] = 'Stili';

$lang['Configuration'] = 'Configurazione';
$lang['Permissions'] = 'Permessi';
$lang['Manage'] = 'Gestione';
$lang['Disallow'] = 'Disabilita nomi';
$lang['Prune'] = 'Pruning';
$lang['Mass_Email'] = 'Email generali';
$lang['Ranks'] = 'Livelli';
$lang['Smilies'] = 'Smilies';
$lang['Ban_Management'] = 'Ban control';
$lang['Word_Censor'] = 'Censura parole';
$lang['Export'] = 'Esporta';
$lang['Create_new'] = 'Crea';
$lang['Add_new'] = 'Aggiungi';
$lang['Backup_DB'] = 'Backup database';
$lang['Restore_DB'] = 'Ripristina database';


//
// Index
//
$lang['Admin'] = 'Amministrazione';
$lang['Not_admin'] = 'Non sei autorizzato ad amministrare questo forum';
$lang['Welcome_phpBB'] = 'Benvenuto in phpbb';
$lang['Admin_intro'] = 'Grazie per aver scelto phpbb come forum. Questa schermata mostra alcune statistiche del tuo forum. Puoi tornare a questa pagina cliccando sul link  <u>Admin index</u> nel pannello di sinistra. Per tornare all\'indice del tuo forum, clicca il logo phpbb nel pannello di sinistra. Gli altri collegamenti nella parte sinistra dello schermo ti permettono di controllare ogni aspetto del tuo forum, in tutte le schermata avrai le informazioni su come usare al meglio le varie funzioni. Per maggiori info e supporto visita ( <a href="http://www.phpbb.com" target="_blank"><strong>phpbb.com</strong></a> o <a href="http://www.phpbb.it" target="_blank"><strong>phpbb.it</strong></a> )';
$lang['Main_index'] = 'Forum index';
$lang['Forum_stats'] = 'Statistiche forum';
$lang['Admin_Index'] = 'Admin index';
$lang['Preview_forum'] = 'Anteprima forum';

$lang['Click_return_admin_index'] = 'Clicca %squi%s per tornare in Admin index';

$lang['Statistic'] = 'Statistiche';
$lang['Value'] = 'Valore';
$lang['Number_posts'] = 'Numero di messaggi';
$lang['Posts_per_day'] = 'Messaggi per giorno';
$lang['Number_topics'] = 'Numero di argomenti';
$lang['Topics_per_day'] = 'Argomenti per giorno';
$lang['Number_users'] = 'Numero di utenti';
$lang['Users_per_day'] = 'Utenti per giorno';
$lang['Board_started'] = 'Forum attivato il';
$lang['Avatar_dir_size'] = 'Dimensione directory avatar';
$lang['Database_size'] = 'Dimensione database';
$lang['Gzip_compression'] ='Compressione Gzip';
$lang['Not_available'] = 'Non disponibile';

$lang['ON'] = 'ON'; // This is for GZip compression
$lang['OFF'] = 'OFF';


//
// DB Utils
//
$lang['Database_Utilities'] = 'Utilità database';

$lang['Restore'] = 'Ripristina';
$lang['Backup'] = 'Backup';
$lang['Restore_explain'] = 'Questa funzione ripristina tutte le tabelle del forum phpbb da un file salvato. Se il tuo server lo supporta puoi caricare un file di testo con compressione Gzip e verrà automaticamente decompresso. <b>ATTENZIONE</b> Questa operazione sovrascriverà tutti i dati esistenti. L\'operazione di ripristino potrebbe impiegare molto tempo per essere completata. Non muoverti da questa pagina finché l\'operazione non sarà completata.';
$lang['Backup_explain'] = 'Qui puoi fare il backup di tutti i dati del forum. Se hai delle tabelle personalizzate nello stesso database di cui vorresti fare il backup inserisci i nomi separati da virgole nel campo Tabelle Addizionali. Se il tuo server lo supporta puoi comprimere i files utilizzando Gzip per ridurre le loro dimensioni prima del download.';

$lang['Backup_options'] = 'Opzioni backup';
$lang['Start_backup'] = 'Inizia backup';
$lang['Full_backup'] = 'Backup completo';
$lang['Structure_backup'] = 'Backup solo struttura';
$lang['Data_backup'] = 'Backup solo dati';
$lang['Additional_tables'] = 'Tabelle addizionali';
$lang['Gzip_compress'] = 'File di compressione Gzip';
$lang['Select_file'] = 'Seleziona un file';
$lang['Start_Restore'] = 'Inizia ripristino';

$lang['Restore_success'] = 'Il database è stato ripristinato con successo.<br /><br />Il tuo forum dovrebbe tornare allo stato che era al momento del backup.';
$lang['Backup_download'] = 'Il tuo download comincerà presto. Attendi...';
$lang['Backups_not_supported'] = 'Spiacenti, il backup del database non può essere concluso, a causa della mancanza di permessi. Contatta il tuo WebHost.';

$lang['Restore_Error_uploading'] = 'Errore nel caricamento del file di backup';
$lang['Restore_Error_filename'] = 'Problema con il nome del file, provane uno alternativo.';
$lang['Restore_Error_decompress'] = 'Non è possibile decomprimere un file Gzip, carica il file di testo.';
$lang['Restore_Error_no_file'] = 'Nessun file è stato caricato';


//
// Auth pages
//
$lang['Select_a_User'] = 'Seleziona un utente';
$lang['Select_a_Group'] = 'Seleziona un gruppo';
$lang['Select_a_Forum'] = 'Seleziona un forum';
$lang['Auth_Control_User'] = 'Controllo permessi utente'; 
$lang['Auth_Control_Group'] = 'Controllo permessi gruppo'; 
$lang['Auth_Control_Forum'] = 'Controllo permessi forum'; 
$lang['Look_up_User'] = 'Cerca utente'; 
$lang['Look_up_Group'] = 'Cerca gruppo'; 
$lang['Look_up_Forum'] = 'Cerca forum';  

$lang['Group_auth_explain'] = 'Qui puoi modificare i permessi e lo stato dei moderatori assegnati ad ogni gruppo. Non dimenticare che quando cambi i permessi di un gruppo, l\'utente potrebbe accedere comunque ai forum grazie ai suoi permessi individuali. In questo caso sarai avvisato.';
$lang['User_auth_explain'] = 'Qui puoi modificare i permessi e lo stato dei moderatori assegnati ad ogni utente individuale. Non dimenticare quando cambi i permessi di un utente i permessi del gruppo gli potrebbero permettere di accedere comunque ai forum. In questo caso sarai avvisato.';
$lang['Forum_auth_explain'] = 'Qui puoi modificare i livelli di autorizzazione per ogni forum. Puoi fare questo utilizzando una modalità semplice e una modalità avanzata. La modalità avanzata offre maggior controllo per ogni operazione sui forum. Ricorda che cambiare i permessi dei forum mostrerà gli utenti che possono eseguire le varie operazioni nei forum.';

$lang['Simple_mode'] = 'Modalità semplice';
$lang['Advanced_mode'] = 'Modalità avanzata';
$lang['Moderator_status'] = 'Stato dei moderatori';

$lang['Allowed_Access'] = 'Accessi ammessi';
$lang['Disallowed_Access'] = 'Accessi non ammessi';
$lang['Is_Moderator'] = 'È moderatore';
$lang['Not_Moderator'] = 'Non è moderatore';

$lang['Conflict_warning'] = 'Attenzione conflitto di autorizzazione';
$lang['Conflict_access_userauth'] = 'Questo utente ha ancora diritti di accesso a questo forum per il suo gruppo di appartenenza. Potresti voler cambiare i permessi del gruppo o rimuovere questo utente dal gruppo per togliere completamente i suoi diritti di accesso. I diritti del gruppo (e i forum coinvolti) sono elencati qui sotto.';
$lang['Conflict_mod_userauth'] = 'Questo utente ha ancora i diritti di moderatore a questo forum per il suo gruppo di appartenenza. Potresti voler cambiare i permessi del gruppo o rimuovere questo utente dal gruppo per togliere completamente i suoi diritti di moderatore. I diritti del gruppo (e i forum coinvolti) sono elencati qui sotto.';

$lang['Conflict_access_groupauth'] = 'I seguenti utenti hanno ancora diritti di accesso a questo forum per le impostazioni dei permessi utenti. Potresti voler cambiare i permessi del gruppo o rimuovere questo utente dal gruppo per togliere completamente i suoi diritti di accesso. I diritti del gruppo (e i forum coinvolti) sono elencati qui sotto.';
$lang['Conflict_mod_groupauth'] = 'I seguenti utenti hanno ancora i diritti di moderatore a questo forum per le impostazioni dei permessi utenti. Potresti voler cambiare i permessi del gruppo o rimuovere questo utente dal gruppo per togliere completamente i suoi diritti di moderatore. I diritti del gruppo (e i forum coinvolti) sono elencati qui sotto.';

$lang['Public'] = 'Pubblico';
$lang['Private'] = 'Privato';
$lang['Registered'] = 'Registrato';
$lang['Administrators'] = 'Amministratori';
$lang['Hidden'] = 'Nascosto';

// These are displayed in the drop down boxes for advanced
// mode forum auth, try and keep them short!
$lang['Forum_ALL'] = 'TUTTI';
$lang['Forum_REG'] = 'REG';
$lang['Forum_PRIVATE'] = 'PRIVATO';
$lang['Forum_MOD'] = 'MOD';
$lang['Forum_ADMIN'] = 'ADMIN';

$lang['View'] = 'Guarda';
$lang['Read'] = 'Leggi';
$lang['Post'] = 'Invia';
$lang['Reply'] = 'Rispondi';
$lang['Edit'] = 'Modifica';
$lang['Delete'] = 'Cancella';
$lang['Sticky'] = 'Importante';
$lang['Announce'] = 'Annuncio'; 
$lang['Vote'] = 'Vota';
$lang['Pollcreate'] = 'Crea Sondaggio';

$lang['Permissions'] = 'Permessi';
$lang['Simple_Permission'] = 'Permesso semplice';

$lang['User_Level'] = 'Livello utente'; 
$lang['Auth_User'] = 'Utente';
$lang['Auth_Admin'] = 'Amministratore';
$lang['Group_memberships'] = 'Membri gruppo utenti';
$lang['Usergroup_members'] = 'Questo gruppo ha i seguenti membri';

$lang['Forum_auth_updated'] = 'Permessi forum aggiornati';
$lang['User_auth_updated'] = 'Permessi utente aggiornati';
$lang['Group_auth_updated'] = 'Permessi gruppo aggiornati';

$lang['Auth_updated'] = 'I permessi sono stati aggiornati';
$lang['Click_return_userauth'] = 'Clicca %squi%s per tornare a permessi utenti';
$lang['Click_return_groupauth'] = 'Clicca %squi%s per tornare a permessi gruppo';
$lang['Click_return_forumauth'] = 'Clicca %squi%s per tornare a permessi forum';


//
// Banning
//
$lang['Ban_control'] = 'Controllo disabilitazione';
$lang['Ban_explain'] = 'Qui puoi controllare la disabilitazione degli utenti. Puoi disabilitare per utente specifico o un individuale, per intervallo di indirizzi IP o hostnames. Questi metodi impediscono all\'utente di raggiungere anche l\'indice del tuo forum. Per impedire ad un utente di registrarsi con uno username diverso puoi anche disabilitare un indirizzo e-mail specifico. Nota che disabilitare solo un indirizzo email non eviterà che quell\'utente possa essere in grado di entrare o di inviare messaggi al tuo forum. Per questo devi usare uno dei primi due metodi.';
$lang['Ban_explain_warn'] = 'Nota che inserendo un intervallo di indirizzi IP, nella disabilitazione verranno inclusi tutti gli indirizzi tra l\'inizio e la fine degli indirizzi. Verranno fatti dei tentativi per minimalizzare il numero degli indirizzi aggiunti al database introducendo abbreviazioni generate automaticamente in modo apprpopriato. Se davvero devi inserire un intervallo di indirizzi, prova a mantenerlo piccolo o meglio specifica un singolo indirizzo.';

$lang['Select_username'] = 'Seleziona uno username';
$lang['Select_ip'] = 'Seleziona un indirizzo IP';
$lang['Select_email'] = 'Seleziona un indirizzo e-mail';

$lang['Ban_username'] = 'Disabilita uno o più utenti specifici';
$lang['Ban_username_explain'] = 'Puoi disabilitare più utenti con una sola operazione utilizzando l\'appropriata combinazione di mouse e tastiera per il tuo computer e browser';

$lang['Ban_IP'] = 'Disabilita uno o più indirizzi IP o hostname';
$lang['IP_hostname'] = 'Indirizzo IP o hostname';
$lang['Ban_IP_explain'] = 'Per specificare diversi indirizzi IP o hostname separali con virgole (,). Per specificare un intervallo di indirizzi IP separa l\'inizio dalla fine con un trattino (-), per specificare un\'abbreviazione usa (*)';

$lang['Ban_email'] = 'Disabilita uno o più indirizzi e-mail';
$lang['Ban_email_explain'] = 'Per specificare diversi indirizzi e-mail separali con virgole (,). Per specificare una abbreviazione per gli username usa *, per esempio *@hotmail.com';

$lang['Unban_username'] = 'Riabilita uno o più utenti specifici';
$lang['Unban_username_explain'] = 'Puoi riabilitare più utenti con un unica operazione utilizzando l\'appropiata combinazione di mouse e tastiera per il tuo computer e browser';

$lang['Unban_IP'] = 'Riabilita uno o più indirizzi IP';
$lang['Unban_IP_explain'] = 'Puoi riabilitare più indirizzi IP con un unica operazione utilizzando l\'appropiata combinazione di mouse e tastiera per il tuo computer e browser';

$lang['Unban_email'] = 'Riabilita uno o più indirizzi e-mail';
$lang['Unban_email_explain'] = 'Puoi riabilitare più indirizzi e-mail con un unica operazione utilizzando l\'appropiata combinazione di mouse e tastiera per il tuo computer e browser';

$lang['No_banned_users'] = 'Non ci sono username disabilitati';
$lang['No_banned_ip'] = 'Non ci sono indirizzi IP disabilitati';
$lang['No_banned_email'] = 'Non ci sono indirizzi e-mail disabilitati';

$lang['Ban_update_sucessful'] = 'La lista degli utenti disabilitati è stata aggiornata con successo';
$lang['Click_return_banadmin'] = 'Clicca %squi%s per tornare a controllo disabilitazione';


//
// Configuration
//
$lang['General_Config'] = 'Configurazione generale';
$lang['Config_explain'] = 'Il modulo qui sotto ti permette di personalizzare tutte le opzioni generali del forum. Per la configurazione dei forum e degli utenti utilizza i collegamenti appropriati nel pannello di sinistra.';

$lang['Click_return_config'] = 'Clicca %squi%s per tornare a Configurazione generale';

$lang['General_settings'] = 'Impostazioni generali forum';
$lang['Server_name'] = 'Nome dominio';
$lang['Server_name_explain'] = 'Il nome del dominio da cui lanci il forum';
$lang['Script_path'] = 'Percorso cartella phpbb';
$lang['Script_path_explain'] = 'Il percorso dove è situato phpbb relativo al nome di dominio';
$lang['Server_port'] = 'Porta del server';
$lang['Server_port_explain'] = 'La porta del tuo server, di solito 80, cambia solo se è diversa';
$lang['Site_name'] = 'Nome del sito';
$lang['Site_desc'] = 'Descrizione del sito';
$lang['Board_disable'] = 'Disabilita il forum';
$lang['Board_disable_explain'] = 'Questo renderà il forum non disponibile per gli utenti. Non uscire dopo aver disabilitato il forum, altrimenti non sarai più in grado di entrare di nuovo!';
$lang['Acct_activation'] = 'Abilita l\'attivazione degli account';
$lang['Acc_None'] = 'Nessuno'; // These three entries are the type of activation
$lang['Acc_User'] = 'Utente';
$lang['Acc_Admin'] = 'Amministratore';

$lang['Abilities_settings'] = 'Impostazioni base per utenti e forum';
$lang['Max_poll_options'] = 'Numero massimo di opzioni per sondaggio';
$lang['Flood_Interval'] = 'Intervallo del Flood';
$lang['Flood_Interval_explain'] = 'Numero di secondi di attesa tra ogni messaggio'; 
$lang['Board_email_form'] = 'Messaggistica e-mail attraverso il forum';
$lang['Board_email_form_explain'] = 'Gli utenti possono inviarsi e-mail utilizzando il forum';
$lang['Topics_per_page'] = 'Argomenti per pagina';
$lang['Posts_per_page'] = 'Messaggi per pagina';
$lang['Hot_threshold'] = 'Numero di messaggi per essere popolare';
$lang['Default_style'] = 'Stile di default';
$lang['Override_style'] = 'Annulla il tema dell\'utente';
$lang['Override_style_explain'] = 'Sostituisce lo stile dell\'utente con quello di default';
$lang['Default_language'] = 'Lingua di default';
$lang['Date_format'] = 'Formato data';
$lang['System_timezone'] = 'Fuso orario del sistema';
$lang['Enable_gzip'] = 'Abilita la compressione GZip';
$lang['Enable_prune'] = 'Abilita il pruning del forum';
$lang['Allow_HTML'] = 'Permetti HTML';
$lang['Allow_BBCode'] = 'Permetti BBCode';
$lang['Allowed_tags'] = 'Tags HTML permessi';
$lang['Allowed_tags_explain'] = 'Separa i tags con virgole';
$lang['Allow_smilies'] = 'Permetti smilies';
$lang['Smilies_path'] = 'Percorso salvataggio smilies';
$lang['Smilies_path_explain'] = 'Percorso principale cartella phpBB, es. images/smilies';
$lang['Allow_sig'] = 'Permetti firma';
$lang['Max_sig_length'] = 'Lunghezza massima firma';
$lang['Max_sig_length_explain'] = 'Numero massimo di caratteri per la firma degli utenti';
$lang['Allow_name_change'] = 'Permetti cambio username';

$lang['Avatar_settings'] = 'Impostazioni avatar';
$lang['Allow_local'] = 'Abilita galleria avatar';
$lang['Allow_remote'] = 'Abilita avatar remoti';
$lang['Allow_remote_explain'] = 'Avatar linkati da un altro sito web';
$lang['Allow_upload'] = 'Abilita il caricamento degli avatar';
$lang['Max_filesize'] = 'Dimensione massima file avatar';
$lang['Max_filesize_explain'] = 'Per i file avatar caricati';
$lang['Max_avatar_size'] = 'Dimensioni massime avatar';
$lang['Max_avatar_size_explain'] = '(altezza x larghezza in pixels)';
$lang['Avatar_storage_path'] = 'Percorso salvataggio avatar';
$lang['Avatar_storage_path_explain'] = 'Percorso principale cartella phpbb, es. images/avatars';
$lang['Avatar_gallery_path'] = 'Percorso galleria avatar';
$lang['Avatar_gallery_path_explain'] = 'Percorso principale cartella phpbb per il per-caricamento delle immagini, es. images/avatars/gallery';

$lang['COPPA_settings'] = 'Impostazioni reg. Minore';
$lang['COPPA_fax'] = 'Numero di fax per reg. Minore';
$lang['COPPA_mail'] = 'Indirizzo per reg. Minore';
$lang['COPPA_mail_explain'] = 'Questo è l\'indirizzo al quale i genitori manderanno il modulo di registrazione Minore';

$lang['Email_settings'] = 'Impostazioni e-mail';
$lang['Admin_email'] = 'Indirizzo e-mail amministratore';
$lang['Email_sig'] = 'Firma e-mail';
$lang['Email_sig_explain'] = 'Questo testo verrà allegato ad ogni e-mail spedita dal forum';
$lang['Use_SMTP'] = 'Usa un Server SMTP per le e-mail';
$lang['Use_SMTP_explain'] = 'Rispondi si se vuoi o devi inviare email attraverso un server specifico invece della funzione mail locale';
$lang['SMTP_server'] = 'Indirizzo server SMTP';
$lang['SMTP_username'] = 'Username SMTP';
$lang['SMTP_username_explain'] = 'Compila il campo username soltanto se il server lo richiede';
$lang['SMTP_password'] = 'Password SMTP';
$lang['SMTP_password_explain'] = 'Compila il campo password soltanto se il server lo richiede';

$lang['Disable_privmsg'] = 'Messaggi privati';
$lang['Inbox_limits'] = 'Numero massimo di messaggi per posta in arrivo';
$lang['Sentbox_limits'] = 'Numero massimo di messaggi per posta inviata';
$lang['Savebox_limits'] = 'Numero massimo di messaggi per posta salvata';

$lang['Cookie_settings'] = 'Impostazioni cookie'; 
$lang['Cookie_settings_explain'] = 'Questo modulo controlla come vengono definiti i cookie inviati ai browser. In molti casi l\'impostazione di default è sufficiente. Se devi cambiare queste impostazioni fallo con attenzione, le impostazioni non corrette possono impedire agli utenti di entrare.';
$lang['Cookie_domain'] = 'Dominio cookie';
$lang['Cookie_name'] = 'Nome cookie';
$lang['Cookie_path'] = 'Percorso cookie';
$lang['Cookie_secure'] = 'Cookie sicuri';
$lang['Cookie_secure_explain'] = 'Se il server funziona via SSL abilita questo altrimenti lascia disabilitato';
$lang['Session_length'] = 'Lunghezza sessione [ secondi ]';

// Visual Confirmation
$lang['Visual_confirm'] = 'Abilita conferma visuale';
$lang['Visual_confirm_explain'] = 'Richiede agli utenti di scrivere un codice definito da un\'immagine al momento della registrazione.';

// Autologin Keys - added 2.0.18
$lang['Allow_autologin'] = 'Permetti login automatici';
$lang['Allow_autologin_explain'] = 'Determina se viene data la possibilità di fare il login automaticamente quando visitano il forum';
$lang['Autologin_time'] = 'Login automatico';
$lang['Autologin_time_explain'] = 'Determina la funzione di auto login in giorni, quindi inserisci un numero valido per attivarlo. Lascia 0 se non lo vuoi attivare.';

// Search Flood Control - added 2.0.20
$lang['Search_Flood_Interval'] = 'Intervallo per le ricerche';
$lang['Search_Flood_Interval_explain'] = 'Numero di secondi che un utente deve aspettare tra una richiesta di ricerca e la prossima'; 

//
// Forum Management
//
$lang['Forum_admin'] = 'Amministrazione forum';
$lang['Forum_admin_explain'] = 'Da questo pannello puoi aggiungere, modificare, cancellare, riordinare e ri-sincronizzare le categorie e i forum';
$lang['Edit_forum'] = 'Modifica forum';
$lang['Create_forum'] = 'Crea un nuovo forum';
$lang['Create_category'] = 'Crea una nuova categoria';
$lang['Remove'] = 'Rimuovi';
$lang['Action'] = 'Azione';
$lang['Update_order'] = 'Aggiorna ordine';
$lang['Config_updated'] = 'Configurazione forum aggiornata con successo';
$lang['Edit'] = 'Modifica';
$lang['Delete'] = 'Cancella';
$lang['Move_up'] = 'Sposta su';
$lang['Move_down'] = 'Sposta giù';
$lang['Resync'] = 'Sincronizza';
$lang['No_mode'] = 'Nessun mode impostato';
$lang['Forum_edit_delete_explain'] = 'Il modulo qui sotto ti permette di personalizzare tutte le opzioni generali del forum. Per la configurazione degli utenti e dei forum usa i collegamenti appropriati nel pannello di sinistra';

$lang['Move_contents'] = 'Sposta tutti i contenuti';
$lang['Forum_delete'] = 'Cancella forum';
$lang['Forum_delete_explain'] = 'Il modulo qui sotto ti permette di cancellare un forum (o una categoria) e decidere dove mettere tutti gli argomenti (o forum) in esso/a contenuti';

$lang['Status_locked'] = 'Bloccato';
$lang['Status_unlocked'] = 'Sblocca';
$lang['Forum_settings'] = 'Impostazioni generali forum';
$lang['Forum_name'] = 'Nome forum';
$lang['Forum_desc'] = 'Descrizione';
$lang['Forum_status'] = 'Stato del forum';
$lang['Forum_pruning'] = 'Eliminazione automatica';

$lang['prune_freq'] = 'Verifica l\'età degli argomenti ogni';
$lang['prune_days'] = 'Rimuovi gli argomenti che non hanno avuto risposte per';
$lang['Set_prune_data'] = 'Hai attivato l\'eliminazione automatica per questo forum ma non hai impostato la frequenza o il numero di giorni per l\'autoeliminazione. Torna indietro e attivalo';

$lang['Move_and_Delete'] = 'Sposta e cancella';

$lang['Delete_all_posts'] = 'Cancella tutti i messaggi';
$lang['Nowhere_to_move'] = 'Nessun posto dove spostare';

$lang['Edit_Category'] = 'Modifica categoria';
$lang['Edit_Category_explain'] = 'Utilizza questo forum per modificare un nome di categorie';

$lang['Forums_updated'] = 'Le informazioni dei forum e delle categorie sono state aggiornate con successo';

$lang['Must_delete_forums'] = 'Devi cancellare tutti i forum per cancellare questa categoria';

$lang['Click_return_forumadmin'] = 'Clicca %squi%s per tornare a amministrazione forum';


//
// Smiley Management
//
$lang['smiley_title'] = 'Utility modifica smiley';
$lang['smile_desc'] = 'Da questa pagina puoi aggiungere, togliere e modificare le emoticons o gli smiley che i tuoi utenti possono utilizzare nei loro messaggi.';

$lang['smiley_config'] = 'Configurazione smiley';
$lang['smiley_code'] = 'Codice smiley';
$lang['smiley_url'] = 'File immagine smiley';
$lang['smiley_emot'] = 'Emozione smiley';
$lang['smile_add'] = 'Aggiungi un nuovo smiley';
$lang['Smile'] = 'Smile';
$lang['Emotion'] = 'Emotion';

$lang['Select_pak'] = 'Seleziona Pacchetto (.pak) file';
$lang['replace_existing'] = 'Sovrascrivi gli smiley esistenti';
$lang['keep_existing'] = 'Mantieni gli smiley esistenti';
$lang['smiley_import_inst'] = 'Devi decomprimere il pacchetto di smiley caricare i file nella cartella appropriata per l\'installazione. Poi seleziona le informazioni corrette da questo modulo per importare il pacchetto di smiley.';
$lang['smiley_import'] = 'Importazione pacchetto smiley';
$lang['choose_smile_pak'] = 'Seleziona un pacchetto di smiley, estensione .pak';
$lang['import'] = 'Importa gli smiley';
$lang['smile_conflicts'] = 'Cosa devi fare in caso di conflitti';
$lang['del_existing_smileys'] = 'Cancella gli smiley esistenti prima di importare';
$lang['import_smile_pack'] = 'Importa pacchetto smiley';
$lang['export_smile_pack'] = 'Crea pacchetto smiley';
$lang['export_smiles'] = 'Per creare un pacchetto di smiley dagli smiley installati, clicca %squi%s per scaricare il file di estensione .pak degli smiley. Nomina questo file in modo appropriato mantenendo l\'estensione .pak. Crea un file zip che contenga tutti i file immagine degli smiley e questo file .pak di configurazione.';

$lang['smiley_add_success'] = 'Gli smiley sono stati aggiunti con successo.';
$lang['smiley_edit_success'] = 'Gli smiley sono stati aggiornati con successo.';
$lang['smiley_import_success'] = 'Il pacchetto di smiley è stato importato con successo!';
$lang['smiley_del_success'] = 'Gli smiley sono stati rimossi con successo.';
$lang['Click_return_smileadmin'] = 'Clicca %squi%s per tornare a Amministrazione smiley';

$lang['Confirm_delete_smiley'] = 'Sei sicuro di voler cancellare questo Smiley?';

//
// User Management
//
$lang['User_admin'] = 'Amministrazione utenti';
$lang['User_admin_explain'] = 'Qui puoi cambiare le informazioni degli utenti e alcune opzioni specifiche. Per modificare il permessi degli utenti, utilizza il modulo di amministrazione dei permessi per utenti e gruppi.';

$lang['Look_up_user'] = 'Cerca utente';

$lang['Admin_user_fail'] = 'Non è stato possibile aggiornare il profilo utente.';
$lang['Admin_user_updated'] = 'Il profilo utente è stato aggiornato con successo.';
$lang['Click_return_useradmin'] = 'Clicca %squi%s per tornare a Amministrazione utenti';

$lang['User_delete'] = 'Cancella questo utente';
$lang['User_delete_explain'] = 'Clicca qui per cancellare questo utente. Questa operazione non può essere annullata.';
$lang['User_deleted'] = 'L\'utente è stato cancellato con successo.';

$lang['User_status'] = 'L\'utente è attivo';
$lang['User_allowpm'] = 'Può inviare messaggi privati';
$lang['User_allowavatar'] = 'Può mostrare gli avatar';

$lang['Admin_avatar_explain'] = 'Qui puoi vedere e cancellare l\'avatar attuale dell\'utente.';

$lang['User_special'] = 'Campi speciali solo per l\'amministratore';
$lang['User_special_explain'] = 'Questi campi non possono essere modificati dagli utenti. Qui puoi impostare il loro stato e altre opzioni che non vengono date agli utenti.';


//
// Group Management
//
$lang['Group_administration'] = 'Amministrazione gruppi';
$lang['Group_admin_explain'] = 'Da questo pannello puoi amministrare tutti i gruppi utenti. Puoi cancellare, creare e modificare i gruppi esistenti. Puoi scegliere i moderatori, modificare lo stato del gruppo (aperto/chiuso) e impostare il nome del gruppo e la descrizione.';
$lang['Error_updating_groups'] = 'C\'è stato un errore durante l\'aggiornamento dei gruppi';
$lang['Updated_group'] = 'Il gruppo è stato aggiornato con successo';
$lang['Added_new_group'] = 'Il nuovo gruppo è stato creato con successo';
$lang['Deleted_group'] = 'Il gruppo è stato cancellato con successo';
$lang['New_group'] = 'Crea nuovo gruppo';
$lang['Edit_group'] = 'Modifica gruppo';
$lang['group_name'] = 'Nome gruppo';
$lang['group_description'] = 'Descrizione gruppo';
$lang['group_moderator'] = 'Moderatore gruppo';
$lang['group_status'] = 'Stato gruppo';
$lang['group_open'] = 'Gruppo aperto';
$lang['group_closed'] = 'Gruppo chiuso';
$lang['group_hidden'] = 'Gruppo nascosto';
$lang['group_delete'] = 'Cancella gruppo';
$lang['group_delete_check'] = 'Cancella questo gruppo';
$lang['submit_group_changes'] = 'Invia modifiche';
$lang['reset_group_changes'] = 'Annulla modifiche';
$lang['No_group_name'] = 'Devi specificare un nome per questo gruppo';
$lang['No_group_moderator'] = 'Devi specificare un moderatore per questo gruppo';
$lang['No_group_mode'] = 'Devi specificare uno stato per questo gruppo, aperto o chiuso';
$lang['No_group_action'] = 'Nessuna azione specificata';
$lang['delete_group_moderator'] = 'Vuoi cancellare il vecchio moderatore del gruppo?';
$lang['delete_moderator_explain'] = 'Se cambi il moderatore del gruppo, seleziona questo box per rimuovere il vecchio moderatore. In caso contrario, non selezionarlo e l\'utente diverrà un normale membro del gruppo.';
$lang['Click_return_groupsadmin'] = 'Clicca %squi%s per tornare a Amministrazione gruppi';
$lang['Select_group'] = 'Seleziona un gruppo';
$lang['Look_up_group'] = 'Controlla gruppo';


//
// Prune Administration
//
$lang['Forum_Prune'] = 'Eliminazione forum';
$lang['Forum_Prune_explain'] = 'Questo cancellerà tutti gli argomenti a cui non è stata inviata una risposta nel numero di giorni che hai selezionato. Se non inserisci un numero TUTTI gli argomenti saranno cancellati. Non verranno cancellati gli argomenti con sondaggi ancora attivi e neppure gli annunci. Devi cancellare questi argomenti manualmente.';
$lang['Do_Prune'] = 'Elimina';
$lang['All_Forums'] = 'Tutti i forum';
$lang['Prune_topics_not_posted'] = 'Elimina gli argomenti senza risposte da giorni';
$lang['Topics_pruned'] = 'Argomenti eliminati';
$lang['Posts_pruned'] = 'Messaggi eliminati';
$lang['Prune_success'] = 'L\'eliminazione dei forum è avvenuta con successo';


//
// Word censor
//
$lang['Words_title'] = 'Censura parole';
$lang['Words_explain'] = 'Da questo pannello puoi aggiungere, modificare e rimuovere parole che saranno censurate automaticamente da tutto il forum. Inoltre non sarà possibile registrarsi con gli username che contengono queste parole. Le abbreviazioni (*) sono accettate nel campo parola , eg. *tra* comprenderà attraverso, tra* comprenderà trave, *tra comprenderà finestra.';
$lang['Word'] = 'Parola';
$lang['Edit_word_censor'] = 'Modifica lista';
$lang['Replacement'] = 'Sostituto';
$lang['Add_new_word'] = 'Aggiungi una nuova parola';
$lang['Update_word'] = 'Aggiorna lista';

$lang['Must_enter_word'] = 'Devi inserire una parola e il suo sostituto';
$lang['No_word_selected'] = 'Nessuna parola selezionata per la modifica';

$lang['Word_updated'] = 'La parola selezionata è stat aggiornata con successo';
$lang['Word_added'] = 'La parola è stata aggiunta con successo';
$lang['Word_removed'] = 'La parola selezionata è stata rimossa con successo';

$lang['Click_return_wordadmin'] = 'Clicca %squi%s per tornare a Censure parole';

$lang['Confirm_delete_word'] = 'Sei sicuro di voler cancellare questa censura?';


//
// Mass Email
//
$lang['Mass_email_explain'] = 'Qui puoi inviare un messaggio a tutti i tuoi utenti o agli utenti di un gruppo specifico. Per fare questo, verrà inviato un messaggio all\'indirizzo e-mail dell\'amministratore che hai fornito, ed una BCC (Blind Carbon Copy) verrà inviata ai destinatari. Se stai inviando una mail ad un grosso gruppo di utenti per favore si paziente dopo aver inviato e non interrompere il caricamento della pagina. Un tempo lungo è normale per una mass-email. quando il processo sarà finito, sarai avvisato.';
$lang['Compose'] = 'Componi'; 

$lang['Recipients'] = 'Destinatari'; 
$lang['All_users'] = 'Tutti gli Utenti';

$lang['Email_successfull'] = 'Il tuo messaggio è stato inviato';
$lang['Click_return_massemail'] = 'Clicca %squi%s per tornare a e-mail generali';


//
// Ranks admin
//
$lang['Ranks_title'] = 'Amministrazione livelli';
$lang['Ranks_explain'] = 'Con questo modulo puoi aggiungere, cancellare, modificare e guardare il livello degli utenti. Puoi anche creare dei livelli personali che possono essere applicati ad un utente attraverso la Gestione Utenti';

$lang['Add_new_rank'] = 'Aggiungi un nuovo livello';

$lang['Rank_title'] = 'Titolo livello';
$lang['Rank_special'] = 'Imposta un livello speciale';
$lang['Rank_minimum'] = 'Messaggi minimi';
$lang['Rank_maximum'] = 'Messaggi massimi';
$lang['Rank_image'] = 'Immagine livello (relativo al percorso del forum)';
$lang['Rank_image_explain'] = 'Utilizza questo per definire una piccola immagine associata con il livello';

$lang['Must_select_rank'] = 'Devi selezionare un livello';
$lang['No_assigned_rank'] = 'Nessun livello speciale assegnato';

$lang['Rank_updated'] = 'Il livello è stato aggiornato con successo';
$lang['Rank_added'] = 'Il livello è stato aggiunto con successo';
$lang['Rank_removed'] = 'Il livello è stato cancellato con successo';
$lang['No_update_ranks'] = 'Il livello è stato cancellato con successo, in ogni caso gli utenti che utilizzavano questo livello non sono stati aggiornati. Dovrai impostare manualmente il nuovo livello a questi utenti';

$lang['Click_return_rankadmin'] = 'Clicca %squi%s per tornare a Amministrazione livelli';

$lang['Confirm_delete_rank'] = 'Sei sicuro di voler cancellare questo rank?';

//
// Disallow Username Admin
//
$lang['Disallow_control'] = 'Controllo disabilitazione utenti';
$lang['Disallow_explain'] = 'Qui puoi controllare gli username che non si possono utilizzare. Gli username disabilitati possono contenere una abbreviazione (*). Per favore nota che non puoi specificare nessuno username che è già stato registrato, devi prima cancellare il nome e poi disabilitarlo';

$lang['Delete_disallow'] = 'Cancella';
$lang['Delete_disallow_title'] = 'Rimuovi uno username disabilitato';
$lang['Delete_disallow_explain'] = 'Puoi rimuovere uno username disabilitato selezionando lo username da questa lista e cliccando su invia';

$lang['Add_disallow'] = 'Aggiungi';
$lang['Add_disallow_title'] = 'Aggiungi uno username disabilitato';
$lang['Add_disallow_explain'] = 'Puoi disabilitare uno username utilizzando l\'abbreviazione * per comprendere ogni carattere';

$lang['No_disallowed'] = 'Nessuno username disabilitato';

$lang['Disallowed_deleted'] = 'Lo username disabilitato è stato rimosso con successo';
$lang['Disallow_successful'] = 'Lo username disabilitato è stato aggiunto con successo';
$lang['Disallowed_already'] = 'Il nome che hai inserito non può essere disabilitato. Esiste già nella lista delle parole censurate o esiste uno username con questo nome';

$lang['Click_return_disallowadmin'] = 'Clicca %squi%s per tornare a Disabilitazione utenti';


//
// Styles Admin
//
$lang['Styles_admin'] = 'Amministrazione stili';
$lang['Styles_explain'] = 'Utilizzando queste opzioni puoi aggiungere, rimuovere e gestire gli stili (modelli e temi) del tuo forum';
$lang['Styles_addnew_explain'] = 'La lista seguente contiene tutti i temi che sono disponibili per i modelli che hai al momento. I temi nella lista non sono ancora stati caricati nel database del forum. Per installarli semplicemente clicca sul link installa di fianco ad ogni stile.';

$lang['Select_template'] = 'Seleziona un modello';

$lang['Style'] = 'Stile';
$lang['Template'] = 'Modello';
$lang['Install'] = 'Installa';
$lang['Download'] = 'Scarica';

$lang['Edit_theme'] = 'Modifica tema';
$lang['Edit_theme_explain'] = 'Nel modulo qui sotto puoi modificare le impostazioni per il tema selezionato';

$lang['Create_theme'] = 'Crea tema';
$lang['Create_theme_explain'] = 'Utilizza il modulo qui sotto per creare un nuovo tema per il modello selezionato. Quando inserisci i colori (devi usare la notazione esadecimale) non  devi includere all\'inizio #, es. CCCCCC è valido, #CCCCCC non valido.';

$lang['Export_themes'] = 'Esporta temi';
$lang['Export_explain'] = 'In questo pannello puoi esportare i dati del tema per il modello selezionato. Seleziona un modello dalla lista qui sotto e lo script creerà il file di configurazione del tema e tenterà di salvarlo nella directory dei modelli (template). Se non può salvare il file, il programma ti darà la possibilità di scaricarlo. Per permettere allo script di salvare il file devi dare il permesso di scrittura alla directory dei modelli sul server. Per ulteriori informazioni consulta la guida utenti di phpbb.';

$lang['Theme_installed'] = 'Il tema selezionato è stato installato con successo';
$lang['Style_removed'] = 'Lo stile selezionato è stato rimosso dal database. Per rimuovere completamente questo stile dal tuo sistema, devi cancellare lo stile dalla cartella dei modelli (template).';
$lang['Theme_info_saved'] = 'Le informazioni dl tema per il modello selezionato sono state salvate. Adesso devi reimpostare i permessi del file theme_info.cfg (e se possibile anche nella directory dei modelli) su sola lettura';
$lang['Theme_updated'] = 'Il tema selezionato è stato aggiornato. Adesso devi esportare le impostazioni del nuovo tema';
$lang['Theme_created'] = 'Tema creato. Adesso devi esportare il tema nel file di configurazione del tema per utilizzarlo da un\'altra parte';

$lang['Confirm_delete_style'] = 'Sei sicuro di voler cancellare questo stile?';

$lang['Download_theme_cfg'] = 'Il processo di esportazione non riesce a scrivere il file di configurazione del tema. Clicca il bottone qui sotto per scaricare questo file con il tuo browser. Dopo averlo scaricato puoi trasferirlo nella cartella che contiene i file dei modelli. Dopo puoi compattare i file per distribuirli o per riutilizzarli';
$lang['No_themes'] = 'Il modello che hai selezionato non ha temi allegati. Per creare un nuovo tema clicca sul link Crea tema nel pannello di sinistra';
$lang['No_template_dir'] = 'Non è possibile aprire la directory dei modelli. Potrebbe essere non leggibile dal server o potrebbe non esistere';
$lang['Cannot_remove_style'] = 'You cannot remove the style selected since it is currently the forum default. Please change the default style and try again.';
$lang['Style_exists'] = 'Non puoi rimuovere lo stile selezionato perchè è quello di default nel forum. Cambia lo stile di default e poi riprova';

$lang['Click_return_styleadmin'] = 'Clicca %squi%s per tornare a Amministrazione stili';

$lang['Theme_settings'] = 'Impostazioni del tema';
$lang['Theme_element'] = 'Elemento del tema';
$lang['Simple_name'] = 'Nome semplice';
$lang['Value'] = 'Valore';
$lang['Save_Settings'] = 'Salva impostazioni';

$lang['Stylesheet'] = 'Foglio di stile CSS';
$lang['Stylesheet_explain'] = 'Nome del file CSS da utilizzare per questo tema.';
$lang['Background_image'] = 'Immagine di sfondo';
$lang['Background_color'] = 'Colore di sfondo';
$lang['Theme_name'] = 'Nome tema';
$lang['Link_color'] = 'Colore link';
$lang['Text_color'] = 'Colore testo';
$lang['VLink_color'] = 'Colore link visitato';
$lang['ALink_color'] = 'Colore link attivo';
$lang['HLink_color'] = 'Colore link hover';
$lang['Tr_color1'] = 'Tabella colonna colore 1';
$lang['Tr_color2'] = 'Tabella colonna colore 2';
$lang['Tr_color3'] = 'Tabella colonna colore 3';
$lang['Tr_class1'] = 'Tabella colonna classe 1';
$lang['Tr_class2'] = 'Tabella colonna classe 2';
$lang['Tr_class3'] = 'Tabella colonna classe 3';
$lang['Th_color1'] = 'Tabella intestazione colore 1';
$lang['Th_color2'] = 'Tabella intestazione colore 2';
$lang['Th_color3'] = 'Tabella intestazione colore 3';
$lang['Th_class1'] = 'Tabella intestazione colore 1';
$lang['Th_class2'] = 'Tabella intestazione colore 2';
$lang['Th_class3'] = 'Tabella intestazione colore 3';
$lang['Td_color1'] = 'Tabella cella colore 1';
$lang['Td_color2'] = 'Tabella cella colore 2';
$lang['Td_color3'] = 'Tabella cella colore 3';
$lang['Td_class1'] = 'Tabella cella colore 1';
$lang['Td_class2'] = 'Tabella cella colore 2';
$lang['Td_class3'] = 'Tabella cella colore 3';
$lang['fontface1'] = 'Nome carattere 1';
$lang['fontface2'] = 'Nome carattere 2';
$lang['fontface3'] = 'Nome carattere 3';
$lang['fontsize1'] = 'Dimensione carattere 1';
$lang['fontsize2'] = 'Dimensione carattere 2';
$lang['fontsize3'] = 'Dimensione carattere 3';
$lang['fontcolor1'] = 'Colore carattere 1';
$lang['fontcolor2'] = 'Colore carattere 2';
$lang['fontcolor3'] = 'Colore carattere 3';
$lang['span_class1'] = 'Classe span 1';
$lang['span_class2'] = 'Classe span 2';
$lang['span_class3'] = 'Classe span 3';
$lang['img_poll_size'] = 'Dimensione immagine votazione [px]';
$lang['img_pm_size'] = 'Dimensione stato messaggi privati [px]';


//
// Install Process
//
$lang['Welcome_install'] = 'Benvenuto nell\'installazione di phpbb 2';
$lang['Initial_config'] = 'Configurazione base';
$lang['DB_config'] = 'Configurazione database';
$lang['Admin_config'] = 'Configurazione amministrazione';
$lang['continue_upgrade'] = 'Dopo aver scaricato il file di configurazione sul tuo computer puoi cliccare sul bottone \"Continua l\'Aggiornamento\" qui sotto per avanzare con il processo di aggiornamento. Caricare il file di configurazione aspetta la fine del processo di aggiornamento.';
$lang['upgrade_submit'] = 'Continua aggiornamento';

$lang['Installer_Error'] = 'Si è verificato un errore durante l\'installazione';
$lang['Previous_Install'] = 'È stata rilevata una precedente installazione';
$lang['Install_db_error'] = 'Si è verificato un errore durante l\'aggiornamento del database';

$lang['Re_install'] = 'La tua installazione precedente è ancora attiva. <br /><br />Se vuoi installare di nuovo phpbb 2 clicca il bottone di conferma qui sotto. Sappi che questa operazione distruggerà tutti i dati esistenti, non verrà fatto alcun backup! Username e password dell\'amministratore che hai usato per entrare nel forum verranno ricreate dopo la nuova installazione, nessun altra impostazione verrà mantenuta. <br /><br />Pensaci bene prima di CONFERMARE!';

$lang['Inst_Step_0'] = 'Grazie per aver scelto phpbb 2. Per completare correttamente l\'installazione devi riempire tutti i campi sottostanti. Prima di procedere assicurarti di avere un database con tutti i dati d\'accesso. Se stai installando il forum su un database che utilizza ODBC, es. MS Access devi prima creargli un DSN prima di procedere all\'installazione.';

$lang['Start_Install'] = 'Inizia installazione';
$lang['Finish_Install'] = 'Termina installazione';

$lang['Default_lang'] = 'Lingua di default del forum';
$lang['DB_Host'] = 'Database Server Hostname / DSN';
$lang['DB_Name'] = 'Nome database';
$lang['DB_Username'] = 'Username database';
$lang['DB_Password'] = 'Password database';
$lang['Database'] = 'Il Tuo database';
$lang['Install_lang'] = 'Scegli una Lingua per l\'installazione';
$lang['dbms'] = 'Tipo di database';
$lang['Table_Prefix'] = 'Prefisso tabelle database';
$lang['Admin_Username'] = 'Username Amministratore';
$lang['Admin_Password'] = 'Password Amministratore';
$lang['Admin_Password_confirm'] = 'Password Amministratore [ Conferma ]';

$lang['Inst_Step_2'] = 'Il tuo username come Amministratore è stato creato. A questo punto la tua installazione di base è terminata. Adesso ti verrà mostrato una schermata dove potrai amministrare il tuo Nuovo phpbb. Per favore verifica i dettagli della Configurazione generale e cambiali in base alle tue esigenze. Grazie per aver scelto phpbb 2.';

$lang['Unwriteable_config'] = 'Non posso scrivere il file config.php automaticamente. Scaricare una copia del tuo file di configurazione cliccando sul bottone qui sotto. Devi caricare questo file nella stessa directory di phpbb 2. Successivamente potrai logarti con nome e password di amministrazione che hai fornito nel modulo precedente e andare nel pannello di controllo (un link apparirà in fondo ad ogni pagina dopo che sei entrato) per verificare le impostazioni generali di configurazione. Grazie per aver scelto phpbb 2.';
$lang['Download_config'] = 'Scarica il file di Configurazione';

$lang['ftp_choose'] = 'Scegli Metodo Scaricamento';
$lang['ftp_option'] = '<br />Poichè le estensioni FTP non sono disponibili in questa versione di PHP usa l\'opzione di caricare automaticamente via ftp il file di configurazione.';
$lang['ftp_instructs'] = 'Hai scelto di caricare automaticamente via ftp il file sull\'account che contiene phpBB 2. Inserisci le informazioni per facilitare il processo. Il percorso FTP deve essere il percorso esatto dell\'installazione di phpbb2 come se stessi caricando via ftp con un normale programma client.';
$lang['ftp_info'] = 'Inserisci le tue informazioni FTP';
$lang['Attempt_ftp'] = 'Tentativo di caricare via FTP il file di configurazione';
$lang['Send_file'] = 'Inviatemi il file e lo caricherò via FTP manualmente';
$lang['ftp_path'] = 'Percorso FTP per phpbb 2';
$lang['ftp_username'] = 'Il tuo username FTP';
$lang['ftp_password'] = 'La tua password FTP';
$lang['Transfer_config'] = 'Inizio trasferimento';
$lang['NoFTP_config'] = 'Il tentativo di trasferire il file via FTP è fallito. Scarica il file config e trasferiscilo sul server manualmente.';

$lang['Install'] = 'Installa';
$lang['Upgrade'] = 'Aggiorna';


$lang['Install_Method'] = 'Scegli un metodo di installazione';

$lang['Install_No_Ext'] = 'La configurazione php del tuo server non supporta il tipo di database che hai scelto';

$lang['Install_No_PCRE'] = 'phpbb2 Richiede il Perl-Compatible Regular Expressions Module. La tua configurazione PHP non lo supporta!';

//
// Version Check
//
$lang['Version_up_to_date'] = 'Nessun aggiornamento disponibile per la versione in uso di phpbb.';
$lang['Version_not_up_to_date'] = 'La versione in uso <il b>non </ b>è recente. Per gli aggiornamenti disponibili visita <a href="http://www.phpbb.com/downloads.php" target="_new">http://www.phpbb.com/downloads.php</a> per vedere l\'ultima versione.';
$lang['Latest_version_info'] = 'L\'ultima versione disponibile è <b>phpbb %s</b>.';
$lang['Current_version_info'] = 'La tua Versione è <b>phpbb %s</b>.';
$lang['Connect_socket_error'] = 'Impossibile il collegamento a phpbb. Rapporto errore:<br />%s';
$lang['Socket_functions_disabled'] = 'Impossibile utilizzare la funzione socket.';
$lang['Mailing_list_subscribe_reminder'] = 'Per le ultime informazioni su aggiornamenti a phpbb <a href="http://www.phpbb.com/support/" target="_new">iscriviti alla mailing list</a>.';
$lang['Version_information'] = 'Informazione versione';

//
// Login attempts configuration
//
$lang['Max_login_attempts'] = 'Tentativi di login permessi';
$lang['Max_login_attempts_explain'] = 'Il numero di tentativi di login consentiti.';
$lang['Login_reset_time'] = 'Tempo di blocco login';
$lang['Login_reset_time_explain'] = 'Determina il tempo in minuti che l\'utente deve aspettare prima di fare di nuovo il login dopo avere ecceduto il numero di tentativi di login consentiti.';

//
// That's all Folks!
// -------------------------------------------------

?>