<?php
/**
 * Interfaccia del template engine.
 * I comandi tutta via devono essere costruiti rispettando l'interfaccia mostrata 
 * in questa classe, in modo che in futuro possano essere costruite classi Wrapper
 * per altri TemplateEngines (esempio: xtemplate, pear::template). 
 * Allo stato attuale nel framework viene restituita una istanza di MySmarty,
 * che implementa l'interfaccia.
 *
 * @package framework
 * @param $identifier string indentificativo della variabile del template
 * @param $var mixed contenuto da assegnare alla varibile
 */
interface TemplateEngine {

    /**
     * Assegna il contenuto di $var ad una variabile del template
     * specificata da un identificativo 
     *
     * @param $identifier string indentificativo della variabile del template
     * @param $var mixed contenuto da assegnare alla varibile
     */
    function assign($identifier, $var);

    /**
     * Assegna il contenuto di $var "appendendolo" ad una variabile array 
     * del template specificata dall'identificativo, se non esiste crea l'array 
     *
     * @param $identifier string indentificativo della variabile del template
     * @param $var mixed contenuto da assegnare alla varibile
     */
    function append($identifier, $var);

    /**
     * Restituisce il contenuto di una variabile del template.
     * Se la varaibile non è specificata restituisce un array associativo 
     * contenente tutte le varaibili specificate fino ad ora
     *
     * @param $identifier string indentificativo della variabile del template
     * @return mixed
     */
    function get_template_vars($identifier = NULL);

    /**
     * Verifica l'esistenza di un file di template
     *
     * @param $template_name string nome del file del template all'interno 
     * 	della direcotry dei template
     * @return boolean
     */
    function template_exists($template_name);

    /**
     * Mostra l'output del template.
     *
     * @param $template_name string nome del file del template all'interno 
     * 	della direcotry dei template
     * @return mixed
     */
    function display($template_name);
}
