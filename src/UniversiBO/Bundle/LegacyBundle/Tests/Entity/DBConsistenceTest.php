<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;
/**
 * suite di test esegue query di controllo per il Database
 * @author davide
 *
 */
class DBConsistenceTest extends DBRepositoryTest
{
    public function setUp()
    {
        if(!defined('DB_TESTING') || !DB_TESTING)
        {
            $this->markTestSkipped();
        }
        parent::setUp();
    }
    
	/**
	--Controlla che tutti i canali siano puntati da un forum
	*/
	function testCanalePuntaForum() {
		$db = $this->db;
		//--Controlla che tutti i canali puntino un forum esistente
		$query = 'SELECT * FROM canale WHERE id_forum NOT IN (SELECT forum_id from phpbb_forums);';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controlla che tutti i canali puntino ad un gruppo esistente
	*/
	function testCanalePuntaGruppoForum() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale 
		WHERE group_id NOT IN (SELECT group_id from phpbb_groups)
		ORDER BY id_canale;
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controlla che files_attivo sia S o N
	*/
	function testCanaleFileAttivoSN() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale
		WHERE files_attivo NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controlla che news_attivo sia S o N
	*/
	function testCanaleNewsAttivoSN() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale
		WHERE news_attivo NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che forum_attivo sia S o N
	*/
	function testCanaleForumAttivoSN() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale
		WHERE forum_attivo NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che links_attivo sia S o N
	*/
	function testCanaleLinksAttivoSN() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale
		WHERE links_attivo NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che i permessi siano minori di 127
	*/
	function testCanalePermessiMinore127() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM canale
		WHERE permessi_groups>127;
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutti i cdl puntino ad un canale esistente
	*/
	function testCdlPuntaCanale() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM classi_corso 
		WHERE id_canale NOT IN (SELECT id_canale from canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo se tutti i canali puntati dai cdl hanno tipo_canale=4 (cdl)
	*/
	function testCdlPuntatoDaCanaleDiTipo4() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM classi_corso cc, canale cn 
		WHERE cc.id_canale=cn.id_canale
		AND cn.tipo_canale!=4;
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutti i cdl puntino ad una categoria del forum esistente (cat_id)
	*/
	function testCdlPuntaCategoriaForum() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM classi_corso 
		WHERE cat_id NOT IN (SELECT cat_id from phpbb_categories);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo le corrispondenze tra titolo categoria e codice cdl --> NON FUNGE
	*/
	function testCdlPuntaCategoriaForumConCodiceNelTitolo() {
		$db = $this->db;

		$query = '---
		SELECT * 
		FROM classi_corso cc, phpbb_categories pc
		WHERE cc.cat_id=pc.cat_id
		AND cc.cod_corso=substring(pc.cat_title from 0 for 4);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		//		else 
		//		$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutti i cdl abbiano un codice docente esistente
	*/
	function testCdlPuntaPresidente() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM classi_corso 
		WHERE cod_doc NOT IN (SELECT cod_doc FROM docente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutti i cdl abbiano un codice facolt? esistente
	*/
	function testCdlPuntaFacolta() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM classi_corso 
		WHERE cod_fac NOT IN (SELECT cod_fac FROM facolta);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id del collaboratore esista tra gli id_utente
	*/
	function testCollaboratorePuntaIdUtente() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM collaboratore 
		WHERE id_utente NOT IN (SELECT id_utente FROM utente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che groups sia o 4 (moderatore) o 64(admin) (se qualcuno ha smesso di collaborare va ttenuto comunque nel chi siamo)
	*/
	function testCollaboratoriAppartengonoAiGruppiUtentiCollaboratoreOAdmin() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM collaboratore c, utente u 
		WHERE c.id_utente=u.id_utente
		AND u.groups NOT IN (4,64);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutti i docenti siano esistenti
	*/
	function testDocenteContattiPuntaDocente() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM docente_contatti 
		WHERE cod_doc NOT IN (SELECT cod_doc FROM docente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutte le facolt? puntino un canale esistente
	*/
	function testFacoltaPuntaCanale() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM facolta 
		WHERE id_canale NOT IN (SELECT id_canale FROM canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che tutte le facolt? abbiano un codice docente esistente
	*/
	function testFacoltaPuntaDocente() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM facolta 
		WHERE cod_doc NOT IN (SELECT cod_doc FROM docente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che i permessi siano maggiori di 127
	*/
	function testFilePermessiMinori127() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM file 
		WHERE permessi_visualizza>127 OR permessi_download>127;
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id_utente esista
	*/
	function testFileAutorePuntaUtente() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM file
		WHERE id_utente NOT IN (SELECT id_utente FROM utente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id categoria sia esistente 
	*/
	function testFilePuntaCategoria() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM file
		WHERE id_categoria NOT IN (SELECT id_categoria FROM file_categoria);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il tipo di file esista
	*/
	function testFilePuntaTipoFile() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM file
		WHERE id_tipo_file NOT IN (SELECT id_tipo_file FROM file_tipo);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che "eliminato" sia S o N
	*/
	function testFileEliminatoSN() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM file
		WHERE eliminato NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che id_file esista
	*/
	function testFileCanalePuntaFile() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM file_canale
		WHERE id_file NOT IN (SELECT id_file FROM file);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--COntrollo che id_canale esista
	*/
	function testFileCanalePuntaCanale() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM file_canale
		WHERE id_canale NOT IN (SELECT id_canale FROM canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l'id_file esista
	*/
	function testFilePuntaFileKeywords() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM file_keywords
		WHERE id_file NOT IN (SELECT id_file FROM file);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	-- Controlla che le password di sito e forum siano uguali (restituisce tuple solo se trova pwd diverse)
	*/
	function testPasswordUtentiForumUguali() {
		$db = $this->db;

		$query = '
		SELECT user_id, u.username, p.user_password, u.password
		FROM phpbb_users p, utente u
		WHERE user_id=id_utente
		AND p.user_password NOT LIKE u.password
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l'id_help esista
	*/
	function testHelpRiferimentoPuntaHelp() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM help_riferimento
		WHERE id_help NOT IN (SELECT id_help FROM help);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il riferimento esista
	*/
	function testHelpRiferimentoPuntaTopic() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM help_riferimento
		WHERE riferimento NOT IN (SELECT riferimento FROM help_topic);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l'id_canale esista
	*/
	function testInfoDidattica() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM info_didattica
		WHERE id_canale NOT IN (SELECT id_canale FROM canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l'id_utente esista
	*/
	function testNewsPuntaIdUtente() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM news
		WHERE id_utente NOT IN (SELECT id_utente FROM utente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che eliminata sia S o N
	*/
	function testNewsElinimataNS() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM news
		WHERE eliminata NOT IN (\'S\', \'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l'id_news esista
	*/
	function testNewsCanalePuntaNew() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM news_canale
		WHERE id_news NOT IN (SELECT id_news FROM news);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id_canale esista
	*/
	function testNewsCanalePuntaCanale() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM news_canale
		WHERE id_canale NOT IN (SELECT id_canale FROM canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il cod_corso esista
	*/
	function testOrientamentoPuntaCorso() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM orientamenti
		WHERE cod_corso NOT IN (SELECT cod_corso FROM classi_corso);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il cod_ori esista
	*/
	function testOrientamenteoPuntaClassiOrientamento() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM orientamenti
		WHERE cod_ori NOT IN (SELECT cod_ori FROM classi_orientamenti);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il cod_ind esista
	*/
	function testOrientamentoPuntaIndirizzo() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM orientamenti
		WHERE cod_ind NOT IN (SELECT cod_ind FROM classi_indirizzi);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'inoltro email sia N o T o U
	*/
	function testUserInoltroEmailNTU() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM utente
		WHERE inoltro_email NOT IN (\'N\',\'U\',\'T\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che la notifica sia 0 1 o 2
	*/
	function testUserNotificaAllowed() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM utente
		WHERE notifica NOT IN (0,1,2);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che ban sia N o S
	*/
	function testUserBanNS() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM utente
		WHERE ban NOT IN (\'S\',\'N\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che default_style sia black o unibo
	*/
	function testUserStyleAllowed() {
		$db = $this->db;

		$query = '
		SELECT * 
		FROM utente
		WHERE default_style NOT IN (\'black\',\'unibo\');
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'utente esista
	*/
	function testUserExista() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM utente_canale
		WHERE id_utente NOT IN (SELECT id_utente FROM utente);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il canale esista
	*/
	function testCanaleExists() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM utente_canale
		WHERE id_canale NOT IN (SELECT id_canale FROM canale);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}


	/**
	--Controllo che il forum esista
	--PHPBB_AUTH_ACCESS
	--Controllo che il gruppo esista
	*/
	function testPhpbbAccessPuntaGroup() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_auth_access
		WHERE group_id NOT IN (SELECT group_id FROM phpbb_groups);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il forum esista
	*/
	function testCanalePuntaPhpbbForum() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_auth_access
		WHERE forum_id NOT IN (SELECT forum_id FROM phpbb_forums);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_BANLIST
	--Controllo che l\'user esista
	*/
	function testPhpbbBanlistPuntaPhpbbUser() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_banlist
		WHERE ban_userid NOT IN (SELECT user_id FROM phpbb_users);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_FORUM_PRUNE
	--Controllo che il forum esista
	*/
	function testPhpbbForumPuntaPhpbbPrune() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_forum_prune
		WHERE forum_id NOT IN (SELECT forum_id FROM phpbb_forums);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}
	
	/**
	--PHPBB_FORUM 
	--Controllo che il forum non sia scrivibile dagli ospiti
	*/
	function testPhpbbForumGuestReadOnly() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_forums
		WHERE auth_post=0 OR auth_reply=0;
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}
	
	/**
	--PHPBB_FORUMS
	--Controllo che la categoria esista
	*/
	function testPhpbbForumPuntaPhpbbCategory() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_forums
		WHERE cat_id NOT IN (SELECT cat_id FROM phpbb_categories);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_POSTS
	--Controllo che il topic esista
	*/
	function testPhpbbPostPuntaPhpbbTopic() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_posts
		WHERE topic_id NOT IN (SELECT topic_id FROM phpbb_topics);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il forum esista
	*/
	function testPhpbbPostPuntaPhpbbForum() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_posts
		WHERE forum_id NOT IN (SELECT forum_id FROM phpbb_forums);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'user esista
	*/
	function testPhpbbPostPuntaPhpbbAutore() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_posts
		WHERE poster_id NOT IN (SELECT user_id FROM phpbb_users);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_POSTS_TEXT
	--Controllo che il post esista
	*/
	function testPhpbbPostTextPuntaPhpbbPost() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_posts_text
		WHERE post_id NOT IN (SELECT post_id FROM phpbb_posts);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_PRIVMSGS
	--Controllo che l\'id user del mittente esista
	*/
	function testPhpbbPrivMsgPuntaAutore() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_privmsgs
		WHERE privmsgs_from_userid NOT IN (SELECT user_id FROM phpbb_users);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id user del destinatario esista
	*/
	function tesPhpbbPrivMsgPuntaDestinatariot() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_privmsgs
		WHERE privmsgs_to_userid NOT IN (SELECT user_id FROM phpbb_users);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_PROVMSGS_TEXT
	--Controllo che i testi puntino ad un messaggio privato esistente
	*/
	function testPhpbbPrivMsgTextPuntaPhpbbPrivMsg() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_privmsgs_text
		WHERE privmsgs_text_id NOT IN (SELECT privmsgs_id FROM phpbb_privmsgs);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--PHPBB_TOPIC
	--Controllo che punti ad un forum esistente
	*/
	function testPhpbbTopicPuntaPhpbbForum() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_topics
		WHERE forum_id NOT IN (SELECT forum_id FROM phpbb_forums);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che l\'id del poster esista
	*/
	function testPhpbbTopicPuntaPhpbbAutore() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_topics
		WHERE topic_poster NOT IN (SELECT user_id FROM phpbb_users);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}

	/**
	--Controllo che il post iniziale sia esistente
	*/
	function testPhpbbTopicPuntaPhpbbPrimoMsg() {
		$db = $this->db;

		$query = '
		SELECT *
		FROM phpbb_topics
		WHERE topic_first_post_id NOT IN (SELECT post_id FROM phpbb_posts);
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());
	}
	
	/**
	 * controllo consistenza log degli interactivecommand
	 */
	function testConsistenzaLogInteractiveCommand(){
		$db = $this->db;
		$query = '
		select * from step_parametri where id_step not in (select id_step from step_log )
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}

	/**
	 * controllo consistenza utente tra sito e forum
	 */
	function testConsistenzaUtenteSitoForum(){
		$db = $this->db;
		$query = '
		select u.id_utente, u.username as utente_sito, pu.username as utente_forum
from phpbb_users pu, utente u
where u.id_utente=pu.user_id
and u.username!=pu.username
	';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}

	/**
	 * controllo esistenza diutenti appartenenti solo al sito
	 */
	function testEsistenzaIdUtenteSoloSito(){
		$db = $this->db;
		$query = '
		select * from  utente where id_utente not in (select user_id from phpbb_users)
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}

	/**
	 * controllo esistenza diutenti appartenenti solo al sito
	 */
	function testEsistenzaIdUtenteSoloForum(){
		$db = $this->db;
		$query = '
		select * from phpbb_users where user_id not in (select id_utente from utente)
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}
	
	
	/**
	 * controllo esistenza di utenti appartenenti solo al sito
	 */
	function testEsistenzaUsernameUtenteSoloSito(){
		$db = $this->db;
		$query = '
		select * from utente where username not in (select username from phpbb_users)
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}
	
	/**
	 * controllo esistenza di utenti appartenenti solo al forum
	 */
	function testEsistenzaUsernameUtenteSoloForum(){
		$db = $this->db;
		$query = '
		select * from phpbb_users where username not in (select username from utente)
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		else
			$this->assertEquals(0, $result->numRows());	
	}
	
	/**
	 * verifico che i max id coincidano
	 *
	 */
	function testConsistenzaMaxIdUtenteSitoForum()
	{
		$db = $this->db;
		$query = '
		select max(id_utente) from utente 
		';

		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		
		$result->fetchInto($row1);

		$query = '
		select max(id_utente) from utente 
		';
		
		$result = & $db->query($query);
		if (DB :: isError($result))
			$this->fail();
		
		$res->fetchInto($row2);
		
		$this->assertEquals($row1[0], $row2[0]);	
		
	}
}
