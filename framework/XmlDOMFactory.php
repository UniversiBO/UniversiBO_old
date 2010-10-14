<?php
class XmlDOMFactory
{
	// @todo forse posso spostarlo direttamente in MyXmlDoc
	
	/*
	 *
	 *
	 * @static
	 * @return DOMDocument se php5, MyXmlDoc se php4
	 */
	function getXmlDOM()
	{
		//riconoscimento versione php
		$esito = version_compare(PHP_VERSION,'5.0.0');
		if ( $esito < 0)
		{
			//php4
			require_once('MyXmlDoc'.PHP_EXTENSION);
			return new MyXmlDoc();
		}	
		else
			//php5
			return new DOMDocument();
		
	}	

}

?>