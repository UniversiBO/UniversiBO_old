<?php

/**
 * classe da testare per l'esempio d'uso di PHPUnit
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class String
{
//contains the internal data
var $data;

// constructor
function String($data) {
$this->data = $data;
}

// creates a deep copy of the string object
function copy() {
$ret = new String($this->data);
return $ret;
}

// adds another string object to this class
function add($string) {
$this->data = $this->data.$string->toString("%s");
}

// returns the formated string
function toString($format) {
$ret = sprintf($format, $this->data);
return $ret;
}

}
?>

