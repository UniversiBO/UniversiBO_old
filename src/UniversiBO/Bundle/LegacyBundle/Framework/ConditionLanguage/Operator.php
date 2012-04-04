<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class Operator
{
	// TODO NB documentare i valori che ho scelto per l'associatività
	const LEFT_ASSOCIATION = 0;
	const RIGHT_ASSOCIATION = 1;


	private $nome;
	private $nIn;
	private $inputFormat;
	private $nOut;
	private $outputFormat;
	private $priorita;
	private $associativita;
	private $executor;
	private $codice;
	/**
	 * riferimento al nodo AST corrispondente all'operazione
	 *
	 * @var unknown_type
	 */
	private $referrer;

	function __construct(
			ParamListFormat $inputFormat,
			ParamListFormat $outputFormat,
			$priorita,
			$associativita,
			$executor,
			$codice,
			$nome,
			& $ref
	)
	{
		$this->nIn = $inputFormat->getParamNum();
		$this->inputFormat = $inputFormat;
		$this->nOut = $outputFormat->getParamNum();
		$this->outputFormat = $outputFormat;
		$this->priorita = $priorita;
		$this->associativita = $associativita;
		$this->executor = $executor;
		$this->codice = $codice;
		$this->nome = strtolower($nome);
		$this->referrer = $ref;
	}

	public function __get($nomeVar)
	{
		if(isset($this->$nomeVar)) return $this->$nomeVar;
	}

	public function getId()
	{
		return md5($this->nome);
	}

		
	public function isMajorPriorityTo(Operator $o)
	{
		return $this->priorita > $o->priorita;
	}

	public function isEqualPriorityTo(Operator $o)
	{
		return $this->priorita == $o->priorita;
	}

	public function isBinary()
	{
	return $this->nIn == 2;
	}

	public function isLeftAssociative()
	{
		return $this->associativita == self::LEFT_ASSOCIATION;
	}


	public function isRightAssociative()
	{
		return $this->associativita == self::RIGHT_ASSOCIATION;
	}

	static public function translateNameToId($name)
	{
		return  md5($name);
	}

	public function toString()
	{
		return $this->__toString();
	}

	public function __toString()
	{
		$s = 'key : '.self::translateNameToId($this->nome).' ; ';
		foreach($this as $name => $property)
			if($name != 'referrer')
			$s .= $name.' : '.$property.' || ';
		return $s;
	}
}
