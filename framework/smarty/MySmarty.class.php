<?php
/**
 * Project:     Smarty: the PHP compiling template engine
 * File:        Smarty.class.php
 *
 * Estensione della normale classe Smarty affinché i template da mostrare vengano cercati in maniera differenziale:
 * si definisce uno stile/cartella default cui cercare se nellla cartella dello stile corrente non si trova il tpl 
 * desiderato
 *
 * @link http://smarty.php.net/
 * @copyright 2001-2004 ispi of Lincoln, Inc.
 * @author Fabrizio Pinto 
 * @package Smarty
 * @version 2.6.5-dev
 */

class MySmarty extends Smarty {
	
	
    /**
     * The name of the directory where default templates are located.
     *
     * @var string
     */
    var $default_template_dir    =  'templates';
    
    function assignUnicode($tpl_var, $value)
    {
        $this->assign($tpl_var, $this->utf8ToLatin1($value));
    }

    /**
     * parse out the type and name from the resource
     *
     * @param string $resource_base_path
     * @param string $resource_name
     * @param string $resource_type
     * @param string $resource_name
     * @return boolean
     */

    function _parse_resource_name(&$params)
    {

        // split tpl_path by the first colon
        $_resource_name_parts = explode(':', $params['resource_name'], 2);

        if (count($_resource_name_parts) == 1) {
            // no resource type given
            $params['resource_type'] = $this->default_resource_type;
            $params['resource_name'] = $_resource_name_parts[0];
        } else {
            if(strlen($_resource_name_parts[0]) == 1) {
                // 1 char is not resource type, but part of filepath
                $params['resource_type'] = $this->default_resource_type;
                $params['resource_name'] = $params['resource_name'];
            } else {
                $params['resource_type'] = $_resource_name_parts[0];
                $params['resource_name'] = $_resource_name_parts[1];
            }
        }
		
//		print_r($params);
//		die;
		
        if ($params['resource_type'] == 'file') {
            if (!preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $params['resource_name'])) {
                // relative pathname to $params['resource_base_path']
                // use the first directory where the file is found
                foreach ((array)$params['resource_base_path'] as $_curr_path) {
                    $_fullpath = $_curr_path . DIRECTORY_SEPARATOR . $params['resource_name'];
                    if (file_exists($_fullpath) && is_file($_fullpath)) {
                        $params['resource_name'] = $_fullpath;
                        return true;
                    }
                    // didn't find the file, try default style path                    
                    $_fullpath = $this->default_template_dir . DIRECTORY_SEPARATOR . $params['resource_name'];
                    if (file_exists($_fullpath) && is_file($_fullpath)) {
                        $params['resource_name'] = $_fullpath;
                        return true;
                    }
                    
                    // didn't find the file, try include_path
                    $_params = array('file_path' => $_fullpath);
                    require_once(SMARTY_CORE_DIR . 'core.get_include_path.php');
                    if(smarty_core_get_include_path($_params, $this)) {
                        $params['resource_name'] = $_params['new_file_path'];
                        return true;
                    }
                }
                return false;
            } else {
                /* absolute path */
                return file_exists($params['resource_name']);
            }
        } elseif (empty($this->_plugins['resource'][$params['resource_type']])) {
            $_params = array('type' => $params['resource_type']);
            require_once(SMARTY_CORE_DIR . 'core.load_resource_plugin.php');
            smarty_core_load_resource_plugin($_params, $this);
        }

        return true;
    }
    
	private function utf8ToLatin1($value)
	{
	    return is_array($value) ? array_map(array($this, 'utf8ToLatin1'), $value) : mb_convert_encoding($value, 'iso-8859-1', 'utf-8');
	}
}