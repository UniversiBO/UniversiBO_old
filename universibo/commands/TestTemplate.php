<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);

/**
 * Comando per testare i template i fase di sviluppo, in questo file vengono inserite
 * le definizione delle interfacce dei template
 *
 * Adds user authorization and double view (popup/index)
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class TestTemplate extends UniversiboCommand {
	function execute(){

		$template =& $this->frontController->getTemplateEngine();
		
		//var_dump($template);
		
/*		
		//$template->assign('common_pageType', 'popup');
		$template->assign('common_pageType', 'index');

		$template->assign('common_templateBaseDir', 'tpl/black/');
		$template->assign('common_rootUrl', 'https://universibo.ing.unibo.it/');
		$template->assign('common_rootEmail', 'universibo@joker.ing.unibo.it');
		$template->assign('common_staffEmail', 'staff_universibo@calvin.ing.unibo.it');
		$template->assign('common_universibo', 'UniversiBO');
		
		$template->assign('common_veryLongDate', 'Sabato 23 Agosto 2003');
		$template->assign('common_longDate', '23 Agosto 2003');
		$template->assign('common_shortDate', '23/07/2003');
		$template->assign('common_time', '15:53');
		$template->assign('common_metaKeywords', 'universibo, università, facoltà, studenti, bologna, professori, lezioni, materiale didattico, didattica, corsi, studio, studi, novità, appunti, dispense, lucidi, esercizi, esami, temi d\'esame, orari lezione, ingegneria, economia, ateneo');
		$template->assign('common_metaDescription', 'Il portale dedicato agli studenti universitari di Bologna');
		$template->assign('common_alert', 'Il sito è momentaneamente accessibile in sola lettura per attività di manutenzione');
		$template->assign('common_title', 'Titolo della pagina');
*/
		//...nell'UniversiBO command



/*
		//solo nella pagine index
		$template->assign('common_logo', 'Logo UniversiBO');
		$template->assign('common_logoType', 'default'); //estate/natale/8marzo/pasqua/carnevale/svalentino/halloween/ecc...
		$template->assign('common_setHomepage', 'Imposta Homepage');
		$template->assign('common_addBookmarks', 'Aggiungi ai preferiti');

		$template->assign('common_fac', 'Facoltà');
		$common_facLinks = array();
		$common_facLinks[] = array ('label'=>'Ingegneria', 'uri'=>'http://www.example.com'); 
		$common_facLinks[] = array ('label'=>'Economia', 'uri'=>'http://www.example.com'); 
		$common_facLinks[] = array ('label'=>'Nome facoltà1', 'uri'=>'http://www.example.com'); 
		$common_facLinks[] = array ('label'=>'Nome facoltà2', 'uri'=>'http://www.example.com'); 
		$template->assign('common_facLinks', $common_facLinks);

		$template->assign('common_services', 'Servizi');
		$common_servicesLinks = array();
		$common_servicesLinks[] = array ('label'=>'Appunti - Latex', 'uri'=>'http://www.example.com'); 
		$common_servicesLinks[] = array ('label'=>'Biblioteca', 'uri'=>'http://www.example.com'); 
		$common_servicesLinks[] = array ('label'=>'Eventi', 'uri'=>'http://www.example.com'); 
		$common_servicesLinks[] = array ('label'=>'Moderatori', 'uri'=>'http://www.example.com'); 
		$common_servicesLinks[] = array ('label'=>'Grafica', 'uri'=>'http://www.example.com'); 
		$template->assign('common_servicesLinks', $common_servicesLinks);

		$template->assign('common_info', 'Informazioni');
		$template->assign('common_help', 'Help');
		$template->assign('common_helpUri', 'index.php?do=ShowHelp');
		$template->assign('common_rules', 'Regolamento');
		$template->assign('common_rulesUri', 'index.php?do=ShowRules');
		$template->assign('common_contacts', 'Contatti - (chi siamo)');
		$template->assign('common_contactsUri', 'index.php?do=ShowContacts');
		$template->assign('common_contribute', 'Collabora');
		$template->assign('common_contributeUri', 'index.php?do=ShowContribute');

		$template->assign('common_manifesto', 'Manifesto');
		$template->assign('common_manifestoUri', 'index.php?do=ShowManifesto');

		$template->assign('common_calendar', 'Calendario');
		$common_calendarLink = array ('label'=>'Agosto', 'uri'=>'index.php?do=ShowCalendar&amp;month=8'); 
		$template->assign('common_calendarLink', $common_calendarLink);
		
		$template->assign('common_docUri', 'http://nikita.ing.unibo.it/~eagleone/documentazione_progetto/');
		$template->assign('common_doc', 'Documentazione');
		$template->assign('common_docUri', 'http://nikita.ing.unibo.it/~eagleone/documentazione_progetto/');
		$template->assign('common_project', 'UniversiBO Open Source Project');
		$template->assign('common_projectUri', 'http://universibo.sourceforge.net/');


		$template->assign('common_disclaimer', 'Ogni marchio citato in questa pagina appartiene al legittimo proprietario.'.
												'Con il contenuto delle pagine appartenenti a questo sito non si è voluto ledere i diritti di nessuno, quindi nel malaugurato caso che questo possa essere avvenuto, vi invitiamo a contattarci affinchè le parti in discussione vengano eliminate o chiarite.');
*/
		
