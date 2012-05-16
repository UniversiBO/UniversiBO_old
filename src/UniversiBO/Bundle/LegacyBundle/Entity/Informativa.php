<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

/**
 * Class for privacy rules
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GNU General Public License v2 or later
 */
class Informativa
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $dataPubblicazione;

    /**
     * @var int
     */
    private $dataFine;

    /**
     * @var string
     */
    private $testo;

    /**
     * Id getter
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return \UniversiBO\Bundle\LegacyBundle\Entity\Informativa
     */
    public function setId($id)
    {
        $this->id = $id;


        return $this;
    }

    /**
     * @return number
     */
    public function getDataPubblicazione()
    {
        return $this->dataPubblicazione;
    }

    /**
     * @param int $dataPubblicazione
     */
    public function setDataPubblicazione($dataPubblicazione)
    {
        $this->dataPubblicazione = $dataPubblicazione;


        return $this;
    }

    /**
     * @return number
     */
    public function getDataFine()
    {
        return $this->dataFine;
    }

    /**
     * @param int $dataFine
     * @return \UniversiBO\Bundle\LegacyBundle\Entity\Informativa
     */
    public function setDataFine($dataFine)
    {
        $this->dataFine = $dataFine;


        return $this;
    }

    /**
     * @return string
     */
    public function getTesto()
    {
        return $this->testo;
    }

    /**
     * @param string $testo
     * @return \UniversiBO\Bundle\LegacyBundle\Entity\Informativa
     */
    public function setTesto($testo)
    {
        $this->testo = $testo;


        return $this;
    }
}
