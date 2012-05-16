<?php

namespace UniversiBO\Bundle\LegacyBundle\Auth;

/**
 * Active Directory Login Handler
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class ActiveDirectoryLogin
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * Class constructor
     *
     * @param string $host
     * @param int $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param string $username
     * @param string $domain
     * @param string $password
     * @return boolean
     */
    public function authenticate($username, $domain, $password)
    {
        @$javaADLoginSock = fsockopen($this->host,    # the host of the server
                $this->port,    # the port to use
                $errno,   # error number if any
                $errstr,  # error message if any
                3);   # give up after 5 secs

        if ( $javaADLoginSock == false )
        {
            \Error::throwError(_ERROR_DEFAULT,array('msg'=>'Impossibile connettersi al server di autenticazione Active Directory di Ateneo, provare pi� tardi oppure segnalare l\'inconveniente allo staff','file'=>__FILE__,'line'=>__LINE__));
        }
        else
        {
            $xml_request = '<?xml version="1.0" encoding="UTF-8"?><ADLogIn><user username="'. mb_convert_encoding($username, "UTF-8", "ISO-8859-1") .'" domain="'. mb_convert_encoding( $domain , "UTF-8", "ISO-8859-1") . '" password="'. mb_convert_encoding( $password , "UTF-8", "ISO-8859-1") . '" /></ADLogIn>';
            fputs ($javaADLoginSock, $xml_request."\n");

            $reply = fgets ($javaADLoginSock,4);

            fclose($javaADLoginSock);

            $result = substr($reply,0,2);
            if ($result == 'NO') return false;		// 'Autenticazione fallita';
            elseif ($result == 'OK') return true;	// 'Autenticazione corretta';
            else  die(); \Error::throwError(_ERROR_DEFAULT,array('msg'=>'Risposta del server di autenticazione Active Directory di Ateneo non valida'.$result,'file'=>__FILE__,'line'=>__LINE__));
        }
    }
}
