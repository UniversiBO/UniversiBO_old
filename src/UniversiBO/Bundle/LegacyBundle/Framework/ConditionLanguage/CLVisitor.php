<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

use \JavaClass;

class CLVisitor
{
    var $level = 0;

       var $listaVariabili = array();
       var $listaOps = array();
       var $trace_enabled;
       var $debug_enabled;
       var $debug_verbose_level;
       var $esito = false;
       // stack
       var $st;
       // segnala se almeno una start condition è false
       private $stop = false;

       private $executorFactory;

    /**
     * Costruttore
     *
     * @param boolean $trace   attiva la tracciabilità delle chiamate
     * @param boolean $debug   attiva il debug dell'interprete
     * @param integer $verbose imposta la verbosità del debug
     */
    public function __construct(ExecutorFactory $executorFactory, $ops = null, $var = null, $trace = false, $debug = false, $verbose = 1)
    {
        $this->executorFactory = $executorFactory;

        $this->st = new Stack();
        if ($ops!= null) $this->listaOps = $ops;
        if ($var!= null) $this->listaVariabili = $var;
        $this->debug_enabled = $debug;
        $this->trace_enabled = $trace;
        $this->debug_verbose_level = $verbose;
    }

    /**
     * Restituisce l'esito dell'interpretazione
     *
     * @return mixed o boolean??
     */
    function getEsito()
    { // TODO definire come passare il o i parametri finali dell'interpretazione

        return $this->esito;
    }


    /**
     * restituisce la tabella degli operatori
     *
     * @return array
     */
    function getOpsTable()
    {
        return $this->listaOps;
    }

    /**
     * restituisce la tabella delle variabili
     *
     * @return array
     */
    function getVarTable()
    {
        return $this->listaVariabili;
    }

    /**************************************************************************
     * 		METODI PER L'INTERPRETAZIONE									  *
     **************************************************************************/

    /**
     * recupera le entità attualmente definite e quelle di base del framework
     *
     * @param	string	identifier
     * @return 	object
     */
    function getEntity($identifier)
    {
        $this->execMe('entity', array('codice' => $identifier));

        return $this->st->pop();
    }


    /**
     * Interpreta il body di un namespace
     *
     * @param string $executor     nome del namespace
     * @param array  $codice	array cosituito da: 'input' => array associativo dei parametri, 'codice' => string con il codice
     * @access private
     */
    function execMe($executor, $codice)
    {
        $ret = $this->executorFactory->dispatch(strtolower($executor),$codice);
        $this->debug('Executor: '.$executor.' Codice: '.$codice.' Esito: '.print_r($ret,true)."\n",1);
        $this->st->groupedPush($ret);
    }


    /**
    * metodo per l'interpretazione degli operatori in accordo alla loro priorit� ed associativit�
    *
    * @param Operator $currentOp
    * @param term $rightTerm nodo AST
    * @param int $i
    * @param Operator $lastOp
    * @param int $length
    * @param NodeListOptional $nodeList
    * @access private
    * @return mixed null o Operator se il prossimo nodo da visitare non � pi� il succesivo del precedente
    */
   function exploreNextOp($currentOp, $rightTerm, $i, &$lastOp, $length,&$nodeList)
   {
           $null = null;
           // TODO NB in caso di errore bisogna svuotare lo stack degli elementi aggiunti (rollback sullo stack?) ? mm se si interrompe la visita non serve svuotare lo stack
           if(!$currentOp->isBinary())
           {
               $this->semanticError("L'operatore $currentOp->nome non � binario", $currentOp->referrer);

               return $null; // TODO se semanticError interrompe il flusso di esecuzione, allora questo return � ridondante
           }

           $this->debug('ExploreNextOp: index '.$i.' length '.$length.' sto per chiamare '. $this->node2string($rightTerm),2);
        $rightTerm->accept($this->callMe()); // (right member)
           if($i == $length-1)
           {
               $this->calcola($currentOp);

               return $null;
           }
           else if($i > $length-1)
           {
               $this->semanticError('Indice non valido', $currentOp->referrer);

               return $null; // TODO se semanticError interrompe il flusso di esecuzione, allora questo return � ridondante
           }

           $i_next = $i+1;
        $this->debug('ExploreNextOp: indexNext '.$i_next.' length '.$length,4);
           $nextOp = $this->getOp($nodeList->elementAt($i_next)->elementAt(0));
           $this->debug('ExploreNextOp: CURROP '.$currentOp->nome.' NEXTOP '.$nextOp->nome,2);

           while(true)
           {

               if($currentOp->isMajorPriorityTo($nextOp) ||
                   ($currentOp->isEqualPriorityTo($nextOp) && $currentOp->isLeftAssociative()))
               {
                   // calcolo io
                   $this->calcola($currentOp);

                   if($lastOp != null &&
                           ($lastOp->isMajorPriorityTo($nextOp) ||
                               ($nextOp->isEqualPriorityTo($lastOp) && $lastOp->isLeftAssociative())
                           )
                       )
                   {
       				// calcola poi il precedente
       				$ret = array( 'op' => $nextOp, 'indice' => $i_next);
       				return $ret;
                   }
                   else
                       // calcola poi il successivo
                       return $this->exploreNextOp($nextOp, $nodeList->elementAt($i_next)->elementAt(1), $i_next, $lastOp, $length, $nodeList);
               }
               else
               {

                   //calcola il successivo poi io
                   $n = $this->exploreNextOp($nextOp, $nodeList->elementAt($i_next)->elementAt(1),$i_next, $currentOp, $length, $nodeList);
                   if($n == null)
                   {
                       $this->calcola($currentOp);

                       return $null;
                   }
                   $nextOp = $n['op'];
                   $i_next = $n['indice'];
               }
           }
   }

