<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Collaborator class, modella le informazioni relative ai collaboratori
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2012
 *
 * @ORM\Table(name="collaborators")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\CollaboratorRepository")
 */
class Collaborator
{
    /**
     * @var string
     * @ORM\Column(name="intro", type="text", nullable=true)
     */
    private $intro;

    /**
     * @var string
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @var string
     * @ORM\Column(name="goals", type="text", nullable=true)
     */
    private $goals;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Universibo\Bundle\CoreBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="show", type="boolean", nullable=false)
     */
    private $show = false;

    public function getIntro()
    {
        return $this->intro;
    }

    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    public function getGoals()
    {
        return $this->goals;
    }

    public function setGoals($goals)
    {
        $this->goals = $goals;

        return $this;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;

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
}
