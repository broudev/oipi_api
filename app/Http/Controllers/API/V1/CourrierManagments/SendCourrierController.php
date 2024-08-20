<?php

namespace App\Http\Controllers\API\V1\CourrierManagments;

use App\Mail\DemandeMailer;
use Illuminate\Http\Request;
use App\Services\CodeGenerator;
use App\Services\UploadService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\CourrierManagments\SendCourrierModels;

class SendCourrierController extends Controller
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
            return DB::table('send_courrier_models')->orderByDesc('id')->get();
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



    public function filter_on_send_courrier(string $query)
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

                return DB::table('send_courrier_models')
                ->where('send_courrier_models.courrier_send_ref','LIKE', '%'.$query.'%')
                ->orWhere('send_courrier_models.adress_email_destinataire','LIKE', '%'.$query.'%')
                ->orWhere('send_courrier_models.destinataire','LIKE', '%'.$query.'%')
                ->orderByDesc('send_courrier_models.id')
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


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request->all();
        try {
            if (empty($request->objet_courrier)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'objet du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->telephone_destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le téléphone du destinataire est obligatoire"
                    ]
                );
            endif;
            if (empty($request->adress_email_destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'Adresse email du destinataire est obligatoire"
                    ]
                );
            endif;

            if (empty($request->destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le destinataire est obligatoire"
                    ]
                );
            endif;
            if (empty($request->decision_data)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La decision du retour du courrier est obligatoire"
                    ]
                );
            endif;

            $this->document_courriers = UploadService::upload_document_courriers_send($request);

            if ($this->document_courriers == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la pièce jointe a échoué."
                    ]
                );
            endif;

            $add_courrier = new SendCourrierModels();
            $add_courrier->courrier_send_ref = CodeGenerator::generateCourrierSendCode();
            $add_courrier->objet_courrier = $request->objet_courrier;
            $add_courrier->telephone_destinataire = $request->telephone_destinataire;
            $add_courrier->adress_email_destinataire = $request->adress_email_destinataire;
            $add_courrier->designation_destinataire = $request->designation_destinataire;
            $add_courrier->destinataire = $request->destinataire;
            $add_courrier->type_send = $request->type_send;
            $add_courrier->decision_data = $request->decision_data;

            if ($request->type_send == "send_out") {
                $add_courrier->retour_courrier = $request->retour_courrier;
                $add_courrier->date_reception = $request->date_reception;
            }

            if ($this->document_courriers != "file_not_found"):
                $add_courrier->document_courrier_url = $this->document_courriers;
            endif;

            $add_courrier->slug = CodeGenerator::generateSlugCode();

            if ($add_courrier->save()) {

                if ($request->type_send == "send_out") {
                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'Ok ! Le courrier a été enregistré avec succès'
                        ]
                    );
                }


                /**
                if($request->type_send == "send_in")
                {
                    $subject = "COURRIER OIPI";

                    $notifications = "Vous avez un nouveau courrier de l' OIPI avec une pièce jointe. "

                        . " <br > <br >"
                        ." <strong>Objet du courrier : </strong> " . " " .$request->objet_courrier
                        . " <br > <br >"
                        . " <strong>Nous vous prions d'accuser réception le mail en cliquant sur le bouton :</strong>" . " "
                        ."<a href='http://' class='btn btn-primary'>j'ai reçu le courrier</a>"
                        . " <br > <br >"
                        . "<strong>Motif de la permission : </strong>" . " " .$request->motif
                        . " <br > <br >"
                        . "<strong>Durée de la permission : </strong>" . " " .$request->duree_permission;



                    Mail::to($request->adress_email_destinataire)->send(new DemandeMailer($notifications,$add_courrier, $subject));
                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'Ok ! Le courrier a été enregistré avec succès'
                        ]
                    );
                }*/


            } else {

                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de l'enregistrement du courrier, veuillez réessayer!"
                    ]
                );
            }
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



            if (empty($request->objet_courrier)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'objet du courrier est obligatoire"
                    ]
                );
            endif;

            if (empty($request->telephone_destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le téléphone du destinataire est obligatoire"
                    ]
                );
            endif;
            if (empty($request->adress_email_destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'Adresse email du destinataire est obligatoire"
                    ]
                );
            endif;

            if (empty($request->destinataire)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "Le destinataire est obligatoire"
                    ]
                );
            endif;

            if (empty($request->decision_data)):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "La decision du retour du courrier est obligatoire"
                    ]
                );
            endif;

            $this->document_courriers = UploadService::upload_document_courriers_send($request);

            if ($this->document_courriers == "error"):
                return response()->json(
                    [
                        'code' => "302",
                        'message' => "L'enregistrement de la pièce jointe a échoué."
                    ]
                );
            endif;


            $update = SendCourrierModels::where('slug', $slug)->first();

            if ($this->document_courriers != "file_not_found"):
                $update->document_courrier_url = $this->document_courriers;
            endif;
            $update->objet_courrier = $request->objet_courrier;
            $update->telephone_destinataire = $request->telephone_destinataire;
            $update->adress_email_destinataire = $request->adress_email_destinataire;
            $update->designation_destinataire = $request->designation_destinataire;
            $update->destinataire = $request->destinataire;
            $update->type_send = $request->type_send;
            $update->decision_data = $request->decision_data;

            if ($request->type_send == "send_out") {
                $update->retour_courrier = $request->retour_courrier;
                $update->date_reception = $request->date_reception;
            }



            if ($update->save()):
                return response()->json(
                    [
                        'status' => 'succès',
                        'code' => 200,
                        'message' => 'Ok ! Le courrier a été modifié avec succès'
                    ]
                );
            else:
                return response()->json(
                    [
                        'status' => 'erreur',
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
            if (!$slug):
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 404,
                        'message' => "Erreur!, Aucun element trouvé"
                    ]
                );
            else:

                DB::table('send_courrier_models')->where('slug', $slug)->delete();
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