    /**
     * ritorna la descrizione di un operatore
     *
     * @param NodeToken $op nodo AST dell'operatore
     * @return array descrizione dell'operatore
     * @access private
     */
    private function getOp($op)
    {
        //var_dump($this->node2string($op)); var_dump(Operator::translateNameToId($this->node2string($op)));
        return $this->listaOps[Operator::translateNameToId($this->node2string($op,true))];
    }

    /**
     * Esegue l'operazione rischiesta. NB si assume che i parametri necessari siano nello stack
     *
     * @param  Operator $op
     * @access private
     */
    function calcola(Operator $op, $filtro = null)
    {
        $input = array();
        $chiavi = $op->inputFormat->getMask();
        for ($i = $op->nIn -1; $i >= 0; $i--) // NB cos� facendo suppongo che i parametri da sostituire partano da 1 a N
            $input[$chiavi[$i]] = ($op->inputFormat->isGrouped($chiavi[$i])) ? $this->st->groupedPop() :$this->st->pop();
        $s='';
        foreach($input as $k => $t)
            $s .= $k.' => '.$t."\t".gettype($t).'; ';
//			$this->debug('OP: '.$op.' CALCOLO: ' . $t, 2);
//		if (!$op->inputFormat->checkFormatByName($input))
//			$this->semanticError('input di '.$op->nome .' non valido',$op->referrer);
        //NB le sostituzioni vengono fatte secondo l'ordine di creazione degli array input e pattern. E' importante partire dai numeri pi� grandi e decrescere
        $this->debug('OP: '.$op->nome.' CALCOLO: ' . $s, -1);
        $this->execMe($op->executor, array('input' => $input, 'codice' => $op->codice, 'outMask' => $op->outputFormat->getMask()));
        if($filtro != null)
        {
            $outputValues = $this->st->groupedPop();
//			var_dump($outputValues);
            $values = ParamListFormat::filterValues($outputValues,$filtro['mask'],$op->outputFormat);

            if($values === false) $this->semanticError('i parametri del filtro '.$filtro['mask'].' non esistono tra i parametri di output '.$op->outputFormat, $op->referrer);
//			var_dump($values); die;
            if($filtro['disjointed'])
                $this->st->groupedPush($values);
            else
                $this->st->push($values);
        }
    }

    /**
     * Stampa l'errore semantico riscontrato nel parsing
     * @param message l'errore riscontrato
     * @param n	il nodo dell'AST in cui si è riscontrato l'errore
     */
    function semanticError($message, $n = null)
    {
        $ln = new JavaClass("util.LineNumberInfo");
//		throw new Exception($message+" "+ $ln->get($n)->desc());
        $this->debug("***** ERROR ***** ".$message." ". (($n != null) ? $ln->get($n)->desc(): ''),0);
//		var_dump($this->listaVariabili);
        $this->debug($this->st->debug(),0);
        @java_reset();
        die;
    }



