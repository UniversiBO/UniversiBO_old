<?php
namespace Universibo\Bundle\LegacyBundle\App;

define('FORM_MAIL' 	    	,1);
define('FORM_TEXT'  		,2);
define('FORM_DATE'  		,3);
/**define('FORM_DD'			,3);
define('FORM_MM'			,4);
define('FORM_YY'			,5);*/
define('FORM_HH'			,6);
define('FORM_MIN'			,7);
// username?
// password?
// title?
// pu� essere utile  definire pi� tipi di FORM_XXX in modo da dare messaggi di errore pi� pertinenti..
// decidere se resituire un oggetto error oppure una stringa con il messaggio di errore

/**
 * FormToolbox class.
 *
 * Fornisce strumenti per semplificare la validazione delle form-request e
 * la gestione delle conversazion
 *
 * @package universibo
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */
class FormToolbox
{
    /**
     * Verifica i parametri secondo i tipi definiti come costanti
     *
     * @param array params 	array di array contenente i parametri da verificare, ogni singola voce array contiene:
     * 						1. param => parametro	2. type => costante FORM_XXX	[3. limit => eventuale limite opzionale] [4 option => il parametro pu� essere vuoto o nullo]
     * 						Ps la data � da passare in un unico array contenente gg mm aa
     * @return mixed true se tutti i parametri sono ok, altrimenti ritorna un oggetto errore
     * @access public
     */
    public function checkParams($params)
    {
        foreach ($params as $paramInfo) {
            if (array_key_exists('param',$paramInfo) && array_key_exists('type',$paramInfo)) {
                switch ($paramInfo['type']) {
                    case FORM_MAIL:			$esito = $this->_checkEmail($paramInfo['param']);
                                             break;
                    case FORM_TEXT:			(array_key_exists('limit', $paramInfo))
                                                ?	$esito = $this->_checkText($paramInfo['param'], $paramInfo['limit'], array_key_exists('option',$paramInfo))
                                                : $esito = $this->_checkText($paramInfo['param'], array_key_exists('option',$paramInfo));
                                            break;
                    case FORM_DATE:			$esito = $this->_checkDate($paramInfo['param']); break;
//					case FORM_DD:			$this->_checkDay($paramInfo['param']); break;
//					case FORM_MM:			$this->_checkMonth($paramInfo['param']); break;
//					case FORM_YY:			$this->_checkYears($paramInfo['param']); break;
                    case FORM_HH:			$esito = $this->_checkHour($paramInfo['param']); break;
                    case FORM_MIN:			$esito = $this->_checkMinute($paramInfo['param']); break;
                    default:			$esito = new Error(_ERROR_CRITIC,
                                                            array (	'msg' => 'Tipo di parametro inesistente',
                                                                    'file' => __FILE__,
                                                                    'line' => __LINE__,
                                                                    'log' => false));
                                            break;
                }

                if ($esito != true) return $esito;

            } else return new Error(_ERROR_CRITIC,
                                array (	'msg' => 'Tipo di parametro inesistente',
                                        'file' => __FILE__,
                                        'line' => __LINE__,
                                        'log' => false));
        }

        return true;
    }

    /**
     * controlla la correttezza tra il range di due date, da eseguire dopo checkParams
     *
     * @return boolean true se corretto
     */
    public function checkRangeDate($data_ins, $ins_hh = 0, $ins_min = 0, $data_scad, $scad_hh = 0, $scad_min = 0)
    {
        if (!array_key_exists('gg', $data_ins) || !array_key_exists('mm', $data_ins) || !array_key_exists('aa', $data_ins))
            return new Error(_ERROR_NOTICE, array ('msg' => 'La data di inserimento specificata non esiste'));

        if (!array_key_exists('gg', $data_scad) || !array_key_exists('mm', $data_scad) || !array_key_exists('aa', $data_scad))
            return new Error(_ERROR_NOTICE, array ('msg' => 'La data di scadenza specificata non esiste'));

        if (!(mktime($ins_hh, $ins_min, "0", $data_ins['mm'], $data_ins['gg'], $data_ins['aa']) <
                mktime($scad_hh, $scad_min, "0", $data_scad['mm'], $data_scad['gg'], $data_scad['aa'])))

            return new Error(_ERROR_NOTICE, array ('msg' => 'La data di scadenza non pu� essere precedente alla data di inserimento'));

        return true;
    }

/******************************************************************
 * PRIVATE METHOD												  *
 ******************************************************************/

    /**
     * @access private
     * @return boolean true se il valore � corretto
     */
    public function _checkEmail ($mail)
    {
        // @TODO
        return true;
    }

    /**
     * @access private
     * @return boolean true se il valore � corretto
     */
    public function _checkText ($text, $limit = 25, $optional = false)
    {
        // @TODO verificare il valore di default di limit

        if (strlen($text) > $limit)
            return new Error(_ERROR_NOTICE, array ('msg' => 'Il testo inserito deve essere inferiore ai '.$limit.' caratteri'));

        if (!$optional && $text == '')
            return new Error(_ERROR_NOTICE, array ('msg' => 'Bisogna inserire obbligatoriamente il testo nel campo'));

        return true;
    }

//	/**
//	 * @access private
//	 * @return boolean true se il valore � corretto
//	 */
//	function _checkDay ($dd)
//	{
//		// NB manca controllo stretto per febbraio e i mesi da 30 gg
//		return (ereg('^([0-9]{1,2})$', $dd) && $dd > 0 && $dd <= 31);
//	}
//
//	/**
//	 * @access private
//	 * @return boolean true se il valore � corretto
//	 */
//	function _checkMonth ($mm)
//	{
//		// NB manca controllo stretto per febbraio e i mesi da 30 gg
//		return (ereg('^([0-9]{1,2})$', $mm) && $mm > 0 && $mm <= 12);
//	}
//
//	/**
//	 * @access private
//	 * @return boolean true se il valore � corretto
//	 */
//	function _checkYear ($yy)
//	{
//		// NB manca controllo stretto per febbraio e i mesi da 30 gg
//		return (ereg('^([0-9]{4})$', $yy) && $yy >= 1970 && $yy <= 2032);
//	}

    /**
     * @access private
     * @return boolean true se il valore � corretto
     */
    public function _checkDate ($date)
    {
        if (!array_key_exists('gg', $date) || !array_key_exists('mm', $date) || !array_key_exists('aa', $date))
            return new Error(_ERROR_NOTICE, array ('msg' => 'La data specificata non esiste'));

        if (!checkdate($date['mm'], $date['gg'], $date['aa']))
            return new Error(_ERROR_NOTICE, array ('msg' => 'La data specificata non esiste'));
    }

    /**
     * @access private
     * @return boolean true se il valore � corretto
     */
    public function _checkHour ($hh)
    {
        if (!preg_match('/^([0-9]{1,2})$/', $hh))

            return new Error(_ERROR_NOTICE, array ('msg' => 'Il formato del campo ora di inserimento non � valido'));

        if ($hh < 0 || $hh > 23)
            return new Error(_ERROR_NOTICE, array ('msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23'));

        return true;
    }

    /**
     * @access private
     * @return boolean true se il valore � corretto
     */
    public function _checkMinute ($mm)
    {
        if(!preg_match('/^([0-9]{1,2})$/', $mm))

            return new Error(_ERROR_NOTICE, array ('msg' => 'Il formato del campo minuti di inserimento non � valido'));

        if($mm < 0 || $mm > 59)

            return new Error(_ERROR_NOTICE, array ('msg' => 'Il campo minuti di inserimento deve essere compreso tra 0 e 59'));

        return true;
    }

}
