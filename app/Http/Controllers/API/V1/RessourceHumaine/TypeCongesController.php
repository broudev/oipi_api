<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\TypeCongeModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeCongesController extends Controller
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
            return TypeCongeModels::all();
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
            if (empty($request->type_conge)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le type de congé est obligatoire"
                    ]
                );
            endif;

            $add_type_conge = new TypeCongeModels();
            $add_type_conge->type_conge = $request->type_conge;
            $add_type_conge->slug = CodeGenerator::generateSlugCode();

            if($add_type_conge->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok!, Le type de congé a été enregistré avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du type de congé, veuillez réessayer!"
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

            if (empty($request->type_conge)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le type de congé est obligatoire"
                    ]
                );
            endif;

            $update_type_conge = TypeCongeModels::where('slug',$slug)->first();
            $update_type_conge->type_conge = $request->type_conge;

            if($update_type_conge->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, Le type de congé a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du type de congé, veuillez réessayer!"
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

                DB::table('type_conge_models')->where('slug', $slug)->delete();
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