    /**************************************************************************
     * 		METODI HELPER													  *
     **************************************************************************/

    /**
     * Helper per interfacciarsi a php/Java bridge e mantenere il codice più leggibile
     *
     * @access private
     * @return php/java bridge object
     */
    function callMe()
    {
        return java_get_closure($this);
    }

    /**
     * stampa a video formattata di una stringa. Usata nel debug e nel trace
     *
     * @param string $s
     * @access private
     */
    function printMe($s)
    {
        print $this->indentLevel().$s;
    }

    function node2string($n, $lowercase = false)
    {
        $s = java_cast($n->toString(), "string");
        if ($lowercase) $s = strtolower($s);

        return $s;
    }

    /**
     * Helper per formattare il valore di un NodeToken
     *
     * @param string $s
     * @return string
     * @access private
     */
    function toSchemeString($s) {
        return str_replace('"','//',$s);
       }

       /**
        * Helper per indentare in accordo al livello dell'albero
        *
        * @return string
        * @access private
        */
    function indentLevel()
   {
    $indent = "\n";
    for ($i = 0 ; $i < $this->level; $i++) $indent = $indent . "  ";

    return $indent;
   }

       /**************************************************************************
     * 		METODI PER IL TRACE E DEBUG										  *
     **************************************************************************/

    /**
     * Metodo che stampa nell'output le volute informazioni di debug, in accordo al livello desiderato
     *
     * @param string $s      informazione di debug
     * @param int    $v      livello di debug cui � associata l'informazione di debug
     * @param string $prefix prefisso per contraddistinguere meglio le informazioni nell'output di debug
     * @access private
     */
    function debug($s, $v = 1, $prefix = '')
    {
        if ($this->debug_enabled && $v <= $this->debug_verbose_level)
        {
            if(is_bool($s)) $s = ($s) ? 'true' : 'false';
            $this->printMe('[DEBUG] '.$prefix.$s);
        }
    }

  function trace_call($s) {
    if ($this->trace_enabled) {
        $this->printMe("Call:   " . $s);
    }
    $this->level++;
  }

  function trace_return($s) {
    $this->level--;
    if ($this->trace_enabled) {
        $end = ($this->level == 0)? "\n" : '';
        $this->printMe("Return: " . $s.$end);
    }
  }



      /**************************************************************************
     * 		AST VISITING													  *
     **************************************************************************/

      /**
       * visita il nodo passato come argomento (pattern Visitor)
       * effettua il dispatch in base al nodo
       *
       * @param object $node nodo dell'AST
       * @access public
       */
    function visit($node)
    {
        $cl = substr(strrchr($node->getClass()->toString(), "."), 1);
//		echo "\n\n".  $cl ."\n\n";
//		echo $this->st->debug();
//		$this->java_inspect($node);
//		echo "\n\n";

        $this->trace_call($cl);
        switch($cl)
        {
            case "NodeListOptional":
            case "NodeList": $this->visitNodeList($node); break;
            case "namespace": $this->visitNamespace($node); break;
            case "NodeOptional": $this->visitNodeOptional($node); break;
            case "NodeToken": $this->visitNodeToken($node); break;
            case "start": $this->visitStart($node); break;
            case "istruzione": $this->visitIstruzione($node); break;
            case "opDefinition": $this->visitOpDef($node); break;
            case "eventDefinition": $this->visitEventDef($node); break;
            case "startDefinition": $this->visitStartDef($node); break;
            case "condition": $this->visitCondition($node); break;
            case "outputFilter" : $this->visitFilter($node); break;
            case "listaParam" : $this->visitListaParam($node); break;
            case "term": $this->visitTerm($node); break;
            case "lista": $this->visitList($node); break;
            case "elem": $this->visitElem($node); break;
            default: $this->semanticError("errore nel dispatch!",$node);

        }
        $this->trace_return($cl);
//		$this->debug($this->st->debug(),1);
    }

    /****************************** DA QUI IN GI� TUTTI ACCESS PRIVATE  *********************************/

    /**
     * va bene sia per NodeList che per NodeListOptional
     */
   function visitNodeList($node) {
      for ( $e = $node->elements(); $e->hasMoreElements(); )
      {
          if ($this->stop) return;
          $e->nextElement()->accept($this->callMe());
      }
   }

