<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Collaboratore class, modella le informazioni relative ai collaboratori
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Collaboratore
{
    /**
     * @var DBCollaboratoreRepository
     */
    private static $repository;

    /**
     * @access private
     */
    public $id_utente;

    /**
     * @access private
     */
    public $intro;

    /**
     * @access private
     */
    public $ruolo;

    /**
     * @access private
     */
    public $recapito;

    /**
     * @var string
     */
    private $obiettivi;

    /**
     * @access private
     */
    public $foto;

    /**
     * @var User
     */
    private $user;

    /**
     * @final
     * @access private
     */
    public $fotoDefault = 'no_foto.png';

    public function __construct($id_utente = 0, $intro = '', $recapito = '', $obiettivi = '', $foto = '', $ruolo = '')
    {
        $this->id_utente	= $id_utente;
        $this->intro		= $intro;
        $this->ruolo		= $ruolo;
        $this->recapito 	= $recapito;
        $this->foto			= $foto;
        $this->obiettivi	= $obiettivi;
    }

    public function getIdUtente()
    {
        return $this->id_utente;
    }

    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    public function getRuolo()
    {
        return $this->ruolo;
    }

    public function setRuolo($ruolo)
    {
        $this->ruolo = $ruolo;
    }

    public function getRecapito()
    {
        return $this->recapito;
    }

    public function setRecapito($recapito)
    {
        $this->recapito = $recapito;
    }

    public function getObiettivi()
    {
        return $this->obiettivi;
    }

    public function setObiettivi($obiettivi)
    {
        $this->obiettivi = $obiettivi;
    }

    public function getFotoFilename()
    {
        return ($this->foto != NULL) ? $this->getIdUtente().'_'.$this->foto : $this->fotoDefault;
    }

    public function setFotoFilename($foto)
    {
        $this->foto = $foto;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Ritorna Preleva tutti i collaboratori dal database
     *
     * @todo non si capisce una mazza
     * @param  int   $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Ritorna un collaboratori dato l'id_utente del database
     *
     * @deprecated
     * @param  int   $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public static function selectCollaboratore($id)
    {
        return self::getRepository()->find($id);
    }

    /**
     * Preleva tutti i collaboratori dal database
     *
     * @deprecated
     * @param  int   $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public static function selectCollaboratoriAll()
    {
        return self::getRepository()->findAll();
    }

    /**
     * Inserisce il profilo di un nuovo collaboratore sul DB
     *
     * @deprecated
     * @return boolean true se avvenua con successo, altrimenti Error object
     */
    public static function insertCollaboratoreItem()
    {

        return self::getRepository()->insert($this);
    }

    /**
     * @return DBCollaboratoreRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.collaboratore');
        }

        return self::$repository;
    }
}
