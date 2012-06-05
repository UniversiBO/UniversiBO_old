<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use \DB;
/**
 * suite di test esegue query di controllo per il Database
 * @author davide
 *
 */
class DBConsistenceTest extends DBRepositoryTest
{
    public function setUp()
    {
        if (!defined('DB_TESTING') || !DB_TESTING) {
            $this->markTestSkipped();
        }
        parent::setUp();
    }

    /**
    --Controlla che tutti i canali siano puntati da un forum
    */
    public function testCanalePuntaForum()
    {
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
    public function testCanalePuntaGruppoForum()
    {
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
    public function testCanaleFileAttivoSN()
    {
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
    public function testCanaleNewsAttivoSN()
    {
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
    public function testCanaleForumAttivoSN()
    {
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
    public function testCanaleLinksAttivoSN()
    {
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
    public function testCanalePermessiMinore127()
    {
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
    public function testCdlPuntaCanale()
    {
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
    public function testCdlPuntatoDaCanaleDiTipo4()
    {
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
    public function testCdlPuntaCategoriaForum()
    {
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
    public function testCdlPuntaCategoriaForumConCodiceNelTitolo()
    {
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
    public function testCdlPuntaPresidente()
    {
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
    public function testCdlPuntaFacolta()
    {
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
    public function testCollaboratorePuntaIdUtente()
    {
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
    public function testCollaboratoriAppartengonoAiGruppiUtentiCollaboratoreOAdmin()
    {
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
    public function testDocenteContattiPuntaDocente()
    {
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
    public function testFacoltaPuntaCanale()
    {
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
    public function testFacoltaPuntaDocente()
    {
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
    public function testFilePermessiMinori127()
    {
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
    public function testFileAutorePuntaUtente()
    {
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
    public function testFilePuntaCategoria()
    {
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
    public function testFilePuntaTipoFile()
    {
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
    public function testFileEliminatoSN()
    {
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
    public function testFileCanalePuntaFile()
    {
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
    public function testFileCanalePuntaCanale()
    {
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
    public function testFilePuntaFileKeywords()
    {
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
    public function testPasswordUtentiForumUguali()
    {
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
    public function testHelpRiferimentoPuntaHelp()
    {
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
    public function testHelpRiferimentoPuntaTopic()
    {
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
    public function testInfoDidattica()
    {
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
    public function testNewsPuntaIdUtente()
    {
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
    public function testNewsElinimataNS()
    {
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
    public function testNewsCanalePuntaNew()
    {
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
    public function testNewsCanalePuntaCanale()
    {
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
    public function testOrientamentoPuntaCorso()
    {
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
    public function testOrientamenteoPuntaClassiOrientamento()
    {
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
    public function testOrientamentoPuntaIndirizzo()
    {
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
    public function testUserInoltroEmailNTU()
    {
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
    public function testUserNotificaAllowed()
    {
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
    public function testUserBanNS()
    {
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
    public function testUserStyleAllowed()
    {
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
    public function testUserExista()
    {
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
    public function testCanaleExists()
    {
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
    public function testPhpbbAccessPuntaGroup()
    {
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
    public function testCanalePuntaPhpbbForum()
    {
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
    public function testPhpbbBanlistPuntaPhpbbUser()
    {
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
    public function testPhpbbForumPuntaPhpbbPrune()
    {
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
    public function testPhpbbForumGuestReadOnly()
    {
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
    public function testPhpbbForumPuntaPhpbbCategory()
    {
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
    public function testPhpbbPostPuntaPhpbbTopic()
    {
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
    public function testPhpbbPostPuntaPhpbbForum()
    {
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
    public function testPhpbbPostPuntaPhpbbAutore()
    {
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
    public function testPhpbbPostTextPuntaPhpbbPost()
    {
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
    public function testPhpbbPrivMsgPuntaAutore()
    {
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
    public function tesPhpbbPrivMsgPuntaDestinatariot()
    {
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
    public function testPhpbbPrivMsgTextPuntaPhpbbPrivMsg()
    {
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
    public function testPhpbbTopicPuntaPhpbbForum()
    {
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
    public function testPhpbbTopicPuntaPhpbbAutore()
    {
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
    public function testPhpbbTopicPuntaPhpbbPrimoMsg()
    {
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
    public function testConsistenzaLogInteractiveCommand()
    {
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
    public function testConsistenzaUtenteSitoForum()
    {
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
    public function testEsistenzaIdUtenteSoloSito()
    {
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
    public function testEsistenzaIdUtenteSoloForum()
    {
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
    public function testEsistenzaUsernameUtenteSoloSito()
    {
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
    public function testEsistenzaUsernameUtenteSoloForum()
    {
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
    public function testConsistenzaMaxIdUtenteSitoForum()
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