   function visitNodeOptional ($n) {
      if ( $n->present() )
         $n->node->accept($this->callMe());
   }

   function visitNodeToken ($n) {
      $this->debug('NODETOKEN: '.$this->toSchemeString($this->node2string($n)),3);
//      $this->st->push($n->tokenImage);
   }


   /**
    * f0 -> ( istruzione() )*
    */
   function visitStart($n) {
          $n->f0->accept($this->callMe());
        $this->esito = ! $this->stop;  // true se non ci sono startDef false
        $this->debug('ESITO: '.$this->esito.' STOP: '.$this->stop,-1);
   }

   /**
    * f0 -> eventDefinition()
    *       | startDefinition()
    *       | opDefinition()
    * @throws Exception
    */
    function visitIstruzione ($n){
        if(!$this->stop)
            $n->f0->choice->accept($this->callMe());
    }

    /**
    * f0 -> <EVENT>
    * f1 -> <VARIABILE>
    * f2 -> <DEF>
    * f3 -> condition()
    * f4 -> <END>
    */
     function visitEventDef ($t){
        if (array_key_exists(md5($this->node2string($t->f1,true)), $this->listaVariabili ))
            $this->semanticError("Variabile gi� esistente", $t);
        $this->listaVariabili[md5($this->node2string($t->f1,true))] = $t->f3; // TODO verificare che key �
        $this->debug('VAR:'. count($this->listaVariabili),1);
     }

    /**
    * f0 -> <STARTS>
    * f1 -> <WHEN>
    * f2 -> condition()
    * f3 -> ( <ENDS> <WHEN> condition() )?
    * f4 -> <END>
    */
    function visitStartDef ($t){
        $o = $t->f3;
        if ($o->present())
        {
            $o->node->elementAt(2)->accept($this->callMe());
            $b = $this->st->pop();
            // se le condizioni di fine sono attive, l'istruzione risulta globalmente falsa
            if ($b) {$this->debug('START: false, POP: '.$b); $this->stop = true; return;}
        }
        $t->f2->accept($this->callMe());
        if (($b=$this->st->pop()) == false) { $this->debug('START: false, POP: '.$b); $this->stop = true; }
    }


   /**
    * f0 -> <DEF_OP>
    * f1 -> <O_BRACE>
    * f2 -> <OP>
    * f3 -> <LIST_SEP>
    * f4 -> <NUM>
    * f5 -> <LIST_SEP>
    * f6 -> <NUM>
    * f7 -> <C_BRACE>
    * f8 -> <IN>
    * f9 -> listaParam()
    * f10 -> <STOP>
    * f11 -> <OUT>
    * f12 -> listaParam()
    * f13 -> <STOP>
    * f14 -> <DEF>
    * f15 -> <NS>
    * f16 -> <VARIABILE>
    * f17 -> <NSLEFT>
    * f18 -> <NSRIGHT>
    * f19 -> <END>
    */
    function visitOpDef ($t){
        if (array_key_exists(md5($this->node2string($t->f2,true)), $this->listaOps ))
            $this->semanticError("Operatore gi� esistente", $t);
        $t->f9->accept($this->callMe());
        $in = $this->st->pop();
        $t->f12->accept($this->callMe());
        $out = $this->st->pop();

        $op = new Operator(
            $in,
            $out,
            $this->node2string($t->f4),
            $this->node2string($t->f6),
            $this->node2string($t->f16),
            $this->node2string($t->f18),
            $this->node2string($t->f2),
            $t);
        $this->listaOps[$op->getId()] = $op;

        $this->debug('OPS:'. count($this->listaOps),1);
    }


