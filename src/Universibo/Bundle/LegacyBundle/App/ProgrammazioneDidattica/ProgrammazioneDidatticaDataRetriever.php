<?php
namespace Universibo\Bundle\LegacyBundle\App\ProgrammazioneDidattica;
/**
 * ProgrammazioneDidatticaDataRetriever
 *
 * Classe interfaccia per descrivere un oggetto che permetta
 * di recuperare i dati della programmazione didattica
 *
 * @version 2.1.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */
interface ProgrammazioneDidatticaDataRetriever
{
    /**
     * @return Facolta[]
     */
    public function getFacoltaList();

    /**
     * @param  string  $codFac
     * @return Facolta
     */
    public function getFacolta($codFac);

    /**
     * @param  string  $codFac
     * @return Corso[]
     */
    public function getCorsoListFacolta($codFac);

    /**
     * @param  string $codCorso
     * @return Corso
     */
    public function getCorso($codCorso);

    /**
     * @param  string  $codMateria
     * @return Materia
     */
    public function getMateria($codMateria);

    /**
     * @param  string  $codDoc
     * @return Docente
     */
    public function getDocente($codDoc);

    /**
     * @param  string              $codCorso
     * @param  int                 $annoAccademico
     * @return AttivitaDidattica[]
     */
    public function getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico);

    /**
     * @param  string              $codCorso
     * @param  int                 $annoAccademico
     * @return AttivitaDidattica[]
     */
    public function getAttivitaDidatticaCorso($codCorso, $annoAccademico);

    /**
     * @param  AttivitaDidattica   $attivitaPadre
     * @return AttivitaDidattica[]
     */
    public function getSdoppiamentiAttivitaDidattica($attivitaPadre);
}
