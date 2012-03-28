<?php

/**
 * ShowQuestions is an extension of UniversiboCommand class.
 *
 * It shows Questionnaire page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
use UniversiBO\Legacy\App\UniversiboCommand;

class ShowQuestions extends UniversiboCommand {
	function execute(){

		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		
		$template->assign('question_PersonalInfo', 'Dati personali: ');
		$template->assign('question_PersonalInfoData', array('Nome','Cognome','E-mail','Telefono'));
		$template->assign('question_q1', 'Saresti disponibile a darci un piccolo contributo(di tempo) per il progetto?');
		$template->assign('question_q1Answers', array('una giornata alla settimana o più;','poche ore alla settimana;','pochi minuti alla settimana;'));
		$template->assign('question_q2', 'Quanto tempo ti connetti a Internet?');
		$template->assign('question_q2Answers', array('quasi mai;','una volta alla settimana;','una volta al giorno;','vivo connesso;'));
		$template->assign('question_q3', 'Quali di queste attività pensi di poter svolgere(anche più di una scelta)?');
		$template->assign('question_q3AnswersMulti', array('attività off-line(contatti con i docenti o studenti, reperimento materiale...);','moderatore 
		(controllare che la gente non scriva cose non permesse...);','scrittura contenuti riguardanti i corsi che frequento;','testare le nuove versioni dei sevizi 
		provandoli on-line;','elaborazione grafica di immagini (icone, scritte, ecc...);','aiutare nella progettazione e programmazione del sito;'));
		/*$template->assign('question_InformaticKnowledge', 'Le seguenti sono domande per avere informazioni sulle vostre conscenze informatiche, non spaventatevi se non siete interessati all\'aspetto tecnico del progetto!!');
		$template->assign('question_q4', 'Sai utilizzare i seguenti Sistemi Operativi?');
		$template->assign('question_q4Sub1', '- Windows');
		$template->assign('question_q4AnswersSub1', array('benissimo;','bene;','lo uso poco;'));
		$template->assign('question_q4Sub2', '- Unix/Linux (Debian)');
		$template->assign('question_q4AnswersSub2', array('conosco benissimo, la distribuzione Debian;','conosco bene Unix;','lo uso raramente;','non lo conosco;'));
		$template->assign('question_q5', 'Conosci i seguenti linguaggi?');
		$template->assign('question_q5Sub1', '- Conosci il linguaggio HTML?');
		$template->assign('question_q5AnswersSub1', array('benissimo: XHMTL, CSS, regole per l\'accessibilità;','sufficentemente: ho fatto qualche sito in HTML;','non lo conosco;'));
		$template->assign('question_q5Sub2', '- Conosci il linguaggio PHP?');
		$template->assign('question_q5AnswersSub2', array('benissimo (conosco anche PEAR, Smarty, PHPUnit, Lemos ClassForms);','sufficientemente;','non lo conosco;'));
		$template->assign('question_q5Sub3', '- Conosci il linguaggio Javascript?');
		$template->assign('question_q5AnswersSub3', array('benissimo;','sufficientemente;','non lo conosco;'));
		$template->assign('question_q5Sub4', '- Conosci XML?');
		$template->assign('question_q5AnswersSub4', array('benissimo;','sufficientemente;','non lo conosco;'));
		$template->assign('question_q5Sub5', '- Conosci il liguaggio Java?');
		$template->assign('question_q5AnswersSub5', array('benissimo;','sufficientemente;','non lo conosco;'));
		$template->assign('question_q5Sub6', '- Conosci SQL?');
		$template->assign('question_q5AnswersSub6', array('benissimo (in particolare PostgreSQL, Oracle, MySQL);','sufficientemente;','non lo conosco;'));
		$template->assign('question_q6', 'Conosci i seguenti software grafici?');
		$template->assign('question_q6Sub1', '- Photoshop');
		$template->assign('question_q6AnswersSub1', array('benissimo;','sufficientemente;','non lo conosco;'));
		$template->assign('question_q6Sub2', '- Gimp');
		$template->assign('question_q6AnswersSub2', array('benissimo;','sufficientemente;','non lo conosco;'));*/
		$template->assign('question_PersonalNotes', 'Altre informazioni personali:');
		$template->assign('question_Privacy', 'Acconsento al trattamento dei miei dati personali ai sensi della legge sulla privacy 1996 N. 675/96;');
		$template->assign('question_Send', 'Invia');
		$template->assign('question_TitleAlt', 'Questionario');
						
		
		return 'default';						
	}
}

?>