//-------------------- HOME
/*
		$template->assign('home_langWelcome', 'Benvenuto in UniversiBO!');
		$template->assign('home_langWhatIs', 'Questo è il nuovo portale per la didattica, dedicato agli studenti dell\'università di Bologna.');
		$template->assign('home_langMission', 'L\'obiettivo verso cui è tracciata la rotta delle iniziative e dei servizi che trovate su questo portale è di "aiutare gli studenti ad aiutarsi tra loro", fornirgli un punto di riferimento centralizzato in cui prelevare tutte le informazioni didattiche riguardanti i propri corsi di studio e offrire un mezzo di interazione semplice e veloce con i docenti che partecipano all\'iniziativa.');
*/
		//...home_news... DA DEFINIRE ...include news.tpl
		
		//$template->display('home.tpl');

	
//-------------------- FAC (Facoltà)
/*   QUESTA PAGINA E' DA INVENTARE... io gli ho dato una struttura simile a quella dei cdl,
			solo che sono elencati di vari corsi di laurea invece che gli insegnamenti 
*/
		$template =& $this->frontController->getTemplateEngine();

		$fac_listCdl = array(); 	//cat := lista di cdl
		$fac_listCdl[] =  array('cod'=>'0048' , 'name'=>'ELETTRONICA', 'link'=> 'index.php?do=showCdl&amp;id_cdl=0048&amp;anno_accademico=2003');
		$fac_listCdl[] =  array('cod'=>'0049' , 'name'=>'GESTIONALE', 'link'=> 'index.php?do=showCdl&amp;id_cdl=0049&amp;anno_accademico=2003');
		$fac_listCdl[] =  array('cod'=>'0050' , 'name'=>'DEI PROCESSI GESTIONALI', 'link'=> 'index.php?do=showCdl&amp;id_cdl=0050&amp;anno_accademico=2003');
		$fac_listCdl[] =  array('cod'=>'0051' , 'name'=>'INFORMATICA', 'link'=> 'index.php?do=showCdl&amp;id_cdl=0051&amp;anno_accademico=2003');
	
		$fac_listCdlType   =  array();   //fac := lista categorie degli anni di cdl
		$fac_listCdlType[] =  array('cod'=>'L' , 'name'=>'Lauree Triennali/Primo Livello', 'list'=> $fac_listCdl);
		$fac_listCdlType[] =  array('cod'=>'S' , 'name'=>'Lauree Specialistiche', 'list'=> $fac_listCdl); 
		$fac_listCdlType[] =  array('cod'=>'V' , 'name'=>'Lauree Vecchio Ordinamento', 'list'=> $fac_listCdl);

		$template->assign('fac_list', $fac_listCdl );

		$template->assign('fac_langFac', 'FACOLTA\'');
		$template->assign('fac_facName', 'INGEGNERIA');
		$template->assign('fac_facLink', 'http://www.ing.unibo.it');
		$template->assign('fac_langList', 'Elenco corsi di laurea attivi su UniversiBO');

		//...cdl_news... DA DEFINIRE ...include news.tpl

		$template->display('facolta.tpl');
		



