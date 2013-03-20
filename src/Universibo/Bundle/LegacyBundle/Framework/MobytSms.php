<?php
namespace
{
    /**
     * Libreria per l'invio di SMS tramite il servizio SMS-Web offerto da Mobyt Srl
     *
     * - <b>Versione 1.2.0</b>
     * - Aggiunto supporto alle nuova {@link mobytSms::setQualityAuto() qualit� automatica}
     * - Aggiunto supporto agli sms con {@link mobytSms::setQualityAutoNotify() notifica}
     * - Aggiunto supporto agli sms {@link mobytWapPush Wap/Push}
     * - <b>Versione 1.2.1</b>
     * - Aggiunto supporto alle suonerie in {@link mobytRTTTL formato RTTTL}
     * - <b>Versione 1.2.2</b>
     * - Bugfix: la versione, utile per motivi di debug, non veniva inviata correttamente al server
     * - <b>Versione 1.2.3</b>
     * - Bugfix: corretto un problema nell'invio di messaggi Wap/Push
     * - <b>Versione 1.2.4</b>
     * - Aggiunta possibilit� di ottenere il {@link mobytSms::getAvailableNotifies() numero di notifiche disponibili}
     * - <b>Versione 1.2.5</b>
     * - Aggiunto supporto per l'invio multiplo tramite SMS-Batch (FTP)
     * - <b>Versione 1.3.0</b>
     * - Classe aggiornata alla nuova documentazione
     * - <b>Versione 1.3.1</b>
     * - Aggiunto supporto ai messaggi Flash
     * - Aggiunta compatibilit� con register_long_arrays off (opzione PHP5) in sms-relay.php
     * - <b>Versione 1.3.2</b>
     * - Risolto problema nella scelta della qualit� se la classe viene inclusa all'interno di una funzione
     * - <b>Versione 1.3.3</b>
     * - Aggiunto supporto al servizio {@link mobytMnc MNC (Mobyt Number Check)}
     * - <b>Versione 1.4.0</b>
     * - Aggiunto supporto al servizio {@link mobytMMS MMS}
     * - <b>Versione 1.4.1</b>
     * - Risolti problemi nell'utilizzo del servizio MNC
     * - <b>Versione 1.4.2</b>
     * - Risolti problemi nell'esempio MNC
     *
     * @version 1.4.2
     * @author  Matteo Beccati - matteo.beccati@mobyt.it
     * @copyright (C) 2003-2005 Mobyt srl
     * @license https://www.mobyt.it/bsd-license.html BSD License
     *
     */

    /**#@+
     * @access	private
     */
    /**
     * Versione della classe
     */
    define('MOBYT_PHPSMS_VERSION',	'1.4.2');
    /**
     * Tipo di autenticazione basata su MD5, con password <b>non</b> inviata in chiaro
     */
    define('MOBYT_AUTH_MD5',	1);
    /**
     * Tipo di autenticazione basata su IP, con password inviata in chiaro
     */
    define('MOBYT_AUTH_PLAIN',	2);

    /**
     * Qualità messaggi in base al valore di default dell'account
     */
    define('MOBYT_QUALITY_DEFAULT',	0);
    /**
     * Qualità messaggi bassa (LQS)
     */
    define('MOBYT_QUALITY_LQS',	1);
    /**
     * Qualità messaggi media (MQS)
     */
    define('MOBYT_QUALITY_MQS',	2);
    /**
     * Qualit� messaggi alta (HQS)
     */
    define('MOBYT_QUALITY_HQS',	3);
    /**
     * Qualit� messaggi automatica
     */
    define('MOBYT_QUALITY_AUTO',	4);
    /**
     * Qualit� messaggi automatica con notifica
     */
    define('MOBYT_QUALITY_AUTO_NY',	5);

