<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework;

/**
 * Writes logs file to preserve important information about
 * framework, can be extended for saving and application actions.
 *
 * It's created with an array configuration and an indentifier
 * that defines the log format (what must be saved in logs).
 * Can handle multiple log resources of different types.
 * Saves log information on filesystems in CVS format in
 * different files for each log type.
 * Saves log requests with the addLogEntry() method
 *
 * @package framework
 * @version 2.0.0
 * @author  Ilias Bartolini
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */


class LogHandler
{
    public $csv_text_delimiter = '"';

    public $csv_separator = ';';

    public $path = '';

    public $file_name = '';

    public $identifier = '';

    public $definition = array();

    public $count_values = 0;


    /**
     * Creates a LogHandler object.
     *
     * @param string $logging_path filesystem path where logs are saved
     *             (system should have read-write rights on this directory)
     * @param string $type_indentifier log type identifier for this handler object
     * @param array  $type_definition  ordered array of column names/types to add in CSV file
     */
    public function __construct($type_indentifier, $logging_path, $type_definition)
    {
        $this->identifier = $type_indentifier;
        $this->path = $logging_path;
        $this->file_name = date('Y').'-'.date('m').'-'.date('d').'-'.
                            $type_indentifier.'.log.csv';
        $this->count_values = count($type_definition);
        $this->definition = $type_definition;
    }



    /**
     * Adds an entry in log file.
     *
     * If entry parameter is undefined default value is an empty string
     *
     * @param  array   $entry associative array of column<->values to add
     * @return boolean true: successfull, false: error
     */
    public function addLogEntry($entry)
    {
        $string='';
        for ($i=0; $i < $this->count_values; $i++) {
            if (!array_key_exists($this->definition[$i],$entry) ) {
                $curr_value = '';
            } else {
                $curr_value = $entry[$this->definition[$i]];
            }

            $curr_value = str_replace($this->csv_text_delimiter, $this->csv_text_delimiter.$this->csv_text_delimiter, $curr_value);
            $curr_value = str_replace("\n",'|', $curr_value);
            $string .= $this->csv_text_delimiter.$curr_value.$this->csv_text_delimiter;
            if ($i < $this->count_values -1) {
                $string .= $this->csv_separator;
            }
        }

        $string .= "\n";
        $this->_addCsvLine($this->path.$this->file_name, $string);

    }




    /**
     * Return csv header line according to current Log format definition.
     *
     * @return string
     * @access private
     */
    public function _getHeaderLine()
    {
        $string = '';
        for ($i=0; $i < $this->count_values; $i++) {
            $curr_value = $this->definition[$i];

            $curr_value = str_replace($this->csv_text_delimiter, $this->csv_text_delimiter.$this->csv_text_delimiter, $curr_value);
            $curr_value = str_replace("\n",'|', $curr_value);
            $string .= $this->csv_text_delimiter.$curr_value.$this->csv_text_delimiter;
            if ($i < $this->count_values -1) {
                $string .= $this->csv_separator;
            }
        }

        $string .= "\n";


        return $string;

    }




    /**
     * Adds a new entry in the csv log file
     * It takes care about file "lock" between multiple requests.
     *
     * @param string $file $logging_path filesystem path where logs are saved
     *             (system should have read-write rights on this directory)
     * @param string $string line in csv format "column1";"column2";..;"columnN"\n
     * @access private
     */
    public function _addCsvLine($full_file_name, $string)
    {
        $addHeader = false;
        if ( !file_exists($full_file_name) == true ) {
            $addHeader = true;
        }

        $fp = fopen ($full_file_name, "a");

        if ($fp === false) return false;

        flock ($fp,2);
        if ( $addHeader == true ) {
            $header = $this->_getHeaderLine();
            fwrite($fp, $header);
        }
        fwrite($fp, $string);
        fflush ($fp);
        flock($fp,3);
        fclose($fp);
    }

}
