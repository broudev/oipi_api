<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeAuthorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($owners)
    {
        try {
            return DB::table('code_authorization_models')->where('owners', $owners)->first();

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

    public function check_code_authorization(Request $request)
    {
        try {

            $date = date('Y-m-d', strtotime(now()));
            $data =  DB::table('code_authorization_models')
            ->where('code', $request->code)
            ->whereDate('date_echeance', $date)
            ->first();

            if($data != null):

                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'code_authorization_data' => $data,
                        'message' => "Ok ! Autorisation accordée."
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 302,
                        'message' => "Erreur ! Autorisation refusée. Le code est introuvable ou invalide."
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

    public function refresh_code($slug)
    {
        //return $slug;

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

                $data = DB::table('code_authorization_models')->where('slug', $slug)->first();

                if($data != null):
                    $date = date('Y-m-d', strtotime(now()));
                    DB::table('code_authorization_models')->where('slug', $slug)->update([
                        'date_echeance' => $date,
                        'old_code' => $data->code,
                        'code' => CodeGenerator::generateCodeAuthorization()
                    ]);

                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Ok ! Le code d'autorisation a été rafraîchi avec succès."
                        ]
                    );
                else:
                    return response()->json(
                        [
                            'status' => 'error',
                            'code' => 404,
                            'message' => "Erreur ! Le rafraîchissement du code d'autorisation a échoué."
                        ]
                    );

                endif;

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


}
