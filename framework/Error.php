<?php

/**
 * @todo si pu? implementare uno stack per gli handlers
 */
//global $_Error_handlers_stack;
//$_Error_handlers_stack = array();

global $_Error_handlers;
$_Error_handlers = array();

global $_Error_repository;
$_Error_repository = array();


/**
 * Error class for error creation, handling, collecting and retrieving
 *
 * @package framework
 * @version 1.0.0
 * @author  Ilias Bartolini
 * @author  Fabrizio Pinto
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class Error
{

    /**
     * @private
     */
    var $error_category;

    /**
     * @private
     */
    var $param;


    /**
     * Constructor: creates an Error object.
     *
     * @param int $error_category defines the error category, see the predefined constants
     * @param mixed $param error parameters, can be an arbitral value.
     *             example: array('msg'=>'this is yet another error message','file'=>__FILE__,'line'=>__LINE__)
     *             error handlers functions must be able to handle $param type.
     */
    public function __construct($error_category, $param = array())
    {
        $this->error_category = $error_category;
        $this->param = $param;
    }

    /**
     * Returns error parameters
     *
     * @return int error parameters
     */
    function getParam()
    {
        return $this->param;
    }

    /**
     * Set error parameters
     *
     * @param mixed $param error parameters
     */
    function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * Append error parameters if actual var param is an array
     *
     * @param array $param error parameters
     */
    function appendParam($param)
    {
        if(is_array($this->param))
            if(is_array($param))
            foreach ($param as $key => $value)
            {
                if (!array_key_exists($key, $this->param))
                    $this->param[$key] = $value;
            }
            else
                if (!array_key_exists($key, $this->param))
                $this->param[$key] = $value;
    }

    /**
     * Returns error category, see the predefined error category constants
     *
     * @return int error category
     */
    function getCategory()
    {
        return $this->error_category;
    }


    /**
     * Static method that defines current error handler callback function, for given error category
     *
     * @static
     * @param int $error_category see the predefined error category constants
     * @param mixed $handler_function can be a string
     *               example: 'my_function_name'
     *				or an array to use class methods
     *               example: array('MyClassName','myMethodName')
     */
    function setHandler($error_category, $handler_function)
    {
        global $_Error_handlers;
        $_Error_handlers[$error_category] = $handler_function;
    }


    /**
     * Static method that returns current error handler callback function for given error category
     *
     * @static
     * @param int $error_category see the predefined error category constants
     * @return mixed current handler function
     */
    function getHandler($error_category)
    {
        global $_Error_handlers;
        return $_Error_handlers[$error_category];
    }


    /**
     * Method that thows an error invoking current error handler callback function for given error category
     *
     * Can be statically called specifing the optional parameters or called
     * without parameters on a given error object istance
     *
     * @static optional
     * @param int $error_category defines the error category, see the predefined constants
     * @param mixed $param error parameters, can be an arbitral value.
     *             example: array('msg'=>'this is yet another error message','file'=>__FILE__,'line'=>__LINE__)
     *             error handlers functions must be able to handle $param type.
     * @return mixed the given handler callback function value
     */
    public static function throwError($error_category=NULL, $param=NULL)
    {
        global $_Error_handlers;
        if ( $error_category === NULL ) $error_category = $this->error_category;
        if ( $param === NULL ) $param =& $this->param;
        return call_user_func( $_Error_handlers[$error_category], $param );
    }

    /**
     * Method that collects an error instance in error repository
     *
     * Can be statically called specifing the optional parameters or called
     * without parameters on a given error object istance
     *
     * @static optional
     * @param int $error_category defines the error category, see the predefined constants
     * @param mixed $param error parameters, can be an arbitral value.
     *             example: array('msg'=>'this is yet another error message','file'=>__FILE__,'line'=>__LINE__)
     *             error handlers functions must be able to handle $param type.
     */
    function collect($error_category=NULL, $param=NULL)
    {
        global $_Error_repository;

        if ( $error_category !== NULL && $param !== NULL )
        {
            $temp_error = new Error($error_category, $param);
            $_Error_repository[] = $temp_error;
        }
        else
        {
            $_Error_repository[] = $this;
        }

    }


    /**
     * Method that retrieve the first instance in error repository of given error category
     * Errors are removed from repository
     *
     * @static
     * @param int $error_category defines the error category, see the predefined constants
     * @return mixed Error class object if successfull, false if no more Errors are in the repository
     */
    function retrieve($error_category)
    {

        global $_Error_repository;

        $count = count($_Error_repository);
        for ($i=0; $i<$count; $i++)
        {
            //var_dump($_Error_repository);
            if ( $_Error_repository[$i] !== NULL  &&  $_Error_repository[$i]->error_category === $error_category )
            {
                $current_error = $_Error_repository[$i];
                $_Error_repository[$i] = NULL;
                return $current_error;
            }

        }

        return false;

    }
}

class_exists('ErrorHandlers');
