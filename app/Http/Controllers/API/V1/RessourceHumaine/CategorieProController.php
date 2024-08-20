<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\CategorieProModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategorieProController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['index']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return CategorieProModels::all();
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
            if (empty($request->categorie_pro)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La categorie est obligatoire"
                    ]
                );
            endif;

            $add_categorie_pro = new CategorieProModels();
            $add_categorie_pro->categorie_pro = $request->categorie_pro;
            $add_categorie_pro->slug = CodeGenerator::generateSlugCode();

            if($add_categorie_pro->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, La catégorie professionnelle a été enregistrée avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement de la catégorie professionnelle, veuillez réessayer!"
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

            if (empty($request->categorie_pro)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La catégorie professionnelle est obligatoire"
                    ]
                );
            endif;

            $update_categorie_pro = CategorieProModels::where('slug',$slug)->first();
            $update_categorie_pro->categorie_pro = $request->categorie_pro;

            if($update_categorie_pro->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok!, La catégorie professionnelle a été modifiée avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification de la catégorie professionnelle, veuillez réessayer!"
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

                DB::table('categorie_pro_models')->where('slug', $slug)->delete();
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
