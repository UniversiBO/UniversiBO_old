<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Commenti;

/**
 * CommentoItem class
 *
 * Rappresenta un singolo commento su un FileStudente.
 *
 * @package universibo
 * @subpackage Commenti
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @author Fabrizio Pinto
 * @author Davide Bellettini
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class CommentoItem
{
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';

    /**
     * @var int
     */
    private $id_commento = 0;
    /**
     * @var int
     */
    private $id_file_studente = 0;
    /**
     * @var int
     */
    private $id_utente = 0;
    /**
     * @var string
     */
    private $commento = '';
    /**
     * @var int
     */
    private $voto = -1;

    /**
     * @var string
     */
    private $eliminato = self::NOT_ELIMINATO;

    /**
     * Crea un oggetto CommentoItem
     * @param $id_file_studente id di un File Studente
     * @param $id_utente id di un utente, quello che ha fatto il commento
     * @param $commento commento a un File Studente
     * @param $voto proposto per un file studente
     */

    public function __construct($id_commento, $id_file_studente, $id_utente,
            $commento, $voto, $eliminato)
    {
        $this->id_commento = $id_commento;
        $this->id_file_studente = $id_file_studente;
        $this->id_utente = $id_utente;
        $this->commento = $commento;
        $this->voto = $voto;
        $this->eliminato = $eliminato;
    }

    public function getIdCommento()
    {
        return $this->id_commento;
    }

    public function isEliminato()
    {
        return $this->eliminato === self::ELIMINATO;
    }

    /**
     * Restituisce l'id_file_studente del commento
     */

    public function getIdFileStudente()
    {
        return $this->id_file_studente;
    }

    /**
     * Setta l'id_file_studente del commento
     */

    public function setIdFileStudente($id_file_studente)
    {
        $this->id_file_studente = $id_file_studente;
    }

    /**
     * Restituisce l'id_utente che ha scritto il commento
     */

    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * Setta l'id_utente che ha scritto il commento
     */

    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Restituisce il commento al File Studente
     */

    public function getCommento()
    {
        return $this->commento;
    }

    /**
     * Setta il commento al File Studente
     */

    public function setCommento($commento)
    {
        $this->commento = $commento;
    }

    /**
     * Restituisce il voto associato al file studente
     */

    public function getVoto()
    {
        return $this->voto;
    }

    /**
     * Setta il voto associato al File Studente
     */

    public function setVoto($voto)
    {
        $this->voto = $voto;
    }
}
