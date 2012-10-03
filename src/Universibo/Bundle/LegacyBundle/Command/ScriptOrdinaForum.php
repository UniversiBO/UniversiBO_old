<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

//NB: NON ASTRAE DAL LIVELLO DATABASE, PUO' VALER LA PENA SPOSTARE TUTTA LA FUNZIONE DENTRO ForumApi?!?!?!

/**
 * ScriptOrdinaForum is an extension of UniversiboCommand class.
 *
 * Si occupa di ordinare i forum di phpbb
 * NON ASTRAE DAL LIVELLO DATABASE, PUO' VALER LA PENA SPOSTARE TUTTA LA FUNZIONE DENTRO ForumApi?!?!?!
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptOrdinaForum extends UniversiboCommand
{

    //NB: NON ASTRAE DAL LIVELLO DATABASE, PUO' VALER LA PENA SPOSTARE TUTTA LA FUNZIONE DENTRO ForumApi?!?!?!

    public function execute()
    {

        //NB: NON ASTRAE DAL LIVELLO DATABASE, PUO' VALER LA PENA SPOSTARE TUTTA LA FUNZIONE DENTRO ForumApi?!?!?!
        $fc = $this->getFrontController();
        $db = $fc->getDbConnection('main');

        $query = 'begin';
        $res = $db->query($query);
        if (DB::isError($res)) die($query);

/*
        $forum = $this->getContainer()->get('universibo_legacy.forum.api');
        $max_forum_id = $forum->getMaxForumId();

        echo 'max_forum_id: ', $max_forum_id, "\n";
*/
        $cdlAll = Cdl::selectCdlAll();
        //var_dump($cdlAll);

        foreach ($cdlAll as $cdl) {
            echo $cdl->getCodiceCdl(),' - ', $cdl->getTitolo(),"\n";

            // creo categoria
            if (! $cdl->getForumCatId()=='') {
                $cat_id = $cdl->getForumCatId();

                //NB: NON ASTRAE DAL LIVELLO DATABASE, PUO' VALER LA PENA SPOSTARE TUTTA LA FUNZIONE DENTRO ForumApi?!?!?!
                $query = 'SELECT forum_id
                            FROM phpbb_forums
                            WHERE cat_id = '.$db->quote($cat_id).'
                            ORDER BY forum_name';

                $res = $db->query($query);

                $order = 0;
                //var_dump($res);
                while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
                    $order++;
                    $query = 'UPDATE phpbb_forums SET forum_order = '.$order.' WHERE forum_id = '.$row[0].';';
                    $res2 = $db->query($query);
                        if (DB::isError($res2)) die($query);
                }
            }
        }

        $query = 'commit';
        $res = $db->query($query);
        if (DB::isError($res)) die($query);
    }
}
