<?php
namespace Universibo\Bundle\LegacyBundle\App\AntiVirus;

/**
 * Classe antivirus per clamav
 *
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2013
 */
class Clamav
{
    /**
     * Command line options
     * 
     * @var type 
     */
    private $opts = '';

    /**
     * Clamav command
     * 
     * @var string
     */
    private $cmd  = '';

    /**
     * class constructor
     * 
     * @param string $cmd
     * @param string $opts
     * @param boolean $enabled
     */
    public function __construct($cmd, $opts, $enabled)
    {
        $this->cmd     = $cmd;
        $this->opts    = $opts;
        $this->enabled = $enabled;
    }

    /**
     * @return true se ci sono virus, false se _non_ ci sono virus
     */
    public function checkFile($filename)
    {
        if(!$this->enabled) {
            return false;
        }
        
        $filename = escapeshellarg($filename);

        $fullCommand =  $this->cmd.' '.$this->opts.' '.$filename;

        $output = array();
        $returnval = null;

        exec ( $fullCommand, $output, $returnval );
        
        return $returnval != 0;
    }
}
