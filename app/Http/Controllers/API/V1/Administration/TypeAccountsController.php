<?php

namespace App\Http\Controllers\API\V1\Administration;

use App\Http\Controllers\Controller;
use App\Models\TypeAccounts\TypeAccountsModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return TypeAccountsModels::all();
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

    public function store(Request $request)
    {

        try {
            if (empty($request->type_accounts)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le type de compte est obligatoire"
                    ]
                );
            endif;

            $add_type_accounts = new TypeAccountsModels();
            $add_type_accounts->type_accounts = $request->type_accounts;
            $add_type_accounts->slug = CodeGenerator::generateSlugCode();

            if($add_type_accounts->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Le type de compte a été enregistré avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du type de compte, veuillez réessayer!"
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




    public function update(Request $request,$slug)
    {
        try {

            if (empty($request->type_accounts)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le type de compte est obligatoire"
                    ]
                );
            endif;

            $update_type_accounts = TypeAccountsModels::where('slug',$slug)->first();
            $update_type_accounts->type_accounts = $request->type_accounts;

            if($update_type_accounts->save()) :
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok!, Le type de compte a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du type de compte, veuillez réessayer!"
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

    public function destroy($slug)
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

                DB::table('type_accounts_models')->where('slug', $slug)->delete();
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
