<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Mail\DemandeMailer;
use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\RessourceHumaine\CongesModels;

class CongesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($employe_matricule) {

        try {
            $conge =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('conges_models', 'employe_contrat_models.employe_matricule', '=', 'conges_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'conges_models.*'
            )->orderBy('conges_models.new_actions', 'asc')
            ->get();

            $customer_conge =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('conges_models', 'employe_contrat_models.employe_matricule', '=', 'conges_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'conges_models.*'
            )->orderBy('conges_models.new_actions', 'asc')
            ->where('conges_models.employe_matricule', $employe_matricule)
            ->get();

            return [
                'conge' => $conge,
                'customer_conge' => $customer_conge
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


    public function get_all_conge() {

        try {
            return DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('conges_models', 'employe_contrat_models.employe_matricule', '=', 'conges_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'conges_models.*'
            )
            ->orderBy('conges_models.new_actions', 'asc')
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

    public function customers_conges($employe_matricule) {

        try {

            return  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('conges_models', 'employe_contrat_models.employe_matricule', '=', 'conges_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'conges_models.*'
            )
            ->orderByDesc('conges_models.created_at')
            ->where('conges_models.employe_matricule', $employe_matricule)
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
                        'message' => "Le motif du congé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->date_depart)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date de depart en congé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->date_retour)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date de retour de congé est obligatoire 🤐"
                    ]
                );
            endif;


            if (empty($request->duree_conge)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La durée dcongé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->destinataire)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La destination du dcongé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->objet_demande)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "L'objet du dcongé est obligatoire 🤐"
                    ]
                );
            endif;

            $add_conge = new CongesModels();

            $add_conge->conge_ref           = CodeGenerator::generateCongeCode();
            $add_conge->objet_demande       = $request->objet_demande;
            $add_conge->motif               = $request->motif;
            $add_conge->date_depart         = $request->date_depart;
            $add_conge->date_retour         = $request->date_retour;
            $add_conge->type_conge          = $request->type_conge;
            $add_conge->employe_matricule   = $request->employe_matricule;
            $add_conge->destinataire        = $request->destinataire;
            $add_conge->duree_conge         = $request->duree_conge;

            $add_conge->slug                = CodeGenerator::generateSlugCode();

            if($add_conge->save()):


                $employee_data = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $destinataire_data = DB::table('employe_information_models')->where('matricule', $request->destinataire)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();
                $subject = "DEMANDE DE CONGE";
                $notifications = "Vous avez une notification de demande de congé de: "
                    . $employee_data->first_name
                    . " " .
                    $employee_data->last_name
                    . " <br > <br >"
                    ." <strong>Référence du congé : </strong> " . " " .$add_conge->conge_ref

                    ." <strong>Objet de la demande : </strong> " . " " .$request->objet_demande
                    . " <br > <br >"
                    ." <strong>Debut du congé : </strong> " . " " .$request->date_depart
                    . " <br > <br >"
                    . " <strong>Retour de congé :</strong>" . " " .$request->date_retour
                    . " <br > <br >"
                    . "<strong>Motif du congé : </strong>" . " " .$request->motif
                    . " <br > <br >"
                    . "<strong>Durée du congé : </strong>" . " " .$request->duree_conge;



                Mail::to($destinataire_data->adresse_email)->send(new DemandeMailer($notifications,$destinataire_data, $subject));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Le congé a été enregistré avec succès.'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Oups ! L'enregistrement du congé a échoué."
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
            ->join('conges_models', 'employe_contrat_models.employe_matricule', '=', 'conges_models.employe_matricule')
            ->join('fonction_models', 'employe_contrat_models.fonction_id', '=', 'fonction_models.id')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('bureau_models', 'employe_contrat_models.bureau_id', '=', 'bureau_models.id')
            ->select(
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'fonction_models.fonction',
                'service_models.service',
                'bureau_models.bureau',
                'conges_models.*'
            )
            ->where('conges_models.slug', $slug)
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
                        'message' => "Le motif du congé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->date_depart)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date de depart en congé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->date_retour)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La date de retour de congé est obligatoire 🤐"
                    ]
                );
            endif;


            if (empty($request->duree_conge)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "La durée dcongé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->destinataire)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "Le destinataire du congé est obligatoire 🤐"
                    ]
                );
            endif;

            if (empty($request->objet_demande)) :
                return response()->json(
                    [
                        'code' => 302,
                        'message' => "L'objet du dcongé est obligatoire 🤐"
                    ]
                );
            endif;



            $update_permission = CongesModels::where('slug', $slug)->first();

            $update_permission->objet_demande = $request->objet_demande;
            $update_permission->motif = $request->motif;
            $update_permission->destinataire = $request->destinataire;
            $update_permission->date_depart = $request->date_depart;
            $update_permission->date_retour = $request->date_retour;
            $update_permission->type_conge = $request->type_conge;
            $update_permission->employe_matricule = $request->employe_matricule;
            $update_permission->duree_conge = $request->duree_conge;


            if($update_permission->save()):

                $employee_data = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $destinataire_data = DB::table('employe_information_models')->where('matricule', $request->destinataire)
                    ->select('first_name', 'last_name', 'adresse_email')
                    ->first();

                $subject = "MODIFICATION DE DEMANDE DE CONGE";

                $notifications = "Vous avez une notification de demande de congé de: "
                    . $employee_data->first_name
                    . " " .
                    $employee_data->last_name
                    . " <br > <br >"
                    ." <strong>Référence du congé : </strong> " . " " .$update_permission->conge_ref
                    . " <br > <br >"
                    ." <strong>Objet de la demande : </strong> " . " " .$request->objet_demande
                    . " <br > <br >"
                    ." <strong>Debut du congé : </strong> " . " " .$request->date_depart
                    . " <br > <br >"
                    . " <strong>Retour de congé :</strong>" . " " .$request->date_retour
                    . " <br > <br >"
                    . "<strong>Motif du congé : </strong>" . " " .$request->motif
                    . " <br > <br >"
                    . "<strong>Durée du congé : </strong>" . " " .$request->duree_conge;


                Mail::to($destinataire_data->adresse_email)->send(new DemandeMailer($notifications,$destinataire_data, $subject));

                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Le congé a été modifié avec succès.'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Oups ! La modification du congé a échoué."
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

                DB::table('conges_models')->where('slug', $slug)->delete();
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



    public function approved_or_rejected_conge(Request $request) 
    {

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

                $check_approuved_data = DB::table('conges_models')->where('slug', $request->slug)
                ->select('status_on','status_off', 'employe_matricule')
                ->first();

                if($request->decison == "approved"):
                    if($check_approuved_data->status_on == 0):
                        DB::table('conges_models')->where('slug', $request->slug)->update([
                            'status_on' => 1,
                            'comments' => $request->decision_comments
                        ]);

                        //$user_email = DB::table('users')->where('id', $check_approuved_data->customer_id)->value('email');

                        //Mail::to($store_administration_compte->email)->send(new SendAccountMailer($request->password,$store_administration_compte, $request->email));
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Congé approuvé avec succès"
                            ]
                        );
                    else:
                        return response()->json(
                            [
                                'status' => 'error',
                                'code' => 404,
                                'message' => "Erreur!, Congé déjà approuvé"
                            ]
                        );
                    endif;
                elseif($request->decison == "rejected"):

                    if($check_approuved_data->status_off == 0):
                        DB::table('conges_models')->where('slug', $request->slug)->update([
                            'status_off' => 1,
                            'comments' => $request->decision_comments
                        ]);


                        //$user_email = DB::table('users')->where('id', $check_approuved_data->customer_id)->value('email');

                        //Mail::to($store_administration_compte->email)->send(new SendAccountMailer($request->password,$store_administration_compte, $request->email));

                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Congé rejeté avec succès"
                            ]
                        );
                    else:
                        return response()->json(
                            [
                                'status' => 'error',
                                'code' => 404,
                                'message' => "Erreur!, Congé déjà rejeté"
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

    public function consulted_conge($slug) {

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

                $check_approuved_data = DB::table('conges_models')->where('slug', $slug)->value('new_actions');

                if($check_approuved_data == 0):
                    $is_success = DB::table('conges_models')->where('slug', $slug)->update([
                        'new_actions' => 1
                    ]);

                    if($is_success):
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok!, Vous venez de consulter le congé"
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
                            'message' => "Ok!, Congé déjà consulté"
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
