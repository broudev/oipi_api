<?php

namespace App\Http\Controllers\API\V1\CourrierManagments;

use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CourrierManagments\CourrierModels;

class CourrierController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public $document_courriers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return DB::table('courrier_models')->orderByDesc('id')->get();
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

    public function filter_on_courrier(string $query)
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
                return DB::table('courrier_models')
                ->where('courrier_models.courrier_ref','LIKE', '%'.$query.'%')
                ->orWhere('courrier_models.objet_courrier','LIKE', '%'.$query.'%')
                ->orWhere('courrier_models.provenance','LIKE', '%'.$query.'%')
                ->orderByDesc('courrier_models.id')
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

    public function get_courrier_imputed()
    {
        try {
            return DB::table('imputation_courriers_responsable_service_models')
            ->join('courrier_models', 'imputation_courriers_responsable_service_models.courrier_ref', '=', 'courrier_models.courrier_ref')
            ->select('courrier_models.*', 'imputation_courriers_responsable_service_models.*')
            ->orderByDesc('courrier_models.id')
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


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if (empty($request->objet_courrier)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'objet du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->provenance)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La provenance du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->date_started)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La date d'arrivée est obligatoire"
                    ]
                );
            endif;


            $this->document_courriers = UploadService::upload_document_courriers($request);

            if($this->document_courriers == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la pièce jointe a échoué."
                    ]
                );
            endif;

            $add_courrier = new CourrierModels();
            $add_courrier->courrier_ref = CodeGenerator::generateCourrierCode();
            $add_courrier->objet_courrier = $request->objet_courrier;
            $add_courrier->provenance = $request->provenance;
            $add_courrier->date_started = $request->date_started;

            if($this->document_courriers != "file_not_found"):
                $add_courrier->document_courrier_url = $this->document_courriers;
            endif;

            $add_courrier->slug = CodeGenerator::generateSlugCode();

            if($add_courrier->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Le courrier a été enregistré avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du courrier, veuillez réessayer!"
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



            if (empty($request->objet_courrier)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'objet du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->provenance)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La provenance du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->date_started)) :
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La date d'arrivée est obligatoire"
                    ]
                );
            endif;

            $this->document_courriers = UploadService::upload_document_courriers($request);

            if($this->document_courriers == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la pièce jointe a échoué."
                    ]
                );
            endif;

            $update = CourrierModels::where('slug',$slug)->first();

            if($this->document_courriers != "file_not_found"):
                $update->document_courrier_url = $this->document_courriers;
            endif;


            $update->provenance = $request->provenance;
            $update->date_started = $request->date_started;
            $update->objet_courrier = $request->objet_courrier;

            if($update->save()) :
                return response()->json(
                    [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Ok ! Le courrier a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification , veuillez réessayer!"
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

                DB::table('courrier_models')->where('slug', $slug)->delete();
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
