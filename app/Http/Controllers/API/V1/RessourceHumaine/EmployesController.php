<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Mail\Notifications;
use App\Models\RessourceHumaine\EmployeContratModels;
use App\Models\RessourceHumaine\EmployeInformationModels;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Notification;

class EmployesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public $employe_photo;

    public $is_use;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employe_list = DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
            ->select(
                'employe_contrat_models.*',
                'service_models.service',
                'fonction_models.fonction',
                'contrats_models.contrats',
                'bureau_models.bureau',
                'categorie_pro_models.categorie_pro',
                'employe_information_models.*'
            )
            ->orderByDesc('employe_information_models.id')
            ->get();

            return $employe_list;

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

    public function get_employe_info($employe_matricule)
    {
        try {


            $employe_info_perso = EmployeInformationModels::where('matricule', $employe_matricule)->first();

            $employe_info_contrats = DB::table('employe_contrat_models')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                ->select(
                    'service_models.*',
                    'fonction_models.*',
                    'contrats_models.*',
                    'bureau_models.*',
                    'categorie_pro_models.*',
                    'employe_contrat_models.*',
                )
                ->where('employe_contrat_models.employe_matricule', $employe_info_perso->matricule)
                ->first();

            return  [
                'employe_info_contrats' => $employe_info_contrats,
                'employe_info_perso' => $employe_info_perso,
            ];

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


    public function get_employe_info_with_status_0()
    {
        try {
            return  DB::table('employe_information_models')->where('status_info',0)->orderByDesc('id')->get();

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

    public function get_employee_where_is_responsible_and_is_agent_equal_zero()
    {
        try {
            return  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')

            ->select(
                'employe_contrat_models.*',
                'service_models.service',
                'employe_information_models.*'
            )
            ->where('is_responsible',0)
            ->where('is_agents',0)
            ->orderByDesc('employe_information_models.id')
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


    public function get_responsable_service()
    {
        try {
            return  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->select(
                'service_models.service',
                'employe_information_models.*'
            )
            ->where('is_responsible',1)
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


    public function show(string $slug)
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
                $employe_info_perso = EmployeInformationModels::where('slug', $slug)->first();
                $employe_info_contrats = DB::table('employe_contrat_models')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                ->select(
                    'service_models.*',
                    'fonction_models.*',
                    'contrats_models.*',
                    'bureau_models.*',
                    'categorie_pro_models.*',
                    'employe_contrat_models.*',
                )
                ->where('employe_contrat_models.employe_matricule', $employe_info_perso->matricule)
                ->first();


                $employee_dossier = DB::table('dossier_employes_models')->where('employe_matricule', $employe_info_perso->matricule)
                ->first();

                return  [
                    'employe_info_contrats' => $employe_info_contrats,
                    'employe_info_perso' => $employe_info_perso,
                    'employee_dossier' => $employee_dossier
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
            if(empty($request->first_name)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le nom est obligatoire"
                    ]
                );
            endif;

            if(empty($request->last_name)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le prénom est obligatoire"
                    ]
                );
            endif;

            if(empty($request->diplome)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le diplome est obligatoire"
                    ]
                );
            endif;

            if(empty($request->date_naissance)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La date de naissance est obligatoire"
                    ]
                );
            endif;

            if(empty($request->lieu_naissance)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le lieu de naissance est obligatoire"
                    ]
                );
            endif;

            if(empty($request->genre)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le genre (sexe) est obligatoire"
                    ]
                );
            endif;

            if(empty($request->situation_matrimoniale)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le lieu de residence est obligatoire"
                    ]
                );
            endif;

            if(empty($request->lieu_residence)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le lieu de naissace est obligatoire"
                    ]
                );
            endif;

            if(empty($request->nationalite)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La nationalité est obligatoire"
                    ]
                );
            endif;

            if(empty($request->type_piece)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le type de la pièce est obligatoire"
                    ]
                );
            endif;

            if(empty($request->piece_number)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le N° de la pièce est obligatoire"
                    ]
                );
            endif;

            if(empty($request->profession)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La profession de l'employé est obligatoire"
                    ]
                );
            endif;




            //return $current_account;
            $update_employe =  EmployeInformationModels::where('slug', $slug)->first();

            $update_employe->first_name = $request->first_name;
            $update_employe->last_name = $request->last_name;
            $update_employe->diplome = $request->diplome;
            $update_employe->profession = $request->profession;
            $update_employe->adresse_email = $request->adresse_email;
            $update_employe->date_naissance = $request->date_naissance;
            $update_employe->lieu_naissance = $request->lieu_naissance;
            $update_employe->genre = $request->genre;
            $update_employe->situation_matrimoniale = $request->situation_matrimoniale;

            $update_employe->lieu_residence = $request->lieu_residence;
            $update_employe->nationalite = $request->nationalite;
            $update_employe->nombre_enfant_a_charge = $request->nombre_enfant_a_charge;
            $update_employe->type_piece = $request->type_piece;
            $update_employe->piece_number = $request->piece_number;
            $update_employe->cnps_number = $request->cnps_number;



            if($update_employe->save()):

                //Mail::to($store_employe_info->email)->send(new Notifications($store_employe_info,));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! Les informations ont été modifiées avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification des informations , veuillez réessayer!"
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

                EmployeInformationModels::where('slug', $slug)->delete();


                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok!,Suppression effectuée"
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
     * Enable is responsible the specified slug
     */
    public function enable_or_disable_is_responsible(string $employee_matricule)
    {
        try {
            if (!$employee_matricule) :
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :
                $is_responsible = EmployeInformationModels::where('matricule', $employee_matricule)->value('is_responsible');
                if($is_responsible == 1):
                    EmployeInformationModels::where('matricule', $employee_matricule)->update(['is_responsible' => 0]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Cool ! La responsabilité de l'employé a été révoquée à ce service avec succès."
                        ]
                    );
                else:
                    EmployeInformationModels::where('matricule', $employee_matricule)->update(['is_responsible' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Cool ! L'employé a été désigné comme responsable de son service avec succès."
                        ]
                    );
                endif;
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
     * Enable is agent the specified slug
     */
    public function enable_or_disable_is_agents(string $employee_matricule)
    {
        try {
            if (!$employee_matricule) :
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :
                $is_agents = EmployeInformationModels::where('matricule', $employee_matricule)->value('is_agents');
                if($is_agents == 1):
                    EmployeInformationModels::where('matricule', $employee_matricule)->update(['is_agents' => 0]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Cool ! Le titre d'agent de l'employé a été révoqué à ce service avec succès."
                        ]
                    );
                else:
                    EmployeInformationModels::where('matricule', $employee_matricule)->update(['is_agents' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Cool ! L'employé a été désigné comme agent de son service avec succès."
                        ]
                    );
                endif;
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
     *
     */

    public function filter_on_employe_with_query(string $query)
    {
        try {
            if (!$query) :
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 404,
                        'message' => "Oupps ! Aucun élément trouvé"
                    ]
                );
            else :

                return DB::table('employe_contrat_models')
                ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                ->select(
                    'employe_contrat_models.*',
                    'service_models.service',
                    'fonction_models.fonction',
                    'contrats_models.contrats',
                    'bureau_models.bureau',
                    'categorie_pro_models.categorie_pro',
                    'employe_information_models.*'
                )
                ->where('employe_information_models.matricule','LIKE', '%'.$query.'%')
                ->orWhere('employe_information_models.first_name','LIKE', '%'.$query.'%')
                ->orWhere('employe_information_models.last_name','LIKE', '%'.$query.'%')
                ->orderByDesc('employe_information_models.id')
                ->limit(150)
                ->get();


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


    public function filter_on_employe_with_status(string $status)
    {
        try {
            if (!$status) :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :
                if($status == "RS"):
                    return DB::table('employe_contrat_models')
                    ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                    ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                    ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                    ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                    ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                    ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                    ->select(
                        'employe_contrat_models.*',
                        'service_models.service',
                        'fonction_models.fonction',
                        'contrats_models.contrats',
                        'bureau_models.bureau',
                        'categorie_pro_models.categorie_pro',
                        'employe_information_models.*'
                    )
                    ->where('employe_information_models.is_responsible',1)
                    ->orderByDesc('employe_information_models.id')
                    ->get();
                endif;

                if($status == "AS"):
                    return DB::table('employe_contrat_models')
                    ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                    ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                    ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                    ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                    ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                    ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                    ->select(
                        'employe_contrat_models.*',
                        'service_models.service',
                        'fonction_models.fonction',
                        'contrats_models.contrats',
                        'bureau_models.bureau',
                        'categorie_pro_models.categorie_pro',
                        'employe_information_models.*'
                    )
                    ->where('employe_information_models.is_agents',1)
                    ->orderByDesc('employe_information_models.id')
                    ->get();
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

    public function filter_on_employe_with_service(string $service)
    {
        try {
            if (!$service) :
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :

                if($service == "all"):

                    return DB::table('employe_contrat_models')
                    ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                    ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                    ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                    ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                    ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                    ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                    ->select(
                        'employe_contrat_models.*',
                        'service_models.service',
                        'fonction_models.fonction',
                        'contrats_models.contrats',
                        'bureau_models.bureau',
                        'categorie_pro_models.categorie_pro',
                        'employe_information_models.*'
                    )
                    ->orderByDesc('employe_information_models.id')
                    ->get();
                else :
                    return DB::table('employe_contrat_models')
                    ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                    ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                    ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
                    ->join('contrats_models', 'employe_contrat_models.contrats_id', '=', 'contrats_models.id')
                    ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
                    ->join('categorie_pro_models', 'employe_contrat_models.categorie_pro_id', '=', 'categorie_pro_models.id')
                    ->select(
                        'employe_contrat_models.*',
                        'service_models.service',
                        'fonction_models.fonction',
                        'contrats_models.contrats',
                        'bureau_models.bureau',
                        'categorie_pro_models.categorie_pro',
                        'employe_information_models.*'
                    )
                    ->where('employe_contrat_models.service_id',$service)
                    ->orderByDesc('employe_information_models.id')
                    ->get();

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






    public function update_employee_photo(Request $request, string $slug)
    {
        try {

            if(empty($request->photo)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La photo est obligatoire"
                    ]
                );
            endif;

            $this->employe_photo = UploadService::upload_employe_photo($request);

            if($this->employe_photo == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la photo a échoué."
                    ]
                );
            endif;

            $update_employe_photo =  EmployeInformationModels::where('slug', $slug)->first();

            if($this->employe_photo != "file_not_found"):
                $update_employe_photo->photo = $this->employe_photo;
            endif;

            if($update_employe_photo->save()):

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! La photo a été modifiée avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification de la photo , veuillez réessayer!"
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




    public function update_employee_matricule(Request $request, string $slug)
    {
        try {

            if(empty($request->matricule)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le matricule est obligatoire"
                    ]
                );
            endif;


            $update_employe_matricule =  EmployeInformationModels::where('slug', $slug)->first();

            $update_employe_matricule->matricule = $request->matricule;

            if($update_employe_matricule->save()):

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => "Ok ! Le matricule a été modifié avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du matricule , veuillez réessayer!"
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
}