   /**
    * f0 -> <NOME>
    * f1 -> ( <OPEN> listaParam() <CLOSE> )?
    * f2 -> ( <STAR> )?
    * f3 -> ( <COMMA> <NOME> ( <OPEN> listaParam() <CLOSE> )? ( <STAR> )? )*
    */
    function visitListaParam($n)
    {
        $l = new ParamListFormat();
        $nestedList = null;
        $grouped = $n->f2->present();
        $struct = $n->f1->present();
        if ($struct)
        {
            $n->f1->node->elementAt(1)->accept($this->callMe());
            $nestedList = $this->st->pop();
        }
        $l->addParam($this->node2string($n->f0), $struct,$grouped,$nestedList);

        if($n->f3->present())
        {
            $tot = $n->f3->size();
            for($i=0; $i < $tot; $i++)
            {
                $s = $n->f3->elementAt($i);
                $nestedList = null;
                $grouped = $s->elementAt(3)->present();
                $struct = $s->elementAt(2)->present();
                if ($struct)
                {
                    $s->elementAt(2)->node->elementAt(1)->accept($this->callMe());
                    $nestedList = $this->st->pop();
                }
                $l->addParam($this->node2string($s->elementAt(1)),$struct,$grouped,$nestedList);
            }
        }
        $this->debug('PARAMS:'. $l,2);

        $this->st->push($l);
    }


   /**
    * f0 -> term() ( <OP> term() )*
   *       | <OP> "(" condition() ( <LIST_SEP> condition() )* ( outputFilter() )? ")"
    */
    function visitCondition ($n) {
        $t = $n->f0->choice;
        switch ($n->f0->which) {
            case 0:
                $t->elementAt(0)->accept($this->callMe());
                if ($t->elementAt(1)->present())
                {
                	$s = $t->elementAt(1)->elementAt(0); //var_dump($this->node2string($s->elementAt(0)));die;
                	$last = null;
                    $this->exploreNextOp($this->getOp($s->elementAt(0)),$s->elementAt(1),0, $last,$t->elementAt(1)->size(),$t->elementAt(1));
                }
       			break;
            case 1:
                $filter = null;
                if ($t->elementAt(4)->present())
                {
                    $t->elementAt(4)->accept($this->callMe());
                    $filter = $this->st->pop();
                }
                $t->elementAt(2)->accept($this->callMe());
                if($t->elementAt(3)->present())
                    for ($i = 0; $i < $t->elementAt(3)->size(); $i++)
          			{
          				$tmp = $t->elementAt(3)->elementAt($i);
                        $tmp->elementAt(1)->accept($this->callMe());
          			}
                $this->calcola($this->getOp($t->elementAt(0)),$filter);
                break;
            default:
                break;
        }
//   		$this->debug('CONDITION: '.$n->f1->size(),4);

   }

   /**
    * f0 -> <OUT>
    * f1 -> ( <OPEN> <CLOSE> )?
    * f2 -> listaParam()
    * f3 -> <STOP>
    */
      function visitFilter($n)
    {
        $n->f2->accept($this->callMe());
        $lista = $this->st->pop();
//		var_dump($lista); die;
        $this->st->push(array('mask' => $lista, 'disjointed' => $n->f1->present() == false));
    }

   /**
    * f0 -> <NS>
    * f1 -> <VARIABILE>
    * f2 -> <NSLEFT>
    * f3 -> <NSRIGHT>
    */
   function visitNamespace($n)
   {

           $executor = $this->node2string($n->f1);
           $this->execMe($this->node2string($n->f1,true),array('codice' => $this->node2string($n->f3)));  // TODO valore restituito o uso lo stack? vedi execMe

//   		$ret = $this->execMe($n->f1->tokenImage,$n->f3->tokenImage);  // TODO valore restituito o uso lo stack? vedi execMe
//   		$s = 'NS RET VALUE: ';
//   		if(is_bool($ret)) $s .= ($ret) ? 'true' : 'false';
//   		else $s .= $ret;
//   		$this->debug($s,3);
//   		if ($ret == NULL) $ret = true;
//   		$this->st->push($ret);
   }

