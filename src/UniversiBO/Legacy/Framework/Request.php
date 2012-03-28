<?php

namespace UniversiBO\Legacy\Framework;

/**
 * Class Request
 * Version 1.1.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modifcation history.
 * 10/01/2002 Initial creation.
 * 03/17/2003 Modified to accept arrays in query string and form action
 * Request class to hold HTTP request parameters.
 *
 * PUBLIC PROPERTIES
 *	If a varaible called variable1 is passed to the script (by post or get), it can be accessed as $a->variable1
 *	where $a is an object of Request class.
 *	$param		associative array containing all the variables of _GET and _POST
 * PUBLIC METHODS
 *  Request()
 */
class Request
{
    private $param=array();
    
    public function __construct (){
    	if (!empty( $_SERVER['PATH_INFO'] )) {
    		$params = explode( '/', $_SERVER['PATH_INFO'] ) ;
    		$_GET['do'] = $params[1];
    		for ($i=2; $i<sizeof($params); $i=$i+2 )
    			$_GET[ $params[$i]] = isset($params[$i+1]) ? $params[$i+1] : null ;
    	}
    	if ($_GET) {
    		while (list ($k, $v) = each ($_GET)){
    			$this->_createVariable($k,$v);
    		}
    	}
    	if ($_POST) {
    		while (list ($k, $v) = each ($_POST)){
    			$this->_createVariable($k,$v);
    		}
    	}
    }
    //Private methods
    private function _createVariable(&$key,&$value){
    	if(is_array($value)){
    		$this->_createArrayVariable($key,$value);
    	}
    	else{
    		$this->_createNonArrayVariable($key,$value);
    	}
    }
    private function _createNonArrayVariable(&$key,&$value){
    	$this->{$key} = $this->_getProcessedString($value);
    	array_push ($this->param, $key);
    }
    private function _createArrayVariable(&$key,&$values){
    	while (list($arrayKey,$arrayValue)=each($values)){
    		$values[$arrayKey]=$this->_getProcessedString($arrayValue);
    	}
    	$this->{$key}=$values;
    	array_push($this->param,$key);
    }
    private function _getProcessedString(&$value){
    	$value=trim($value);
    	$value=htmlspecialchars($value,ENT_QUOTES);
    	$value=stripcslashes($value);
    	return $value;
    }
}