<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\EmployeContratModels;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeContratsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if(empty($request->employe_code)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le code de l'employé est obligatoire"
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

            if(empty($request->fonction)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La fonction de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->service)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le service de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->categorie_pro)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La catégorie professionnelle de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->bureau)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le site de travail de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->contrats)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le contrat de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->date_embauche)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La date de prise de fonction de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->salaire_net_mensuel)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le salaire mensuel net de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->salaire_categoriel)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le salaire catégoriel de l'employé est obligatoire"
                    ]
                );
            endif;






            $store_employe_info = new EmployeContratModels();

            $store_employe_info->employe_code = $request->employe_code;
            $store_employe_info->profession = $request->profession;
            $store_employe_info->fonction = $request->fonction;
            $store_employe_info->service = $request->service;
            $store_employe_info->categorie_pro = $request->categorie_pro;
            $store_employe_info->bureau = $request->bureau;
            $store_employe_info->contrats = $request->contrats;
            $store_employe_info->date_embauche = date('Y-m-d', strtotime($request->date_embauche));


            $store_employe_info->salaire_categoriel = $request->salaire_categoriel;
            $store_employe_info->salaire_mensuel_net = $request->salaire_net_mensuel;


            $store_employe_info->slug = CodeGenerator::generateSlugCode();

            $store_employe_info->status_contrats = 1;

            if($store_employe_info->save()):

                //$notifiction = "Bonjour cher.". " " .$store_employe_info->first_name ." bienvenue à l'Office Ivoirien de la Propriété Intellectuelle (OIPI) " ;
                //Mail::to($store_employe_info->email)->send(new Notifications($notifiction));

                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'status_contrats' => $store_employe_info->status_contrats,
                        'message' => "Ok ! Les informations professionnelles de l'employé ont été enregistrées avec succès. "
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement des informations professionnelles de l'employé, veuillez réessayer!"
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $employe_matricule)
    {
        try {

            if(empty($request->employe_matricule)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le matricule de l'employé est obligatoire"
                    ]
                );
            endif;


            if(empty($request->fonction)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La fonction de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->service)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le service de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->categorie_pro)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La catégorie professionnelle de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->bureau)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le site de travail de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->contrats)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le contrat de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->date_embauche)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! La date de prise de fonction de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->salaire_net_mensuel)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le salaire mensuel net de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->salaire_categoriel)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! Le salaire catégoriel de l'employé est obligatoire"
                    ]
                );
            endif;



            $update_employe_contrats =  EmployeContratModels::where('employe_matricule', $employe_matricule)->first();

            if($update_employe_contrats == null):
                $update_employe_contrats = new EmployeContratModels();

                $update_employe_contrats->employe_matricule = $request->employe_matricule;
                $update_employe_contrats->fonction_id = $request->fonction;
                $update_employe_contrats->service_id = $request->service;
                $update_employe_contrats->categorie_pro_id = $request->categorie_pro;
                $update_employe_contrats->bureau_id = $request->bureau;
                $update_employe_contrats->contrats_id = $request->contrats;
                $update_employe_contrats->date_embauche = date('Y-m-d', strtotime($request->date_embauche));


                $update_employe_contrats->salaire_categoriel = $request->salaire_categoriel;
                $update_employe_contrats->salaire_mensuel_net = $request->salaire_net_mensuel;

                $update_employe_contrats->status_contrats = 1;

                if($update_employe_contrats->save()){

                    $check_status_info_value = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)->value('status_info');

                    if($check_status_info_value == 0){

                        $is_update = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)->update([
                            'status_info' => 1,
                        ]);
                        if($is_update == 1){
                            return response()->json(
                                [
                                    'status' => 'succès',
                                    'code' => 200,
                                    'message' => "Ok ! Les informations professionnelles de l'employé ont été modifiées avec succès. "
                                ]
                            );
                        }
                    }else {
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok ! Les informations professionnelles de l'employé ont été modifiées avec succès. "
                            ]
                        );
                    }

                }else{
                    return response()->json(
                        [
                            'status' => 'erreur',
                            'code' => 300,
                            'message' => "Erreur ! Échec de la modification des informations professionnelles de l'employé, veuillez réessayer!"
                        ]
                    );
                }

            else:

                $update_employe_contrats->employe_matricule = $request->employe_matricule;
                $update_employe_contrats->fonction_id = $request->fonction;
                $update_employe_contrats->service_id = $request->service;
                $update_employe_contrats->categorie_pro_id = $request->categorie_pro;
                $update_employe_contrats->bureau_id = $request->bureau;
                $update_employe_contrats->contrats_id = $request->contrats;
                $update_employe_contrats->date_embauche = date('Y-m-d', strtotime($request->date_embauche));


                $update_employe_contrats->salaire_categoriel = $request->salaire_categoriel;
                $update_employe_contrats->salaire_mensuel_net = $request->salaire_net_mensuel;

                $update_employe_contrats->status_contrats = 1;

                if($update_employe_contrats->save()){

                    $check_status_info_value = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)->value('status_info');

                    if($check_status_info_value == 0){

                        $is_update = DB::table('employe_information_models')->where('matricule', $request->employe_matricule)->update([
                            'status_info' => 1,
                        ]);
                        if($is_update == 1){
                            return response()->json(
                                [
                                    'status' => 'succès',
                                    'code' => 200,
                                    'message' => "Ok ! Les informations professionnelles de l'employé ont été modifiées avec succès. "
                                ]
                            );
                        }
                    }else {
                        return response()->json(
                            [
                                'status' => 'succès',
                                'code' => 200,
                                'message' => "Ok ! Les informations professionnelles de l'employé ont été modifiées avec succès. "
                            ]
                        );
                    }

                }else{
                    return response()->json(
                        [
                            'status' => 'erreur',
                            'code' => 300,
                            'message' => "Erreur ! Échec de la modification des informations professionnelles de l'employé, veuillez réessayer!"
                        ]
                    );
                }
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


}
