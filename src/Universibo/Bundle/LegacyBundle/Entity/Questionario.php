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
     * @ORM\SequenceGenerator(sequenceName="questionario_id_questionario_seq", allocationSize="1", initialValue="1")
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
     * @var smallint $tempoDisp
     *
     * @ORM\Column(name="tempo_disp", type="smallint", nullable=false)
     */
    private $tempoDisp;

    /**
     * @var smallint $tempoInternet
     *
     * @ORM\Column(name="tempo_internet", type="smallint", nullable=false)
     */
    private $tempoInternet;

    /**
     * @var string $attivOffline
     *
     * @ORM\Column(name="attiv_offline", type="string", nullable=false)
     */
    private $attivOffline;

    /**
     * @var string $attivModeratore
     *
     * @ORM\Column(name="attiv_moderatore", type="string", nullable=false)
     */
    private $attivModeratore;

    /**
     * @var string $attivContenuti
     *
     * @ORM\Column(name="attiv_contenuti", type="string", nullable=false)
     */
    private $attivContenuti;

    /**
     * @var string $attivTest
     *
     * @ORM\Column(name="attiv_test", type="string", nullable=false)
     */
    private $attivTest;

    /**
     * @var string $attivGrafica
     *
     * @ORM\Column(name="attiv_grafica", type="string", nullable=false)
     */
    private $attivGrafica;

    /**
     * @var string $attivProg
     *
     * @ORM\Column(name="attiv_prog", type="string", nullable=false)
     */
    private $attivProg;

    /**
     * @var text $altro
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
     * @param int $id
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
}