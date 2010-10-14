<?php

require_once('SOAP/Client.php');

/**
 * WebService_WsDidatticaServerImplService_Didattica
 *
 * Web service stub generata con PEAR::SOAP
 * Per rigenerare la stub eseguire
 * --------------
 * require_once('SOAP/WSDL.php');
 * $wsdlurl = 'http://localhost:8080/axis/services/Didattica?wsdl';
 * $wsdl = new SOAP_WSDL($wsdlurl);
 * echo $wsdl->generateProxyCode();
 * --------------
 *
 * @package universibo
 * @version 2.1.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */


class WebService_WsDidatticaServerImplService_Didattica extends SOAP_Client
{
    function WebService_WsDidatticaServerImplService_Didattica($location)
    {
        $this->SOAP_Client($location, 0);
    }

    function &getFacoltaDesc($codFac) {
        return $this->call("getFacoltaDesc",
                        $v = array("codFac"=>$codFac),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getFacolta($codFac) {
        return $this->call("getFacolta",
                        $v = array("codFac"=>$codFac),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getFacoltaList() {
        return $this->call("getFacoltaList",
                        $v = null,
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getCorsoListFacolta($codFac) {
        return $this->call("getCorsoListFacolta",
                        $v = array("codFac"=>$codFac),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getMateria($codMateria) {
        return $this->call("getMateria",
                        $v = array("codMateria"=>$codMateria),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getDocente($codDoc) {
        return $this->call("getDocente",
                        $v = array("codDoc"=>$codDoc),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico) {
        return $this->call("getAttivitaDidatticaPadreCorso",
                        $v = array("codCorso"=>$codCorso, "annoAccademico"=>$annoAccademico),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getAttivitaDidatticaCorso($codCorso, $annoAccademico) {
        return $this->call("getAttivitaDidatticaCorso",
                        $v = array("codCorso"=>$codCorso, "annoAccademico"=>$annoAccademico),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getSdoppiamentiAttivitaDidattica($attivitaPadre) {
        // attivitaPadre is a ComplexType AttivitaDidattica,
        //refer to wsdl for more info
        $attivitaPadre =& new SOAP_Value('attivitaPadre','{urn:didattica.universibo.unibo.it}AttivitaDidattica',$attivitaPadre);
        return $this->call("getSdoppiamentiAttivitaDidattica",
                        $v = array("attivitaPadre"=>$attivitaPadre),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }

    function &getCorso($codCorso) {
        return $this->call("getCorso",
                        $v = array("codCorso"=>$codCorso),
                        array('namespace'=>'http://didattica.universibo.unibo.it',
                            'soapaction'=>'',
                            'style'=>'rpc',
                            'use'=>'encoded' ));
    }
}

?>
