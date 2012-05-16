<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class ParamListFormat
{
    private $list;
    private $reverseLookup;

    public function __construct()
    {
        $this->list = array();
        $this->reverseLookup = array();
    }

    /**
     * Aggiunge un parametro alla lista
     *
     * @param string $name
     * @param boolean $isArray
     * @param integer $position
     * @return boolean indica l'avvenuto inserimento
     */
    public function addParam($name, $isStruct = false, $isGrouped = false, $nestedList = null)
    {
        if(array_key_exists($name, $this->list)) return false;
        $this->list[$name] = array('nome' => $name, 'struct' => $isStruct, 'grouped' => $isGrouped, 'structDesc' => $nestedList);
        $this->reverseLookup[$name] = count($this->list) - 1;

        return true;
    }

    public function getIndex($key)
    {
        if(!array_key_exists($key, $this->reverseLookup)) return false;

        return $this->reverseLookup[$key];
    }

    public function isGrouped($nome)
    {
        return $this->list[$nome]['grouped'];
    }

    public function parseElemString($s)
    {
        $s = substr($s,0,strlen($s)-1);
        list($pos, $nome, $isStruct) = explode(':',$s);
        $ret = array();
        $ret[$pos] = array('nome' => $nome, 'struct' => $isStruct);

        return $ret;
    }

    /**
     * ritorna un array con le chiavi degli output presenti
     *
     * @return array
     */
    public function getMask()
    {
        return array_keys($this->list);
    }

    /**
     * controlla che la lista di parametri passati corrisponda al formato settato
     *
     * @param array $argumentList
     * @return boolean
     */
    public function checkFormatByPosition($argumentList)
    {
        $tot = count($this->list);
        if (!is_array($argumentList)) return false;

        for ($i=0; $i < $tot; $i++)
            if($this->list[$i]['struct'])
            {
                if(!is_array($argumentList[$i])) return false;
                if ($this->list[$i]['structDesc'] != null)
                    foreach($argumentList[$i] as $k)
                    if(!$this->list[$i]['structDesc']->checkFormatByPosition($k)) return false;
            }
            else if(is_array($argumentList[$i])) return false;


            return true;
    }

    /**
     * controlla che la lista di parametri passati corrisponda al formato settato
     *
     * @param array $argumentList array associativo nomeParametro => valoreParametro
     * @return boolean
     */
    public function checkFormatByName($argumentList)
    {
        //var_dump($argumentList); var_dump($this->list);die;
        if (!is_array($argumentList)) return false;

        foreach ($argumentList as $name => $value)
        {
            if(!array_key_exists($name,$this->list)) return false;
            if($this->list[$name]['struct'])
            {
                if(!is_array($value)) return false;
                if ($this->list[$i]['structDesc'] != null)
                    foreach($value as $k)
                    if(!$this->list[$name]['structDesc']->checkFormatByPosition($k)) return false;
            }
            else if(is_array($value)) return false;
        }

        return true;
    }

    /**
     * restituisce true se l'istanza corrente contiene tutti gli elementi di $f
     *
     * @param ParamListFormat $f
     * @return boolean
     */
    private function isSupersetOf(ParamListFormat $f)
    {
        $keys = $f->getMask();
        foreach($keys as $k)
            if(!array_key_exists($k,$this->list)) return false;


        return true;
    }

    private function getChilds($associative = false)
    {
        $childs = array();
        if($associative)
        {
            foreach($this->list as $i)
                if($i['struct'])
                $childs[$i['nome'] ] = $i['structDesc'];
        }
        else
        {
            foreach($this->list as $i)

                if($i['struct'])
                $childs[$this->reverseLookup[$i['nome']]] = $i['structDesc'];
        }

        return $childs;
    }


    /**
     * filtra l'output dell'istanza attuale in accordo al formato desiderato
     *
     * @param array           $values valori di output NB suppongo chiavi numeriche
     * @param ParamListFormat $filter formato desiderato in uscita
     * @param ParamListFormat $output formato dell'output
     * @return array	output filtrato
     */
    static public function filterValues($values, ParamListFormat $filter, ParamListFormat $output)
    {
        // VERIFY lo metto qui il check su $values o suppongo che chi lo passi lo abbia gi� verificato?
        // � una esplorazione depthfirst.. va bene? direi di s�, perch� tanto i livelli sono per forza limitati
        // TODO cos� non va bene... perch� nel caso di array con formato potrei aver ridotto i parametri cui sono interessato...
        // TODO se io ho un parametro grouped, � impossibile pensare che abbia mantenuto il nome del param come chiave!?!? ma forse se � grouped, vuol dire che � un array di val associati ad una certa chiave
        //		echo "\n[FILTRO] $filter $output $values \n";
        //		var_dump($values);
        if(!$output->isSupersetOf($filter))
        {
            $rami=$output->getChilds();
            //			var_dump($rami); die();
            foreach($rami as $k => $v)
            {
                $ret = self::filterValues($values[$k],$filter,$v);
                if($ret !== false) return $ret;
            }

            return false; // se son qui vuol dire che nessun ramo soddisfa il filtro
        }

        $ret = array();
        $wantedParam = $filter->getMask();
        foreach($wantedParam as $p)
            $ret[$p] = ($output->list[$p]['struct'] == false)
            ? $values[$output->getIndex($p)]
            : (self::filterValues($values[$output->getIndex($p)],$filter->list[$p]['structDesc'],$output->list[$p]['structDesc']));

        return $ret;
    }

    public function getParamNum()
    {
        return count($this->list);
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        $s = '#';
        foreach ($this->list as $key => $elem)
            $s .= $key .':'. $elem['nome'] . (($elem['struct'])?'('. $elem['structDesc'].')':'') .';' ;

        return $s.'#';
    }
}
