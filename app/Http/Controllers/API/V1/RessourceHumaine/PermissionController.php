<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Mail\DemandeMailer;
use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\RessourceHumaine\PermissionsModels;

class PermissionController extends Controller
{
    public $permission_file;


    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }



    public function get_all_permissions() {

        try {
            return DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('permissions_models', 'employe_contrat_models.employe_matricule', '=', 'permissions_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'permissions_models.*'
            )
            ->orderBy('permissions_models.new_actions', 'asc')
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

    public function index($employe_matricule) {

        try {
            $permission =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('permissions_models', 'employe_contrat_models.employe_matricule', '=', 'permissions_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'permissions_models.*'
            )->orderBy('permissions_models.new_actions', 'asc')
            ->get();

            $customer_permission =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('permissions_models', 'employe_contrat_models.employe_matricule', '=', 'permissions_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'permissions_models.*'
            )->orderBy('permissions_models.new_actions', 'asc')
            ->where('permissions_models.employe_matricule', $employe_matricule)
            ->get();

            return [
                'permission' => $permission,
                'customer_permission' => $customer_permission
            ];
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

    public function customers_permissions($employe_matricule) {

        try {

            return  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('permissions_models', 'employe_contrat_models.employe_matricule', '=', 'permissions_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'permissions_models.*'
            )

            ->orderByDesc('permissions_models.created_at')
            ->where('permissions_models.employe_matricule', $employe_matricule)
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
            if (empty($request->motif)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le motif de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->permission_start)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date ou l'heure de début de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->permission_end)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date ou l'heure de fin de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if($request->type_duration == "hour"):
                if (empty($request->date_permission)) :
                    return response()->json(
                        [
                            'code' => 302,
                            'message' => "La date de la permission est obligatoire 🤐"
                        ]
                    );
                endif;
            endif;

            if (empty($request->duree_permission)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La durée de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->destinataire)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La destination de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            $this->permission_file = UploadService::upload_permission_file($request);

            if($this->permission_file == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de l'image du produit a échoué."
                    ]
                );
            endif;

            $add_permission = new PermissionsModels();

            $add_permission->permission_ref = CodeGenerator::generatePermissionCode();

            $add_permission->motif = $request->motif;
            $add_permission->permission_start = $request->permission_start;
            $add_permission->permission_end = $request->permission_end;
            $add_permission->type_duration = $request->type_duration;
            $add_permission->employe_matricule = $request->employe_matricule;
            $add_permission->destinataire = $request->destinataire;
            $add_permission->duree_permission = $request->duree_permission;

            $add_permission->date_permission = $request->date_permission;

            if($this->permission_file != "file_not_found"):
                $add_permission->permission_file = $this->permission_file;
            endif;

            $add_permission->slug = CodeGenerator::generateSlugCode();

            if($add_permission->save()):

                $employee_data = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $destinataire_data = DB::table('employe_information_models')->where('matricule', $request->destinataire)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $subject = "DEMANDE DE PERMISSION";

                $notifications = "Vous avez une notification de demande de permission de: "
                    . $employee_data->first_name
                    . " " .
                    $employee_data->last_name
                    . " <br > <br >"
                    ." <strong>Référence de la permission : </strong> " . " " .$add_permission->permission_ref
                    . " <br > <br >"
                    ." <strong>Debut de la permission : </strong> " . " " .$request->permission_start
                    . " <br > <br >"
                    . " <strong>Fin de la permission :</strong>" . " " .$request->permission_end
                    . " <br > <br >"
                    . "<strong>Motif de la permission : </strong>" . " " .$request->motif
                    . " <br > <br >"
                    . "<strong>Durée de la permission : </strong>" . " " .$request->duree_permission;



                Mail::to($destinataire_data->adresse_email)->send(new DemandeMailer($notifications,$destinataire_data, $subject));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! La permission a été enregistrée avec succès.'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Oups ! L'enregistrement de la permission a échoué."
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
     * Display the specified resource.
     */
    public function edit($slug) {

        try {
            return DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('permissions_models', 'employe_contrat_models.employe_matricule', '=', 'permissions_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'permissions_models.*'
            )
            ->where('permissions_models.slug', $slug)
            ->first();
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        //return $request->all();

        try {
            if (empty($request->motif)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le motif de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->permission_start)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date ou l'heure de début de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->permission_end)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date ou l'heure de fin de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if($request->type_duration == "hour"):
                if (empty($request->date_permission)) :
                    return response()->json(
                        [
                            'code' => 302,
                            'message' => "La date de la permission est obligatoire 🤐"
                        ]
                    );
                endif;
            endif;



            if (empty($request->duree_permission)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La durée de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->destinataire)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La destination de la permission est obligatoire 🤐"
                    ]
                );
            endif;

            $this->permission_file = UploadService::upload_permission_file($request);

            if($this->permission_file == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de l'image du produit a échoué."
                    ]
                );
            endif;

            $update_permission = PermissionsModels::where('slug', $slug)->first();

            $update_permission->motif = $request->motif;
            $update_permission->permission_start = $request->permission_start;
            $update_permission->permission_end = $request->permission_end;
            $update_permission->destinataire = $request->destinataire;
            $update_permission->type_duration = $request->type_duration;
            $update_permission->employe_matricule = $request->employe_matricule;
            $update_permission->duree_permission = $request->duree_permission;

            $update_permission->date_permission = $request->date_permission;


            if($this->permission_file != "file_not_found"):
                $update_permission->permission_file = $this->permission_file;
            endif;

            if($update_permission->save()):

                $employee_data = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $destinataire_data = DB::table('employe_information_models')->where('matricule', $request->destinataire)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $subject = "MODIFICATION DE DEMANDE DE PERMISSION";

                $notifications = "Vous avez une notification de demande de permission de: "
                    . $employee_data->first_name
                    . " " .
                    $employee_data->last_name
                    . " <br > <br >"
                    ." <strong>Référence de la permission : </strong> " . " " .$update_permission->permission_ref
                    . " <br > <br >"
                    ." <strong>Debut de la permission : </strong> " . " " .$request->permission_start
                    . " <br > <br >"
                    . " <strong>Fin de la permission :</strong>" . " " .$request->permission_end
                    . " <br > <br >"
                    . "<strong>Motif de la permission : </strong>" . " " .$request->motif
                    . " <br > <br >"
                    . "<strong>Durée de la permission : </strong>" . " " .$request->duree_permission;


                    

                Mail::to($destinataire_data->adresse_email)->send(new DemandeMailer($notifications,$destinataire_data, $subject));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! La permission a été modifiée avec succès.'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Oups ! La modification de la permission a échoué."
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
                        'message' => "Erreur!, Aucun element trouvé"
                    ]
                );
            else :

                DB::table('permissions_models')->where('slug', $slug)->delete();
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



    public function approved_or_rejected_permission(Request $request) {

        //return $request->all();
        try {
            if (empty($request->decison)) :
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 302,
                        'message' => "Erreur!, La décision est obligatoire"
                    ]
                );
            else :

                $check_approuved_data = DB::table('permissions_models')->where('slug', $request->slug)
                ->select('status_on','status_off', 'employe_matricule')
                ->first();

                if($request->decison == "approved"):
                    if($check_approuved_data->status_on == 0):
                        DB::table('permissions_models')->where('slug', $request->slug)->update([
                            'status_on' => 1,
                            'comments' => $request->decision_comments
                        ]);

                        //$user_email = DB::table('users')->where('id', $check_approuved_data->customer_id)->value('email');

                        //Mail::to($store_administration_compte->email)->send(new SendAccountMailer($request->password,$store_administration_compte, $request->email));
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Permission approuvée avec succès"
                            ]
                        );
                    else:
                        return response()->json(
                            [
                                'status' => 'error',
                                'code' => 404,
                                'message' => "Erreur!, Permission déjà approuvée"
                            ]
                        );
                    endif;
                elseif($request->decison == "rejected"):

                    if($check_approuved_data->status_off == 0):
                        DB::table('permissions_models')->where('slug', $request->slug)->update([
                            'status_off' => 1,
                            'comments' => $request->decision_comments
                        ]);


                        //$user_email = DB::table('users')->where('id', $check_approuved_data->customer_id)->value('email');

                        //Mail::to($store_administration_compte->email)->send(new SendAccountMailer($request->password,$store_administration_compte, $request->email));

                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Permission rejetée avec succès"
                            ]
                        );
                    else:
                        return response()->json(
                            [
                                'status' => 'error',
                                'code' => 404,
                                'message' => "Erreur!, Permission déjà rejetée"
                            ]
                        );
                    endif;
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

    public function consulted_permission($slug) {

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

                $check_approuved_data = DB::table('permissions_models')->where('slug', $slug)->value('new_actions');

                if($check_approuved_data == 0):

                    $is_success = DB::table('permissions_models')->where('slug', $slug)->update(['new_actions' => 1]);

                    if($is_success):
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Vous venez de consulter la permission"
                            ]
                        );
                    else:
                        return response()->json(
                            [
                                'status' => 'erreur',
                                'code' => 302,
                                'message' => "Oups ! L'activiation du statut de la consultation a échouée"
                            ]
                        );
                    endif;

                elseif($check_approuved_data == 1):
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 201,
                            'message' => "Ok!, Permission déjà consultée"
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
}
