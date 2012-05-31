<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class Stack
{
    private $_innerStack = array();
    private $groupedValuesTrace = array();
    private $groupedLength = 0;
    public $length = 0;

    public function pop()
    {
        if ($this->length == 0) return;
        $this->length--;
        $this->_removeFromGroupedValues(1);

        return array_pop($this->_innerStack);
    }

    public function push($a,$count = true) {array_push($this->_innerStack, $a); $this->length++; if($count) $this->_addGroupedValues(1);}

    public function groupedPush($arrayOfValues)
    {
        foreach($arrayOfValues as $val)
            $this->push($val,false);
        $this->_addGroupedValues(count($arrayOfValues));
    }

    public function groupedPop()
    {
        if($this->groupedLength == 0) return;
        $this->groupedLength--;
        $this->length -= $this->groupedValuesTrace[$this->groupedLength];

        return array_splice($this->_innerStack, - $this->groupedValuesTrace[$this->groupedLength]);
    }

    public function svuota() {$this->_innerStack = array(); $this->length = 0; $this->groupedValuesTrace = array(); $this->groupedLength = 0;}

    public function debug()
    {
        $s ="STACK: Length ".$this->length;
        foreach ($this->_innerStack as $i) {
            $s .= " Elem ";
            if (is_object($i) &&  method_exists($i,"toString"))
                $s .= $i->toString();
            else if(is_object($i))
                $s .= get_class($i);
            else if(is_bool($i))
                $s .= ($i) ? 'true':'false';
            else if(is_array($i))
                $s .= print_r($i, true);
            else
                $s .= $i;
        }
        $s .= "\n";

        return $s;
    }

    public function _addGroupedValues($num)
    {
        $this->groupedValuesTrace[] = $num;
        $this->groupedLength++;
    }

    public function _removeFromGroupedValues($num)
    {
        if ($this->groupedLength == 0) {echo 'errore '.$num."\n"; return;}
        while ( $this->groupedValuesTrace[($this->groupedLength)-1] <= $num) {
            $num -= $this->groupedValuesTrace[($this->groupedLength)-1];
            array_pop($this->groupedValuesTrace);
            $this->groupedLength--;
            if($num <= 0 || $this->groupedLength <=0) return;
        }

        if($num > 0 && $this->groupedLength > 0)
            $this->groupedValuesTrace[($this->groupedLength)-1] -= $num;
    }

}