//-------------------- CDL (Corso di laurea)
/*
		$template->assign('cdl_langCdl', 'Corso di laurea');
		$template->assign('cdl_cdlName', 'INFORMATICA');
		$template->assign('cdl_cdlCode', '0051');
		$template->assign('cdl_langList', 'Elenco insegnamenti attivi su UniversiBO nell\'anno accademico');
		$template->assign('cdl_langOther', 'Visualizza altri anni accademici');
		$template->assign('cdl_thisAA', '2003/2004');
		$template->assign('cdl_nextAA', '2004/2005');
		$template->assign('cdl_prevAA', '2002/2003');
		
		$cdl_cicle[] = array(); //ciclo := lista di corsi
		$cdl_cicle[] = array('name'='CALCOLATORI ELETTRONICI L-A', 'teacher' => 'NERI GIOVANNI', 'link' => 'index.php?do=ShowCourse&amp;id_argomento=56'); 
		$cdl_cicle[] = array('name'='COMUNICAZIONI ELETTRICHE L-A', 'teacher' => 'CAINI CARLO', 'link' => 'index.php?do=ShowCourse&amp;id_argomento=59');  
		$cdl_cicle[] = array('name'='SISTEMI OPERATIVI L-A', 'teacher' => 'CIAMPOLINI ANNA', 'link' => 'index.php?do=ShowCourse&amp;id_argomento=56');
		  
		$cdl_year[] = array(); //anno di corso := lista di cicli
		$cdl_year[] = array('cod'=>1 , 'name'='Primo', 'list'=> $cdl_cicle);
		$cdl_year[] = array('cod'=>2 , 'name'='Secondo', 'list'=> $cdl_cicle);
		$cdl_year[] = array('cod'=>3 , 'name'='Terzo', 'list'=> $cdl_cicle);
		$cdl_year[] = array('cod'=>'E' , 'name'='Estensivo', 'list'=> $cdl_cicle);
		
		
		$cdl_list[] = array(); //cdl := lista degli anni di corso
		$cdl_list[] =  array('cod'=>1 , 'name'='Primo', 'list'=> $cdl_year);  
		$cdl_list[] =  array('cod'=>2 , 'name'='Secondo', 'list'=> $cdl_year);
		$cdl_list[] =  array('cod'=>3 , 'name'='Terzo', 'list'=> $cdl_year);
		$cdl_list[] =  array('cod'=>4 , 'name'='Quarto', 'list'=> $cdl_year);
		$cdl_list[] =  array('cod'=>5 , 'name'='Quinto', 'list'=> $cdl_year);

		$template->assign('cdl_list', $cdl_list );


		//...cdl_news... DA DEFINIRE ...include news.tpl

		$template->assign('home_mission', 'L\'obiettivo verso cui è tracciata la rotta delle iniziative e dei servizi che trovate su questo portale è di "aiutare gli studenti ad aiutarsi tra loro", fornirgli un punto di riferimento centralizzato in cui prelevare tutte le informazioni didattiche riguardanti i propri corsi di studio e offrire un mezzo di interazione semplice e veloce con i docenti che partecipano all\'iniziativa.');

		$template->display('cdl.tpl');
		
*/
		
		return 'default';						
	}
}
?>