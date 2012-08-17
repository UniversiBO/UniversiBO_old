<?php
namespace Universibo\Bundle\LegacyBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Universibo\Bundle\SSOBundle\Model\UserInterface as SSOUserInterface;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Auth\ActiveDirectoryLogin;
use Universibo\Bundle\LegacyBundle\Auth\PasswordUtil;
/**
 * User class
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Davide Bellettini
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2003
 */
class User implements UserInterface, SSOUserInterface, \Serializable
{
    const ALGORITMO_DEFAULT = 'sha1';

    const NONE = 0;
    const OSPITE = 1;
    const STUDENTE = 2;
    const COLLABORATORE = 4;
    const TUTOR = 8;
    const DOCENTE = 16;
    const PERSONALE = 32;
    const ADMIN = 64;
    const ALL = 127;
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';
    const NICK_ELIMINATO = 'ex-utente';

    /**
     * @access private
     */
    public $id_utente = 0;

    /**
     * @access private
     */
    public $username = '';

    /**
     */
    private $password = '';

    /**
     * @access private
     */
    public $email = '';

    /**
     * @access private
     */
    public $ultimoLogin = 0;

    /**
     * @access private
     */
    public $bookmark = NULL; //array()

    /**
     * @access private
     */
    public $ADUsername = '';

    /**
     * @access private
     */
    public $groups = 0;

    /**
     * @access private
     */
    public $notifica = 0;

    /**
     * @access private
     */
    public $ban = false;

    /**
     * @access private
     */
    public $phone = '';

    /**
     * @access private
     */
    public $defaultStyle = '';

    /**
     * @access private
     */
    public $eliminato = '';

    private $algoritmo;

    private $salt;

    private static $roleConversions = array(
            self::ADMIN => 'ROLE_ADMIN',
            self::COLLABORATORE => 'ROLE_COLLABORATOR',
            self::DOCENTE => 'ROLE_PROFESSOR',
            self::OSPITE => 'ROLE_GUEST', self::PERSONALE => 'ROLE_STAFF',
            self::STUDENTE => 'ROLE_STUDENT',
            self::TUTOR => 'ROLE_TUTOR',);

    private static $repository = null;

    /**
     *  Verifica se la sintassi dello username e` valido.
     *  Sono permessi fino a 25 caratteri: alfanumerici, lettere accentate, spazi, punti, underscore
     *
     * @param  string  $username stringa dello username da verificare
     * @return boolean
     */
    public static function isUsernameValid($username)
    {
        $username = trim($username);
        $username_pattern = mb_convert_encoding(
                '/^([[:alnum:]àèéìòù \._]{1,25})$/', 'iso-8859-1', 'utf-8');

        return preg_match($username_pattern, $username)
                && strcasecmp($username, self::NICK_ELIMINATO) != 0;
    }

    /**
     * Verifica se la sintassi della password ? valida.
     * Lunghezza min 5, max 30 caratteri
     *
     * @deprecated
     * @param  string  $password stringa della password da verificare
     * @return boolean
     */
    public static function isPasswordValid($password)
    {
        return PasswordUtil::isPasswordValid($password);
    }

    /**
     * Genera una password casuale
     *
     * @return string password casuale
     */
    public static function generateRandomPassword($length = 8)
    {
        return PasswordUtil::generateRandomPassword($length);
    }

    /**
     * Restituisce l'array associativo del codice dei gruppi e
     * della corrispettiva stringa descrittiva.
     *
     * @param  boolean $singolare
     * @return array
     */
    public static function groupsNames($singolare = true)
    {
        if ($singolare == true) {
            return array(self::OSPITE => "Ospite",
                    self::STUDENTE => "Studente",
                    self::COLLABORATORE => "Collaboratore",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docente",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Admin");
        } else {
            return array(self::OSPITE => "Ospiti",
                    self::STUDENTE => "Studenti",
                    self::COLLABORATORE => "Collaboratori",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docenti",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Admin");
        }
    }

