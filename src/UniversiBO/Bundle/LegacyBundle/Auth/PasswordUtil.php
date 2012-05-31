<?php

namespace UniversiBO\Bundle\LegacyBundle\Auth;

/**
 * Password Util Class
 */
class PasswordUtil
{
    /**
     *  Verifica se la sintassi della password ? valida.
     *  Lunghezza min 5, max 30 caratteri
     *
     * @param  string  $password stringa della password da verificare
     * @return boolean
     */
    public static function isPasswordValid( $password )
    {
        //$password_pattern='/^([[:alnum:]]{5,30})$/';
        //preg_match($password_pattern , $password );
        $length = strlen( $password );

        return ( $length > 5 && $length < 30 );
    }

    /**
     * Generates a random password
     *
     * @return string random password
     */
    public static function generateRandomPassword($length = 8)
    {
        $chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $max_chars = count($chars) - 1;

        $hash = md5(microtime());
        $loWord = substr($hash, -8);
        $seed = hexdec($loWord);
        $seed &= 0x7fffffff;

        mt_srand( $seed );

        $rand_str = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_str = $rand_str . $chars[mt_rand(0, $max_chars)];
        }

        return $rand_str;
    }

    /**
     * Ritorna l'hash sicuro di una stringa
     *
     * @param  string $string
     * @return string
     */
    public static function passwordHashFunction($string, $salt = '', $algoritmo = 'md5')
    {
        $password = $salt.$string;

        switch ($algoritmo) {
            case 'sha256':
                return hash($algoritmo, $password);
            case 'sha1':
                return sha1($password);
            default:
                return md5($password);
        }
    }
}