    /**
     * Tipo operazione TEXT
     */
    define('MOBYT_OPERATION_TEXT',	0);
    /**
     * Tipo operazione RING
     */
    define('MOBYT_OPERATION_RING',	1);
    /**
     * Tipo operazione Logo Operatore
     */
    define('MOBYT_OPERATION_OLGO',	2);
    /**
     * Tipo operazione Logo Gruppo
     */
    define('MOBYT_OPERATION_GLGO',	3);
    /**
     * Tipo operazione 8 bit
     */
    define('MOBYT_OPERATION_8BIT',	4);
    /**
     * Tipo operazione Flash
     */
    define('MOBYT_OPERATION_FLASH',	5);

    /**
     * @global array Array di conversione per le qualit�
     */
    $GLOBALS['mobyt_qty'] = array(
            MOBYT_QUALITY_LQS		=> 'll',
            MOBYT_QUALITY_MQS		=> 'l',
            MOBYT_QUALITY_HQS		=> 'h',
            MOBYT_QUALITY_AUTO		=> 'a',
            MOBYT_QUALITY_AUTO_NY	=> 'a'
    );

    /**
     * @global array Array di conversione per l'operazione
     */
    $GLOBALS['mobyt_ops'] = array(
            MOBYT_OPERATION_TEXT	=> 'TEXT',
            MOBYT_OPERATION_RING	=> 'RING',
            MOBYT_OPERATION_OLGO	=> 'OLGO',
            MOBYT_OPERATION_GLGO	=> 'GLGO',
            MOBYT_OPERATION_8BIT	=> '8BIT',
            MOBYT_OPERATION_FLASH	=> 'FLASH'
    );
    /**#@-*/
}
namespace Universibo\Bundle\LegacyBundle\Framework
{
    /**
     * Classe per l'invio di SMS tramite il servizio SMS-Web
     *
     * Le impostazioni utilizzate di default sono:
     * - Mittente: <b>"MobytSms"</b>
     * - Autenticazione: <b>MD5</b>
     * - Qualit�: <b>Non impostata</b> - Il default � l'utilizzo della modalit� automatica
     *
     * @example sms-single.php Invio di un singolo sms in alta qualit� con autenticazione MD5
     */
    class MobytSms
    {
        /**#@+
         * @access	private
         * @var		string
         */
        public $auth = MOBYT_AUTH_MD5;
        public $quality = MOBYT_QUALITY_DEFAULT;
        public $operation = MOBYT_OPERATION_TEXT;
        public $from;
        public $login;
        public $pwd;
        public $udh;
        /**#@-*/

        /**
         * @param string	Username di accesso (Login)
         * @param string	Password dispositiva
         * @param string	Intestazione mittente
         *
         * @see setFrom
         */
        public function __construct($login, $pwd, $from = 'MobytSms')
        {
            $this->login = $login;
            $this->pwd = $pwd;
            $this->setFrom($from);
        }

        /**
         * Imposta intestazione mittente
         *
         * Il mittente pu� essere composto da un massimo di 11 caratteri alfanumerici o un numero telefonico
         * con prefisso internazionale.
         *
         * @param string	Intestazione mittente
         */
        public function setFrom($from)
        {
            $this->from = substr($from, 0, 14);
        }

        /**
         * Utilizza l'autenticazione di tipo MD5
         */
        public function setAuthMd5()
        {
            $this->auth = MOBYT_AUTH_MD5;
        }

        /**
         * Utilizza l'autenticazione con password in chiaro basata sull'IP
         */
        public function setAuthPlain()
        {
            $this->auth = MOBYT_AUTH_PLAIN;
        }


        /**
         * Imposta la qualit� messaggi al default dell'account
         */
        public function setQualityDefault()
        {
            $this->quality = MOBYT_QUALITY_DEFAULT;
        }

        /**
         * Imposta la qualit� messaggi come bassa
         */
        public function setQualityLow()
        {
            $this->quality = MOBYT_QUALITY_LQS;
        }

        /**
         * Imposta la qualit� messaggi come media
         */
        public function setQualityMedium()
        {
            $this->quality = MOBYT_QUALITY_MQS;
        }

        /**
         * Imposta la qualit� messaggi come alta
         */
        public function setQualityHigh()
        {
            $this->quality = MOBYT_QUALITY_HQS;
        }

