<?php

namespace Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\LegacyBundle\Entity\StepParametri
 *
 * @ORM\Table(name="step_parametri")
 * @ORM\Entity
 */
class StepParametri
{
    /**
     * @var integer $idStep
     *
     * @ORM\Column(name="id_step", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string $paramName
     *
     * @ORM\Column(name="param_name", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $paramName;

    /**
     * @var string $callbackName
     *
     * @ORM\Column(name="callback_name", type="string", length=255, nullable=false)
     */
    private $callbackName;

    /**
     * @var string $paramValue
     *
     * @ORM\Column(name="param_value", type="string", length=255, nullable=false)
     */
    private $paramValue;

    /**
     * @param  int                                                                     $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepParametri
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
     * @param  string                                                                  $paramName
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepParametri
     */
    public function setParamName($paramName)
    {
        $this->paramName = $paramName;

        return $this;
    }

    /**
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }

    /**
     * @param  string                                                                  $callbackName
     * @return \Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepParametri
     */
    public function setCallbackName($callbackName)
    {
        $this->callbackName = $callbackName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallbackName()
    {
        return $this->callbackName;
    }

    /**
     * @param string $paramValue
     */
    public function setParamValue($paramValue)
    {
        $this->paramValue = $paramValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getParamValue()
    {
        return $this->paramValue;
    }
}
