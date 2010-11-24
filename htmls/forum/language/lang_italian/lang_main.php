<?php
/***************************************************************************
 *                            lang_main.php [Italian]
 *                              -------------------
 *     begin                : Sat Dec 16 2000
 *     copyright            : (C) 2001 The phpBB Group
 *     email                : support@phpbb.com
 *
 *     $Id: lang_main.php,v 1.85.2.18 2005/10/05 19:00:45 grahamje Exp $
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

//
// CONTRIBUTORS:
//	 Add your details here if wanted, e.g. Name, username, email address, website
// 2002-08-27  Philip M. White        - fixed many grammar problems
//

//
// The format of this file is ---> $lang['message'] = 'text';
//
// You should also try to set a locale and a character encoding (plus direction). The encoding and direction
// will be sent to the template. The locale may or may not work, it's dependent on OS support and the syntax
// varies ... give it your best guess!
//

$lang['ENCODING'] = 'iso-8859-1';
$lang['DIRECTION'] = 'ltr';
$lang['LEFT'] = 'sinistra';
$lang['RIGHT'] = 'destra';
$lang['DATE_FORMAT'] =  'd/m/y H:i'; // This should be changed to the default date format for your language, php date() format

// This is optional, if you would like a _SHORT_ message output
// along with our copyright message indicating you are the translator
// please add it here.
// $lang['TRANSLATION'] = '';
$lang['TRANSLATION_INFO'] = '<a href="http://www.phpbb.it" class="copyright" target="_blank">phpbb.it</a>';
$lang['TRANSLATION'] = '<a href="http://www.phpbb.it" class="copyright" target="_blank">phpbb.it</a>';
//
// Common, these terms are used
// extensively on several pages
//
$lang['Forum'] = 'Forum';
$lang['Category'] = 'Categoria';
$lang['Topic'] = 'Argomento';
$lang['Topics'] = 'Argomenti';
$lang['Replies'] = 'Risposte';
$lang['Views'] = 'Consultazioni';
$lang['Post'] = 'Messaggio';
$lang['Posts'] = 'Messaggi';
$lang['Posted'] = 'Inviato';
$lang['Username'] = 'Username';
$lang['Password'] = 'Password';
$lang['Email'] = 'Email';
$lang['Poster'] = 'Scritto da';
$lang['Author'] = 'Autore';
$lang['Time'] = 'Data';
$lang['Hours'] = 'Ore';
$lang['Message'] = 'Messaggio';

$lang['1_Day'] = '1 Giorno';
$lang['7_Days'] = '7 Giorni';
$lang['2_Weeks'] = '2 Settimane';
$lang['1_Month'] = '1 Mese';
$lang['3_Months'] = '3 Mesi';
$lang['6_Months'] = '6 Mesi';
$lang['1_Year'] = '1 Anno';

$lang['Go'] = 'Vai';
$lang['Jump_to'] = 'Vai a';
$lang['Submit'] = 'Invia';
$lang['Reset'] = 'Azzera';
$lang['Cancel'] = 'Cancella';
$lang['Preview'] = 'Anteprima';
$lang['Confirm'] = 'Conferma';
$lang['Spellcheck'] = 'Controllo ortografico';
$lang['Yes'] = 'Si';
$lang['No'] = 'No';
$lang['Enabled'] = 'Abilitato';
$lang['Disabled'] = 'Disabilitato';
$lang['Error'] = 'Errore';

$lang['Next'] = 'Successivo';
$lang['Previous'] = 'Precedente';
$lang['Goto_page'] = 'Vai a';
$lang['Joined'] = 'Registrato';
$lang['IP_Address'] = 'Indirizzo IP';

$lang['Select_forum'] = 'Seleziona forum';
$lang['View_latest_post'] = 'Leggi gli ultimi messaggi';
$lang['View_newest_post'] = 'Leggi i nuovi messaggi';
$lang['Page_of'] = 'Pagina <b>%d</b> di <b>%d</b>'; // Replaces with: Page 1 of 2 for example

$lang['ICQ'] = 'ICQ';
$lang['AIM'] = 'AIM';
$lang['MSNM'] = 'MSN';
$lang['YIM'] = 'Yahoo';

$lang['Forum_Index'] = 'Indice del forum';  // eg. sitename Forum Index, %s can be removed if you prefer

$lang['Post_new_topic'] = 'Nuovo argomento';
$lang['Reply_to_topic'] = 'Rispondi';
$lang['Reply_with_quote'] = 'Rispondi citando';

$lang['Click_return_topic'] = 'Clicca %squi%s per tornare all\'argomento'; // %s's here are for uris, do not remove!
$lang['Click_return_login'] = 'Clicca %squi%s per riprovare il login';
$lang['Click_return_forum'] = 'Clicca %squi%s per tornare al forum';
$lang['Click_view_message'] = 'Clicca %squi%s per vedere il tuo messaggio';
$lang['Click_return_modcp'] = 'Clicca %squi%s per tornare per tornare al Pannello di Controllo Moderatore';
$lang['Click_return_group'] = 'Clicca %squi%s per tornare a info gruppo';

$lang['Admin_panel'] = 'Amministrazione';

$lang['Board_disable'] = 'Spiacenti ma il forum al momento non è disponibile, prova più tardi.';


//
// Global Header strings
//
$lang['Registered_users'] = 'Utenti registrati:';
$lang['Browsing_forum'] = 'Utenti presenti in questo forum:';
$lang['Online_users_zero_total'] = 'In totale ci sono <b>0</b> utenti in linea :: ';
$lang['Online_users_total'] = 'In totale ci sono <b>%d</b> utenti in linea :: ';
$lang['Online_user_total'] = 'In totale c\'è <b>%d</b> utente in linea :: ';
$lang['Reg_users_zero_total'] = '0 Registrati, ';
$lang['Reg_users_total'] = '%d Registrati, ';
$lang['Reg_user_total'] = '%d Registrato, ';
$lang['Hidden_users_zero_total'] = '0 Nascosti e ';
$lang['Hidden_user_total'] = '%d Nascosto e ';
$lang['Hidden_users_total'] = '%d Nascosti e ';
$lang['Guest_users_zero_total'] = '0 Ospiti';
$lang['Guest_users_total'] = '%d Ospiti';
$lang['Guest_user_total'] = '%d Ospite';
$lang['Record_online_users'] = 'Record utenti in linea <b>%s</b> in data %s'; // first %s = number of users, second %s is the date.

$lang['Admin_online_color'] = '%sAmministratore%s';
$lang['Mod_online_color'] = '%sModeratore%s';

$lang['You_last_visit'] = 'Ultimo accesso %s'; // %s replaced by date/time
$lang['Current_time'] = 'La data di oggi è %s'; // %s replaced by time

//$lang['Search_new'] = 'Leggi messaggi non letti';
$lang['Search_new'] = 'Leggi i messaggi dall\'ultima visita';
$lang['Search_your_posts'] = 'Leggi tutti i tuoi messaggi';
$lang['Search_unanswered'] = 'Leggi messaggi senza risposta';

$lang['Register'] = 'Registrati';
$lang['Profile'] = 'Profilo';
$lang['Edit_profile'] = 'Modifica il tuo profilo';
$lang['Search'] = 'Cerca';
$lang['Memberlist'] = 'Lista utenti';
$lang['FAQ'] = 'FAQ';
$lang['BBCode_guide'] = 'Guida BBCode';
$lang['Usergroups'] = 'Gruppi';
$lang['Last_Post'] = 'Ultimo messaggio';
$lang['Moderator'] = 'Moderatore';
$lang['Moderators'] = 'Moderatori';


//
// Stats block text
//
$lang['Posted_articles_zero_total'] = 'Non ci sono messaggi nel forum'; // Number of posts
$lang['Posted_articles_total'] = 'Ci sono <b>%d</b> messaggi nel forum'; // Number of posts
$lang['Posted_article_total'] = 'C\'è <b>%d</b> messaggio nel forum'; // Number of posts
$lang['Registered_users_zero_total'] = 'Abbiamo <b>0</b> utenti registrati'; // # registered users
$lang['Registered_users_total'] = 'Abbiamo <b>%d</b> utenti registrati'; // # registered users
$lang['Registered_user_total'] = 'Abbiamo <b>%d</b> utente registrato'; // # registered users
$lang['Newest_user'] = 'Ultimo utente iscritto <b>%s%s%s</b>'; // a href, username, /a 

$lang['No_new_posts_last_visit'] = 'Dal tuo ultimo accesso non sono presenti nuovi messaggi';
$lang['No_new_posts'] = 'Non ci sono nuovi messaggi';
$lang['New_posts'] = 'Nuovi messaggi';
$lang['New_post'] = 'Nuovo messaggio';
$lang['No_new_posts_hot'] = 'Non ci sono nuovi messaggi [ Popolari ]';
$lang['New_posts_hot'] = 'Nuovi messaggi [ Popolari ]';
$lang['No_new_posts_locked'] = 'Non ci sono nuovi messaggi [ Chiusi ]';
$lang['New_posts_locked'] = 'Nuovi messaggi [ Chiusi ]';
$lang['Forum_is_locked'] = 'Il forum è chiuso';


//
// Login
//
$lang['Enter_password'] = 'Inserisci username e password per entrare.';
//$lang['Login'] = 'Log in';
//$lang['Logout'] = 'Log out';
$lang['Login'] = 'Entra';
$lang['Logout'] = 'Esci';

$lang['Forgotten_password'] = 'Ho dimenticato la password';

$lang['Log_me_in'] = 'Connessione automatica ad ogni visita';

$lang['Error_login'] = 'I dati inseriti non sono corretti.';


//
// Index page
//
$lang['Index'] = 'Index';
$lang['No_Posts'] = 'Nessun messaggio';
$lang['No_forums'] = 'Questo forum è vuoto';

$lang['Private_Message'] = 'Messaggio privato';
$lang['Private_Messages'] = 'Messaggi privati';
$lang['Who_is_Online'] = 'Chi c\'è in linea';

$lang['Mark_all_forums'] = 'Segna forum come già letti';
$lang['Forums_marked_read'] = 'Tutti i forum sono stati segnati come già letti';


//
// Viewforum
//
$lang['View_forum'] = 'Guarda forum';

$lang['Forum_not_exist'] = 'Il forum selezionato non esiste.';
$lang['Reached_on_error'] = 'Sei arrivato in questa pagina per errore.';

$lang['Display_topics'] = 'Mostra prima gli argomenti di';
$lang['All_Topics'] = 'Tutti gli argomenti';

$lang['Topic_Announcement'] = '<b>Annuncio:</b>';
$lang['Topic_Sticky'] = '<b>Importante:</b>';
$lang['Topic_Moved'] = '<b>Spostato:</b>';
$lang['Topic_Poll'] = '<b>[ Sondaggio ]</b>';

$lang['Mark_all_topics'] = 'Segna argomenti come già letti';
$lang['Topics_marked_read'] = 'Gli argomenti di questo forum sono stati segnati come già letti';

$lang['Rules_post_can'] = '<b>Puoi</b> inserire nuovi argomenti';
$lang['Rules_post_cannot'] = '<b>Non puoi</b> inserire nuovi argomenti';
$lang['Rules_reply_can'] = '<b>Puoi</b> rispondere a tutti gli argomenti';
$lang['Rules_reply_cannot'] = '<b>Non puoi</b> rispondere a nessun argomento';
$lang['Rules_edit_can'] = '<b>Puoi</b> modificare i tuoi messaggi';
$lang['Rules_edit_cannot'] = '<b>Non puoi</b> modificare i tuoi messaggi';
$lang['Rules_delete_can'] = '<b>Puoi</b> cancellare i tuoi messaggi';
$lang['Rules_delete_cannot'] = '<b>Non puoi</b> cancellare i tuoi messaggi';
$lang['Rules_vote_can'] = '<b>Puoi</b> votare nei sondaggi';
$lang['Rules_vote_cannot'] = '<b>Non puoi</b> votare nei sondaggi';
$lang['Rules_moderate'] = '<b>Puoi</b> %sModerare questo forum%s'; // %s replaced by a href links, do not remove!  

$lang['No_topics_post_one'] = 'Non ci sono argomenti in questo forum.<br />Clicca <b>inserisci nuovo argomento</b> per crearne uno.';


//
// Viewtopic
//
$lang['View_topic'] = 'Leggi argomento';

$lang['Guest'] = 'Ospite';
$lang['Post_subject'] = 'Oggetto';
$lang['View_next_topic'] = 'Successivo';
$lang['View_previous_topic'] = 'Precedente';
$lang['Submit_vote'] = 'Invia voto';
$lang['View_results'] = 'Guarda i risultati';

$lang['No_newer_topics'] = 'Non ci sono nuovi argomenti in questo forum';
$lang['No_older_topics'] = 'Non ci sono vecchi argomenti in questo forum';
$lang['Topic_post_not_exist'] = 'L\'argomento o il messaggio che hai richiesto non esiste';
$lang['No_posts_topic'] = 'L\'argomento non contiene messaggi';

$lang['Display_posts'] = 'Mostra prima i messaggi di';
$lang['All_Posts'] = 'Tutti i messaggi';
$lang['Newest_First'] = 'Nuovi';
$lang['Oldest_First'] = 'Vecchi';

$lang['Back_to_top'] = 'Top';

$lang['Read_profile'] = 'Profilo'; 
$lang['Visit_website'] = 'HomePage';
$lang['ICQ_status'] = 'ICQ';
$lang['Edit_delete_post'] = 'Modifica/Cancella messaggio';
$lang['View_IP'] = 'Mostra indirizzo IP';
$lang['Delete_post'] = 'Cancella messaggio';

$lang['wrote'] = 'ha scritto'; // proceeds the username and is followed by the quoted text
$lang['Quote'] = 'Citazione'; // comes before bbcode quote output.
$lang['Code'] = 'Codice'; // comes before bbcode code output.

$lang['Edited_time_total'] = 'L\'ultima modifica di %s il %s, modificato %d volta'; // Last edited by me on 12 Oct 2001; edited 1 time in total
$lang['Edited_times_total'] = 'L\'ultima modifica di %s il %s, modificato %d volte'; // Last edited by me on 12 Oct 2001; edited 2 times in total

$lang['Lock_topic'] = 'Blocca argomento';
$lang['Unlock_topic'] = 'Sblocca argomento';
$lang['Move_topic'] = 'Sposta argomento';
$lang['Delete_topic'] = 'Cancella argomento';
$lang['Split_topic'] = 'Dividi argomento';

$lang['Stop_watching_topic'] = 'Smetti di controllare questo argomento';
$lang['Start_watching_topic'] = 'Controlla questo argomento';
$lang['No_longer_watching'] = 'Non stai più controllando questo argomento';
$lang['You_are_watching'] = 'Adesso stai controllando questo argomento';

$lang['Total_votes'] = 'Voti Totali';

//
// Posting/Replying (Not private messaging!)
//
$lang['Message_body'] = 'Struttura messaggio';
$lang['Topic_review'] = 'Revisione argomento';

$lang['No_post_mode'] = 'Metodo di risposta non specificato'; // If posting.php is called without a mode (newtopic/reply/delete/etc, shouldn't be shown normaly)

$lang['Post_a_new_topic'] = 'Nuovo argomento';
$lang['Post_a_reply'] = 'Rispondi';
$lang['Post_topic_as'] = 'Tipo di argomento';
$lang['Edit_Post'] = 'Modifica messaggio';
$lang['Options'] = 'Opzioni';

$lang['Post_Announcement'] = 'Annuncio';
$lang['Post_Sticky'] = 'Importante';
$lang['Post_Normal'] = 'Normale';

$lang['Confirm_delete'] = 'Sei sicuro di voler cancellare questo messaggio?';
$lang['Confirm_delete_poll'] = 'Sei sicuro di voler cancellare questo sondaggio?';

$lang['Flood_Error'] = 'Non puoi inviare un nuovo messaggio! Attendi qualche istante prima di inserire un nuovo messaggio.';
$lang['Empty_subject'] = 'Devi specificare l\'oggetto quando inserisci un nuovo argomento.';
$lang['Empty_message'] = 'Devi scrivere un messaggio per inserirlo.';
$lang['Forum_locked'] = 'Questo forum è chiuso: Non puoi inserire, rispondere o modificare gli argomenti.';
$lang['Topic_locked'] = 'Quest\'argomento è chiuso: Non puoi inserire, rispondere o modificare i messaggi.';
$lang['No_post_id'] = 'Non è stato specificato nessun messaggio';
$lang['No_topic_id'] = 'Non è stato specificato nessun argomento a cui rispondere';
$lang['No_valid_mode'] = 'Puoi solo inviare, rispondere, modificare o citare messaggi. Torna indietro e prova di nuovo.';
$lang['No_such_post'] = 'Questo messaggio non esiste. Torna indietro e prova di nuovo.';
$lang['Edit_own_posts'] = 'Puoi modificare solo i tuoi messaggi.';
$lang['Delete_own_posts'] = 'Puoi cancellare solo i tuoi messaggi.';
$lang['Cannot_delete_replied'] = 'Non puoi cancellare i messaggi che hanno una risposta.';
$lang['Cannot_delete_poll'] = 'Non puoi cancellare un sondaggio attivo.';
$lang['Empty_poll_title'] = 'Devi inserire un titolo per il tuo sondaggio.';
$lang['To_few_poll_options'] = 'Devi inserire almeno due opzioni per il sondaggio.';
$lang['To_many_poll_options'] = 'Ci sono troppe opzioni per il sondaggio.';
$lang['Post_has_no_poll'] = 'Questo messaggio non ha sondaggi.';
$lang['Already_voted'] = 'Hai già votato questo sondaggio.';
$lang['No_vote_option'] = 'Devi specificare un\'opzione quando voti.';

$lang['Add_poll'] = 'Aggiungi Sondaggio';
$lang['Add_poll_explain'] = 'Se non vuoi aggiungere un sondaggio al tuo argomento lascia vuoti i campi.';
$lang['Poll_question'] = 'Domanda del sondaggio';
$lang['Poll_option'] = 'Opzione del sondaggio';
$lang['Add_option'] = 'Aggiungi opzione';
$lang['Update'] = 'Aggiorna';
$lang['Delete'] = 'Cancella';
$lang['Poll_for'] = 'Attiva il sondaggio per';
$lang['Days'] = 'Giorni'; // This is used for the Run poll for ... Days + in admin_forums for pruning
$lang['Poll_for_explain'] = '[ Scrivi 0 o lascia vuoto per un sondaggio senza fine ]';
$lang['Delete_poll'] = 'Cancella sondaggio';

$lang['Disable_HTML_post'] = 'Disabilita HTML nel messaggio';
$lang['Disable_BBCode_post'] = 'Disabilita BBCode nel messaggio';
$lang['Disable_Smilies_post'] = 'Disabilita Smilies nel messaggio';

$lang['HTML_is_ON'] = 'HTML <u>ATTIVO</u>';
$lang['HTML_is_OFF'] = 'HTML <u>DISATTIVATO</u>';
$lang['BBCode_is_ON'] = '%sBBCode%s <u>ATTIVO</u>'; // %s are replaced with URI pointing to FAQ
$lang['BBCode_is_OFF'] = '%sBBCode%s <u>DISATTIVATO</u>';
$lang['Smilies_are_ON'] = 'Smilies <u>ATTIVI</u>';
$lang['Smilies_are_OFF'] = 'Smilies <u>DISATTIVATI</u>';

$lang['Attach_signature'] = 'Aggiungi firma (puoi cambiare la firma nel profilo)';
$lang['Notify'] = 'Avvisami quando viene inviata una risposta';

$lang['Stored'] = 'Messaggio inserito con successo.';
$lang['Deleted'] = 'Il messaggio è stato cancellato.';
$lang['Poll_delete'] = 'Il sondaggio è stato cancellato.';
$lang['Vote_cast'] = 'Il tuo voto è stato aggiunto.';

$lang['Topic_reply_notification'] = 'Notifica risposta all\'argomento';

$lang['bbcode_b_help'] = 'Grassetto: [b]testo[/b]  (alt+b)';
$lang['bbcode_i_help'] = 'Corsivo: [i]testo[/i]  (alt+i)';
$lang['bbcode_u_help'] = 'Sottolineato: [u]testo[/u]  (alt+u)';
$lang['bbcode_q_help'] = 'Citazione: [quote]testo[/quote]  (alt+q)';
$lang['bbcode_c_help'] = 'Codice: [code]codice[/code]  (alt+c)';
$lang['bbcode_l_help'] = 'Lista: [list]testo[/list] (alt+l)';
$lang['bbcode_o_help'] = 'Lista ordinata: [list=]testo[/list]  (alt+o)';
$lang['bbcode_p_help'] = 'Inserisci immagine: [img]http://image_url[/img]  (alt+p)';
$lang['bbcode_w_help'] = 'Inserisci URL: [url]http://url[/url] o [url=http://url]testo URL[/url]  (alt+w)';
$lang['bbcode_a_help'] = 'Chiudi tutti i bbCode tags aperti';
$lang['bbcode_s_help'] = 'Colore font: [color=red]testo[/color]  Suggerimento: puoi anche usare color=#FF0000';
$lang['bbcode_f_help'] = 'Dimensione font: [size=x-small]testo piccolo[/size]';

$lang['Emoticons'] = 'Emoticons';
$lang['More_emoticons'] = 'Altre emoticons';

$lang['Font_color'] = 'Colore';
$lang['color_default'] = 'Default';
$lang['color_dark_red'] = 'Rosso scuro';
$lang['color_red'] = 'Rosso';
$lang['color_orange'] = 'Arancione';
$lang['color_brown'] = 'Marrone';
$lang['color_yellow'] = 'Giallo';
$lang['color_green'] = 'Verde';
$lang['color_olive'] = 'Oliva';
$lang['color_cyan'] = 'Ciano';
$lang['color_blue'] = 'Blu';
$lang['color_dark_blue'] = 'Blu scuro';
$lang['color_indigo'] = 'Indaco';
$lang['color_violet'] = 'Viola';
$lang['color_white'] = 'Bianco';
$lang['color_black'] = 'Nero';

$lang['Font_size'] = 'Dimensione';
$lang['font_tiny'] = 'Minuscolo';
$lang['font_small'] = 'Piccolo';
$lang['font_normal'] = 'Normale';
$lang['font_large'] = 'Grande';
$lang['font_huge'] = 'Enorme';

$lang['Close_Tags'] = 'Chiudi Tags';
$lang['Styles_tip'] = 'Info: selezionando il testo potrai applicare velocemente i BBcode';


//
// Private Messaging
//
$lang['Private_Messaging'] = 'Messaggi privati';

$lang['Login_check_pm'] = 'Messaggi privati';
$lang['New_pms'] = '%d nuovi messaggi'; // You have 2 new messages
$lang['New_pm'] = '%d nuovo messaggio'; // You have 1 new message
$lang['No_new_pm'] = 'Non ci sono nuovi messaggi';
$lang['Unread_pms'] = '%d messaggi non letti';
$lang['Unread_pm'] = '%d messaggio non letto';
$lang['No_unread_pm'] = 'Hai letto tutti i messaggi';
$lang['You_new_pm'] = 'Hai un nuovo messaggio in Posta in Arrivo';
$lang['You_new_pms'] = 'Ci sono nuovi messaggi in Posta in Arrivo';
$lang['You_no_new_pm'] = 'Non ci sono nuovi messaggi';

$lang['Unread_message'] = 'Messaggio da leggere';
$lang['Read_message'] = 'Messaggio letto';

$lang['Read_pm'] = 'Leggi messaggio';
$lang['Post_new_pm'] = 'Nuovo messaggio';
$lang['Post_reply_pm'] = 'Rispondi';
$lang['Post_quote_pm'] = 'Cita messaggio';
$lang['Edit_pm'] = 'Modifica messaggio';

$lang['Inbox'] = 'Posta in Arrivo';
$lang['Outbox'] = 'Posta in Uscita';
$lang['Savebox'] = 'Posta Salvata';
$lang['Sentbox'] = 'Posta Inviata';
$lang['Flag'] = 'Stato';
$lang['Subject'] = 'Oggetto';
$lang['From'] = 'Da';
$lang['To'] = 'A';
$lang['Date'] = 'Data';
$lang['Mark'] = 'Seleziona';
$lang['Sent'] = 'Inviato';
$lang['Saved'] = 'Salvato';
$lang['Delete_marked'] = 'Cancella selezionati';
$lang['Delete_all'] = 'Cancella tutti';
$lang['Save_marked'] = 'Salva selezionati'; 
$lang['Save_message'] = 'Salva messaggio';
$lang['Delete_message'] = 'Cancella messaggio';

$lang['Display_messages'] = 'Mostra i messaggi di'; // Followed by number of days/weeks/months
$lang['All_Messages'] = 'Tutti i messaggi';

$lang['No_messages_folder'] = 'Non ci sono messaggi in questa cartella';

$lang['PM_disabled'] = 'I messaggi privati sono stati disabilitati dal Amministratore del Forum.';
$lang['Cannot_send_privmsg'] = 'L\'Amministratore del forum ti ha revocato i permessi per inviare messaggi privati.';
$lang['No_to_user'] = 'Devi specificare un username per inviare il messaggio.';
$lang['No_such_user'] = 'L\'utente non esiste.';

$lang['Disable_HTML_pm'] = 'Disabilita HTML nel messaggio';
$lang['Disable_BBCode_pm'] = 'Disabilita BBCode nel messaggio';
$lang['Disable_Smilies_pm'] = 'Disabilita Smilies nel messaggio';

$lang['Message_sent'] = 'Il tuo messaggio è stato spedito.';

$lang['Click_return_inbox'] = 'Torna alla cartella %sPosta in Arrivo%s';
$lang['Click_return_index'] = 'Torna %sall\'Indice%s';

$lang['Send_a_new_message'] = 'Invia nuovo messaggio privato';
$lang['Send_a_reply'] = 'Rispondi a messaggio privato';
$lang['Edit_message'] = 'Modifica messaggio privato';

$lang['Notification_subject'] = 'Hai un nuovo Messaggio Privato!';

$lang['Find_username'] = 'Cerca un username';
$lang['Find'] = 'Cerca';
$lang['No_match'] = 'Nessun risultato.';

$lang['No_post_id'] = 'Non è stato specificato nessun ID';
$lang['No_such_folder'] = 'Questa cartella non esiste';
$lang['No_folder'] = 'Nessuna cartella specificata';

$lang['Mark_all'] = 'Seleziona tutti';
$lang['Unmark_all'] = 'Deseleziona tutti';

$lang['Confirm_delete_pm'] = 'Sei sicuro di voler cancellare questo messaggio?';
$lang['Confirm_delete_pms'] = 'Sei sicuro di voler cancellare questi messaggi?';

$lang['Inbox_size'] = 'Utilizzo posta in arrivo %d%%'; // eg. Your Inbox is 50% full
$lang['Sentbox_size'] = 'Utilizzo posta in uscita %d%%'; 
$lang['Savebox_size'] = 'Utilizzo posta salvata %d%%'; 

$lang['Click_view_privmsg'] = 'Clicca %squi%s per andare alla cartella di Posta in Arrivo';


//
// Profiles/Registration
//
$lang['Viewing_user_profile'] = 'Guarda il profilo :: %s'; // %s is username 
$lang['About_user'] = 'Tutto su %s'; // %s is username

$lang['Preferences'] = 'Preferenze';
$lang['Items_required'] = 'Le voci contrassegnate con * sono obbligatorie.';
$lang['Registration_info'] = 'Dettagli registrazione';
$lang['Profile_info'] = 'Dettagli profilo';
$lang['Profile_info_warn'] = 'Queste informazioni saranno visibili da tutti gli utenti';
$lang['Avatar_panel'] = 'Pannello di controllo avatar';
$lang['Avatar_gallery'] = 'Galleria avatar';

$lang['Website'] = 'Sito web';
$lang['Location'] = 'Residenza';
$lang['Contact'] = 'Contatto';
$lang['Email_address'] = 'Indirizzo e-mail';
$lang['Send_private_message'] = 'Invia messaggio privato';
$lang['Hidden_email'] = '[ Nascosto ]';
$lang['Interests'] = 'Interessi';
$lang['Occupation'] = 'Impiego'; 
$lang['Poster_rank'] = 'Livello utente';

$lang['Total_posts'] = 'Messaggi totali';
$lang['User_post_pct_stats'] = '%.2f%% del totale'; // 1.25% of total
$lang['User_post_day_stats'] = '%.2f messaggi al giorno'; // 1.5 posts per day
$lang['Search_user_posts'] = 'Guarda tutti i messaggi scritti da %s'; // Find all posts by username

$lang['No_user_id_specified'] = 'L\'utente non esiste.';
$lang['Wrong_Profile'] = 'Non puoi modificare questo profilo.';

$lang['Only_one_avatar'] = 'Può essere specificato un solo tipo di avatar';
$lang['File_no_data'] = 'Il file all\'URL che hai fornito non contiene dati';
$lang['No_connection_URL'] = 'Non è possibile connettersi all\'URL che hai fornito';
$lang['Incomplete_URL'] = 'L\'URL che hai fornito è incompleto';
$lang['Wrong_remote_avatar_format'] = 'L\'URL dell\'avatar remoto non è valido';
$lang['No_send_account_inactive'] = 'Spiacenti, ma la tua password non può essere recuperata perchè il tuo account al momento è inattivo. Contatta l\'Amministratore per ulteriori informazioni.';

$lang['Always_smile'] = 'Abilita sempre gli Smilies';
$lang['Always_html'] = 'Abilita sempre HTML';
$lang['Always_bbcode'] = 'Abilita sempre BBCode';
$lang['Always_add_sig'] = 'Aggiungi sempre la mia firma';
$lang['Always_notify'] = 'Avvisami sempre delle risposte';
$lang['Always_notify_explain'] = 'Verrai avvisato con un e-mail quando un utente risponde ad un argomento a cui hai risposto. Questo può essere cambiato ogni volta che inserisci un nuovo messaggio.';

$lang['Board_style'] = 'Stile forum';
$lang['Board_lang'] = 'Lingua';
$lang['No_themes'] = 'Non sono presenti stili nel Database';
$lang['Timezone'] = 'Fuso orario';
$lang['Date_format'] = 'Formato data';
$lang['Date_format_explain'] = 'La sintassi utilizzata e\' la funzione <a href=\'http://www.php.net/manual/it/html/function.date.html\' target=\'_other\'>data()</a> del PHP.';
$lang['Signature'] = 'Firma';
$lang['Signature_explain'] = 'Testo che verrà visualizzato come firma in tutti i tuoi messaggi. C\'è un limite di %d caratteri';
$lang['Public_view_email'] = 'Mostra sempre il mio indirizzo Email';

$lang['Current_password'] = 'Password attuale';
$lang['New_password'] = 'Nuova password';
$lang['Confirm_password'] = 'Conferma password';
$lang['Confirm_password_explain'] = 'Devi confermare la tua password attuale se vuoi cambiarla o modificare il tuo indirizzo email';
$lang['password_if_changed'] = 'Devi inserire la password solo se vuoi cambiarla';
$lang['password_confirm_if_changed'] = 'Devi confermare la tua password solo se ne hai inserita una nuova qui sopra';

$lang['Avatar'] = 'Avatar';
$lang['Avatar_explain'] = 'Mostra una piccola immagine sotto i tuoi dettagli nel messaggio. Può essere mostrata una sola immagine, la sua larghezza massima è di %d pixel, l\'altezza di %d pixel e il file deve essere più piccolo di %dkB.';
$lang['Upload_Avatar_file'] = 'Carica avatar da PC';
$lang['Upload_Avatar_URL'] = 'Carica avatar da un URL';
$lang['Upload_Avatar_URL_explain'] = 'Inserisci URL dell\'avatar, che verrà copiata in questo Sito.';
$lang['Pick_local_Avatar'] = 'Seleziona avatar dalla galleria';
$lang['Link_remote_Avatar'] = 'Link esterno avatar';
$lang['Link_remote_Avatar_explain'] = 'Inserisci URL dell\'avatar che vuoi inserire.';
$lang['Avatar_URL'] = 'URL avatar';
$lang['Select_from_gallery'] = 'Seleziona avatar dalla galleria';
$lang['View_avatar_gallery'] = 'Mostra galleria';

$lang['Select_avatar'] = 'Seleziona avatar';
$lang['Return_profile'] = 'Cancella avatar';
$lang['Select_category'] = 'Seleziona categoria';

$lang['Delete_Image'] = 'Cancella immagine';
$lang['Current_Image'] = 'Immagine attuale';

$lang['Notify_on_privmsg'] = 'Notifica nei nuovi messaggi privati';
$lang['Popup_on_privmsg'] = 'Popup nuovo Messaggio Privato'; 
$lang['Popup_on_privmsg_explain'] = 'Apre una piccola nuova finestra per informarti quando arriva un nuovo messaggio privato.';
$lang['Hide_user'] = 'Nascondi il tuo stato online';

$lang['Profile_updated'] = 'Il tuo profilo è stato aggiornato';
$lang['Profile_updated_inactive'] = 'Il tuo profilo è stato aggiornato. Hai modificato dettagli importanti anche se il tuo account non è attivo. Controlla la tua e-mail per riattivare il tuo account, o, se richiesta, attendi la riattivazione da parte dell\'Amministratore.';

$lang['Password_mismatch'] = 'La password inserita non corrisponde.';
$lang['Current_password_mismatch'] = 'La password inserita non corrisponde a quella presente nel Database.';
$lang['Password_long'] = 'La password non deve essere più lunga di 32 caratteri.';
$lang['Username_taken'] = 'Username in uso da un altro utente.';
$lang['Username_invalid'] = 'Errore, l\'username contiene un carattere non valido come \'.';
$lang['Username_disallowed'] = 'Username disabilitato dall\'Amministratore.';
$lang['Email_taken'] = 'L\'indirizzo e-mail è già presente nel nostro Database.';
$lang['Email_banned'] = 'L\'indirizzo e-mail stato escluso dall\'Amministratore.';
$lang['Email_invalid'] = 'Indirizzo e-mail non valido.';
$lang['Signature_too_long'] = 'La firma è troppo lunga.';
$lang['Fields_empty'] = 'Devi riempire tutti i campi richiesti.';
$lang['Avatar_filetype'] = 'Il file avatar deve essere .jpg, .gif o .png';
$lang['Avatar_filesize'] = 'La grandezza del file dell\'avatar deve essere inferiore a %d kB'; // The avatar image file size must be less than 6 KB
$lang['Avatar_imagesize'] = 'L\'avatar non può superare la dimensione di %d pixel di larghezza e di %d pixel d\'altezza'; 

$lang['Welcome_subject'] = 'Benvenuto nel Forum di %s'; // Welcome to my.com forums
$lang['New_account_subject'] = 'Account nuovo utente';
$lang['Account_activated_subject'] = 'Account attivato';

$lang['Account_added'] = 'Grazie per esserti registrato, il tuo account è stato creato. Utilizza username e password per accedere';
$lang['Account_inactive'] = 'Il tuo account è stato creato. Questo forum richiede l\'attivazione dell\'account. La chiave per l\'attivazione è stata inviata all\'indirizzo e-mail che hai inserito. Controlla la tua e-mail per ulteriori informazioni';
$lang['Account_inactive_admin'] = 'Il tuo account è stato creato. Questo forum richiede l\'attivazione dell\'account da parte dell\'amministratore. Ti verrà inviata un e-mail dall\'amministratore e sarai informato sullo stato di attivazione del tuo account';
$lang['Account_active'] = 'Il tuo account è stato attivato. Grazie per esserti registrato.';
$lang['Account_active_admin'] = 'Il tuo account è stato attivato. Grazie per esserti registrato.';
$lang['Reactivate'] = 'Riattiva il tuo account!';
$lang['Already_activated'] = 'Questo account è già stato attivato';
$lang['COPPA'] = 'Il tuo account è stato creato, ma deve essere approvato. Controlla la tua e-mail per ulteriori dettagli.';

$lang['Registration'] = 'Condizioni per la Registrazione';
$lang['Reg_agreement'] = 'Anche se gli Amministratori e i Moderatori di questo forum cercheranno di rimuovere o modificare tutto il materiale contestabile il più velocemente possibile, è comunque impossibile verificare ogni messaggio. Tuttavia sei consapevole che tutti i messaggi di questo forum esprimono il punto di vista e le opinioni dell\'autore e non quelle degli Amministratori, dei Moderatori o del Webmaster (eccetto i messaggi degli stessi) e per questo non sono perseguibili.<br /><br />L\'utente concorda di non inviare messaggi abusivi, osceni, volgari, diffamatori, di odio, minatori, sessuali o qualunque altro materiale che possa violare qualunque legge applicabile. Inserendo messaggi di questo tipo l\'utente verrà immediatamente e permanentemente escluso (e il tuo provider verrà informato). L\'indirizzo IP di tutti i messaggi vengono registrati per aiutare a rinforzare queste condizioni. L\'utente concorda che l\'Amministratore i Moderatori e Webmaster di questo forum hanno il diritto di rimuovere, modificare, o chiudere argomenti qualora si ritengana necessario. Come utente concordi che ogni informazione che è stata inserita verrà conservata in un database. Poichè queste informazioni non verranno cedute a terzi senza il tuo consenso, Webmaster, Amministratore e i Moderatori non sono ritenuti responsabili per gli attacchi da parte degli hackers che possano compromettere i dati.<br /><br />Questo Forum usa i cookies per conservare informazioni sul tuo computer locale. Questi cookies non contengono le informazioni che hai inserirai, servono soltanto per velocizzarne il processo. L\'indirizzo Email viene utilizzato solo per confermare i dettagli della tua registrazione e per la password (e per inviare una nuova password nel caso dovessi perdere quella attuale).<br /><br />Cliccando Registra qui sotto accetti queste condizioni.';

$lang['Agree_under_13'] = 'Accetto queste condizioni e ho <b>meno</b> di 13 anni';
$lang['Agree_over_13'] = 'Accetto queste condizioni e ho <b>più</b> di 13 anni';
$lang['Agree_not'] = 'Non accetto queste condizioni';

$lang['Wrong_activation'] = 'La chiave di attivazione che hai fornito non corrisponde a nessuna presente nel database.';
$lang['Send_password'] = 'Inviami una nuova password'; 
$lang['Password_updated'] = 'Una nuova password è stata creata, controlla la tua e-mail per maggiori dettagli su come attivarla.';
$lang['No_email_match'] = 'L\'indirizzo e-mail inserito non corrisponde a quella attuale per questo username.';
$lang['New_password_activation'] = 'Attivazione nuova password';
$lang['Password_activated'] = 'Il tuo account è stato riattivato. Per entrare usa la password ricevuta via e-mail.';

$lang['Send_email_msg'] = 'Invia un messaggio e-mail';
$lang['No_user_specified'] = 'Non è stato specificato nessun utente';
$lang['User_prevent_email'] = 'L\'utente non gradisce ricevere e-mail. Prova ad inviare un messaggio privato.';
$lang['User_not_exist'] = 'Questo utente non esiste';
$lang['CC_email'] = 'Invia una copia di questa e-mail a te stesso';
$lang['Email_message_desc'] = 'Questo messaggio verrà inviato come testo, non includere nessun codice HTML o BBCode. L\'indirizzo per la risposta di questo messaggio è il tuo indirizzo e-mail.';
$lang['Flood_email_limit'] = 'Non puoi inviare un\'altra e-mail al momento. Prova più tardi.';
$lang['Recipient'] = 'Cestino';
$lang['Email_sent'] = 'Questa e-mail è stata inviata.';
$lang['Send_email'] = 'Invia e-mail';
$lang['Empty_subject_email'] = 'Devi specificare un oggetto per l\'e-mail.';
$lang['Empty_message_email'] = 'Devi inserire un messaggio da inviare.';


//
// Visual confirmation system strings
//
$lang['Confirm_code_wrong'] = 'Il codice di conferma inserito non è corretto';
$lang['Too_many_registers'] = 'Hai superato il numero massimo di tentativi per questa sessione. Ritenta più tardi.';
$lang['Confirm_code_impaired'] = 'Se non riesci a visualizzare il codice di registrazione contatta l\'%sAmministratore%s.';
$lang['Confirm_code'] = 'Codice di conferma';
$lang['Confirm_code_explain'] = 'Inserisci il codice di conferma visuale come indicato nell\'immagine. Il sistema riconosce la differenza tra maiuscole e minuscole, lo zero ha una barra diagonale per distinguerlo dalla lettera O.';



//
// Memberslist
//
$lang['Select_sort_method'] = 'Seleziona un ordine';
$lang['Sort'] = 'Ordina';
$lang['Sort_Top_Ten'] = 'I migliori 10 autori';
$lang['Sort_Joined'] = 'Data di registrazione';
$lang['Sort_Username'] = 'Username';
$lang['Sort_Location'] = 'Località';
$lang['Sort_Posts'] = 'Messaggi totali';
$lang['Sort_Email'] = 'Email';
$lang['Sort_Website'] = 'Sito Web';
$lang['Sort_Ascending'] = 'Crescente';
$lang['Sort_Descending'] = 'Decrescente';
$lang['Order'] = 'Ordina';


//
// Group control panel
//
$lang['Group_Control_Panel'] = 'Pannello di controllo gruppo';
$lang['Group_member_details'] = 'Dettagli utenti gruppo';
$lang['Group_member_join'] = 'Iscrivi un gruppo';

$lang['Group_Information'] = 'Informazioni gruppo';
$lang['Group_name'] = 'Nome Gruppo';
$lang['Group_description'] = 'Descrizione gruppo';
$lang['Group_membership'] = 'Appartenenza al gruppo';
$lang['Group_Members'] = 'Utenti del gruppo';
$lang['Group_Moderator'] = 'Moderatore gruppo';
$lang['Pending_members'] = 'Nuovi iscritti in attesa';

$lang['Group_type'] = 'Tipo di gruppo';
$lang['Group_open'] = 'Gruppo aperto';
$lang['Group_closed'] = 'Gruppo chiuso';
$lang['Group_hidden'] = 'Gruppo nascosto';

$lang['Current_memberships'] = 'Utenti attuali gruppo';
$lang['Non_member_groups'] = 'Non sei iscritto al gruppo';
$lang['Memberships_pending'] = 'Nuovi iscritti al gruppo in attesa';

$lang['No_groups_exist'] = 'Non esistono gruppi';
$lang['Group_not_exist'] = 'Gruppo non esistente';

$lang['Join_group'] = 'Iscriviti al gruppo';
$lang['No_group_members'] = 'Questo gruppo non ha utenti iscritti';
$lang['Group_hidden_members'] = 'Gruppo nascosto, non puoi vedere i suoi utenti';
$lang['No_pending_group_members'] = 'Questo gruppo non ha utenti in attesa';
$lang['Group_joined'] = 'Ti sei iscritto a questo gruppo con successo.<br />Sarai avvisato quando la tua iscrizione verrà approvata dal moderatore del gruppo.';
$lang['Group_request'] = 'C\'è una richiesta di iscrizione al tuo gruppo.';
$lang['Group_approved'] = 'La tua richiesta è stata approvata.';
$lang['Group_added'] = 'Sei stato aggiunto a questo gruppo.'; 
$lang['Already_member_group'] = 'Sei già iscritto a questo gruppo';
$lang['User_is_member_group'] = 'L\'utente è già iscritto a questo gruppo';
$lang['Group_type_updated'] = 'Tipo di gruppo aggiornato.';

$lang['Could_not_add_user'] = 'L\'utente selezionato non esiste.';
$lang['Could_not_anon_user'] = 'L\'utente anonimo non può essere iscritto ad un gruppo.';

$lang['Confirm_unsub'] = 'Sei sicuro di volerti cancellare da questo gruppo?';
$lang['Confirm_unsub_pending'] = 'La tua iscrizione a questo Gruppo non è ancora stata approvata, sei sicuro di volerti cancellare?';

$lang['Unsub_success'] = 'Sei stato cancellato da questo gruppo.';

$lang['Approve_selected'] = 'Approvazione selezionata';
$lang['Deny_selected'] = 'Rifiuto selezionato';
$lang['Not_logged_in'] = 'Per iscriverti ad un gruppo devi essere registrato.';
$lang['Remove_selected'] = 'Rimuovi selezionati';
$lang['Add_member'] = 'Aggiungi utente';
$lang['Not_group_moderator'] = 'Non sei Moderatore di questo gruppo, non puoi eseguire questa azione.';

$lang['Login_to_join'] = 'Entra per iscriverti o gestire un gruppo di utenti';
$lang['This_open_group'] = 'Gruppo aperto, clicca per richiedere l\'adesione';
$lang['This_closed_group'] = 'Gruppo chiuso, non si accettano altri membri';
$lang['This_hidden_group'] = 'Gruppo nascosto, non è permesso aggiungere nuovi utenti automaticamente';
$lang['Member_this_group'] = 'Sei iscritto a questo gruppo';
$lang['Pending_this_group'] = 'La tua iscrizione a questo gruppo è in attesa di approvazione';
$lang['Are_group_moderator'] = 'Sei Moderatore di questo gruppo';
$lang['None'] = 'Nessuno';

$lang['Subscribe'] = 'Iscriviti';
$lang['Unsubscribe'] = 'Cancella';
$lang['View_Information'] = 'Guarda informazioni';


//
// Search
//
$lang['Search_query'] = 'Motore di Ricerca';
$lang['Search_options'] = 'Opzioni di Ricerca';

$lang['Search_keywords'] = 'Cerca per parole chiave';
$lang['Search_keywords_explain'] = 'Puoi usare <u>AND</u> per definire le parole che devono essere nel risultato della ricerca, <u>OR</u> per definire le parole che potrebbero essere nel risultato e <u>NOT</u> per definire le parole che non devono essere nel risultato. Usa * come abbreviazione per parole parziali';
$lang['Search_author'] = 'Cerca per autore';
$lang['Search_author_explain'] = 'Usa * come abbreviazione per parole parziali';

$lang['Search_for_any'] = 'Cerca per parola o usa frase esatta';
$lang['Search_for_all'] = 'Cerca tutte le parole';
$lang['Search_title_msg'] = 'Cerca nel titolo o nel testo';
$lang['Search_msg_only'] = 'Cerca solo nel testo';

$lang['Return_first'] = 'Mostra i primi'; // followed by xxx characters in a select box
$lang['characters_posts'] = 'caratteri del messaggio';

$lang['Search_previous'] = 'Cerca i messaggi di'; // followed by days, weeks, months, year, all in a select box

$lang['Sort_by'] = 'Ordina per';
$lang['Sort_Time'] = 'Data messaggio';
$lang['Sort_Post_Subject'] = 'Oggetto messaggio';
$lang['Sort_Topic_Title'] = 'Titolo argomento';
$lang['Sort_Author'] = 'Autore';
$lang['Sort_Forum'] = 'Forum';

$lang['Display_results'] = 'Mostra i risultati come';
$lang['All_available'] = 'Tutto disponibile';
$lang['No_searchable_forums'] = 'Non hai i permessi per utilizzare il motore di ricerca del forum.';

$lang['No_search_match'] = 'Nessun argomento o messaggio con questo criterio di ricerca';
$lang['Found_search_match'] = 'La ricerca ha trovato %d risultato'; // eg. Search found 1 match
$lang['Found_search_matches'] = 'La ricerca ha trovato %d risultati'; // eg. Search found 24 matches
$lang['Search_Flood_Error'] = 'Non puoi eseguire una nuova ricerca, l\'amministratore ha impostato un tempo di limite per le ricerche. Riprova tra qualche istante.';

$lang['Close_window'] = 'Chiudi Finestra';


//
// Auth related entries
//
// Note the %s will be replaced with one of the following 'user' arrays
$lang['Sorry_auth_announce'] = 'Solo %s possono inviare annunci.';
$lang['Sorry_auth_sticky'] = 'Solo %s possono inviare messaggi importanti.'; 
$lang['Sorry_auth_read'] = 'Solo %s possono leggere gli argomenti.'; 
$lang['Sorry_auth_post'] = 'Solo %s possono inserire argomenti.'; 
$lang['Sorry_auth_reply'] = 'Solo %s possono rispondere ai messaggi.';
$lang['Sorry_auth_edit'] = 'Solo %s possono modificare i messaggi.'; 
$lang['Sorry_auth_delete'] = 'Solo %s possono cancellare i messaggi.';
$lang['Sorry_auth_vote'] = 'Solo %s possono votare ai sondaggi.';

// These replace the %s in the above strings
$lang['Auth_Anonymous_Users'] = '<b>gli utenti anonimi</b>';
$lang['Auth_Registered_Users'] = '<b>gli utenti registrati</b>';
$lang['Auth_Users_granted_access'] = '<b>gli utenti con accesso speciale</b>';
$lang['Auth_Moderators'] = '<b>i Moderatori</b>';
$lang['Auth_Administrators'] = '<b>gli Amministratori</b>';

$lang['Not_Moderator'] = 'Non sei Moderatore di questo forum.';
$lang['Not_Authorised'] = 'Non autorizzato';

$lang['You_been_banned'] = 'Sei stato escluso da questo forum<br />contatta l\'Amministratore del Sito per ulteriori informazioni.';


//
// Viewonline
//
$lang['Reg_users_zero_online'] = 'Ci sono 0 utenti registrati e '; // There are 5 Registered and
$lang['Reg_users_online'] = 'Ci sono %d utenti registrati e '; // There are 5 Registered and
$lang['Reg_user_online'] = 'C\'è %d utente registrato e '; // There is 1 Registered and
$lang['Hidden_users_zero_online'] = '0 utenti nascosti in linea'; // 6 Hidden users online
$lang['Hidden_users_online'] = '%d utenti nascosti in linea'; // 6 Hidden users online
$lang['Hidden_user_online'] = '%d utente nascosto in linea'; // 6 Hidden users online
$lang['Guest_users_online'] = 'Ci sono %d ospiti in linea'; // There are 10 Guest users online
$lang['Guest_users_zero_online'] = 'Ci sono 0 ospiti in linea'; // There are 10 Guest users online
$lang['Guest_user_online'] = 'C\'è %d ospite in linea'; // There is 1 Guest user online
$lang['No_users_browsing'] = 'Al momento non ci sono utenti nel forum';

$lang['Online_explain'] = 'Questi dati si basano sugli utenti connessi negli ultimi cinque minuti';

$lang['Forum_Location'] = 'Località del forum';
$lang['Last_updated'] = 'Ultimo aggiornamento';

$lang['Forum_index'] = 'Indice Forum';
$lang['Logging_on'] = 'Sta entrando';
$lang['Posting_message'] = 'Sta inviando un messaggio';
$lang['Searching_forums'] = 'Sta cercando nei forum';
$lang['Viewing_profile'] = 'Sta guardando il profilo';
$lang['Viewing_online'] = 'Sta guardando chi c\'è in linea';
$lang['Viewing_member_list'] = 'Sta guardando la lista utenti';
$lang['Viewing_priv_msgs'] = 'Sta guardando i messaggi privati';
$lang['Viewing_FAQ'] = 'Sta guardando le FAQ';


//
// Moderator Control Panel
//
$lang['Mod_CP'] = 'Pannello di controllo Moderatori';
$lang['Mod_CP_explain'] = 'Utilizzando il modulo qui sotto puoi eseguire operazioni di massa su questo forum. Puoi bloccare, sbloccare, spostare o cancellare.';

$lang['Select'] = 'Seleziona';
$lang['Delete'] = 'Cancella';
$lang['Move'] = 'Sposta';
$lang['Lock'] = 'Chiudi';
$lang['Unlock'] = 'Apri';

$lang['Topics_Removed'] = 'Gli argomenti selezionati sono stati rimossi dal database.';
$lang['Topics_Locked'] = 'Gli argomenti selezionati sono stati chiusi.';
$lang['Topics_Moved'] = 'Gli argomenti selezionati sono stati spostati.';
$lang['Topics_Unlocked'] = 'Gli argomenti selezionati sono stati ri-aperti.';
$lang['No_Topics_Moved'] = 'Non è stato spostato nessun argomenti.';

$lang['Confirm_delete_topic'] = 'Sei sicuro di voler eliminare gli argomenti selezionati?';
$lang['Confirm_lock_topic'] = 'Sei sicuro di voler chiudere gli argomenti selezionati?';
$lang['Confirm_unlock_topic'] = 'Sei sicuro di voler ri-aprire gli argomenti selezionati?';
$lang['Confirm_move_topic'] = 'Sei sicuro di voler spostare gli argomenti selezionati?';

$lang['Move_to_forum'] = 'Vai al forum';
$lang['Leave_shadow_topic'] = 'Lascia una traccia nel forum di creazione.';

$lang['Split_Topic'] = 'Divisione argomenti';
$lang['Split_Topic_explain'] = 'Utilizzando il modulo qui sotto puoi dividere un argomenti in due, sia selezionando i messaggi individualmente, sia dividendo l\'argomento da una parte di selezionato messaggio in poi';
$lang['Split_title'] = 'Titolo nuovo argomento';
$lang['Split_forum'] = 'Forum per il nuovo argomento';
$lang['Split_posts'] = 'Dividi i messaggi selezionati';
$lang['Split_after'] = 'Dividi partendo dal messaggio selezionato';
$lang['Topic_split'] = 'L\'argomento selezionato è stato diviso';

$lang['Too_many_error'] = 'Hai selezionato troppi messaggi. Puoi selezionare solo il messaggio da cui dividere l\'argomento!';

$lang['None_selected'] = 'Nessun argomento selezionato nel quale eseguire questa operazione. Torna indietro e selezionane almeno uno.';
$lang['New_forum'] = 'Nuovo forum';

$lang['This_posts_IP'] = 'Indirizzo IP per questo messaggio';
$lang['Other_IP_this_user'] = 'Altri indirizzi IP utilizzati da questo utente';
$lang['Users_this_IP'] = 'Utenti che utilizzano questo indirizzo IP';
$lang['IP_info'] = 'Informazioni indirizzo IP';
$lang['Lookup_IP'] = 'Cerca indirizzo IP';


//
// Timezones ... for display on each page
//
$lang['All_times'] = 'Tutti i fusi orari sono %s'; // eg. All times are GMT - 12 Hours (times from next block)

$lang['-12'] = 'GMT - 12 ore';
$lang['-11'] = 'GMT - 11 ore';
$lang['-10'] = 'GMT - 10 ore';
$lang['-9'] = 'GMT - 9 ore';
$lang['-8'] = 'GMT - 8 ore';
$lang['-7'] = 'GMT - 7 ore';
$lang['-6'] = 'GMT - 6 ore';
$lang['-5'] = 'GMT - 5 ore';
$lang['-4'] = 'GMT - 4 ore';
$lang['-3.5'] = 'GMT - 3.5 ore';
$lang['-3'] = 'GMT - 3 ore';
$lang['-2'] = 'GMT - 2 ore';
$lang['-1'] = 'GMT - 1 ore';
$lang['0'] = 'GMT';
$lang['1'] = 'GMT + 1 ora';
$lang['2'] = 'GMT + 2 ore';
$lang['3'] = 'GMT + 3 ore';
$lang['3.5'] = 'GMT + 3.5 ore';
$lang['4'] = 'GMT + 4 ore';
$lang['4.5'] = 'GMT + 4.5 ore';
$lang['5'] = 'GMT + 5 ore';
$lang['5.5'] = 'GMT + 5.5 ore';
$lang['6'] = 'GMT + 6 ore';
$lang['6.5'] = 'GMT + 6.5 ore';
$lang['7'] = 'GMT + 7 ore';
$lang['8'] = 'GMT + 8 ore';
$lang['9'] = 'GMT + 9 ore';
$lang['9.5'] = 'GMT + 9.5 ore';
$lang['10'] = 'GMT + 10 ore';
$lang['11'] = 'GMT + 11 ore';
$lang['12'] = 'GMT + 12 ore';
$lang['13'] = 'GMT + 13 ore';

// These are displayed in the timezone select box
$lang['tz']['-12'] = 'GMT -12:00 ore';
$lang['tz']['-11'] = 'GMT -11:00 ore';
$lang['tz']['-10'] = 'GMT -10:00 ore';
$lang['tz']['-9'] = 'GMT -9:00 ore';
$lang['tz']['-8'] = 'GMT -8:00 ore';
$lang['tz']['-7'] = 'GMT -7:00 ore';
$lang['tz']['-6'] = 'GMT -6:00 ore';
$lang['tz']['-5'] = 'GMT -5:00 ore';
$lang['tz']['-4'] = 'GMT -4:00 ore';
$lang['tz']['-3.5'] = 'GMT -3:30 ore';
$lang['tz']['-3'] = 'GMT -3:00 ore';
$lang['tz']['-2'] = 'GMT -2:00 ore';
$lang['tz']['-1'] = 'GMT -1:00 ora';
$lang['tz']['0'] = 'GMT';
$lang['tz']['1'] = 'GMT +1:00 ora';
$lang['tz']['2'] = 'GMT +2:00 ore';
$lang['tz']['3'] = 'GMT +3:00 ore';
$lang['tz']['3.5'] = 'GMT +3:30 ore';
$lang['tz']['4'] = 'GMT +4:00 ore';
$lang['tz']['4.5'] = 'GMT +4:30 ore';
$lang['tz']['5'] = 'GMT +5:00 ore';
$lang['tz']['5.5'] = 'GMT +5:30 ore';
$lang['tz']['6'] = 'GMT +6:00 ore';
$lang['tz']['6.5'] = 'GMT +6:30 ore';
$lang['tz']['7'] = 'GMT +7:00 ore';
$lang['tz']['8'] = 'GMT +8:00 ore';
$lang['tz']['9'] = 'GMT +9:00 ore';
$lang['tz']['9.5'] = 'GMT +9:30 ore';
$lang['tz']['10'] = 'GMT + 10 ore';
$lang['tz']['11'] = 'GMT + 11 ore';
$lang['tz']['12'] = 'GMT + 12 ore';
$lang['tz']['13'] = 'GMT + 13 ore';

$lang['datetime']['Sunday'] = 'Domenica';
$lang['datetime']['Monday'] = 'Lunedì';
$lang['datetime']['Tuesday'] = 'Martedì';
$lang['datetime']['Wednesday'] = 'Mercoledì';
$lang['datetime']['Thursday'] = 'Giovedì';
$lang['datetime']['Friday'] = 'Venerdì';
$lang['datetime']['Saturday'] = 'Sabato';
$lang['datetime']['Sun'] = 'Dom';
$lang['datetime']['Mon'] = 'Lun';
$lang['datetime']['Tue'] = 'Mar';
$lang['datetime']['Wed'] = 'Mer';
$lang['datetime']['Thu'] = 'Gio';
$lang['datetime']['Fri'] = 'Ven';
$lang['datetime']['Sat'] = 'Sab';
$lang['datetime']['January'] = 'Gennaio';
$lang['datetime']['February'] = 'Febbraio';
$lang['datetime']['March'] = 'Marzo';
$lang['datetime']['April'] = 'Aprile';
$lang['datetime']['May'] = 'Maggio';
$lang['datetime']['June'] = 'Giugno';
$lang['datetime']['July'] = 'Luglio';
$lang['datetime']['August'] = 'Agosto';
$lang['datetime']['September'] = 'Settembre';
$lang['datetime']['October'] = 'Ottobre';
$lang['datetime']['November'] = 'Novembre';
$lang['datetime']['December'] = 'Dicembre';
$lang['datetime']['Jan'] = 'Gen';
$lang['datetime']['Feb'] = 'Feb';
$lang['datetime']['Mar'] = 'Mar';
$lang['datetime']['Apr'] = 'Apr';
$lang['datetime']['May'] = 'Mag';
$lang['datetime']['Jun'] = 'Giu';
$lang['datetime']['Jul'] = 'Lug';
$lang['datetime']['Aug'] = 'Ago';
$lang['datetime']['Sep'] = 'Set';
$lang['datetime']['Oct'] = 'Ott';
$lang['datetime']['Nov'] = 'Nov';
$lang['datetime']['Dec'] = 'Dic';

//
// Errors (not related to a
// specific failure on a page)
//
$lang['Information'] = 'Informazione';
$lang['Critical_Information'] = 'Informazione Critica';

$lang['General_Error'] = 'Errore Generale';
$lang['Critical_Error'] = 'Errore Critico';
$lang['An_error_occured'] = 'Si è verificato un errore';
$lang['A_critical_error'] = 'Si è verificato un errore critico';

$lang['Admin_reauthenticate'] = ' Per Amministrare il Forum ti devi riautenticare.';
$lang['Login_attempts_exceeded'] = 'Il numero massimo di %s tentativi di login consentiti sono stati superati. Potrai riprovare tra %s minuti.';
$lang['Please_remove_install_contrib'] = 'Elimina le cartelle install/ e contrib/ dal tuo server';

$lang['Session_invalid'] = 'Sessione non valida. Ricompila il form.';

//
// That's all, Folks!
// -------------------------------------------------

?>