   /**
    * f0 -> <DOLLARO> <VARIABILE>
    *       | namespace()
    *       | elem()
    *       | "(" condition() ")"
    *       | <AT> <VARIABILE> ( <CALL> <VARIABILE> "(" ( condition() ( "," condition() )* )? ")" )*
    *       | lista()
    */
    function visitTerm($n){
       $s =$n->f0->choice;

       switch ($n->f0->which)
       {
       		case 0: // $VARIABILE
                if (!array_key_exists(md5($this->node2string($s->elementAt(1),true)),$this->listaVariabili))
            		$this->semanticError("La variabile " . $this->node2string($s->elementAt(1)) . " non � stata definita", $n);
            	$this->listaVariabili[md5($this->node2string($s->elementAt(1),true))]->accept($this->callMe());
                  break;
              case 1: //NAMESPACE
              case 2: //ELEM
              case 5: //LISTA
                  $s->accept($this->callMe());
                  break;
              case 3: // CONDITION
                  $s->elementAt(1)->accept($this->callMe());
                  break;
              case 4: // METHOD CALL
                  $o = $this->getEntity($this->node2string($s->elementAt(1),true));
                  $list = $s->elementAt(2);
                  if($list->present())
                  {
          			for ($i = 0; $i < $list->size(); $i++)
          			{
          				$tmp = $list->elementAt($i);
          				$method = $this->node2string($tmp->elementAt(1));
          				$args = array();
          				$listaArgs = $tmp->elementAt(3);
          				if ($listaArgs->present())
          				{
          					$listaArgs = $listaArgs->node;
          					$args[] = $this->node2string($listaArgs->elementAt(0));
          					if ($listaArgs->elementAt(1)->present())
          						for ($j = 0; $j < $listaArgs->size(); $j++)
          						{
          							$listaArgs->elementAt($j)->elementAt(1)->accept($this->callMe());
          							$args[] = $this->st->pop();
          						}
          				}
          				if (!method_exists($o,$method))
          					$this->semanticError('ENTITY: il metodo '. $method . ' non � definito per '.get_class($o),$n);
          				$this->st->push(call_user_func_array(array(&$o,$method),$args));
          			}
                  }
                  else
                      $this->st->push($o);
                  break;
       }
   }

   /**
    * f0 -> "{"
    * f1 -> condition()
    * f2 -> ( <LIST_SEP> condition() )*
    * f3 -> "}"
    */

    function visitList($n)
    {
        $n->elementAt(1)->accept($this->callMe());
        $o = $n->elementAt(2);
        if($o->present())
        $list[] = $this->st->pop();
        $tot = $o->size();
        for($i=0; $i < $tot; $i++)
        {
            $o->elementAt($i)->elementAt(1)->accept($this->callMe());
            $list[] = $this->st->pop();
        }

        $this->st->push($list);
    }


   /**
    * f0 -> <NUM>
    *       | <TIME>
    *       | <STRINGA>
    */
    function visitElem($n)
    {
        $s = $n->f0->choice;
        if ($n->f0->which == 1) $this->st->push(time());
        else
        {
            $str = $this->node2string($s);
            $ret = $str;
            //Date d/m/yy and dd/mm/yyyy
            //1/1/00 through 31/12/99 and 01/01/1900 through 31/12/2099
            //Matches invalid dates such as February 31st
            //Accepts dashes, spaces, forward slashes and dots as date separators
            if(preg_match('/\b'.
                    '(0?[1-9]|[12][0-9]|3[01])'.
                    '[- \/.](0?[1-9]|1[012])'.
                    '[- \/.](19|20)?[0-9]{2}'.
                    '\b/',
                    $str))
            {
                $date = preg_split('/[- \/.]/',$str, -1, PREG_SPLIT_NO_EMPTY);
                $this->debug('DATA: '.$str.' TOKEN: ' .print_r($date,true), 3);
                $ret = mktime(0,0,0,(int) $date[1],(int) $date[0],(int) $date[2]);
                $this->debug('DATA: '.$str.' TIME: ' . $ret.' CHECK: '.print_r(getdate($ret),true), 3);
            }
            //Date m/d/y and mm/dd/yyyy
            //1/1/99 through 12/31/99 and 01/01/1900 through 12/31/2099
            //Matches invalid dates such as February 31st
            //Accepts dashes, spaces, forward slashes and dots as date separators
            else if(preg_match('/\b(0?[1-9]|1[012])[- \/.](0?[1-9]|[12][0-9]|3[01])[- \/.](19|20)?[0-9]{2}\b/',$str))
            {
                $date = preg_split('/[- \/.]/',$str, -1, PREG_SPLIT_NO_EMPTY);
                $this->debug('DATA: '.$str.' TOKEN: ' .print_r($date,true), 3);
                $ret = mktime(0,0,0,$date[0],$date[1],$date[2]);
                $this->debug('DATA: '.$str.' TIME: ' . $ret.' CHECK: '.print_r(getdate($ret),true), 3);
            }
            $this->st->push($ret);
        }

    }
}