        /**
         * Imposta la qualit� messaggi automatica
         */
        public function setQualityAuto()
        {
            $this->quality = MOBYT_QUALITY_AUTO;
        }

        /**
         * Imposta la qualit� messaggi automatica con notifica
         *
         * @example sms-single-notify.php Invio di un singolo sms con notifica
         * @example sms-multi-notify.php Invio sms multipli con notifica
         */
        public function setQualityAutoNotify()
        {
            $this->quality = MOBYT_QUALITY_AUTO_NY;
        }

        /**
         * Imposta il tipo di messaggio a TEXT
         */
        public function setOperationText()
        {
            $this->operation = MOBYT_OPERATION_TEXT;
        }

        /**
         * Imposta il tipo di messaggio a RING (suoneria)
         *
         * L'invio di messaggi di tipo RING necessita l'invio in alta qualit� o con notifica.
         * Questa verr� impostata automaticamente, tranne nel caso in cui sia stata impostata la qualit� automatica con notifica
         *
         * @example sms-single-ring.php Invio di un singolo sms in modalit� RING
         */
        public function setOperationRing()
        {
            $this->operation = MOBYT_OPERATION_RING;

            if ($this->quality != MOBYT_QUALITY_AUTO_NY)
                $this->setQualityHigh();
        }

        /**
         * Imposta il tipo di messaggio a OLGO (logo operatore)
         *
         * L'invio di messaggi di tipo OLGO necessita l'invio in alta qualit� o con notifica.
         * Questa verr� impostata automaticamente, tranne nel caso in cui sia stata impostata la qualit� automatica con notifica
         */
        public function setOperationOlgo()
        {
            $this->operation = MOBYT_OPERATION_OLGO;

            if ($this->quality != MOBYT_QUALITY_AUTO_NY)
                $this->setQualityHigh();
        }

        /**
         * Imposta il tipo di messaggio a GLGO (logo gruppo)
         *
         * L'invio di messaggi di tipo GLGO necessita l'invio in alta qualit� o con notifica.
         * Questa verr� impostata automaticamente, tranne nel caso in cui sia stata impostata la qualit� automatica con notifica
         */
        public function setOperationGlgo()
        {
            $this->operation = MOBYT_OPERATION_GLGO;

            if ($this->quality != MOBYT_QUALITY_AUTO_NY)
                $this->setQualityHigh();
        }

        /**
         * Imposta il tipo di messaggio a 8 bit
         *
         * L'invio di messaggi di tipo 8BIT necessita l'invio in alta qualit� o con notifica.
         * Questa verr� impostata automaticamente, tranne nel caso in cui sia stata impostata la qualit� automatica con notifica
         *
         * @param string UDH
         */
        public function setOperation8Bit($udh)
        {
            $this->operation = MOBYT_OPERATION_8BIT;

            $this->udh = $udh;

            if ($this->quality != MOBYT_QUALITY_AUTO_NY)
                $this->setQualityHigh();
        }

        /**
         * Imposta il tipo di messaggio a FLASH
         */
        public function setOperationFlash()
        {
            $this->operation = MOBYT_OPERATION_FLASH;
        }

        /**
         * Controlla il credito disponibile espresso in Euro
         *
         * @returns mixed Un intero corrispondente al credito o <i>FALSE</i> in caso di errore
         *
         * @example sms-credit.php Controllo credito e messaggi disponibili
         */
        public function getCredit()
        {
            $op = 'GETCREDIT';

            $fields = array(
                    'operation' => $op,
                    'id'		=> $this->login,
                    'password'	=> $this->auth == MOBYT_AUTH_MD5 ? '' : $this->pwd,
                    'ticket'	=> $this->auth == MOBYT_AUTH_MD5 ? md5($this->login.$op.$this->pwd) : ''
            );

            if (preg_match('/^OK (\d+)/', $this->httpPost($fields), $m))
                return intval($m[1]);

            return false;
        }

