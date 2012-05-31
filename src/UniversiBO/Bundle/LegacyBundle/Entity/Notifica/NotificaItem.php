<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Notifica;
use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 *
 * NotificaItem class
 *
 * Rappresenta una singola Notifica.
 *
 * @package universibo
 * @subpackage Notifica
 * @version 2.0.0
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class NotificaItem
{
    const ELIMINATA = 'S';
    const NOT_ELIMINATA = 'N';

    const URGENTE = 'S';
    const NOT_URGENTE = 'N';
    /**
     * @var string
     */
    private $titolo = '';

    /**
     * @var string
     */
    private $messaggio = '';

    /**
     * data e ora di inserimento
     * @var int
     */
    private $timestamp = 0;

    /**
     * @var boolean
     */
    private $urgente = false;

    /**
     * @var int
     */
    private $id_notifica = 0;

    /**
     * @var boolean
     */
    private $eliminata = false;

    /**
     * @var string
     */
    private $destinatario = '';

    /**
     * @var string
     */
    private $error = '';

    /**
     * @var DBNotificaItemRepository
     */
    private static $repository;

    /**
     * Crea un oggetto NotificaItem con i parametri passati
     *
     *
     * @param  int      $id_notifica id della news
     * @param  string   $titolo      titolo della news max 150 caratteri
     * @param  string   $messaggio   corpo della news
     * @param  int      $timestamp   timestamp dell'inserimento
     * @param  boolean  $urgente     flag notizia urgente o meno
     * @param  boolean  $eliminata   flag stato della news
     * @return NewsItem
     */

    public function __construct($id_notifica, $titolo, $messaggio, $dataIns,
            $urgente, $eliminata, $destinatario)
    {
        $this->id_notifica = $id_notifica;
        $this->titolo = $titolo;
        $this->messaggio = $messaggio;
        $this->timestamp = $dataIns;
        $this->urgente = $urgente;
        $this->eliminata = $eliminata;
        $this->destinatario = $destinatario;
    }

    /**
     * Recupera il titolo della notifica
     *
     * @return String
     */
    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * Recupera il titolo della notifica
     *
     * @return String
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Recupera il testo della notifica
     *
     * @return string
     */
    public function getMessaggio()
    {
        return $this->messaggio;
    }

    /**
     * Recupera la data di inserimento della notifica
     *
     * @return int
     */
    public function getDataIns()
    {
        return $this->timestamp;
    }

    /**
     * Recupera eventuali errori della notifica
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Recupera l'urgenza della notifica
     *
     * @return boolean
     */
    public function isUrgente()
    {
        return $this->urgente;
    }

    /**
     * Recupera l'id della notifica
     *
     * @return int
     */
    public function getIdNotifica()
    {
        return $this->id_notifica;
    }

    /**
     * Recupera il destinatario
     *
     * @return string
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Recupera il protocollo
     *
     * @return string
     */
    public function getIndirizzo()
    {
        $strarr = explode('://', $this->getDestinatario());

        return strtolower($strarr[1]);
    }

    /**
     * Recupera il protocollo
     *
     * @return string
     */
    public function getProtocollo()
    {
        $strarr = explode('://', $this->getDestinatario());

        return strtolower($strarr[0]);
    }

    /**
     * Imposta il destinatario "protocollo://indirizzo"
     *
     * @param string $destinatario destinatario della news max 150 caratteri
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * Recupera lo stato della notifica
     *
     * @return boolean
     */
    public function isEliminata()
    {
        return $this->eliminata;
    }

    /**
     * Imposta il titolo della notifica
     *
     * @param string $titolo titolo della news max 150 caratteri
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    }

    /**
     * Imposta il testo della notifica
     *
     * @param string $notifica corpo della news
     */
    public function setMessaggio($messaggio)
    {
        $this->messaggio = $messaggio;
    }

    /**
     * Imposta la data di inserimento della notifica
     *
     * @param int $dataIns timestamp del giorno di inserimento
     */
    public function setDataIns($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Imposta l'urgenza della notifica
     *
     * @param boolean $urgente flag notifica urgente o meno
     */
    public function setUrgente($urgente)
    {
        $this->urgente = $urgente;
    }

    /**
     *
     * Imposta l'id della notifica
     *
     * @param int $id_notifica id della news
     */
    public function setIdNotifica($id_notifica)
    {
        $this->id_notifica = $id_notifica;
    }

    /**
     * Imposta lo stato della notifica
     *
     * @param boolean $eliminata flag stato della news
     */
    public function setEliminata($eliminata)
    {
        $this->eliminata = $eliminata;
    }

    /**
     * Inserisce una notifica sul DB
     *
     * @deprecated
     * @param  array   $array_id_canali elenco dei canali in cui bisogna inserire la notifica. Se non si passa un canale si recupera quello corrente.
     * @return boolean true se avvenua con successo, altrimenti Error object
     */

    public function insertNotificaItem()
    {
        ignore_user_abort(1);
        $result = self::getRepository()->insert($this);
        ignore_user_abort(0);

        return $result;
    }

    /**
     * @return DBNotificaItemRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBNotificaItemRepository(
                    FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}

define('NOTIFICA_ELIMINATA', NotificaItem::ELIMINATA);
define('NOTIFICA_NOT_ELIMINATA', NotificaItem::NOT_ELIMINATA);

define('NOTIFICA_URGENTE', NotificaItem::URGENTE);
define('NOTIFICA_NOT_URGENTE', NotificaItem::NOT_URGENTE);
