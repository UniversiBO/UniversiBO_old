<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Collaboratore class, modella le informazioni relative ai collaboratori
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2012
 * 
 * @ORM\Table(name="collaboratore")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\CollaboratoreRepository") 
 */
class Collaboratore
{
    /**
     * @var DBCollaboratoreRepository
     */
    private static $repository;

    /**
     * @var string
     * @ORM\Column(name="intro", type="text", nullable=true) 
     */
    private $intro;

    /**
     * @var string
     * @ORM\Column(name="ruolo", type="string", length=255, nullable=true)
     */
    private $ruolo;

    /**
     * @var string
     *
     * @ORM\Column(name="recapito", type="string", length=255, nullable=true)
     */
    private $recapito;

    /**
     * @var string
     * @ORM\Column(name="obiettivi", type="text", nullable=true)
     */
    private $obiettivi;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @var User
     * 
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_utente", referencedColumnName="id_utente")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="show", type="string", length=1, nullable=false)
     */
    private $show = 'N';

    /**
     * @final
     * @access private
     */
    private $fotoDefault = 'no_foto.png';

    public function __construct($intro = '', $recapito = '', $obiettivi = '', $foto = '', $ruolo = '')
    {
        $this->intro		= $intro;
        $this->ruolo		= $ruolo;
        $this->recapito 	= $recapito;
        $this->foto			= $foto;
        $this->obiettivi	= $obiettivi;
    }

    public function getIdUtente()
    {
        return $this->getUser()->getIdUser();
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function setIntro($intro)
    {
        $this->intro = $intro;
        
        return $this;
    }

    public function getRuolo()
    {
        return $this->ruolo;
    }

    public function setRuolo($ruolo)
    {
        $this->ruolo = $ruolo;
        
        return $this;
    }

    public function getRecapito()
    {
        return $this->recapito;
    }

    public function setRecapito($recapito)
    {
        $this->recapito = $recapito;
        
        return $this;
    }

    public function getObiettivi()
    {
        return $this->obiettivi;
    }

    public function setObiettivi($obiettivi)
    {
        $this->obiettivi = $obiettivi;
        
        return $this;
    }

    public function getFotoFilename()
    {
        return ($this->foto != NULL) ? $this->getIdUtente().'_'.$this->foto : $this->fotoDefault;
    }

    public function setFotoFilename($foto)
    {
        $this->foto = $foto;
        
        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        
        return $this;
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
    
    public function getShow()
    {
        return $this->show;
    }
    
    public function setShow($show)
    {
        $this->show = $show;
        
        return $this;
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
