<?php
/**
 * Class XmlDoc
 * Version 1.1.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcations to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * 03/17/2003 Changed the xml read in one chunk
 * XmlDoc class converts an xml file into a dom (tree) structure using PHP's XPAT xml support.
 * Accepts an xml file in its constructor.
 *
 * PUBLIC PROPERTIES
 *	var $root		XmlNode object representing root of the document
 *	var $numNodes	number of element nodes in the xml file
 *	var $error		error string
 * PUBLIC METHODS
 *	isError()		checks if $error has a string
 *	parse($xmlFile)	parse the xml file and set $root.
 * 
 * @package framework
*/
class XmlDoc{
	var $root;
	var $numNodes=0;
	var $error;
	/***************************************************************************************
	PUBLIC METHODS
	****************************************************************************************/
	function isError(){
		if(!isset($this->error)) return false;
		return true;
	}

	function parse($xmlFile){
		$parser=xml_parser_create();
		xml_set_object($parser,$this);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_element_handler($parser, "_startElement", "_endElement");
		xml_set_character_data_handler($parser, "_characterData");

		if (!($fp = fopen($xmlFile, "r"))) {
			$this->error="Fatal: could not open XML input";
			return;
		}
		$xmlFileSize=filesize($xmlFile); 
		$data = fread($fp, $xmlFileSize+1); 
		if (!xml_parse($parser, $data, TRUE)){ 
     		$errorString=xml_error_string(xml_get_error_code($parser)); 
     		$lineNumber=xml_get_current_line_number($parser); 
     		$this->error="Fatal: XML error: $errorString at line $lineNumber"; 
     		return; 
		}
		xml_parser_free($parser);
	}
	/******************************************************************************
	PRIVATE METHODS AND PROPERTIES
	*******************************************************************************/
	var $_stack=array();
	function _startElement($parser, $name, $attrs){
		$node=new XmlNode;
		$node->name=$name;
		$node->attributes=$attrs;

		if($this->numNodes==0){
			$this->root=$node;
			$this->_stack[count($this->_stack)]=&$this->root;
		}
		else{
			$stackPosition=count($this->_stack)-1;
			$parentNode=&$this->_stack[$stackPosition];
			array_push($parentNode->children,$node);
			$currNodePosition=count($parentNode->children)-1;
			$currentNode=&$parentNode->children[$currNodePosition];
			$this->_stack[count($this->_stack)]=&$currentNode;
		}
		$this->numNodes++;
	}

	function _endElement($parser, $name){
		array_pop($this->_stack);
	}

	function _characterData($parser, $data){
		$lastNode=&$this->_stack[count($this->_stack)-1];
		$data=trim($data);
		//var_dump($lastNode);
		$charData=$lastNode->charData." ".$data;
		$charData=trim($charData);
		$lastNode->charData=$charData;
	}
}

/**
 * Class XmlNode
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcation to the line above.
 * Please include any modification history.
 * 10/01/2002 Initial creation.
 * XmlNode class represents an element node in an xml file, parsed using XmlDoc.
 *
 * PUBLIC PROPERTIES
 *	var $children		array containing all the children (XmlNode objects) of this node
 *	var $name			name of this node
 *	var $attributes		associative array containing all attributes as name=>value pair
 *	var $charData		character data of this node
 * PUBLIC METHODS
 *	numChildren()				number of child elements of this node
 *	numAttributes()				number of attributes of this node
 *	&getChild($nodeName)		get child (a deep copy of XmlNode object) of this node identified by $nodeName
 *	&getChildren($nodeName)		an array of all children identified by $nodeName
 * 	&childrenAsArray()			returns an associative array of children
 *	&getDescendant($nodeName)	get descendant (including child) of this node identified $nodeName
 *	dumpAsHtml()				dump the node in a html table (for debug)
 *
 * @package framework	
 */
class XmlNode{
	var $children;
	var $name;
	var $attributes;
	var $charData='';
	/*************************************************************************************
	PUBLIC METHODS
	**************************************************************************************/
	function XmlNode(){
		$this->children=array();
	}

	function numChildren(){
		return count($this->children);
	}

	function numAttributes(){
		return count($this->attributes);
	}

	function &getChild($nodeName){
		$n=$this->numChildren();
		for($i=0;$i<$n;$i++){
			$node=&$this->children[$i];
			if($node->name==$nodeName)
				return $node;
		}
		return NULL;	
	}

	function &getChildren($nodeName){
		$n=$this->numChildren();
		$children=array();
		for($i=0;$i<$n;$i++){
			$node=&$this->children[$i];
			if($node->name==$nodeName)
				$children[count($children)]=&$node;
		}
		return $children;
	}

	function &getDescendant($nodeName){
		$node=&$this->getChild($nodeName);
		if($node != NULL) return $node;

		$n=$this->numChildren();
		for($i=0;$i<$n;$i++){
			$child=&$this->children[$i];
			$node=&$child->getDescendant($nodeName);
			if($node != NULL) return $node;
		}
	}
	
	function dumpAsHtml(){
		$attribDump=$this->_getCommaSeparatedAttributes($this->attributes);
		$childrenName=$this->_getChildrenName($this);
		print "<html><body><table width='100%' cellpadding='5' cellspacing='5'>";
		print "<tr><td width='10%'>Name</td><td width='30%'>Char data</td><td width='30%'>Attribute dump</td><td width='30%'>Children</td></tr>";
		print	"<tr><td width='10%'>$this->name </td><td width='30%'>$this->charData </td><td width='30%'>$attribDump </td><td width='30%'>$childrenName </td></tr>";
		$this->_dumpChildren($this);
		print "</table></body></html>";
	}
	/***********************************************************************************
	PRIVATE METHODS
	************************************************************************************/
	function _getChildrenName(&$node){
		$n=$node->numChildren();
		$name="";
		for($i=0;$i<$n;$i++)
			$name=$name.$this->children[$i]->name.", ";
		return $name;
	}

	function _dumpChildren(&$node){
		$n=$node->numChildren();
		for($i=0;$i<$n;$i++){
			$child=&$node->children[$i];
			$attribDump=$child->_getCommaSeparatedAttributes($child->attributes);
			$childrenName=$child->_getChildrenName($child);
			print "<tr><td width='10%'>$child->name </td><td width='30%'>$child->charData </td><td width='30%'>$attribDump </td><td width='30%'>$childrenName </td></tr>";
			$child->_dumpChildren($child);
		}
	}

	function _getCommaSeparatedAttributes(&$attributes){
		if(count($attributes)==0) return;
		$theString="";
		foreach($attributes as $key=>$value){
			$theString=$theString.$key."=".$value.", ";
		}
		return $theString;
	}
}
?>