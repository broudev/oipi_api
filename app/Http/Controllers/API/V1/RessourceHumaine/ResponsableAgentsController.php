<?php

namespace App\Http\Controllers\API\V1\RessourceHumaine;

use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RessourceHumaine\EmployeContratModels;
use App\Models\RessourceHumaine\ResponsableAgentsModels;
use App\Models\RessourceHumaine\EmployeInformationModels;

class ResponsableAgentsController extends Controller
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
            $responsable =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('responsable_agents_models', 'employe_contrat_models.employe_matricule', '=', 'responsable_agents_models.employee_matricule')
            ->select(
                'service_models.service',
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'employe_information_models.photo',
                'employe_information_models.adresse_email',
                'employe_information_models.is_responsible',
                'responsable_agents_models.*'
            )
            ->where('responsable_agents_models.type_responsable_agent', 'responsable')
            ->get();

            $agents =  DB::table('employe_contrat_models')
            ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
            ->join('responsable_agents_models', 'employe_contrat_models.employe_matricule', '=', 'responsable_agents_models.employee_matricule')
            ->select(
                'service_models.service',
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'employe_information_models.photo',
                'employe_information_models.adresse_email',
                'employe_information_models.is_agents',
                'responsable_agents_models.*'
            )
            ->where('responsable_agents_models.type_responsable_agent', 'agent')
            ->get();


            return [
                'responsable_list' => $responsable,
                'agent_list' => $agents
            ];
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

    public function get_all_responsable()
    {
        try {

            return DB::table('employe_contrat_models')
                ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('responsable_agents_models', 'employe_contrat_models.employe_matricule', '=', 'responsable_agents_models.employee_matricule')
                ->select(
                    'service_models.service',
                    'employe_information_models.first_name',
                    'employe_information_models.last_name',
                    'employe_information_models.photo',
                    'employe_information_models.adresse_email',
                    'employe_information_models.is_responsible',
                    'responsable_agents_models.*'
                )
                ->where('responsable_agents_models.type_responsable_agent', 'responsable')
                ->get();

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

    

    public function get_agents_by_employee_service($employee_service)
    {
        try {
            if(!$employee_service ) {
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 404,
                        'message' => "Erreur!, Aucun element trouvé"
                    ]
                );
            }else{
                DB::table('employe_contrat_models')
                ->join('employe_information_models', 'employe_contrat_models.employe_matricule', '=', 'employe_information_models.matricule')
                ->join('service_models', 'employe_contrat_models.service_id', '=', 'service_models.id')
                ->join('responsable_agents_models', 'employe_contrat_models.employe_matricule', '=', 'responsable_agents_models.employee_matricule')
                ->select(
                    'service_models.service',
                    'employe_information_models.first_name',
                    'employe_information_models.last_name',
                    'employe_information_models.photo',
                    'employe_information_models.adresse_email',
                    'employe_information_models.is_responsible',
                    'responsable_agents_models.*'
                )
                ->where('responsable_agents_models.type_responsable_agent', 'agent')
                ->where('responsable_agents_models.employee_service_id', $employee_service)
                ->first();
            }
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
            if (empty($request->type_responsable_agent)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La responsable est obligatoire"
                    ]
                );
            endif;

            if (empty($request->employee_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'employé est obligatoire"
                    ]
                );
            endif;
            $is_founded =  EmployeContratModels::where('employe_matricule', $request->employee_matricule)->first();


            $add_responsable_agents = new ResponsableAgentsModels();
            $add_responsable_agents->employee_matricule = $request->employee_matricule;
            $add_responsable_agents->employee_service_id = $is_founded->service_id;
            $add_responsable_agents->type_responsable_agent = $request->type_responsable_agent;
            $add_responsable_agents->slug = CodeGenerator::generateSlugCode();

            if($add_responsable_agents->save()) :
                if($request->type_responsable_agent == "responsable"):

                    EmployeInformationModels::where('matricule', $request->employee_matricule)->update(['is_responsible' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => 'Ok!, Le responsable ou agent a été enregistré avec succès'
                        ]
                    );
                endif;

                if($request->type_responsable_agent == "agent"):
                    EmployeInformationModels::where('matricule', $request->employee_matricule)->update(['is_agents' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => 'Ok!, Le responsable ou agent a été enregistré avec succès'
                        ]
                    );
                endif;

            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du responsable ou agent, veuillez réessayer!"
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

            if (empty($request->type_responsable_agent)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La responsable est obligatoire"
                    ]
                );
            endif;

            if (empty($request->employee_matricule)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'employé est obligatoire"
                    ]
                );
            endif;

            $is_founded =  EmployeContratModels::where('employe_matricule', $request->employee_matricule)->first();
            $update_responsable_agents = ResponsableAgentsModels::where('slug',$slug)->first();
            $update_responsable_agents->employee_matricule = $request->employee_matricule;
            $update_responsable_agents->employee_service_id = $is_founded->service_id;
            $update_responsable_agents->type_responsable_agent = $request->type_responsable_agent;

            if($update_responsable_agents->save()) :

                if($request->type_responsable_agent == "responsable"):

                    EmployeInformationModels::where('matricule', $request->employee_matricule)->update(['is_responsible' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Ok !, Le responsable du service a été modifié avec succès"
                        ]
                    );
                endif;

                if($request->type_responsable_agent == "agent"):
                    EmployeInformationModels::where('matricule', $request->employee_matricule)->update(['is_agents' => 1]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Ok !, L'agent a été modifié avec succès"
                        ]
                    );
                endif;
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du responsable ou de l'agent, veuillez réessayer!"
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

                DB::table('type_responsable_models')->where('slug', $slug)->delete();
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
