<?php
/*
 * Wrapper delle funzioni di php5 per php4
 *
 *
 */
//class MyXmlDoc
//{
//	/*
//	 * DOMDocument
//	 * @private
//	 */
//	var $dom = null;
//	
//	// nodo root
//	var $documentElement = null;
//	
//	function load($nomeFileXml)
//	{
//		//var_dump($nomeFileXml);
//		//var_dump(domxml_open_file($nomeFileXml));
//		if (!$this->dom = domxml_open_file(realpath($nomeFileXml)))
//			// @todo Al posto di utente??
//			// @dubbio: Error è del framework o di universibo?
//			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella lettura del file di configurazione', 'file' => __FILE__, 'line' => __LINE__));
//				
//		$this->documentElement = new MyDomElement($this->dom->document_element());	
//	}
//
//	/*
//	 * in PHP5 ritorna un DOM Node list invece che un array
//	 *
//     * cioé:
//	 * $element->item(0) invece che $element[0].
//	 *
//	 * @return MyDOMNodeList
//	 */
//	function getElementsByTagName($nomeTag)
//	{
//		return new MyDOMNodeList($this->dom->get_elements_by_tagname($nomeTag));
//	}
//}


class MyXmlDoc
{
	/*
	 * DOMDocument
	 * @private
	 */
	var $dom = null;
	
	// nodo root
	var $documentElement = null;
	
	function load($nomeFileXml)
	{
		if (!$this->dom = domxml_open_file(realpath($nomeFileXml)))
			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella lettura del file di configurazione', 'file' => __FILE__, 'line' => __LINE__));
		
//		$this->documentElement = new MyDomElement($this->dom->document_element());
		$this->documentElement = NodeDispatcher::getMyNode($this->dom->document_element());
	}

	/*
	 * @return MyDOMNodeList
	 */
	function getElementsByTagName($nomeTag)
	{
		return new MyDOMNodeList($this->dom->get_elements_by_tagname($nomeTag));
	}
}

class MyDomNode
{
	var $realElement =null;
	
	// Inizio proprietà pubbliche stile PHP5
	var $nodeName = null;
		
	var $nodeValue = null;

	var $firstChild = null;

 	//var $lastChild = null;
 	
	var $nodeType = null;

	var $childNodes = null;

	var $parentNode = null;
	
	
	
	function MyDomNode($domNode)
	{
		if ($domNode == null)
			// Error o meglio return null? ci creiamo le Exception?
			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
			
		$this->_setRealElement($domNode);
		
		$this->_setNodeValue();
		
		$this->_setFirstChild();
		
		$this->_setChildNodes();
		
		$this->_setNodeName();
		
		$this->_setNodeType();
		
		//$this->_setParentNode();
	}
	
	/// metodi privati di inizializzazione
	
	function _setRealElement($domNode)
	{		
		$this->realElement 	=& $domNode;
	}
	
	function _setParentNode()
	{
		// @bug: questo non funzia
		$this->parentNode =& NodeDispatcher::getMyNode($this->realElement->parent_node());
	}
	
	function _setNodeType()
	{
		$this->nodeType = $this->realElement->node_type();
	}
	
	function _setNodeName()
	{
		$this->nodeName	= $this->realElement->node_name();
	}
	
	function _setChildNodes()
	{
		if ($this->realElement->has_child_nodes())
			$this->childNodes	=& new MyDOMNodeList ($this->realElement->child_nodes());
	}
	
	function _setNodeValue() 
	{
		$this->nodeValue	= $this->realElement->node_value();
	}
	
	function _setFirstChild()
	{
		if ($this->realElement->has_child_nodes())
			$this->firstChild	= NodeDispatcher::getMyNode($this->realElement->first_child());	
	}
	
	/// fine metodi privati di inizializzazione
	
	/*
	 * @static
	 */
	function getMe($elemento)
	{
		return new MyDomNode($elemento);
	}
		
