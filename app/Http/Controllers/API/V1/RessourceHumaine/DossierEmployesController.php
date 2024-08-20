<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Models\RessourceHumaine\DossierFileModels;
use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\DossierEmployesModels;

class DossierEmployesController extends Controller
{
    public $employe_photo;

    public $ducoment_decision;
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        try {
            return  DB::table('dossier_employes_models')
            ->join('employe_information_models', 'dossier_employes_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->select('employe_information_models.first_name','employe_information_models.last_name', 'dossier_employes_models.*')
            ->get();

        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'erreur',
                    'code' => 300,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    public function customers_dossiers($employe_matricule) {

        try {

            return  DB::table('dossier_employes_models')
            ->join('employe_information_models', 'dossier_employes_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'dossier_employes_models.*'
            )
            ->where('dossier_employes_models.employe_matricule', $employe_matricule)
            ->orderByDesc('dossier_employes_models.created_at')
            ->first();


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



    public function get_customers_documents($dossiers_code) {

        try {

            return  DB::table('dossier_employes_models')
            ->join('employe_information_models', 'dossier_employes_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('dossier_file_models', 'dossier_employes_models.dossiers_code', '=', 'dossier_file_models.dossiers_code')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.adresse_email',
                'employe_information_models.last_name',
                'employe_information_models.matricule',
                'dossier_employes_models.libelle_dossiers',
                'dossier_file_models.*'
            )
            ->where('dossier_employes_models.dossiers_code', $dossiers_code)
            ->get();


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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if(empty($request->libelle_dossiers)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le libellé du dossier est obligatoire"
                    ]
                );
            endif;

            if(empty($request->employe_matricule)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le propriéaire du dossier est obligatoire"
                    ]
                );
            endif;



            $checkEmploye = DB::table('dossier_employes_models')->where('employe_matricule',$request->employe_matricule)->first();

            if($checkEmploye != null):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Un dossier existant porte votre matricule déjà."
                    ]
                );
            endif;



            $store_folder = new DossierEmployesModels();

            $store_folder->dossiers_code = CodeGenerator::generateDosCode();
            $store_folder->libelle_dossiers = $request->libelle_dossiers;
            $store_folder->employe_matricule = $request->employe_matricule;


            $store_folder->slug = CodeGenerator::generateSlugCode();


            if($store_folder->save()):


                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,

                        'message' => "Ok ! Le dossier a été enregistré avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du dossier, veuillez réessayer!"
                    ]
                );
            endif;



        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'erreur',
                    'code' => 300,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $dossiers_code)
    {
        try {
            if (!$dossiers_code) :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :

                $ducoment_data = DB::table('dossier_employes_models')
                ->join('employe_information_models', 'dossier_employes_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->join('dossier_file_models', 'dossier_employes_models.dossiers_code', '=', 'dossier_file_models.dossiers_code')
                ->select(
                    'employe_information_models.first_name',
                    'employe_information_models.adresse_email',
                    'employe_information_models.last_name',
                    'employe_information_models.matricule',
                    'dossier_employes_models.libelle_dossiers',
                    'dossier_file_models.*'
                )
                ->where('dossier_employes_models.dossiers_code', $dossiers_code)
                ->get();

                $check_document_decision = DB::table('dossier_file_models')
                ->where('dossiers_code', $dossiers_code)
                ->where('status_on', 0)
                ->first();


                if($check_document_decision == null):
                    $this->ducoment_decision = 'is_validate_all';

                    $employee_matricule = DB::table('dossier_employes_models')->select('employe_matricule')
                    ->where('dossiers_code', $dossiers_code)->first();

                    DB::table('dossier_employes_models')->where('dossiers_code', $dossiers_code)
                    ->update(['status_folder_on' => 1]);

                    DB::table('employe_information_models')->where('matricule', $employee_matricule->employe_matricule)
                    ->update(['status_dossiers' => 1]);
                else:
                    $this->ducoment_decision = 'is_not_validate_all';

                    $employee_matricule = DB::table('dossier_employes_models')->select('employe_matricule')
                    ->where('dossiers_code', $dossiers_code)->first();

                    DB::table('dossier_employes_models')->where('dossiers_code', $dossiers_code)
                    ->update(['status_folder_off' => 0]);

                    DB::table('employe_information_models')->where('matricule', $employee_matricule->employe_matricule)
                    ->update(['status_dossiers' => 0]);
                endif;

                return [
                    'ducoment_data' => $ducoment_data,
                    'is_decision' => $this->ducoment_decision,
                ];
            endif;
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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {

        //return $request->all();
        try {


            if(empty($request->libelle_dossiers)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le libellé du dossier est obligatoire"
                    ]
                );
            endif;

            if(empty($request->employe_matricule)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le propriéaire du dossier est obligatoire"
                    ]
                );
            endif;


            //return $current_account;
            $update_folders =  DossierEmployesModels::where('slug', $slug)->first();

            $update_folders->libelle_dossiers = $request->libelle_dossiers;
            $update_folders->employe_matricule = $request->employe_matricule;

            if($update_folders->save()):

                //Mail::to($store_employe_info->email)->send(new Notifications($store_employe_info,));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! Le dossier a été modifié avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du dossier de l'employé, veuillez réessayer!"
                    ]
                );
            endif;

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
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :

                DossierEmployesModels::where('slug', $slug)->delete();


                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! Suppression effectuée"
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

}
