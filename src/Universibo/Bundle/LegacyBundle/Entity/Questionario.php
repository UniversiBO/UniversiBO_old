<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\LegacyBundle\Entity\Questionario
 *
 * @ORM\Table(name="questionario")
 * @ORM\Entity
 */
class Questionario
{
    /**
     * @var integer $idQuestionario
     *
     * @ORM\Column(name="id_questionario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="questionario_id_questionari_seq", allocationSize="1", initialValue="1")
     */
    private $id;

    /**
     * @var integer $data
     *
     * @ORM\Column(name="data", type="integer", nullable=false)
     */
    private $data;

    /**
     * @var string $nome
     *
     * @ORM\Column(name="nome", type="string", length=50, nullable=false)
     */
    private $nome;

    /**
     * @var string $cognome
     *
     * @ORM\Column(name="cognome", type="string", length=50, nullable=false)
     */
    private $cognome;

    /**
     * @var string $mail
     *
     * @ORM\Column(name="mail", type="string", length=50, nullable=false)
     */
    private $mail;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=false)
     */
    private $telefono;

    /**
     * @var int $tempoDisp
     *
     * @ORM\Column(name="tempo_disp", type="smallint", nullable=false)
     */
    private $tempoDisponibile;

    /**
     * @var int $tempoInternet
     *
     * @ORM\Column(name="tempo_internet", type="smallint", nullable=false)
     */
    private $tempoInternet;

    /**
     * @var string $attivOffline
     *
     * @ORM\Column(name="attiv_offline", type="string", nullable=false)
     */
    private $attivitaOffline;

    /**
     * @var string $attivModeratore
     *
     * @ORM\Column(name="attiv_moderatore", type="string", nullable=false)
     */
    private $attivitaModeratore;

    /**
     * @var string $attivContenuti
     *
     * @ORM\Column(name="attiv_contenuti", type="string", nullable=false)
     */
    private $attivitaContenuti;

    /**
     * @var string $attivTest
     *
     * @ORM\Column(name="attiv_test", type="string", nullable=false)
     */
    private $attivitaTest;

    /**
     * @var string $attivGrafica
     *
     * @ORM\Column(name="attiv_grafica", type="string", nullable=false)
     */
    private $attivitaGrafica;

    /**
     * @var string $attivitaProgettazione
     *
     * @ORM\Column(name="attiv_prog", type="string", nullable=false)
     */
    private $attivitaProgettazione;

    /**
     * @var string $altro
     *
     * @ORM\Column(name="altro", type="text", nullable=false)
     */
    private $altro;

    /**
     * @var integer $idUtente
     *
     * @ORM\Column(name="id_utente", type="integer", nullable=false)
     */
    private $idUtente;

    /**
     * @var string $cdl
     *
     * @ORM\Column(name="cdl", type="string", length=50, nullable=false)
     */
    private $cdl;

    /**
     * @param  int                                                 $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int                                                 $data
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return number
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  string                                              $nome
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param  string                                              $cognome
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;

        return $this;
    }

    /**
     * @return string
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * @param  string                                              $mail
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param  string                                              $telefono
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param  int                                                 $tempoDisponibile
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setTempoDisponibile($tempoDisponibile)
    {
        $this->tempoDisponibile = $tempoDisponibile;

        return $this;
    }

    /**
     * @return int
     */
    public function getTempoDisponibile()
    {
        return $this->tempoDisponibile;
    }

    /**
     * @param  int                                                 $tempoInternet
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setTempoInternet($tempoInternet)
    {
        $this->tempoInternet = $tempoInternet;

        return $this;
    }

    /**
     * @return int
     */
    public function getTempoInternet()
    {
        return $this->tempoInternet;
    }

    /**
     * @param  string                                              $attivitaOffline
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaOffline($attivitaOffline)
    {
        $this->attivitaOffline = $attivitaOffline;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaOffline()
    {
        return $this->attivitaOffline;
    }

    /**
     * @param  string                                              $attivitaModeratore
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaModeratore($attivitaModeratore)
    {
        $this->attivitaModeratore = $attivitaModeratore;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaModeratore()
    {
        return $this->attivitaModeratore;
    }

    /**
     * @param  string                                              $attivitaContenuti
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaContenuti($attivitaContenuti)
    {
        $this->attivitaContenuti = $attivitaContenuti;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaContenuti()
    {
        return $this->attivitaContenuti;
    }

    /**
     * @param  string                                              $attivitaTest
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaTest($attivitaTest)
    {
        $this->attivitaTest = $attivitaTest;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaTest()
    {
        return $this->attivitaTest;
    }

    /**
     * @param  string                                              $attivitaGrafica
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaGrafica($attivitaGrafica)
    {
        $this->attivitaGrafica = $attivitaGrafica;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaGrafica()
    {
        return $this->attivitaGrafica;
    }

    /**
     * @param  string                                              $attivitaProgettazione
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAttivitaProgettazione($attivitaProgettazione)
    {
        $this->attivitaProgettazione = $attivitaProgettazione;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttivitaProgettazione()
    {
        return $this->attivitaProgettazione;
    }

    /**
     * @param  string                                              $altro
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setAltro($altro)
    {
        $this->altro = $altro;

        return $this;
    }

    /**
     * @return string
     */
    public function getAltro()
    {
        return $this->altro;
    }

    /**
     * @param  int                                                 $idUtente
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setIdUtente($idUtente)
    {
        $this->idUtente = $idUtente;

        return $this;
    }

    /**
     * @return number
     */
    public function getIdUtente()
    {
        return $this->idUtente;
    }

    /**
     * @param  string                                              $cdl
     * @return \Universibo\Bundle\LegacyBundle\Entity\Questionario
     */
    public function setCdl($cdl)
    {
        $this->cdl = $cdl;

        return $this;
    }

    /**
     * @return string
     */
    public function getCdl()
    {
        return $this->cdl;
    }
}
