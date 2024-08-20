<?php

namespace App\Http\Controllers\API\V1\CourrierManagments;

use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CourrierManagments\ImputationCourriersAgentsModels;

class ImputationCourriersAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return DB::table('imputation_courriers_agents_models')
            ->orderByDesc('id')
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

    public function get_courrier_impted_by_employee($employee_matricule)
    {
        try {
            return DB::table('imputation_courriers_agents_models')
            ->join('courrier_models', 'imputation_courriers_agents_models.courrier_ref', '=', 'courrier_models.courrier_ref')
            ->where('imputation_courriers_agents_models.employee_matricule', $employee_matricule)
            ->select('courrier_models.*', 'imputation_courriers_agents_models.*')
            ->orderByDesc('id')
            ->get();
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if (empty($request->courrier_ref)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La reférence du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->employee_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'agent est obligatoire"
                    ]
                );
            endif;


            if (empty($request->date_execution)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La date d'exécution est obligatoire"
                    ]
                );
            endif;



            $add_imputation_courrier = new ImputationCourriersAgentsModels();
            $add_imputation_courrier->courrier_ref = $request->courrier_ref;
            $add_imputation_courrier->employee_matricule = $request->employee_matricule;
            $add_imputation_courrier->date_execution = date('Y-m-d', $request->date_execution);

            $add_imputation_courrier->slug = CodeGenerator::generateSlugCode();

            if($add_imputation_courrier->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => "Ok ! Le courrier a été imputé avec succès à l'agent service"
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'imputation du courrier à l'agent, veuillez réessayer!"
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

            if (empty($request->courrier_ref)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La reférence du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->employee_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'agent est obligatoire"
                    ]
                );
            endif;


            if (empty($request->date_execution)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La date d'exécution est obligatoire"
                    ]
                );
            endif;

            $update_imputation_courrier = ImputationCourriersAgentsModels::where('slug',$slug)->first();

            $update_imputation_courrier->courrier_ref = $request->courrier_ref;
            $update_imputation_courrier->employee_matricule = $request->employee_matricule;
            $update_imputation_courrier->date_execution = date('Y-m-d', $request->date_execution);

            if($update_imputation_courrier->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! L'imputation a été modifiée au avec succès"
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification , veuillez réessayer!"
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

                DB::table('imputation_courriers_agents_models')->where('slug', $slug)->delete();
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