	function hasChildNodes()
	{
		return $this->realElement->has_child_nodes();
	}
				
}


class MyDomElement extends MyDomNode
{
	// Inizio proprietà pubbliche stile PHP5
	var $tagName = null;
	
	function MyDomElement($domElement)
	{
		if ($domElement == null)
			// Error o meglio return null? ci creiamo le Exception?
			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
		
		parent::MyDomNode($domElement);
		
		// qual'è la funzione in php4 per il tagName?
		$this->tagName = $this->realElement->node_name();
	}
	
	/*
	 * in PHP5 ritorna un DOM Node list invece che un array
	 *
     * cioé:
	 * $element->item(0) invece che $element[0].
	 *
	 * @return MyDOMNodeList
	 */
	function getElementsByTagName($nomeTag)
	{
		return new MyDOMNodeList($this->realElement->get_elements_by_tagname($nomeTag));
	}
			
//	function getAttributeNode($nomeAttr)
//	{	
//		return NodeDispatcher::getMyNode($this->realElement->get_attribute_node($nomeAttr));
//	}
			
	function getAttribute($nomeAttr)
	{	
		return $this->realElement->get_attribute($nomeAttr);
	}

	function getMe($elemento)
	{
		return new MyDomElement($elemento);
	}
	
}


class MyDomText extends MyDomNode
{
	function MyDomText($domElement)
	{
		if ($domElement == null)
			// Error o meglio return null? ci creiamo le Exception?
			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
		
		parent::MyDomNode($domElement);	
	}


	function getMe($elemento)
	{
		return new MyDomText($elemento);
	}
	
}


class MyDomAttribute extends MyDomNode
{
	var $name = null;
	
	var $value = null;
	
	
	function MyDomAttribute($domElement)
	{
		if ($domElement == null)
			// Error o meglio return null? ci creiamo le Exception?
			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
		
		parent::MyDomNode($domElement);
		
		// qual è la funz giusta da invocare ? 
		$this->name =& $this->realElement->node_name();	
		
		// qual è la funz giusta da invocare ? 
		$this->value =& $this->realElement->node_value();
	}


	function getMe($elemento)
	{
		return new MyDomAttribute($elemento);
	}
	
	
	
}

// riesco a farlo considerare anche come array in modo da scorrerlo con il foreach?
class MyDOMNodeList
{
	var $arrayDiElementi = null;

	// proprietà interfaccia Array
	var $length = 0;
	
	function MyDOMNodeList($listaElementi)
	{
		// @dubbio: controllo input
		$listaAppoggio =& $listaElementi;	
		
		$this->arrayDiElementi = array();

		foreach ( $listaAppoggio as $elemento)
		{
			$this->arrayDiElementi[] = NodeDispatcher::getMyNode($elemento);
		}
		
		$this->length = count($this->arrayDiElementi);
		//var_dump($this->arrayDiElementi);
	}

	function item($indice)
	{
		// @dubbio: controllo che $indice sia valore corretto?
		if ($indice >= 0 && $indice < $this->length)
			return $this->arrayDiElementi[$indice];
		else return null;		// @dubbio: oppure lancio errore?
	}
}


class NodeDispatcher
{
	function getMyNode($elemento)
	{
		if ($elemento == null)
			// Error o meglio return null? ci creiamo le Exception?
			//Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
			return null;
		
		$tipo_nodo = $elemento->node_type();

		$dispatch_array = array (	1 => 'MyDomElement',
									2 => 'MyDomAttribute',
									3 => 'MyDomText',
									);
		
		
		if (array_key_exists($tipo_nodo, $dispatch_array))
		{
			$class_name = $dispatch_array[$tipo_nodo];
		}
		else 
			$class_name = 'MyDomNode';
//		
//		require_once($class_name.PHP_EXTENSION);
//		
//		$cache_canali[$id_canale] =& call_user_func(array($class_name,'factoryCanale'), $id_canale);
//
//		return $cache_canali[$id_canale];
		
		return call_user_func(array($class_name, 'getMe'), $elemento);
		
	}
}

