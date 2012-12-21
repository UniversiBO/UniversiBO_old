<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

/*
+--+ Project Name: KronoClass
+--+ Version: 0.6
+--+ Project Author: Tommaso D'Argenio
+--+ Author Email: rajasi@ziobudda.net, info@holosoft.it
+--+ Build Date:  January 10 2003 16.18 (CET)
+--+ Update: March 19 2003

+--+ DISCLAIMER
Copyright (c) 2002-03 Tommaso D'Argenio <rajasi@ziobudda.net>
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public License
as published by the Free Software Foundation; either version
2.1 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
PURPOSE.  See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this program; if not, write to the
Free Software Foundation, Inc.,
59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
http://www.fsf.org

+--+ NOTES FROM AUTHOR
* Please, if you make any change in the code let me know by email!!!
   if use this class in your project, please let me know, in this way i can publish it.

+--+ Requirements:
        PHP 4.0+
*/

/**
* Class for compute some calculations on date and time
*
* @copyright  2002-2003 Holosoft - Tommaso D'Argenio <rajasi@ziobudda.net>
* @version $Id v 0.6 2003/03/19 20.30.00(CET) marms Exp $
* @link http://www.holosoft.it/  Holosoft Home Page
* @link http://www.phpclasses.org/browse.html/package/943.html KronoClass Home at phpclasses
* @link http://freshmeat.net/projects/kronoclass/?topic_id=914 KronoClass Home at freshmeat
* @package kronos
*/
class Krono
{
    /** Array that contain the name of days in long format 	*
    *   @access private
    */
    public $day_name_ext;

    /** Array that contain the name of days in short format
    *   @access private
    */
    public $day_name_con;

    /** Array that contain the name of month in long format
    *   @access private
    */
    public $month_name_ext;

    /** Array that contain the name of month in short format
    *   @access private
    */
    public $month_name_con;

    /** General purpose use
    *   @access private
    */
    public $data_from;

    /** General purpose use
    *   @access private
    */
    public $data_to;

    /** Used for errors
    *  @access private
    */
    public $error;

    /** Variable for choose long or short day names format
    *  @access public
    */
    public $abbr;

    /** Set to desidered language
    *  @access public
    */
    public $lan;

    /** Version number
    *   @access private
    */
    public $version='0.6';

    /**
    *	Set to desidered date format
    * + possible values:
    * + it -> italian (dd-mm-yyyy)
    * + en,std -> international (mm-dd-yyyy)
    * + ansi -> used in dbase and other source (yyyy-mm-dd)
    *
    * 	@access public
    */
    public $date_format;

    /** Char for separating date
    *   @access public
    */
    public $separator;

    /** Constructor
    *   @access public
    *   @see Krono::$date_format
    *   @param string $lan The language to use for month/day names
    *   @param string $date_format the format for date
    *   @param char $separator Character to use as date separator
    *   @return void
    */
    public function __construct($lan='it', $date_format='it', $separator='/')
    {
        $this->day_name_ext=
        array(
                'it'=>array('Domenica','Luned�','Marted�','Mercoled�','Gioved�','Venerd�','Sabato'),
                'en'=>array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),
                'de'=>array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'),
                'fr'=>array('Dimanche','Lund�','Mard�','Mercred�','Jeud�','Vendred�','Samed�'),
                'es'=>array('Domingo','Lunes','Martes','Miercole','Jueves','Viernes','Sabado'),
                'id'=>array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                'no'=>array('Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lordag','Sondag'),
                'jp'=>array('Nichiyoubi','Getsuyoubi','Kayoubi','Suiyoubi','Mokuyoubi','Kinyoubi','Douyoubi'),
                'fi'=>array('Sunnuntaina','Maanantaina','Tiistaina','Keskiviikkona','Torstaina','Perjantaina','Lauantaina'),
                'nl'=>array('Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag')
            );


