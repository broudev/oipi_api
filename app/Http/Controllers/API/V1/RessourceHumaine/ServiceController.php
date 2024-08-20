<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\ServiceModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
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
            return ServiceModels::all();
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
        try {
            if (empty($request->service)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le service est obligatoire"
                    ]
                );
            endif;

            $add_service = new ServiceModels();
            $add_service->service = $request->service;
            $add_service->slug = CodeGenerator::generateSlugCode();

            if($add_service->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok!, Le service a été enregistré avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du service, veuillez réessayer!"
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

            if (empty($request->service)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le service est obligatoire"
                    ]
                );
            endif;

            $update_service = ServiceModels::where('slug',$slug)->first();
            $update_service->service = $request->service;

            if($update_service->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, Le service a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du service, veuillez réessayer!"
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

                DB::table('service_models')->where('slug', $slug)->delete();
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
