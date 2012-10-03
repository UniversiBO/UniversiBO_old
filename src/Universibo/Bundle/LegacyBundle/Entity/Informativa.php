<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class for privacy rules
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GNU General Public License v2 or later
 *
 * @ORM\Table(name="informativa")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\InformativaRepository")
 */
class Informativa
{
    /**
     * @var int
     * @ORM\Column(name="id_informativa", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="informativa_id_informativa_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(name="data_pubblicazione", type="integer", nullable=false)
     */
    protected $dataPubblicazione;

    /**
     * @var int
     * @ORM\Column(name="data_fine", type="integer", nullable=true)
     */
    protected $dataFine;

    /**
     * @var string
     * @ORM\Column(name="testo", type="text", nullable=false)
     */
    protected $testo;

    /**
     * Id getter
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int                                                $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\Informativa
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
     * @param  int                                                $dataFine
     * @return \Universibo\Bundle\LegacyBundle\Entity\Informativa
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
     * @param  string                                             $testo
     * @return \Universibo\Bundle\LegacyBundle\Entity\Informativa
     */
    public function setTesto($testo)
    {
        $this->testo = $testo;

        return $this;
    }
}
