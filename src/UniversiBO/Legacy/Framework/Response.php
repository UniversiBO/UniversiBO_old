<?php

namespace UniversiBO\Legacy\Framework;

/**
 * Class Response
 * Version 1.0.0
 * Author: Deepak Dutta, http://www.eocene.net
 * Unrestricted license, subject to no modifcation to the line above.
 * Please include any modifcation history.
 * 10/01/2002 Initial creation.
 * Response class to store output content for eventual output.
 *
 * PUBLIC PROPERTIES
 *	$content			content is stored here or eventual output
 * PUBLIC METHODS
 *	Response()				sets up ob_start()
 *	write(&$string)			stores the $string into $content
 *	writeC($string)			stores a literal string in $content
 *	getContent()			returns the buffer content
 *	emptyBuffer()			empty the buffer and ends buffering
 *	redirect($location)		redirect to another url after emptying the buffer
 *
 * @package framework
 * @version 1.0.0
 * @author Deepak Dutta
 * @author Ilias Bartolini
 * @author Davide Bellettini
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class Response
{
    private $content;
    
    public function __construct(){
    	ob_start();
    }
    
    public function write(&$string){
    	$this->content .=$string;
    }
    
    public function getContent(){
    	return ob_get_contents();
    }
    
    public function emptyBuffer(){
    	ob_end_clean();
    }
    
    public function redirect($location) {
    	ob_end_clean();
    	header("Location: $location");
    	exit();
    }
}