        /**
         * Controlla il numero approssimativo di messaggi disponibili
         *
         * <b>N.B.</b> Il numero di messaggi disponibile dipende dalla qualit� con cui verranno inviati.
         *
         * @returns mixed Un intero corrispondente al numero di messaggi o <i>FALSE</i> in caso di errore
         *
         * @example sms-credit.php Controllo credito e messaggi disponibili
         */
        public function getAvailableSms()
        {
            $op = 'GETMESS';

            $fields = array(
                    'operation' => $op,
                    'id'		=> $this->login,
                    'password'	=> $this->auth == MOBYT_AUTH_MD5 ? '' : $this->pwd,
                    'ticket'	=> $this->auth == MOBYT_AUTH_MD5 ? md5($this->login.$op.$this->pwd) : ''
            );

            if (preg_match('/^OK (\d+)/', $this->httpPost($fields), $m))
                return intval($m[1]);

            return false;
        }

        /**
         * Controlla il numero di notifiche disponibili
         *
         * @returns mixed Un intero corrispondente al numero di notifiche o <i>FALSE</i> in caso di errore
         */
        public function getAvailableNotifies()
        {
            $op = 'GETNOTIFY';

            $fields = array(
                    'operation' => $op,
                    'id'		=> $this->login,
                    'password'	=> $this->auth == MOBYT_AUTH_MD5 ? '' : $this->pwd,
                    'ticket'	=> $this->auth == MOBYT_AUTH_MD5 ? md5($this->login.$op.$this->pwd) : ''
            );

            if (preg_match('/^OK (\d+)/', $this->httpPost($fields), $m))
                return intval($m[1]);

            return false;
        }

        /**
         * Invia un SMS
         *
         * Nel caso sia utilizzata la qualit� automatica con notifica, ser� necessario passare un identificatore univoco di max 20 caratteri numerici come terzo parametro. Qualora non venisse impostato, ne verr� generato uno casuale in maniera automatica, per permettere il corretto invio del messaggio.
         *
         * @param string Numero telefonico con prefisso internazionale (es. +393201234567)
         * @param string Testo del messaggio (max 160 caratteri)
         * @param string Identificatore univoco del messaggio da utilizzare nel caso sia richiesta la notifica
         *
         * @returns string Risposta ricevuta dal gateway ("OK ..." o "KO ...")
         *
         * @example sms-single.php Invio di un singolo sms in alta qualit� con autenticazione MD5
         */
        public function sendSms($rcpt, $text, $act = '')
        {
            global $mobyt_qty, $mobyt_ops;

            $operation = isset($mobyt_ops[$this->operation]) ? $mobyt_ops[$this->operation] : 'TEXT';

            $fields = array(
                    'operation' => $operation,
                    'from'		=> $this->from,
                    'rcpt'		=> $rcpt,
                    'data'		=> $text,
                    'id'		=> $this->login
            );

            if ($this->quality == MOBYT_QUALITY_AUTO_NY) {
                if ($act == '') {
                    // Generate random act
                    while (strlen($act) < 16)
                        $act .= preg_replace('/[^0-9]/', '', md5(uniqid('', true)));

                    if (strlen($act) > 20)
                        $act = substr($act, 0, 20);
                }

                $fields['act'] = $act;
            }

            if ($this->quality != MOBYT_QUALITY_DEFAULT && isset($mobyt_qty[$this->quality]))
                $fields['qty'] = $mobyt_qty[$this->quality];

            if ($this->auth == MOBYT_AUTH_MD5) {
                $fields['password'] = '';
                $fields['ticket'] = md5($this->login.$operation.$rcpt.$this->from.$text.$this->pwd);
            } else {
                $fields['password'] = $this->pwd;
                $fields['ticket'] = '';
            }

            if ($this->operation == MOBYT_OPERATION_8BIT)
                $fields['udh'] = $this->udh;

            return trim($this->httpPost($fields));
        }

