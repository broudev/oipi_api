<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\PointageConfigModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointageConfigController extends Controller
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
            return DB::table('pointage_config_models')->first();
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
            if (empty($request->initial_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure initiale est obligatoire"
                    ]
                );
            endif;
            if (empty($request->tolerable_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure tolérable est obligatoire"
                    ]
                );
            endif;

            if (empty($request->out_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure de sortie est obligatoire"
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

            if (empty($request->break_time)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure de pause est obligatoire"
                    ]
                );
            endif;

            if (empty($request->total_hour_of_work)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure total de travail est obligatoire"
                    ]
                );
            endif;

            $add_pointage_config = new PointageConfigModels();
            $add_pointage_config->initial_hour = $request->initial_hour;
            $add_pointage_config->tolerable_hour = $request->tolerable_hour;
            $add_pointage_config->out_hour = $request->out_hour;
            $add_pointage_config->break_time = $request->break_time;
            $add_pointage_config->total_hour_of_work = $request->total_hour_of_work;
            $add_pointage_config->slug = CodeGenerator::generateSlugCode();

            if($add_pointage_config->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, La configuration a été enregistrée avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement, veuillez réessayer!"
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

            if (empty($request->initial_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure initiale est obligatoire"
                    ]
                );
            endif;
            if (empty($request->tolerable_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure tolérable est obligatoire"
                    ]
                );
            endif;

            if (empty($request->out_hour)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure de sortie est obligatoire"
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

            if (empty($request->total_hour_of_work)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'heure total de travail est obligatoire"
                    ]
                );
            endif;

            $update_pointage_config = PointageConfigModels::where('slug',$slug)->first();
            $update_pointage_config->initial_hour = $request->initial_hour;
            $update_pointage_config->tolerable_hour = $request->tolerable_hour;
            $update_pointage_config->out_hour = $request->out_hour;
            $update_pointage_config->break_time = $request->break_time;
            $update_pointage_config->total_hour_of_work = $request->total_hour_of_work;
            if($update_pointage_config->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, La configuration a été modifiée avec succès'
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

                DB::table('pointage_config_models')->where('slug', $slug)->delete();
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
