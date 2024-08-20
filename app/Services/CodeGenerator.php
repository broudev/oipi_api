<?php
namespace App\Services;


use DateTime;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CodeGenerator

{


    public static function generatePermissionCode()
    {

        $verify_last_code = DB::table('permissions_models')
        ->orderByDesc('id')
        ->value('permission_ref');
        //return $verify_last_code;
        //ST06082023-01

        if($verify_last_code == null):

            return 'PERM'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 5);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'PERM'.'_'.$incrementedNumber;
        endif;
    }


    public static function generateCustomerCode()
    {
        return "CUST" ."-".  date('YHis', strtotime(now()));
    }





    public static function generateCongeCode()
    {
        $verify_last_code = DB::table('conges_models')
        ->orderByDesc('id')
        ->value('conge_ref');
        //return $verify_last_code;
        //ST06082023-01

        if($verify_last_code == null):

            return 'CONG'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 5);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'CONG'.'_'.$incrementedNumber;
        endif;
    }

    public static function generateCourrierCode()
    {
        $verify_last_code = DB::table('courrier_models')
        ->orderByDesc('id')
        ->value('courrier_ref');
        //return $verify_last_code;
        //ST06082023-01

        if($verify_last_code == null):

            return 'COUR'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 5);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'COUR'.'_'.$incrementedNumber;
        endif;
    }


    public static function generateCourrierSendCode()
    {
        $verify_last_code = DB::table('send_courrier_models')
        ->orderByDesc('id')
        ->value('courrier_send_ref');

        if($verify_last_code == null):

            return 'COURS'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 5);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'COURS'.'_'.$incrementedNumber;
        endif;
    }


    public static function generateDosCode()
    {
        return "DOS" ."-".  date('dmY-His', strtotime(now()));
    }


    public static function generateSlugCode()
    {
        $lenght= 50;
        $keys = substr(str_shuffle(
            str_repeat($x = '1234567890', ceil($lenght / strlen($x)))
        ), 3, $lenght);

        return $keys;
    }

    public static function generateCodeAuthorization()
    {
        $lenght= 8;
        $keys = substr(str_shuffle(
            str_repeat($x = 'AUTHORIZATION1234567890', ceil($lenght / strlen($x)))
        ), 3, $lenght);
        return $keys;
    }


    public static function generatePassword()
    {
        $lenght= 8;
        $keys = substr(str_shuffle(
            str_repeat($x = 'AUTHOrizaTIONpassword1234567890', ceil($lenght / strlen($x)))
        ), 3, $lenght);
        return $keys . '-' . date('Hs', strtotime(now()));
    }



    public static function generateEmployeMatricule()
    {

        $verify_last_code = DB::table('employe_information_models')
        ->orderBy('id', 'desc')
        ->value('matricule');
        //return $verify_last_code;
        //ST06082023-01

        if($verify_last_code == null):

            return 'OIPI'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 5);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'OIPI'.'_'.$incrementedNumber;
        endif;
    }

    public static function generatePointageCode()
    {

        $verify_last_code = DB::table('pointages_models')
        ->orderBy('id', 'desc')
        ->value('pointage_ref');

        //$date = date('YHi', strtotime(now()));

        if($verify_last_code == null):

            return 'PT'.'_'.'00001';
        else:
            $get_code_number = substr($verify_last_code, 3);

            $incrementedNumber = str_pad($get_code_number + 1, strlen($get_code_number), '0', STR_PAD_LEFT);

            return 'PT'.'_'.$incrementedNumber;
        endif;
    }

    public static function generateEmployeCode()
    {
        return "EMP" ."-".  date('dmY-His', strtotime(now()));
    }


    public static function format_my_date($date)
    {
        $date = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }


    public static function get_nombre_heure($date1, $date2)
    {
        $start_time = new DateTime($date1);

        $end_time = new DateTime($date2);

        $interval = $start_time->diff($end_time);
        $working_hours = $interval->format('%h:%I');

        return $working_hours;
    }


    public static function somme_heure($heure_array)
    {

        $heures = array($heure_array['h1'], $heure_array['h2'], $heure_array['h3'], $heure_array['h4']);

        $totalSecondes = 0;


        foreach ($heures as $heure) {
            $secondes = strtotime($heure);
            $totalSecondes += $secondes;
        }
        $heuresTotales = date("H:i", $totalSecondes);

        return $heuresTotales;
    }


    public static function total_heure($heure)
    {

        $totalSecondes = 0;

        foreach ($heure as $heure) {
            $secondes = strtotime($heure);
            $totalSecondes += $secondes;
        }

        $heuresTotales = date("H:i", $totalSecondes);

        return $heuresTotales;
    }
}
