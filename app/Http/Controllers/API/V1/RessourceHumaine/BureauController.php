<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\BureauModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BureauController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return BureauModels::all();
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
            if (empty($request->bureau)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le site est obligatoire"
                    ]
                );
            endif;

            $add_bureau = new BureauModels();
            $add_bureau->bureau = $request->bureau;
            $add_bureau->slug = CodeGenerator::generateSlugCode();

            if($add_bureau->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok!, Le site a été enregistré avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du site, veuillez réessayer!"
                    ]
                );
            endif;
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        try {

            if (empty($request->bureau)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le site est obligatoire"
                    ]
                );
            endif;

            $update_bureau = BureauModels::where('slug',$slug)->first();
            $update_bureau->bureau = $request->bureau;

            if($update_bureau->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok!, Le site a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du site, veuillez réessayer!"
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

                DB::table('bureau_models')->where('slug', $slug)->delete();
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