        $this->day_name_con=
        array(
                'it'=>array('Dom','Lun','Mar','Mer','Gio','Ven','Sab'),
                'en'=>array('Sun','Mon','Tue','Wed','Thu','Fri','Sat'),
                'de'=>array('So','Mo','Di','Mi','Do','Fr','Sa'),
                'fr'=>array('Dim','Lun','Mar','Mer','Jeu','Ven','Sam'),
                'es'=>array('Dom','Lun','Mar','Mie','Jue','Vie','Sab'),
                'id'=>array('Min','Sen','Sel','Rab','Kam','Jum','Sab'),
                'no'=>array('Man','Tir','Ons','Tor','Fre','Lor','Son'),
                'jp'=>array('Nic','Get','Kay','Sui','Mok','Kin','Dou'),
                'fi'=>array('Sun','Maa','Tii','Kes','Tor','Per','Lau'),
                'nl'=>array('Zo','Ma','Di','Wo','Do','Vr','Za')
            );

        $this->month_name_ext=
        array(
                'it'=>array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'),
                'en'=>array('January','February','March','April','May','June','July','August','September','October','November','December'),
                'de'=>array('Januar','Februar','Marz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'),
                'fr'=>array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'),
                'es'=>array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'),
                'id'=>array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'),
                'no'=>array('Januar','February','Mars','April','Mai','Juni','Juli','','','Oktober','','Desember'),
                'jp'=>array('Ichigatsu','Nigatsu','Sangatsu','Shigatsu','Gogatsu','Rokugatsu','Shicigatsu','Hachigatsu','Kugatsu','Jugatsu','Juichigatsu','Junigatsu'),
                'fi'=>array('Tammikuun','Helmikuun','Maaliskuun','Huhtikuun','Toukokuun','Kesakuun','Heinakuun','Elokuun','Syyskuun','Lokakuun','Marraskuun','Joulukuun'),
                'nl'=>array('Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December')
            );

