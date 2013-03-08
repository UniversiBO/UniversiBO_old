<?php
/**
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2012
 */
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Collaborator entity
 * @ORM\Table(name="collaboratore")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\CollaboratoreRepository")
 */
class Collaboratore
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="collaboratore_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(name="intro", type="text", nullable=true)
     * @var string
     */
    private $intro;

    /**
     * @ORM\Column(name="ruolo", type="string", length=255, nullable=true)
     * @var string
     */
    private $ruolo;

    /**
     * @ORM\Column(name="recapito", type="string", length=255, nullable=true)
     * @var string
     */
    private $recapito;

    /**
     * @ORM\Column(name="obiettivi", type="text", nullable=true)
     * @var string
     */
    private $obiettivi;

     /**
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     * @var string
     */
    private $foto;

    /**
     * @ORM\Column(name="show", type="string", length=1, nullable=false)
     * @var string
     */
    private $show = 'N';

    /**
     * @ORM\OneToOne(targetEntity="Universibo\Bundle\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="id_utente", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $fotoDefault = 'no_foto.png';

    public function getId()
    {
        return $this->id;
    }

    public function getIdUtente()
    {
        return $this->user !== null ? $this->getUser()->getId() : null;
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
        if (empty($this->foto)) {
            $this->foto = $this->fotoDefault;
        }

        $prefix = $this->foto === $this->fotoDefault ? '' : $this->getIdUtente().'_';

        return $prefix.$this->foto;
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

    /**
     * Show getter
     *
     * @return boolean
     */
    public function getShow()
    {
        return $this->show === 'Y';
    }

    /**
     * Set if the collaborator is shown or not
     *
     * @param  boolean       $show
     * @return Collaboratore
     */
    public function setShow($show)
    {
        $this->show = $show ? 'Y' : 'N';

        return $this;
    }
}
