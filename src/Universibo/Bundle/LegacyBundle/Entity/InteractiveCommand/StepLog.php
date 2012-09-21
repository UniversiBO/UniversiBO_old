<?php

namespace Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\LegacyBundle\Entity\StepLog
 *
 * @ORM\Table(name="step_log")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLogRepository")
 */
class StepLog
{
    /**
     * @var integer $idStep
     *
     * @ORM\Column(name="id_step", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="step_id_step_seq", allocationSize=1, initialValue="1")
     */
    private $id;

    /**
     * @var integer $idUtente
     *
     * @ORM\Column(name="id_utente", type="integer", nullable=false)
     */
    private $idUtente;

    /**
     * @var integer $dataUltimaInterazione
     *
     * @ORM\Column(name="data_ultima_interazione", type="integer", nullable=false)
     */
    private $dataUltimaInterazione;

    /**
     * @var string $nomeClasse
     *
     * @ORM\Column(name="nome_classe", type="string", length=255, nullable=false)
     */
    private $nomeClasse;

    /**
     * @var string $esitoPositivo
     *
     * @ORM\Column(name="esito_positivo", type="string", nullable=true)
     */
    private $esitoPositivo;

    /**
     * @param  int                                                               $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog
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
     * @param int $idUtente
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
     * @param  int                                                               $dataUltimaInterazione
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog
     */
    public function setDataUltimaInterazione($dataUltimaInterazione)
    {
        $this->dataUltimaInterazione = $dataUltimaInterazione;

        return $this;
    }

    /**
     * @return number
     */
    public function getDataUltimaInterazione()
    {
        return $this->dataUltimaInterazione;
    }

    /**
     * @param  string                                                            $nomeClasse
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog
     */
    public function setNomeClasse($nomeClasse)
    {
        $this->nomeClasse = $nomeClasse;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomeClasse()
    {
        return $this->nomeClasse;
    }

    /**
     * @param  string                                                            $esitoPositivo
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog
     */
    public function setEsitoPositivo($esitoPositivo)
    {
        $this->esitoPositivo = $esitoPositivo;

        return $this;
    }

    /**
     * @return string
     */
    public function getEsitoPositivo()
    {
        return $this->esitoPositivo;
    }
}