        $this->month_name_con=
        array(
                'it'=>array('Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'),
                'en'=>array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),
                'de'=>array('Jan','Feb','Mar','Apr','Mag','Jun','Jul','Aug','Sep','Okt','Nov','Dez'),
                'fr'=>array('Jan','Fev','Mar','Avr','Mai','Jui','Jul','Aou','Sep','Oct','Nov','Dec'),
                'es'=>array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'),
                'id'=>array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'),
                'no'=>array('Jan','Feb','Mar','Apr','Mai','Jun','Jul','','','Okt','','Des'),
                'jp'=>array('Ich','Nig','San','Shi','Gog','Rok','Shi','Hac','Kug','Jug','Jui','Jun'),
                'fi'=>array('Tam','Hel','Maa','Huh','Tou','Kes','Hei','Elo','Syy','Lok','Mar','Jou'),
                'nl'=>array('Jan','Feb','Mrt','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Dec')
            );

        $this->lan=$lan;
        $this->date_format=$date_format;
        $this->separator=$separator;
    }

    /** Function that exit with the error message if given
    *   @access private
    *   @return void
    */
    public function exit_on_error()
    {
        if($this->error!='')
            echo ' [Fatal Error] <b>'.$this->error.'</b> ';
        exit;
    }

    /** Return the last modified date of class file
    *   @access private
    *   @return string The formatted date of this file last modified field
    */
    public function _update()
    {
        $s=stat(__FILE__);

        return $this->k_date('%l %d %F %Y',$s[9]);
    }

    /** Return the format string for date function according to date_format parameter and separator
    *   @access private
    *   @return string
    */
    public function _format()
    {
        switch ($this->date_format) {
            case 'ansi':
                if(!$this->abbr)

                    return 'Y'.$this->separator.'m'.$this->separator.'d';
                else
                    return 'Y'.$this->separator.'n'.$this->separator.'j';
            break;
            case 'it':
                if(!$this->abbr)

                    return 'd'.$this->separator.'m'.$this->separator.'Y';
                else
                    return 'j'.$this->separator.'n'.$this->separator.'Y';
            break;
            case 'en':
            case 'std':
                if(!$this->abbr)

                    return 'm'.$this->separator.'d'.$this->separator.'Y';
                else
                    return 'n'.$this->separator.'j'.$this->separator.'Y';
            break;
            default: $this->error='Date Format not recognized, must be "ansi", "it", "std" or "en" !! '; $this->exit_on_error();
        }
    }

    /**
    *  Return the literal name of language code
    *  @access private
    *  @return string The long name of language format
    */
    public function _language()
    {
        switch ($this->lan) {
            case 'it': return 'Italian'; break;
            case 'en': return 'English'; break;
            case 'de': return 'Deutch'; break;
            case 'fr': return 'French'; break;
            case 'es': return 'Spanish'; break;
            case 'id': return 'Indonesian'; break;
            case 'no': return 'Norway'; break;
            case 'jp': return 'Japanese'; break;
            case 'fi': return 'Finnish'; break;
            default: return 'Language not recognized!!';
        }
    }

    /** Print out some debug information
    *    @access: private
    *    @return void
    */
    public function _debug()
    {
        echo '<span style="font-family:helvetica,verdana,serif;font-size:12px;color:darkgray;">
                    <b>Debug Information</b><br>
                    Format of Date: <i>'.$this->_format().'</i><br>
                    Date Separator: <i>'.$this->separator.'</i><br>
                    Language: <i>'.$this->_language().'</i>
                      </span>
                    <br><hr size="1px" width="50%" color="black" align="left"><br>
                    ';
    }
    /** Print out a disclaimer
    *    @access private
    *    @return void
    */
    public function _disclaimer()
    {
        echo '<span style="font-family:helvetica,verdana,serif;font-size:14px;color:#ff9900;">';
        echo '<b>KronoClass</b> v. '.$this->version.' <br>';
        echo '<i>Copyright (c) 2002-2003 by Tommaso D\'Argenio &lt;<a href="mailto:rajasi@ziobudda.net" title="Send me an email">rajasi@ziobudda.net</a>&gt;<br>';
        echo 'Last modified on: '.$this->_update().'</i><br><hr size="1px" width="50%" color="black" align="left"></span><br>';
    }

    /** Return if a given time is daylight saving or not
    *    @access private
    *    @return int 1 if time is daylight saving 0 otherwise.
    */
    public function _is_daylight($time)
    {
        list($dom, $dow, $month, $hour, $min) = explode(":", date("d:w:m:H:i", $time));

        if ($month > 4 && $month < 10) {
          $retval = 1;        # May thru September
        } elseif ($month == 4 && $dom > 7) {
          $retval = 1;        # After first week in April
        } elseif ($month == 4 && $dom <= 7 && $dow == 0 && $hour >= 2) {
          $retval = 1;        # After 2am on first Sunday ($dow=0) in April
        } elseif ($month == 4 && $dom <= 7 && $dow != 0 && ($dom-$dow > 0)) {
          $retval = 1;        # After Sunday of first week in April
        } elseif ($month == 10 && $dom < 25) {
          $retval = 1;        # Before last week of October
        } elseif ($month == 10 && $dom >= 25 && $dow == 0 && $hour < 2) {
          $retval = 1;        # Before 2am on last Sunday in October
        } elseif ($month == 10 && $dom >= 25 && $dow != 0 && ($dom-24-$dow < 1) ) {
          $retval = 1;        # Before Sunday of last week in October
        } else {
          $retval = 0;
        }

        return($retval);
    }

    /** Convert the name of a day in its numerical value.
    *    + i.e.: Monday stay for 0, Saturday stay for 6
    *    @access public
    *    @param string $day The name of day, short or long.
    *    @return int the number of day
    */
    public function day_to_n($day)
    {
        if ($day=='' || strlen($day)<3) {
            $this->error='Day name not valid!';
            $this->exit_on_error();
        }

        $day=ucwords($day);
        if(strlen($day)==3)
            $ar=$this->day_name_con[$this->lan];
        else
            $ar=$this->day_name_ext[$this->lan];

        if (in_array($day,$ar)) {
            $k=array_keys($ar,$day);

            return($k[0]);
        }
    }

    /** Convert the numerical value of a day in its name for the setted language by constructor.
    *    + Short o long format is choosed by setting the abbr value to true o false
    *    @access public
    *    @param int $day The number of day, 0 stay for Sunday and 6 for Saturday
    *    @return string the name of day in language setted by constructor
    */
    public function n_to_day($day)
    {
        if ($day>6 || $day<0) {
            $this->error='Day range not valid. Must be 0 to 6!';
            $this->exit_on_error();
        }

        if($this->abbr===true)

            return($this->day_name_con[$this->lan][$day]);
        elseif($this->abbr!=true)
            return($this->day_name_ext[$this->lan][$day]);
    }

    /** Convert the name of a month in its numerical value.
    *    + i.e.: February stay for 2, December stay for 12
    *    @access public
    *    @param string $month The name of month, short or long format, in language setted by constructor
    *    @return int The number rappresenting the month
    */
    public function month_to_n($month)
    {
        if ($month=='' || strlen($month)<3) {
            $this->error='Month name not valid!';
            $this->exit_on_error();
        }

        $month=ucwords($month);
        if(strlen($month)==3)
            $ar=$this->month_name_con[$this->lan];
        else
            $ar=$this->month_name_ext[$this->lan];

        if (in_array($month,$ar)) {
            $k=array_search($month,$ar);

            return($k+1);
        } else

            return -1;
    }

    /** Convert the numerical value of a month in its name.
    *    + Short o long format is choosed by setting the abbr value to true o false
    *    @access public
    *    @param string $month The number of month
    *    @return string The name of month in language setted by constructor
    */
    public function n_to_month($month)
    {
        if ($month>12 || $month<1) {
            $this->error='Month range not valid. Must be 1 to 12!';
            $this->exit_on_error();
        }

        if($this->abbr===true)

            return($this->month_name_con[$this->lan][$month-1]);
        elseif($this->abbr!=true)
            return($this->month_name_ext[$this->lan][$month-1]);
    }

    /** Define if the day of date given is equal to day given.
    *    + Is Friday the 22nd of November 2002 ?
    *    + date according to date_format parameter passed on inizialization
    *    @access public
    *    @param date $data The date to check
    *    @param string $day The name of day to check
    *    @return mixed 1 if check is true, otherwise the day of date
    */
    public function is_day($data,$day)
    {
        $data=str_replace('-','/',$data);
        $data=str_replace('.','/',$data);
        $exp=explode('/',$data);

        $data_unix=$this->k_mktime($exp);
        $giorno_unix=date('w',$data_unix);

        if (!is_numeric($day)) {
            $day=$this->day_to_n($day);
        }

        if($giorno_unix==$day)

            return 1;
        else
            return $this->n_to_day($giorno_unix);
    }

    /** Trasform a classical date format in unix timestamp format.
    *    + date according to date_format parameter passed on inizialization
    *    + Remember that unix timestamp is the amount of seconds since 1/1/1970
    *    @access public
    *    @param date $date The date to transform
    *    @return timestamp The date transformed in timestamp
    */
    public function date_to_timestamp($date)
    {
        $date=str_replace('-','/',$date);
        $date=str_replace('.','/',$date);
        $exp=explode('/',$date);

        return $this->k_mktime($exp);
    }

    /** Define what's the day difference between two given date.
    *    + date according to date_format parameter passed on inizialization
    *    @access public
    *    @param date $data_ini The start date
    *    @param date $data_fin The end date
    *    @return int The difference in days between the two given dates
    */
    public function days_diff($data_ini,$data_fin)
    {
        $data_ini=str_replace('-','/',$data_ini);
        $data_ini=str_replace('.','/',$data_ini);
        $data_fin=str_replace('-','/',$data_fin);
        $data_fin=str_replace('.','/',$data_fin);

        $exp_ini=explode('/',$data_ini);
        $exp_fin=explode('/',$data_fin);

        $ini=date('z',$this->k_mktime($exp_ini));
        $fin=date('z',$this->k_mktime($exp_fin));

        $days = floor(($this->k_mktime($exp_fin)-$this->k_mktime($exp_ini))/(60*60*24));

        return $days;
    }

    /**
    *	Give the difference between two times.
    *	+ (i.e.: how minutes from 4.50 to 12.50?).
    *	+ Accept only 24H format.
    *	+ the time is a string like: "4.50" or "4:50"
    *   @access public
    *   @param string $time_from The start time
    *	@param string $time_to The end time
    *   @param char $result_in The format of result
    *	+ "m" -> for minutes
    *	+ "s" -> for seconds
    *	+ "h" -> for hours
    *   @return string The difference between times according to format given in $result_in
    */
    public function times_diff($time_from,$time_to,$result_in="m")
    {
        if ( (strstr($time_from,'.') || strstr($time_from,':')) && (strstr($time_to,'.') || strstr($time_to,':')) ) {
            $time_from=str_replace(':','.',$time_from);
            $time_to=str_replace(':','.',$time_to);

            $t1=explode('.',$time_from);
            $t2=explode('.',$time_to);

            $h1=$t1[0];
            $m1=$t1[1];

            $h2=$t2[0];
            $m2=$t2[1];

            if ($h1<=24 && $h2<=24 && $h1>=0 && $h2>=0 && $m1<=59 && $m2<=59 && $m1>=0 && $m2>=0) {
                $diff=($h2*3600+$m2*60)-($h1*3600+$m1*60);
                if($result_in=="s")

                    return $diff;
                elseif ($result_in=="m") {
                    return $diff/60;
                } elseif ($result_in=="h") {
                    $r=$diff/3600;
                    $t=explode('.',$r);
                    $h=$t[0];
                    if($h>24)
                        $h-=24;
                    $m=round("0.$t[1]"*60);

                    return $h.'h'.$m.'m';
                }
            } else {
                $this->error='Time range not valid. Must be 0 to 24 for hours and 0 to 59 for minutes!';
                $this->exit_on_error();
            }
        } else {
            $this->error='Time format not valid. Must be in format HH:mm or HH.mm';
            $this->exit_on_error();
        }
    }

    /**
    *	Add some minutes or hours to a given time.
    *	+ i.e.: (add 2 hours to 14.10 -> result is 16.10)
    *	+ Accept only 24H format.
    *	+ the time is a string like: "4.50" or "4:50"
    *   @param string $time The time string to transform
    *	@param int $add The hours or minutes to add
    *	@param char $what is what add to time
    *	+ "m" -> for add minutes
    *	+ "h" -> for add hours
    *	+ "t" -> for add time string given in HH:mm format
    *	@return string Result is in format HH:mm, return -1 on error
    */
    public function times_add($time,$add,$what)
    {
        if ( (strstr($time,'.') || strstr($time,':'))) {
            $time=str_replace(':','.',$time);
            $t1=explode('.',$time);
            $h1=$t1[0];
            $m1=$t1[1];
            if ($h1<=24 && $h1>=0  && $m1<=59 && $m1>=0) {
                if ($what=="m") {
                    $res=($h1*60)+$m1+$add;
                    $r=$res/60;
                    $t=explode('.',$r);
                    $h=$t[0];
                    if($h>24)
                        $h-=24;
                    $m=round("0.$t[1]"*60);

                    return $h.':'.$m;
                } elseif ($what=="h") {
                    $res=($h1*60)+$m1+($add*60);
                    $r=$res/60;
                    $t=explode('.',$r);
                    $h=$t[0];
                    if($h>24)
                        $h-=24;
                    $m=round("0.$t[1]"*60);

                    return $h.':'.$m;
                } elseif ($what=="t") {
                    if ( (strstr($add,'.') || strstr($add,':'))) {
                        $add=str_replace(':','.',$add);
                        $t1=explode('.',$add);
                        $h2=$t1[0];
                        $m2=$t1[1];
                        if ($h2<=24 && $h2>=0  && $m2<=59 && $m2>=0) {
                            $res=($h1*60)+($h2*60)+$m1+$m2;
                            $r=$res/60;
                            $t=explode('.',$r);
                            $h=$t[0];
                            if($h>24)
                                $h-=24;
                            $m=round("0.$t[1]"*60);

                            return $h.':'.$m;
                        }
                    } else {
                        $this->error='Time format not valid. Must be in format HH:mm or HH.mm';
                        $this->exit_on_error();
                    }
                }
            } else {
                $this->error='Time range not valid. Must be 0 to 24 for hours and 0 to 59 for minutes!';
                $this->exit_on_error();
            }
        } else {
            $this->error='Time format not valid. Must be in format HH:mm or HH.mm';
            $this->exit_on_error();
        }
    }

    /** Define how days left to given date. date according to date_format parameter passed on inizialization
    *    @access public
    *    @param date $data The date in traditional format for calculating diff
    *    @return int The amount of days between today and given date
    */
    public function how_to($data)
    {
        $data=str_replace('-','/',$data);
        $data=str_replace('.','/',$data);
        $exp=explode('/',$data);
        $data_unix=$this->k_mktime($exp);
        if($data_unix>time())

            return (date("z",$data_unix)-(date("z")));
        else {
            $this->error='Cannot perform calculation on past time!';
            $this->exit_on_error();
        }
    }


    /** Work like php native mktime() but with date accordingly to format used
    *    @access private
    *    @param array $exp The date to transform
    *    @return timestamp The timestamp calculated on date given
    */
    public function k_mktime($exp)
    {
        switch ($this->date_format) {
            case 'ansi': return mktime(0,0,0,$exp[1],$exp[2],$exp[0]); break; // using YYYY-MM-DD
            case 'it': return mktime(0,0,0,$exp[1],$exp[0],$exp[2]); break;// using DD-MM-YYYY
            case 'std': return mktime(0,0,0,$exp[0],$exp[1],$exp[2]); break; // using MM-DD-YYYY
            case 'en': return mktime(0,0,0,$exp[0],$exp[1],$exp[2]); break; // using MM-DD-YYYY
            default: $this->error='Date Format not recognized, must be "ansi", "it", "std" or "en" !! '; $this->exit_on_error();
        }
    }

    /**
    *	Return a single component of given date according to format in date_format
    *   date example with hour: 03/05/2003 23:43:00 (use only ':' as time separator)
    *   @access public
    *   @return date
    *   @param date to extract atom from
    *   @param atom ->
    *		 	+	'm' for return month;
    *			+	'd' for return day;
    *			+	'y' for return last two number of year
    *			+	'Y' for return entire year
    *			+	'h' for hours
    *			+	'i' for minutes
    *			+	's' for seconds
    */
    public function atom_date($date,$atom)
    {
        if (strlen($date)<10) {
            $date.=' 00:00:00';
        }

        $t=explode(' ',$date);
        $exp1=explode('/',$t[0]);
        $exp2=explode(':',$t[1]);
        $exp=array_merge($exp1,$exp2);
        // Extract only time
        switch ($atom) {
            case 'h': return $exp[3]; break;
            case 'i': return $exp[4]; break;
            case 's': return $exp[5]; break;
        }
        // Extract day,month and year
        switch ($this->date_format) {
            case 'ansi':
            {
                switch ($atom) {
                    case 'd': return $exp[2]; break;
                    case 'm': return $exp[1]; break;
                    case 'y': return substr($exp[0],2,2); break;
                    case 'Y': return $exp[0]; break;
                    default: $this->error='Atom not recognized, must be "d", "m", "y" or "Y" !!'; $this->exit_on_error();
                }
                break;
            }
            case 'it':
            {
                switch ($atom) {
                    case 'd': return $exp[0]; break;
                    case 'm': return $exp[1]; break;
                    case 'y': return substr($exp[2],2,2); break;
                    case 'Y': return $exp[2]; break;
                    default: $this->error='Atom not recognized, must be "d", "m", "y" or "Y" !!'; $this->exit_on_error();
                }
                break;
            }
            case 'en':
            case 'std':
            {
                switch ($atom) {
                    case 'd': return $exp[1]; break;
                    case 'm': return $exp[0]; break;
                    case 'y': return substr($exp[2],2,2); break;
                    case 'Y': return $exp[2]; break;
                    default: $this->error='Atom not recognized, must be "d", "m", "y" or "Y" !!'; $this->exit_on_error();
                }
                break;
            }
            default: $this->error='Date Format not recognized, must be "ansi", "it", "std" or "en" !! '; $this->exit_on_error();
        }
    }


    /** Date like function. Using the same format functionality
    *  @access public
    *  @return string The date according with format given
    *  @param string format ->
    *	+ valid format parameter:
    *	+ %l (L lowercase): Day textual long
    *	+ %d: Day of month, 2 digits with leading zeros
    *	+ %F: Month textual Long
    *	+ %Y: Year, 4 digits
    *	+ %y: Year, 2 digits
    *	+ %m: Month numeric, 2 digits with leading zeros
    *	+ %D: Day textual short
    *	+ %M: Month textual short
    *	+ %n: Month numeric, without leading zeros
    *	+ %j: Day of month, without leading zeros
    *  @param timestamp $timestamp The time to transform
    */
    public function k_date($format="%l %d %F %Y",$timestamp=0)
    {
        if($timestamp==0)
            $timestamp=time();


        if (!preg_match('/\%l|\%F|\%D|\%M/',$format)) {
            return date(str_replace('%','',$format),$timestamp);
        } else {
            $out=$format;
            if (strstr($format,'%l')) {
                $this->abbr=false;
                $out=str_replace('%l',$this->n_to_day(date('w',$timestamp)),$out);
            }
            if (strstr($format,'%F')) {
                $this->abbr=false;
                $out=str_replace('%F',$this->n_to_month(date('m',$timestamp)),$out);
            }
            if (strstr($format,'%D')) {
                $this->abbr=true;
                $out=str_replace('%D',$this->n_to_day(date('w',$timestamp)),$out);
            }
            if (strstr($format,'%M')) {
                $this->abbr=true;
                $out=str_replace('%M',$this->n_to_month(date('m',$timestamp)),$out);
            }
            if(strstr($format,'%Y'))
                $out=str_replace('%Y',date('Y',$timestamp),$out);
            if(strstr($format,'%y'))
                $out=str_replace('%y',date('y',$timestamp),$out);
            if(strstr($format,'%d'))
                $out=str_replace('%d',date('d',$timestamp),$out);
            if(strstr($format,'%m'))
                $out=str_replace('%m',date('m',$timestamp),$out);
            if(strstr($format,'%n'))
                $out=str_replace('%n',date('n',$timestamp),$out);
            if(strstr($format,'%j'))
                $out=str_replace('%j',date('j',$timestamp),$out);

            return $out;
        }
    }

    /** Perform operation like sum or subtraction on date
    *  @access public
    *  @return date The date transformed by calc
    *  @param  string $operator Operator may be
    *  +  '+' -> for sum
    *  +  'sum' -> for sum
    *  +  'add' -> for sum
    *  +  '-' -> for subtraction
    *  +  'sub'-> for subtraction
    *  +  'sot'-> for subtraction
    *  @param  date $date The date to calc on
    *  @param  string $operand is a number plus '%D' for days, '%M' for months, '%Y' for years
    *  + Example:
    *	- Add 1 month to a date:
    *	- $obj->operation('+','10/01/2003','1%M');
    *
    *	- Subtract 20 days from a date:
    *	- $obj->operation('-','10/01/2003','20%D');
    */
    public function operation($operator,$date,$operand)
    {
        $ts=$this->date_to_timestamp($date);

        if (!strstr($operand,'%')) {
            $this->error='Bad operand type!!';
            $this->exit_on_error();
        }

        $t=explode('%',$operand);
        $how=$t[0];

        switch ($t[1]) {
            case 'D':
            {
                if ($operator=='+' || $operator=='sum' || $operator=='add') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts),date('d',$ts)+$how,date('Y',$ts)));
                } elseif ($operator=='-' || $operator=='sub' || $operator=='sot') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts),date('d',$ts)-$how,date('Y',$ts)));
                } else {
                    $this->error='Operator not recognized!!';
                    $this->exit_on_error();
                }
                break;
            }
            case 'M':
            {
                if ($operator=='+' || $operator=='sum' || $operator=='add') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts)+$how,date('d',$ts),date('Y',$ts)));
                } elseif ($operator=='-' || $operator=='sub' || $operator=='sot') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts)-$how,date('d',$ts),date('Y',$ts)));
                } else {
                    $this->error='Operator not recognized!!';
                    $this->exit_on_error();
                }
                break;
            }
            case 'Y':
            {
                if ($operator=='+' || $operator=='sum' || $operator=='add') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts),date('d',$ts),date('Y',$ts)+$how));
                } elseif ($operator=='-' || $operator=='sub' || $operator=='sot') {
                    return date($this->_format(),mktime(0,0,0,date('m',$ts),date('d',$ts),date('Y',$ts)-$how));
                } else {
                    $this->error='Operator not recognized!!';
                    $this->exit_on_error();
                }
                break;
            }
            default:
            {
                $this->error='Bad operand type!!';
                $this->exit_on_error();
            }
        }
    }

    /** Return the timestamp from a NIST TIME SERVER on the net. Get the atomic time!
    *   + attention
    *   + have to stay on line for work!!
     *   @access public
    *   @return timestamp The timestamp from internet
    */
    public function net_timestamp($server='time-a.nist.gov', $port=37)
    {
        if ($fp = fsockopen($server, $port, $errno, $errstr, 25)) {
            fputs($fp, "\n");
            $timevalue = fread($fp, 49);
            fclose($fp);
        } else {
            $this->error=$server.' Time Server unavailable or u\'re not connected on the net!!';
            $this->exit_on_error();
        }

        $ts = (abs(hexdec('7fffffff') - hexdec(bin2hex($timevalue)) - hexdec('7fffffff')) - 2208988800);

        return $ts;
    }

    /** Returns the current time in swatch .beat format. Remember that 1000 beats = 24 hours
    *    @access public
    *    @return string The swatch beat time
    */
    public function swatch_time()
    {
      $offset = 60;
      $beat_division = 24 * 60 / 1000;
      $current_date = getdate(time());
      $hour = $current_date["hours"];
      $minute = $current_date["minutes"];
      $seconds = $current_date["seconds"];
      $total_minutes = $minute + $offset + $hour * 60;
      $beats = round ($total_minutes / $beat_division);
      if ($beats >= 1000) {
        $beats = $beats % 1000;
      }

       return ("@".$beats);
    }

    /** Get the date of Nth day of the month ..
    *    + example: what is the date of the 2nd Sunday of April 2003 ???
     *  @access public
    *  @return date The date
    *  @param int $number The ordinal value to get date
    *  @param string $weekday The name of day given in Long or short format
    *  @param mixed $month the name or number of month
    *  @param int $year the year number
    */
    public function get_nth_day($number,$weekday,$month,$year=0)
    {
        if ($number>5) {
            $this->error='There isn\'t more than 5 '.$weekday.' in a month, usually!!';
            $this->exit_on_error();
        }

        $date_counter=1;
        $week_counter=0;

        if($year==0)
            $year=date('Y');

        if(strlen($weekday)>3)
            $format_dow='%l';
        else
            $format_dow='%D';

        if (!is_numeric($month)) {
            $month=$this->month_to_n($month);
        }

        do {
            $itsit=mktime(0,0,0,$month,$date_counter,$year);
            $dow=$this->k_date($format_dow,$itsit);
            if ($dow==$weekday) {
                $week_counter++;
            }

            if ( ($week_counter==$number) && ($weekday==$dow) ) {
                $week_counter=$number;
                if($date_counter > 1) // Thanks to Maurizio Marini <maumar@datalogica.com>
                    $date_counter--;
            } else {
                $date_counter++;
            }
        } while ($week_counter<$number);

        $itsit=mktime(0,0,0,$month,$date_counter,$year);
        $format=$this->_format();
        $f=explode($this->separator,$format);
        $format='%'.$f[0].$this->separator.'%'.$f[1].$this->separator.'%'.$f[2];

        if ($this->k_date('n',$itsit)!=$month) {
            $this->error='Bad request, try again!!';
            $this->exit_on_error();
        } else {
            return $this->k_date($format,$itsit);
        }
    }

    /**
    * Return the literal value of a unix timestamp or seconds
    * + i.e.: so 3670 seconds mean 1 hour, 1 minute and 10 seconds.
    * @param int $seconds Number of seconds to transform.
    * @access public
    * @return string The seconds transformed in text
    */
    public function time_to_text($seconds)
    {
        if ($seconds<=60) {
            $hours=0;
            $minutes=0;
        } elseif ($seconds>=60 && $seconds<3600) {
            $hours=0;
            $minutes=$seconds/60;
            $t=explode('.',$minutes);
            $minutes=$t[0];
            $seconds=round("0.$t[1]"*60);
        } elseif ($seconds>=3600) {
            $r=$seconds/3600;
            $t=explode('.',$r);
            $hours=$t[0];
            if($hours>24)
                $hours-=24;

            $minutes="0.$t[1]"*60;
            $t=explode('.',$minutes);
            $minutes=$t[0];
            $seconds=round("0.$t[1]"*60);
        }

        return $hours.'h'.$minutes.'m'.$seconds.'s';
    }

}
