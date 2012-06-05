<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

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

    public $lang_path = '';

    public $language = '';

    public $messages = array();

    public $loaded_packs = array();

    /**
     * Class constructor
     */
    public function __construct( $lang_path, $lang_code )
    {
        $this->lang_path = $lang_path;
        $this->setLanguage( $lang_code );
    }

    public function getLanguage()
    {
        return $this->language ;
    }

    public function setLanguage( $lang_code )
    {
        $this->language = $lang_code ;
        foreach ($this->loaded_packs as $pack_name => $value) {
            $this->loadLangPack($pack_name);
        }
    }

    public function getMessage( $message_id )
    {
        return $this->messages[$message_id];
    }

    public function loadLangPack( $pack_name )
    {
        $full_file_name = $this->lang_path . 'lang_'.$this->language . PHP_EXTENSION ;
        if (!$fp=fopen( $full_file_name, 'r' )) {
            return false;
        }

        $lang = $this->messages;

        include($full_file_name);

        $this->loaded_packs[$pack_name] = true;
    }
}
