<?php
/**
 * Multilanguage class, handles dictionary for multilanguage
 * messages support.
 * Has a kronos class for date-time format definition
 *
 * @package framework
 * @version 2.0.0
 * @author  pswitek, Ilias Bartolini
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class MultiLanguage 
{ 

	var $lang_path = ''; 

	var $language = ''; 

	var $messages = array();
	
	var $loaded_packs = array();
	
	/**
	 * 
	 *
	 *
	 */
	function MultiLanguage( $lang_path, $lang_code )
	{
		$this->lang_path = $lang_path;
		$this->setLanguage( $lang_code );
	}

	function getLanguage()
	{ 
		return $this->language ; 
	} 

	function setLanguage( $lang_code )
	{ 
		$this->language = $lang_code ; 
		foreach ($this->loaded_packs as $pack_name => $value)
		{
			$this->loadLangPack($pack_name);
		}
	} 

	function getMessage( $message_id )
	{ 
		return $this->messages[$message_id]; 
	} 

	function loadLangPack( $pack_name )
	{ 
		$full_file_name = $this->lang_path . 'lang_'.$this->language . PHP_EXTENSION ;
		if (!$fp=fopen( $full_file_name, 'r' ))
		{
			return false;
		} 

		$lang = $this->messages;
		
		include($full_file_name);
		
		$this->loaded_packs[$pack_name] = true;

	}

}
