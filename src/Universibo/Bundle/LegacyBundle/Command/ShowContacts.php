<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\Entity\Collaboratore;

use Universibo\Bundle\LegacyBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowContacts is an extension of UniversiboCommand class.
 *
 * It shows Contacts page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowContacts extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->getSessionUser();

        $template->assign('contacts_langAltTitle', 'Chi Siamo');

        $contacts_path = $this->frontController->getAppSetting('contactsPath');
        $template->assign('contacts_path', $contacts_path);

        $infoCollaboratori = User::selectAllCollaboratori();
        foreach ($infoCollaboratori as $collaboratore) {
            $username = User::getUsernameFromId($collaboratore->getIdUser());
            $coll = Collaboratore::selectCollaboratore(
                    $collaboratore->getIdUser());
            if (!$coll) {
                $name = $user->getUsername();
                if ($name == $username)
                    $collaboratori[] = array('username' => $username,
                            'URI' => 'false',
                            'inserisci' => 'v2.php?do=CollaboratoreProfiloAdd&id_coll='
                                    . $user->getIdUser());
                else
                    $collaboratori[] = array('username' => $username,
                            'URI' => 'false', 'inserisci' => 'false');
            } else
                $collaboratori[] = array('username' => $username,
                        'URI' => 'v2.php?do=ShowCollaboratore&id_coll='
                                . $collaboratore->getIdUser(),
                        'inserisci' => 'false');

        }

        $num_collaboratori = count($collaboratori);

        uasort($collaboratori, function ($a, $b) {
            return strcasecmp($a['username'], $b['username']);
        });
        $template->assign('contacts_langPersonal', $collaboratori);

        $template
                ->assignUnicode('contacts_langIntro',
                        'UniversiBO è l\'associazione studentesca universitaria dell\'Ateneo di Bologna che dal settembre 2004 supporta la Web Community degli studenti.

Attraverso l\'utilizzo di tecnologie OpenSource, UniversiBO si impegna a estendere i confini delle aule delle Facoltà ponendosi come innovativo luogo d\'incontro virtuale. Grazie alla diffusione e alla condivisione di "informazione", si propone infatti di incentivare gli studenti a partecipare attivamente alla vita universitaria. Desidera inoltre porsi come punto di collegamento tra il corpo docente e il mondo studentesco. Nel contempo promuove e favorisce l\'informatizzazione e la filosofia del Software Libero per l\'Università di Bologna.

Tutte le richieste di aiuto ed informazioni possono essere rivolte all\'indirizzo info_universibo@mama.ing.unibo.it

UniversiBO nasce nel 2002 dall\'idea di tre studenti. Al momento attuale lo Staff è composto invece da circa '
                                . $num_collaboratori
                                . ' collaboratori, quasi tutti studenti dell\' Ateneo bolognese.

Qui di seguito si presentano divisi per ruoli nel caso vogliate contattarli nello specifico per ogni vostra esigenza.');

        return 'default';
    }
}
