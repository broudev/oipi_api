<?php

namespace App\Http\Controllers\API\V1\Statistique;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatistiqueController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function get_admin_statistique()
    {
        setlocale(LC_TIME, 'French');
        try {

            // employee
            $all_employee = DB::table('employe_information_models')->count();
            $all_employee_men = DB::table('employe_information_models')->where('genre','Homme')->count();
            $all_employee_women = DB::table('employe_information_models')->where('genre','Femme')->count();

            // courrier
            $all_courrier = DB::table('courrier_models')->count();
            $courrier_traiter = DB::table('courrier_models')->where('status_completed',1)->count();
            $courrier_non_traiter = DB::table('courrier_models')
            ->where('status_responsable_imputed',0)
            ->where('status_agent_imputed',0)
            ->count();

            $courrier_imputer_au_responsable = DB::table('courrier_models')
            ->where('status_responsable_imputed',1)->where('status_agent_imputed',0)->count();

            $courrier_imputer_au_agent = DB::table('courrier_models')
            ->where('status_responsable_imputed',1)->where('status_agent_imputed',1)->count();


            return [
                'all_employee' => $all_employee,
                'all_employee_men' => $all_employee_men,
                'all_employee_women' => $all_employee_women,
                'all_courrier' => $all_courrier,
                'courrier_traiter' => $courrier_traiter,
                'courrier_non_traiter' => $courrier_non_traiter,
                'courrier_imputer_au_responsable' => $courrier_imputer_au_responsable,
                'courrier_imputer_au_agent' => $courrier_imputer_au_agent,

            ];


        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 300,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }


    //get_rh_statistique


    public function get_rh_statistique()
    {
        setlocale(LC_TIME, 'French');
        try {

            // employee
            $all_employee = DB::table('employe_information_models')->count();
            $all_employee_men = DB::table('employe_information_models')->where('genre','Homme')->count();
            $all_employee_women = DB::table('employe_information_models')->where('genre','Femme')->count();

            $cumule_absence  =  DB::table('pointages_models')->where('start_time', '00:00:00')->where('end_time', '00:00:00')
            ->count();

            $cumule_presence=  DB::table('pointages_models')->where('start_time','!=', '00:00:00')->where('end_time','!=', '00:00:00')
            ->count();

            return [
                'all_employee' => $all_employee,
                'all_employee_men' => $all_employee_men,
                'all_employee_women' => $all_employee_women,
                'cumule_presence' => $cumule_presence,
                'cumule_absence' => $cumule_absence,

            ];


        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 300,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }


    public function get_attendance_statistique()
    {
        setlocale(LC_TIME, 'French');

        try {

            $cumule_absence =  DB::table('pointages_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('DATE_FORMAT(date_started, "%m") month'))
            ->groupBy('month')
            ->where('start_time', '00:00:00')
            ->where('end_time', '00:00:00')
            ->orderBy('date_started', 'asc')
            ->pluck('data');

            $cumule_presence =  DB::table('pointages_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('DATE_FORMAT(date_started, "%m") month'))
            ->groupBy('month')
            ->where('start_time','!=', '00:00:00')
            ->where('end_time','!=', '00:00:00')
            ->orderBy('date_started', 'asc')
            ->pluck('data');

            $month =  DB::table('pointages_models')
            ->select(DB::raw('DATE_FORMAT(date_started, "%m/%Y") month'))
            ->groupBy('month')
            ->orderBy('date_started', 'asc')
            ->limit(12)
            ->pluck('month');
            return [
                'cumule_presence' => $cumule_presence,
                'cumule_absence' => $cumule_absence,
                'month' => $month
            ];

        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }
    }




    public function get_admin_courriers_statistique()
    {


        try {

            // TOTAL COURRIERS
            $all_courrier =  DB::table('courrier_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('MONTH(created_at) month'))
            ->groupBy('month')
            ->pluck('data');

            // COURRIER TRAITES
            $courrier_traiter =  DB::table('courrier_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('MONTH(created_at) month'))
            ->groupBy('month')
            ->where('status_completed',1)
            ->pluck('data');

            // COURRIER NON TRAITES
            $courrier_non_traiter =  DB::table('courrier_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('MONTH(created_at) month'))
            ->groupBy('month')
            ->where('status_responsable_imputed',0)
            ->where('status_agent_imputed',0)
            ->pluck('data');


            // COURRIER IMPUTED
            $courrier_imputed =  DB::table('courrier_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('MONTH(created_at) month'))
            ->groupBy('month')
            ->where('status_responsable_imputed',1)
            ->where('status_agent_imputed',0)
            ->pluck('data');

            // COURRIER NON IMPUTED
            $courrier_not_imputed =  DB::table('courrier_models')
            ->select(DB::raw('count(*) as `data`'),  DB::raw('MONTH(created_at) month'))
            ->groupBy('month')
            ->where('status_responsable_imputed',0)
            ->where('status_agent_imputed',0)
            ->pluck('data');

            $month =  DB::table('courrier_models')
            ->select(DB::raw('DATE_FORMAT(created_at, "%b %Y") month'))
            ->groupBy('month')
            ->limit(12)
            ->pluck('month');


            return [
                'total_courriers' => $all_courrier,
                'courrier_traiter' => $courrier_traiter,
                'courrier_non_traiter' => $courrier_non_traiter,
                'courrier_imputed' => $courrier_imputed,
                'courrier_not_imputed' => $courrier_not_imputed,
                'month' => $month
            ];

        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

}