        /**
         * Invia un SMS a pi� destinatari
         *
         * Nel caso sia utilizzata la qualit� automatica con notifica, ser� necessario passare un array associativo come primo parametro, le cui chiavi siano identificatori univoci di max 20 caratteri numerici.
         *
         * @example sms-multi.php Invio di un sms a pi� numeri in media qualit� con autenticazione tramite password in chiaro
         *
         * @param array Array di numeri telefonici con prefisso internazionale (es. +393201234567)
         * @param string Testo del messaggio (max 160 caratteri)
         *
         * @returns string Elenco di risposte ricevute dal gateway ("OK ..." o "KO ..."), separate da caratteri di "a capo" (\n)
         */
        public function sendMultiSms($rcpts, $text)
        {
            global $mobyt_qty, $mobyt_ops;

            if (!is_array($rcpts))
                return $this->sendSms($rcpts, $text);

            $operation = isset($mobyt_ops[$this->operation]) ? $mobyt_ops[$this->operation] : 'TEXT';

            $fields = array(
                    'id'		=> $this->login,
                    'password'	=> $this->auth == MOBYT_AUTH_MD5 ? '' : $this->pwd,
                    'operation' => $operation,
                    'from'		=> $this->from,
                    'data'		=> $text
            );

            if ($this->quality != MOBYT_QUALITY_DEFAULT && isset($mobyt_qty[$this->quality]))
                $fields['qty'] = $mobyt_qty[$this->quality];

            if ($this->operation == MOBYT_OPERATION_8BIT)
                $fileds['udh'] = $this->udh;

            $ret = array();
            foreach ($rcpts as $act => $rcpt) {
                $fields['rcpt']  = $rcpt;
                $fields['ticket'] = $this->auth == MOBYT_AUTH_MD5 ?
                md5($this->login.$operation.$rcpt.$this->from.$text.$this->pwd) :
                '';

                if ($this->quality == MOBYT_QUALITY_AUTO_NY)
                    $fields['act'] = $act;

                $ret[] = trim($this->httpPost($fields));
            }

            return join("\n", $ret);
        }

        /**
         * Send an HTTP POST request, choosing either cURL or fsockopen
         *
         * @access private
         */
        public function httpPost($fields, $url = '/sms-gw/sendsmart')
        {
            $qs = array();
            foreach ($fields as $k => $v)
                $qs[] = $k.'='.urlencode($v);
            $qs = join('&', $qs);

            if (function_exists('curl_init'))
                return mobytSms::httpPostCurl($qs, $url);

            $errno = $errstr = '';
            if ($fp = @fsockopen('smsweb.mobyt.it', 80, $errno, $errstr, 30)) {
                fputs($fp, "POST ".$url." HTTP/1.0\r\n");
                fputs($fp, "Host: smsweb.mobyt.it\r\n");
                fputs($fp, "User-Agent: phpMobytSms/".MOBYT_PHPSMS_VERSION."\r\n");
                fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-Length: ".strlen($qs)."\r\n");
                fputs($fp, "Connection: close\r\n");
                fputs($fp, "\r\n".$qs);

                $content = '';
                while (!feof($fp))
                    $content .= fgets($fp, 1024);

                fclose($fp);

                return preg_replace("/^.*?\r\n\r\n/s", '', $content);
            }

            return false;
        }

        /**
         * Send an HTTP POST request, through cURL
         *
         * @access private
         */
        public function httpPostCurl($qs, $url)
        {
            if ($ch = @curl_init('http://smsweb.mobyt.it'.$url)) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'phpMobytSms/'.MOBYT_PHPSMS_VERSION.' (curl)');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $qs);

                return curl_exec($ch);
            }
            //echo curl_error($ch);
            return false;
        }

        /**
         * Converti stringa in formato esadecimale OTA, per invio RING, 8BIT, ecc...
         *
         * @param string Stringa da convertire
         *
         * @return string
         */
        public function stringToOTA($str)
        {
            $ret = '';

            $len = strlen($str);
            for ($x = 0; $x < $len; $x++)
                $ret .= sprintf('%X', ord($str{$x}));

            return $ret;
        }
    }
}