    /**
     * Crea un oggetto User
     *
     * In pratica non dovrebbe mai essere necessario utilizzarlo a meno che non si voglia
     * creare un utente "custom", l'utente andrebbe sempre creato attraverso il medoto
     * factory selectUser
     *
     * @see selectUser
     * @param  int     $id_utente    numero identificativo utente, -1 non registrato du DB, 0 utente ospite
     * @param  int     $groups       nuovo gruppo da impostare
     * @param  string  $username     username dell'utente
     * @param  string  $MD5          hash MD5 della password utente
     * @param  string  $email        indirizzo e-mail dell'utente
     * @param  int     $ultimo_login timestamp dell'utlimo login all'interno del sito
     * @param  string  $AD_username  username dell'active directory di ateneo dell'utente
     * @param  array() $bookmark     array con elenco dei id_canale dell'utente associati ai rispettivi ruoli
     * @return User
     */
    public function __construct($id_utente, $groups, $username = NULL,
            $password = NULL, $email = NULL, $notifica = NULL,
            $ultimo_login = NULL, $AD_username = NULL, $phone = '',
            $defaultStyle = '', $bookmark = NULL,
            $eliminato = self::NOT_ELIMINATO, $hashedPassword = false)
    {
        $this->id_utente = $id_utente;
        $this->groups = $groups;
        $this->username = trim($username);
        $this->email = $email;
        $this->ADUsername = $AD_username;
        $this->ultimoLogin = $ultimo_login;
        $this->notifica = $notifica;
        $this->phone = $phone;
        $this->defaultStyle = $defaultStyle;
        $this->bookmark = $bookmark;
        $this->eliminato = $eliminato;

        if ($hashedPassword) {
            $this->password = $password;
        } else {
            $this->updatePassword($password);
        }
    }

    /**
     * Ritorna lo username dello User
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Username setter
     *
     * @param  string                                      $username
     * @return \Universibo\Bundle\LegacyBundle\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Ritorna il livello di notifica dei messaggi
     *
     * @return string
     */
    public function getLivelloNotifica()
    {
        return $this->notifica;
    }

    /**
     * Imposta il livello di notifica dei messaggi
     *
     * @param string $notifica il livello da impostare
     */
    public function setLivelloNotifica($notifica)
    {
        $this->notifica = $notifica;
    }

