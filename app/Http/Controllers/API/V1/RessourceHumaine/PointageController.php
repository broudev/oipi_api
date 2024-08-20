<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\PointagesModels;
use App\Services\CodeGenerator;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PointageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return DB::table('pointages_models')
                ->join('employe_information_models', 'pointages_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->select(
                    'employe_information_models.first_name',
                    'employe_information_models.last_name',
                    'employe_information_models.photo',
                    'pointages_models.*'
                )
                ->orderByDesc('pointages_models.date_started')
                ->get();
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

    public function get_employe_not_pointng()
    {
        try {

            $employees_matricule = DB::table('pointages_models')
            ->whereDate('created_at', date('Y-m-d', strtotime(now())))
            ->pluck('employe_matricule');



            /**
             * $employe_list = DB::table('employe_contrat_models')
                ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                ->select(
                    'employe_contrat_models.*',
                    'service_models.service',
                    'fonction_models.fonction',
                    'contrats_models.contrats',
                    'bureau_models.bureau',
                    'categorie_pro_models.categorie_pro',
                    'employe_information_models.*'
                )
                ->orderByDesc('employe_information_models.id')
                ->get();
             */

            
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



    public function get_customer_attendance($employe_matricule)
    {
        try {
            return DB::table('pointages_models')
                ->join('employe_information_models', 'pointages_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->select(
                    'employe_information_models.first_name',
                    'employe_information_models.last_name',
                    'employe_information_models.photo',
                    'pointages_models.*'
                )
                ->orderByDesc('pointages_models.date_started')
                ->where('pointages_models.employe_matricule', $employe_matricule)
                ->get();
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

    // STATISTIQUE

    public function get_customer_attendance_statistique($employe_matricule)
    {

        //EMP-08012024-111834
        try {
            $date_debut = $this->get_date_debut();

            $total_of_mounth =  DB::table('pointages_models')->select('total_hour_of_work')->where('employe_matricule', $employe_matricule)
                ->whereMonth('date_started', date('m', strtotime(now())))
                ->pluck('total_hour_of_work');

            $total_of_week =  DB::table('pointages_models')->select('total_hour_of_work')->where('employe_matricule', $employe_matricule)
                ->whereMonth('date_started', date('m', strtotime(now())))
                ->whereBetween('date_started', $this->listeJoursEntreDimancheEtSamedi())
                ->pluck('total_hour_of_work');

            $total_of_today =  DB::table('pointages_models')->select('total_hour_of_work')->where('employe_matricule', $employe_matricule)
                ->whereMonth('date_started', date('m', strtotime(now())))
                ->where('date_started', $date_debut)
                ->pluck('total_hour_of_work');

            return [
                'total_of_mounth' => $total_of_mounth,
                'total_of_week' => $total_of_week,
                'total_of_today' => $total_of_today,
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
    //->whereBetween('created_at', [$date_debut, $date_fin])
    protected function get_date_debut()
    {
        $current_date = new DateTime();
        $date_debut = $current_date->sub(new DateInterval('P1D'));
        $date_debut = $date_debut->format('Y-m-d');
        return $date_debut;
    }

    protected function get_date_fin()
    {
        $current_date = new DateTime();
        $date_fin = $current_date->format('Y-m-d');
        return $date_fin;
    }


    // FORMATER ET RECUPERER LA LISTE DES JOURS EN DIMANCE ET SAMEDI
    protected function listeJoursEntreDimancheEtSamedi()
    {
        $debutSemaine = Carbon::now()->startOfWeek(); // Obtient le début de la semaine (par défaut, dimanche)
        $finSemaine = Carbon::now()->endOfWeek(); // Obtient la fin de la semaine (par défaut, samedi)

        $listeJours = new Collection();

        // Parcourir les jours de la semaine
        for ($date = $debutSemaine; $date->lte($finSemaine); $date->addDay()) {
            $listeJours->push($date->toDateString()); // Ajouter le jour formaté à la liste
        }

        return $listeJours;
    }



    public function get_current_pointage()
    {
        try {
            $current_date = date('Y-m-d', strtotime(now()));

            return DB::table('pointages_models')
                ->join('employe_information_models', 'pointages_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->select(
                    'employe_information_models.first_name',
                    'employe_information_models.last_name',
                    'employe_information_models.photo',
                    'pointages_models.*'
                )
                ->orderByDesc('pointages_models.created_at')
                ->whereDate('pointages_models.date_started', $current_date)
                ->get();
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



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if (empty($request->employe_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Aucun employé selectionné."
                    ]
                );
            endif;
            if (empty($request->date_started)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La date du jour est obligatoire"
                    ]
                );
            endif;



            if (empty($request->break_time)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure de pause est obligatoire"
                    ]
                );
            endif;


            $employee_is_checked = DB::table('pointages_models')->where('employe_matricule', $request->employe_matricule)
            ->whereDate('created_at', date('Y-m-d', strtotime($request->date_started)))
            ->first();

            if ($employee_is_checked) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Cet employé a déjà un pointage enregistré aujourd'hui."
                    ]
                );
            endif;



            $add_pointage = new PointagesModels();

            $add_pointage->pointage_ref = CodeGenerator::generatePointageCode();
            $add_pointage->employe_matricule = $request->employe_matricule;
            $add_pointage->date_started = date('Y-m-d', strtotime($request->date_started));
            $add_pointage->start_time = $request->start_time;
            $add_pointage->end_time = $request->end_time;
            $add_pointage->break_time = $request->break_time;
            $add_pointage->total_hour_of_work = $request->total_hour_of_work;
            $add_pointage->overtime = $request->overtime;
            $add_pointage->tolerable_hour = $request->heure_tolerable;
            $add_pointage->slug = CodeGenerator::generateSlugCode();

            if ($add_pointage->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Pointage enregistré avec succès'
                    ]
                );
            else :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du pointage, veuillez réessayer!"
                    ]
                );
            endif;
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'erreur',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        try {

            if (empty($request->employe_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Aucun employé selectionné."
                    ]
                );
            endif;



            if (empty($request->break_time)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure de pause est obligatoire"
                    ]
                );
            endif;



            $update_pointage = PointagesModels::where('slug', $slug)->first();
            $update_pointage->employe_matricule = $request->employe_matricule;
            $update_pointage->date_started = date('Y-m-d', strtotime($request->date_started));
            $update_pointage->start_time = $request->start_time;
            $update_pointage->end_time = $request->end_time;
            $update_pointage->break_time = $request->break_time;
            $update_pointage->total_hour_of_work = $request->total_hour_of_work;
            $update_pointage->overtime = $request->overtime;


            if ($update_pointage->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, Pointage modifié avec succès'
                    ]
                );
            else :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du pointage , veuillez réessayer!"
                    ]
                );
            endif;
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'erreur',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        try {
            if (!$slug) :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Erreur!, Aucun element trouvé"
                    ]
                );
            else :

                DB::table('pointages_models')->where('slug', $slug)->delete();
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => "Ok!, Suppression éffectuée"
                    ]
                );

            endif;
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'erreur',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }
    }
}