//class MyDomElement
//{
//	var $realElement =null;
//	
//	// Inizio proprietà pubbliche stile PHP5
//	var $nodeValue = null;
//
//	var $firstChild = null;
//
//	var $nodeType = null;
//
//	var $childNodes = null;
//
//	var $tagName = null;
//	
//	var $parentNode = null;
//
//	function MyDomElement($domElement)
//	{
//		// si può controllare se $domElement è effetivamente un oggetto DomElement?
//		if ($domElement == null)
//			// Error o meglio return null? ci creiamo le Exception?
//			Error :: throwError(_ERROR_CRITICAL, array ('id_utente' => '', 'msg' => 'Errore nella creazione del nodo', 'file' => __FILE__, 'line' => __LINE__));
//			
//		$this->realElement 	=& $domElement;
//		
//		//var_dump($this->realElement);
//		// node_value è definito solo per le foglie?
////		if ( $this->realElement->node_type() == XML_TEXT_NODE ) 
////		{
////			$this->nodeValue	=& $this->realElement->node_value();
////		}	
//
//		$this->nodeValue	=& $this->realElement->node_value();
//		
////		$child = $this->realElement->first_child();
////		if ($child != null)
////			$this->firstChild	=& new MyDomElement ($child);
//		if ($this->realElement->has_child_nodes())
//			$this->firstChild	=& new MyDomElement ($this->realElement->first_child());
//			
////		$childs = $this->realElement->child_nodes();
////		if ($childs != null)
////			$this->childNodes	=& new MyDOMNodeList($childs);
//		
//		if ($this->realElement->has_child_nodes())
//			$this->childNodes	=& new MyDOMNodeList ($this->realElement->child_nodes());
//		
//		// @TODO cercare il nome della funz per accedere al tagname
//		// NB nodeName è definito per qualsiasi nodo in php 5
//		//    tagName esiste solo per i nodi element e document
//		$this->tagName	=& $this->realElement->node_name();
//		
//		$this->nodeType =& $this->realElement->node_type();
//		
//		$parentNode =& $this->realElement->parent_node();
//	}
//	
//	/*
//	 * in PHP5 ritorna un DOM Node list invece che un array
//	 *
//     * cioé:
//	 * $element->item(0) invece che $element[0].
//	 *
//	 * @return MyDOMNodeList
//	 */
//	function getElementsByTagName($nomeTag)
//	{
//		return new MyDOMNodeList($this->realElement->get_elements_by_tagname($nomeTag));
//	}
//	
//	function hasChildNodes()
//	{
//		return $this->realElement->has_child_nodes();
//	}
//	
//	function getAttribute($nomeAttr)
//	{	
////		if ($this->realElement->type == 1)
//			return $this->realElement->get_attribute($nomeAttr);
////		var_dump($this->realElement);
////		var_dump(debug_backtrace());
////		return '';
//	}
//}
//
//// riesco a farlo considerare anche come array in modo da scorrerlo con il foreach?
//class MyDOMNodeList
//{
//	var $arrayDiElementi = null;
//
//	// proprietà interfaccia Array
//	var $length = 0;
//	
//	function MyDOMNodeList($listaElementi)
//	{
//		// @dubbio: controllo input
//		$listaAppoggio =& $listaElementi;	
//		
//		$this->arrayDiElementi = array();
//
//		foreach ( $listaAppoggio as $elemento)
//		{
//			$this->arrayDiElementi[] =& new MyDomElement($elemento);
//		}
//		
//		$this->length = count($this->arrayDiElementi);
//		//var_dump($this->arrayDiElementi);
//	}
//
//	function item($indice)
//	{
//		// @dubbio: controllo che $indice sia valore corretto?
//		if ($indice >= 0 && $indice < $this->length)
//			return $this->arrayDiElementi[$indice];
//		else return null;		// @dubbio: oppure lancio errore?
//	}
//}
