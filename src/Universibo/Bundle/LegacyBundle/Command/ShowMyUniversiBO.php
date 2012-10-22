<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowMyUniversiBO is an extension of UniversiboCommand class.
 *
 * Mostra la MyUniversiBO dell'utente loggato, con le ultime 5 notizie e
 * gli ultimi 5 files presenti nei canali da lui aggiunti...
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowMyUniversiBO extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $utente = $this->get('security.context')->getToken()->getUser();
        $router = $this->get('router');

        $channelRepo = $this->getContainer()->get('universibo_legacy.repository.canale');

        //procedure per ricavare e mostrare le ultime 5 notizie dei canali a cui si ? iscritto...

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => 0, 'msg' => 'Non esiste una pagina MyUniversiBO per utenti ospite.
                                                                                                     Se sei uno studente registrati cliccando su Registrazione Studenti nel menu di destra.
                                                                                                     La sessione potrebbe essere scaduta verifica di aver abilitato i cookie.',
                            'file' => __FILE__, 'line' => __LINE__));

        $arrayIdCanaliNews = array();
        $arrayIdCanaliFiles = array();
        $arrayCanali = array();

        $ruoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($utente->getId());

        foreach ($ruoli as $key => $ruolo) {
            if ($ruolo->isMyUniversibo()) {

                $canale = $channelRepo->find($ruolo->getIdCanale());
                $arrayCanali[] = $key;
                if ($canale->getServizioNews()) {
                    $id_canale = $canale->getIdCanale();
                    $arrayIdCanaliNews[] = $id_canale;
                }
                if ($canale->getServizioFiles()) {
                    $id_canale = $canale->getIdCanale();
                    $arrayIdCanaliFiles[] = $id_canale;
                }
            }
        }

        $arrayNewsItems = $this->getLatestNewsCanale(5, $arrayIdCanaliNews);

        $this
                ->executePlugin('ShowMyNews',
                        array('id_notizie' => $arrayNewsItems,
                                'chk_diritti' => false));

        $arrayFilesItems = $this->getLatestFileCanale(5, $arrayIdCanaliFiles);

        $this
                ->executePlugin('ShowMyFileTitoli',
                        array('files' => $arrayFilesItems, 'chk_diritti' => false));

        $template->assign('showMyScheda',$router->generate('universibo_legacy_user', array('id_utente' => $utente->getId())));
    }

    /**
     * Preleva da database il numero di files del canale $id_canale
     *
     * @static
     * @param  int $id_canale identificativo su database del canale
     * @return int $res numero files
     */

    public function getNumFilesCanale($id_canale)
    {
        return $this->getContainer()->get('universibo_legacy.repository.files.file_item')->countByChannel($id_canale);
    }

    /**
     * Preleva da database gli ultimi $num files del canale $id_canale
     *
     * @deprecated
     * @param  int   $num       numero files da prelevare
     * @param  int   $id_canale identificativo su database del canale
     * @return array elenco FileItem , false se non ci sono notizie
     */

    public function getLatestFileCanale($num, array $id_canali)
    {
        return $this->getContainer()->get('universibo_legacy.repository.files.file_item')->findLatestByChannels($id_canali, $num);
    }

    /**
     * Preleva da database le ultime $num notizie non scadute dai canali $id_canali
     *
     * @deprecated
     * @param  int   $num       numero notize da prelevare
     * @param  array $id_canali contenente gli id_canali, identificativi su database del canale
     * @return array elenco NewsItem , false se non ci sono notizie
     */

    public function getLatestNewsCanale($num, array $id_canali)
    {
        return $this->getContainer()->get('universibo_legacy.repository.news.news_item')->findLatestByChannels($id_canali, $num);
    }

}
