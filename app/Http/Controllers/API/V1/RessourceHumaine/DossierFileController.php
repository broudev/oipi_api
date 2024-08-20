<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Mail\Notifications;
use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\RessourceHumaine\DossierFileModels;

class DossierFileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public $folder_file;
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if(empty($request->libelle_file)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le libellé du fichier est obligatoire"
                    ]
                );
            endif;

            if(empty($request->dossiers_code)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le code du dossier est obligatoire"
                    ]
                );
            endif;



            $checkFileName = DB::table('dossier_file_models')
                ->where('libelle_file',$request->libelle_file)
                ->where('dossiers_code',$request->dossiers_code)
                ->first();

            if($checkFileName != null):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Un fichier porte le même nom."
                    ]
                );
            endif;

            //return  CodeGenerator::generateEmployeMatricule();

            $this->folder_file = UploadService::upload_folder_file($request);

            if($this->folder_file == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la photo a échoué."
                    ]
                );
            endif;

            $store_folder_file = new DossierFileModels();

            $store_folder_file->dossiers_code = $request->dossiers_code;
            $store_folder_file->libelle_file = $request->libelle_file;

            if($this->folder_file != "file_not_found"):
                $store_folder_file->file_url = $this->folder_file;
            endif;

            $store_folder_file->slug = CodeGenerator::generateSlugCode();


            if($store_folder_file->save()):


                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => "Ok ! Le fichier a été enregistré dans le dossier de l'employé avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du fichier dans le dossier de l'employé, veuillez réessayer!"
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


    public function update(Request $request, $slug)
    {
        //return $request->all();
        try {
            if(empty($request->libelle_file)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le libellé du fichier est obligatoire"
                    ]
                );
            endif;

            if(empty($request->dossiers_code)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le code du dossier est obligatoire"
                    ]
                );
            endif;


            //return  CodeGenerator::generateEmployeMatricule();

            $this->folder_file = UploadService::upload_folder_file($request);

            if($this->folder_file == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la photo a échoué."
                    ]
                );
            endif;

            $update_folder_file =  DossierFileModels::where('slug', $slug)->first();

            $update_folder_file->dossiers_code = $request->dossiers_code;
            $update_folder_file->libelle_file = $request->libelle_file;

            if($this->folder_file != "file_not_found"):
                $update_folder_file->file_url = $this->folder_file;
            endif;

            if($update_folder_file->save()):


                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => "Ok ! Le fichier a été modifié avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du fichier, veuillez réessayer!"
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


    public function confirmedOrRejectedFile(Request $request)
    {
        try {
            if (!$request->slug) :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :

                if($request->keywords == "confirmed"):

                    DB::table('dossier_file_models')->where('slug', $request->slug)->update([
                        'status_on' => 1, 'status_off' => 0
                    ]);

                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Le document a été approuvé avec succès"
                        ]
                    );
                endif;


                if($request->keywords == "rejected"):

                    $employee_data = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)
                    ->select('first_name', 'last_name')->first();

                    $notification = "Vous avez une notification de rejet d'un document fourni: #".$request->keyword;

                    Mail::to($request->adresse_mail)->send(new Notifications($notification,$request->notification_data,$employee_data));

                    DB::table('dossier_file_models')->where('slug', $request->slug)->update([
                        'status_off' => 1, 'status_on' => 0
                    ]);


                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Votre notification a été envoyée avec succès"
                        ]
                    );
                endif;
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

                DossierFileModels::where('slug', $slug)->delete();


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