    /**
     * Ritorna l'ID dello User nel database
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->id_utente;
    }

    public function setIdUser($id)
    {
        $this->id_utente = $id;
    }

    /**
     * Ritorna la email dello User
     *
     * @return int
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Imposta la email dello User
     *
     * @param  string  $email    nuova email da impostare
     * @param  boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
     * @return boolean
     */
    public function updateEmail($email, $updateDB = false)
    {
        $this->setEmail($email);

        if ($updateDB) {
            return self::getRepository()->updateEmail($this);
        }

        return true;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Ritorna lo OR bit a bit dei gruppi di appartenenza dello User
     *
     * es:  self::STUDENTE|self::ADMIN  =  2|64  =  66
     *
     * @return int
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function getRoles()
    {
        $roles = array();

        foreach (self::$roleConversions as $old => $new) {
            if (0 !== ($this->groups & $old)) {
                $roles[] = $new;
            }
        }

        return $roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Imposta il gruppo di appartenenza dello User
     *
     * @deprecated
     * @param  int     $groups   nuovo gruppo da impostare
     * @param  boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
     * @return boolean
     */
    public function updateGroups($groups, $updateDB = false)
    {
        $this->setGroups($groups);

        if ($updateDB) {
            return self::getRepository()->updateGroups($this);
        }

        return true;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * Ritorna il timestamp dell'ultimo login dello User
     *
     * @return int
     */
    public function getUltimoLogin()
    {
        return $this->ultimoLogin;
    }

    /**
     * Imposta il timestamp dell'ultimo login dello User
     *
     * @param  int     $ultimoLogin timestamp dell'ultimo login da impostare
     * @param  boolean $updateDB    se true e l'id_utente>0 la modifica viene propagata al DB
     * @return boolean
     */
    public function updateUltimoLogin($ultimoLogin, $updateDB = false)
    {
        $this->setUltimoLogin($ultimoLogin);

        if ($updateDB) {
            return self::getRepository()->updateUltimoLogin($this);
        }

        return true;
    }

    public function setUltimoLogin($ultimoLogin)
    {
        $this->ultimoLogin = $ultimoLogin;
    }

    /**
     * Ritorna un array contenente gli oggetti Ruolo associati ai canali dell'utente
     *
     * @return array
     */
    public function getRuoli()
    {
        if ($this->bookmark == NULL) {
            $this->bookmark = array();
            $ruoli = Ruolo::selectUserRuoli($this->getIdUser());
            $num_elementi = count($ruoli);
            for ($i = 0; $i < $num_elementi; $i++) {
                $this->bookmark[$ruoli[$i]->getIdCanale()] = $ruoli[$i];
            }
        }

        return $this->bookmark;
    }

    /**
     * Ritorna un array contenente i nomi dei ruoli categorizzati per anno, selezionando l'eventuale canale passato
     */
    public function getRuoliInfoGroupedByYear($id_canale = null)
    {
        $user_ruoli = $this->getRuoli();
        $elenco_canali = array();
        $found = ($id_canale == null);
        foreach ($user_ruoli as $r) {
            if ($this->isAdmin() || $r->isReferente()) {
                $elenco_canali[] = $r->getIdCanale();

                if (!$found && $r->getIdCanale() == $id_canale) {
                    $found = true;
                }
            }
        }

        if (!$found && $this->isAdmin())
            $elenco_canali[] = $id_canale;

        $elenco_canali_retrieve = array();

        foreach ($elenco_canali as $id_current_canale) {
            $current_canale = Canale::retrieveCanale($id_current_canale);
            $elenco_canali_retrieve[$id_current_canale] = $current_canale;
            $didatticaCanale = PrgAttivitaDidattica::factoryCanale(
                    $id_current_canale);
            //			var_dump($didatticaCanale);
            $annoCorso = (count($didatticaCanale) > 0) ? $didatticaCanale[0]
                            ->getAnnoAccademico() : 'altro';
            $nome_current_canale = $current_canale->getTitolo();
            $f7_canale[$annoCorso][$id_current_canale] = array(
                    'nome' => $nome_current_canale,
                    'spunta' => ($id_canale != null
                            && $id_current_canale == $id_canale) ? 'true'
                            : 'false');
        }
        krsort($f7_canale);
        $tot = count($f7_canale);
        $list_keys = array_keys($f7_canale);
        for ($i = 0; $i < $tot; $i++)
        //			var_dump($f7_canale[$i]);
            uasort($f7_canale[$list_keys[$i]], array($this, '_compareCanale'));

        return $f7_canale;
    }

    /**
     * Ordina la struttura dei canali
     *
     */
    private static function _compareCanale($a, $b)
    {
        $nomea = strtolower($a['nome']);
        $nomeb = strtolower($b['nome']);

        return strnatcasecmp($nomea, $nomeb);
    }

    /**
     * Ritorna lo username dell'ActiveDirectory di ateneo associato all'utente corrente
     *
     * @return string
     */
    public function getADUsername()
    {
        return $this->ADUsername;
    }

    /**
     * Imposta lo username dell'ActiveDirectory di ateneo associato all'utente corrente
     *
     * @param  string  $ADUsername username dell'ActiveDirectory di ateneo da impostare
     * @param  boolean $updateDB   se true e l'id_utente>0 la modifica viene propagata al DB
     * @return boolean
     */
    public function updateADUsername($ADUsername, $updateDB = false)
    {
        $this->setADUsername($ADUsername);

        if ($updateDB) {
            return self::getRepository()->updateADUsername($this);
        }

        return true;
    }

    public function setADUsername($ADUsername)
    {
        $this->ADUsername = $ADUsername;
    }

    /**
     * Ritorna la stringa con il numero di telefono
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Ritorna lo stile grafico predefinito
     *
     * @return string
     */
    public function getDefaultStyle()
    {
        return $this->defaultStyle;
    }

    /**
     * Restituisce il nome del gruppo da usare nel blocchetto contatti
     * (admin e collaboratori compaiono come studenti)
     *
     * @param  boolean $singolare
     * @return array
     */
    public static function publicGroupsName($singolare = true)
    {
        if ($singolare == true) {
            return array(self::OSPITE => "Ospite",
                    self::STUDENTE => "Studente",
                    self::COLLABORATORE => "Studente",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docente",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Studente");
        } else {
            return array(self::OSPITE => "Ospiti",
                    self::STUDENTE => "Studenti",
                    self::COLLABORATORE => "Studenti",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docenti",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Studenti");
        }
    }

    /**
     * Ritorna l'hash sicuro di una stringa
     *
     * @param  string $string
     * @return string
     */
    public static function passwordHashFunction($string, $salt = '',
            $algoritmo = 'md5')
    {
        return PasswordUtil::passwordHashFunction($string, $salt, $algoritmo);
    }

    /**
     * Ritorna l'hash MD5 della password dell'utente
     *
     * @deprecated use {getPassword()} instead
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->getPassword();
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @todo make it Legacy Free
     *
     * @param  string  $password
     * @param  boolean $updateDB
     * @return boolean
     */
    public function updatePassword($password, $updateDB = false)
    {
        $this->setNewPassword($password);

        if ($updateDB) {
            return self::getRepository()->updatePassword($this);
        }

        return true;
    }

    public function setNewPassword($password)
    {
        $this->setSalt(self::generateRandomPassword(8));
        $this->setAlgoritmo(self::ALGORITMO_DEFAULT);
        $this
                ->setPassword(
                        PasswordUtil::passwordHashFunction($password,
                                $this->getSalt(), $this->getAlgoritmo()));
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function matchesPassword($password, $updateDB = false)
    {
        $matches = $this->password
                == self::passwordHashFunction($password, $this->getSalt(),
                        $this->getAlgoritmo());

        if ($matches && $this->getAlgoritmo() !== self::ALGORITMO_DEFAULT) {
            $this->updatePassword($password, $updateDB);
        }

        return $matches;
    }

    /**
     * Imposta il numero di telefono
     *
     * @param  boolean $phome il numero di telefono
     * @return boolean
     */
    public function setPhone($phome)
    {
        $this->phone = $phome;
    }

    /**
     * Imposta il nome del template di default
     *
     * @param  boolean $defaultStyle nome del template di default
     * @return boolean
     */
    public function setDefaultStyle($defaultStyle)
    {
        $this->defaultStyle = $defaultStyle;
    }

    /**
     * Imposta i diritti per l'accesso ai servizi di interazione
     *
     * @param  boolean $ban true se l'utente non ha accesso, false se l'utente ha accesso
     * @return boolean
     */
    public function setBan($ban)
    {
        $this->ban = $ban;
    }

    public function setBanned($banned)
    {
        $this->setBan($banned);
    }

    /**
     * Ritorna true se ad un utente ? impedito l'accesso ai servizi di interazione,
     * la fase di autorizzazione deve tenere conto di quest? propriet?
     *
     * @return boolean
     */
    public function isBanned()
    {
        return $this->ban;
    }

    /**
     * Ritorna true se l'utente ha voluto la cancellazione
     *
     * @return boolean
     */
    public function isEliminato()
    {
        return $this->eliminato == self::ELIMINATO;
    }

    /**
     * Imposta l'utente come eliminato. NB questa modifica non viene salvata
     * automaticamente nel db. Bisogna invocare updateUser
     *
     * @return boolean
     */
    public function setEliminato($elimina = true)
    {
        return ($this->eliminato = ($elimina) ? self::ELIMINATO
                : self::NOT_ELIMINATO);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Admin.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Admin.
     *
     * @return boolean
     */
    public function isAdmin($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ((int) $groups & (int) self::ADMIN);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Personale.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Personale.
     *
     * @return boolean
     */
    public function isPersonale($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ((int) $groups & (int) self::PERSONALE);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Docente.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Docente.
     *
     * @static
     * @return boolean
     */
    public function isDocente($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ((int) $groups & (int) self::DOCENTE);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Tutor.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Tutor.
     *
     * @static
     * @return boolean
     */
    public function isTutor($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ($groups & self::TUTOR);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Moderatori.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Moderatori.
     *
     * @static
     * @return boolean
     */
    public function isCollaboratore($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ((int) $groups & (int) self::COLLABORATORE);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Studenter.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Studente.
     *
     * @static
     * @return boolean
     */
    public function isStudente($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        return (boolean) ($groups & self::STUDENTE);
    }

    /**
     * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Ospite.
     * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Ospite.
     * Un utente non ? ospite se appartiene anche ad altri gruppi.
     *
     * @static
     * @return boolean
     */
    public function isOspite($groups = NULL)
    {
        if ($groups == NULL)
            $groups = $this->getGroups();

        if ($groups == self::OSPITE)
            return true;
        return false;
    }

    /**
     * Restituisce l'array dell'elenco dei nomi dei gruppi
     * a cui appartiene una persona
     *
     * @static
     * @param  boolean $singolare
     * @return array
     */
    public function getUserGroupsNames($singolare = true)
    {
        $nomi_gruppi = self::groupsNames($singolare);
        $return = array();

        if ($this->isOspite())
            $return[] = $nomi_gruppi[self::OSPITE];
        if ($this->isStudente())
            $return[] = $nomi_gruppi[self::STUDENTE];
        if ($this->isCollaboratore())
            $return[] = $nomi_gruppi[self::COLLABORATORE];
        if ($this->isTutor())
            $return[] = $nomi_gruppi[self::TUTOR];
        if ($this->isDocente())
            $return[] = $nomi_gruppi[self::DOCENTE];
        if ($this->isPersonale())
            $return[] = $nomi_gruppi[self::PERSONALE];
        if ($this->isAdmin())
            $return[] = $nomi_gruppi[self::ADMIN];

        return $return;

    }

    /**
     * Restituisce l'array dell'elenco dei nomi dei gruppi
     * a cui appartiene una persona
     *
     * @static
     * @param  boolean $singolare
     * @return array
     */
    public function getUserPublicGroupName($singolare = true)
    {
        $nomi_gruppi = self::publicGroupsName($singolare);

        if ($this->isOspite())
            return $nomi_gruppi[self::OSPITE];
        if ($this->isStudente())
            return $nomi_gruppi[self::STUDENTE];
        if ($this->isCollaboratore())
            return $nomi_gruppi[self::COLLABORATORE];
        if ($this->isTutor())
            return $nomi_gruppi[self::TUTOR];
        if ($this->isDocente())
            return $nomi_gruppi[self::DOCENTE];
        if ($this->isPersonale())
            return $nomi_gruppi[self::PERSONALE];
        if ($this->isAdmin())
            return $nomi_gruppi[self::ADMIN];
    }

    /**
     * Restituisce true se lo username specificato ? gi? registrato sul DB
     *
     * @static
     * @param  string  $username username da ricercare
     * @return boolean
     */
    public static function usernameExists($username, $caseSensitive = false)
    {
        return self::getRepository()->usernameExists($username, $caseSensitive);
    }

    /**
     * Crea un oggetto utente collaboratore
     *
     * @static
     * to do
     * @return mixed User se eseguita con successo, false se l'utente non esiste
     */
    public static function selectAllCollaboratori()
    {
        return self::getRepository()->findCollaboratori();
    }

    /**
     * @param array	lista dei ruoli di cui si vogliono sapere gli appartenenti
     * @return array array di lista di IdUser per ogni gruppo specificato
     */
    public static function getIdUsersFromDesiredGroups(
            $arrayWithDesiredGroupsConstant)
    {
        if (!is_array($arrayWithDesiredGroupsConstant)
                || count($arrayWithDesiredGroupsConstant) === 0)

            return array();

        return self::getRepository()
                ->getIdUsersFromDesiredGroups($arrayWithDesiredGroupsConstant);
    }

    /**
     * Crea un oggetto utente dato il suo numero identificativo id_utente del database, 0 se si vuole creare un utente ospite
     *
     * @deprecated
     * @param  int     $id_utente numero identificativo utente
     * @param  boolean $dbcache   se true esegue il pre-caching del bookmark in modo da migliorare le prestazioni
     * @return mixed   User se eseguita con successo, false se l'utente non esiste
     */
    public static function selectUser($id_utente)
    {
        if ($id_utente == 0) {
            $user = new User(0, self::OSPITE);

            return $user;
        } elseif ($id_utente > 0) {
            return self::getRepository()->find($id_utente);
        }
    }

    /**
     * Crea un oggetto utente dato il suo usernamedel database
     *
     * @param  string $username nome identificativo utente
     * @return mixed  User se eseguita con successo, false se l'utente non esiste
     */
    public static function selectUserUsername($username)
    {
        return self::getRepository()->findByUsername(trim($username));
    }

    /**
     * Ritorna un array di oggetti utente che rispettano entrambe le stringhe di ricerca (AND)
     * Possono essere usati _ e % come caratteri spaciali
     *
     * @deprecated
     * @param  string $username nome identificativo utente
     * @param  string $username nome identificativo utente
     * @return array  di User
     */
    public static function selectUsersSearch($username = '%', $email = '%')
    {
        return self::getRepository()->findLike(trim($username), $email);
    }

    /**
     * Inserisce su DB le informazioni riguardanti un nuovo utente
     *
     * @return boolean true se avvenua con successo, altrimenti Error object
     */
    public function insertUser()
    {
        return self::getRepository()->insertUser($this);
    }

    /**
     * Aggiorna il contenuto su DB riguardante le informazioni utente
     *
     * @return boolean true se avvenua con successo, altrimenti false e throws Error object
     */
    public function updateUser()
    {
        return self::getRepository()->updateUser($this);
    }

    /**
     * Restituisce true se l'utente dell'active directory ? gi? registrato sul DB
     *
     * @static
     * @deprecated
     * @param  string  $adUsername username da ricercare
     * @return boolean
     */
    public static function activeDirectoryUsernameExists($adUsername)
    {
        return self::getRepository()
                ->activeDirectoryUsernameExists($adUsername);
    }

    /**
     * Resituisce l'id utente a partire dallo username dell'active directory
     *
     * @param  string $ad_username username AD dell'utente
     * @return mixed  l'id utente se lo trova, altrimenti false
     */
    public static function getIdFromADUsername($adUsername)
    {
        return self::getRepository()->getIdFromADUsername($adUsername);
    }

    /**
     * Restituisce true se il gruppo dell'utente apparteniene ai gruppi specificati in $groups
     * altrimenti false
     *
     * @param  int     $groups gruppi di cui si vuole verificare l'accesso
     * @return boolean
     */
    public function isGroupAllowed($groups)
    {
        return (boolean) ((int) $this->groups & (int) $groups);
    }

    /**
     * Restituisce il nick dello user avendo l'id
     *
     * @deprecated
     * @param $id_user id dello user
     * @return il nickname
     */

    public static function getUsernameFromId($id)
    {
        return self::getRepository()->getUsernameFromId($id);
    }

    /**
     * Restituisce true se l'utente viene autenticato con successo sull'active directory di ateneo
     *
     * @param  string  $ad_username username utente
     * @param  string  $ad_domain   dominio dell'active directory
     * @param  string  $ad_password password dell'utente
     * @return boolean
     */
    public static function activeDirectoryLogin($username, $domain, $password,
            $host, $port)
    {
        $login = new ActiveDirectoryLogin($host, $port);

        return $login->authenticate($username, $domain, $password);
    }

    public function getAlgoritmo()
    {
        return $this->algoritmo;
    }

    public function setAlgoritmo($algoritmo)
    {
        $this->algoritmo = $algoritmo;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @TODO implement method
     * @see Symfony\Component\Security\Core\User.UserInterface::equals()
     */
    public function equals(UserInterface $user)
    {
        $equals = $user->getUsername() === $this->getUsername();
        $equals = $equals && $user->getPassword() === $this->getPassword();

        return $equals;
    }

    /**
     * @return DBUserRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.user');
        }

        return self::$repository;
    }

    public function serialize()
    {
        $data = array('id_utente' => $this->id_utente,
                'username' => $this->username,
                'password' => $this->password,
                'ultimoLogin' => $this->ultimoLogin,
                'bookmark' => $this->bookmark,
                'ADUsername' => $this->ADUsername,
                'groups' => $this->groups, 'notifica' => $this->notifica,
                'ban' => $this->ban, 'phone' => $this->phone,
                'defaultStyle' => $this->defaultStyle,
                'eliminato' => $this->eliminato,
                'algoritmo' => $this->algoritmo, 'salt' => $this->salt);

        return serialize($data);
    }

    public function unserialize($data)
    {
        foreach (unserialize($data) as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setPrincipalName($userPrincipal)
    {
        return $this->setADUsername($userPrincipal);
    }

    public function getPrincipalName()
    {
        return $this->getADUsername();
    }
}
