<?php

namespace App\Http\Controllers\API\V1\UserAccounts;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\SendAccountMailer;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\UserAccounts\EmployeeAccountsModels;

class EmployeeAccountsController extends Controller
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
            return  EmployeeAccountsModels::join('users', 'employee_accounts_models.user_id', '=', 'users.id')
            ->join('role_models', 'employee_accounts_models.role_id', '=', 'role_models.id')
            ->join('employe_information_models', 'employee_accounts_models.employe_matricule', '=', 'employe_information_models.matricule')
            ->select(
                'users.email',
                'users.connected',
                'role_models.role',
                'employe_information_models.first_name',
                'employe_information_models.last_name',
                'employe_information_models.photo',
                'employee_accounts_models.*'
            )
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

    public function store(Request $request)
    {
        try {

            if(empty($request->employe_matricule)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Le matricule de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->adress_email)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! L'adresse email de l'employé est obligatoire"
                    ]
                );
            endif;

            if(empty($request->role)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Le rôle est obligatoire"
                    ]
                );
            endif;

            if(empty($request->type_accounts)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Le type du compte est obligatoire"
                    ]
                );
            endif;


            $checkUser = DB::table('users')->where('email',$request->adress_email)->first();

            if($checkUser != null):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur! L'adresse email est déjà utilisée pour un autre compte"
                    ]
                );
            endif;


            $my_password = CodeGenerator::generatePassword();

            $store_user = new User();
            $store_user->email = $request->adress_email;
            $store_user->password = password_hash($my_password, PASSWORD_BCRYPT);

            if($store_user->save()):

                $store_employee_compte = new EmployeeAccountsModels();

                $store_employee_compte->employe_matricule = $request->employe_matricule;
                $store_employee_compte->type_accounts = $request->type_accounts;
                $store_employee_compte->user_id = $store_user->id;
                $store_employee_compte->role_id = $request->role;
                $store_employee_compte->slug = CodeGenerator::generateSlugCode();

                if($store_employee_compte->save()):

                    DB::table('employe_information_models')->where('matricule', $request->employe_matricule)->update([
                        'account_created' => 1,
                    ]);



                    $employee_data = DB::table('employe_information_models')
                        ->where('matricule', $request->employe_matricule)
                        ->select('first_name', 'last_name')
                        ->first();


                    Mail::to($request->adress_email)->send(new SendAccountMailer($my_password,$employee_data, $request->adress_email));

                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => "Ok ! Le compte  a été créé avec succès. L'utilisateur va recevoir ses accès par mail."
                        ]
                    );
                else:

                    User::where('id', $store_user->id)->delete();

                    return response()->json(
                        [
                            'status' => 'error',
                            'code' => 300,
                            'message' => "Erreur ! Échec de la création du compte, veuillez réessayer!"
                        ]
                    );
                endif;

            else:
                User::where('id', $store_user->id)->delete();
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la création du compte , veuillez réessayer!"
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
                return  EmployeeAccountsModels::join('users', 'employee_accounts_models.user_id', '=', 'users.id')
                ->join('role_models', 'employee_accounts_models.role_id', '=', 'role_models.id')
                ->select('users.email', 'users.connected', 'role_models.role', 'employee_accounts_models.*')
                ->get();

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
        try {

            if(empty($request->role)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Le rôle est obligatoire"
                    ]
                );
            endif;

            if(empty($request->type_accounts)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Erreur ! Le type du compte est obligatoire"
                    ]
                );
            endif;

            $current_account =  EmployeeAccountsModels::where('slug', $slug)->first();
            if($current_account == null):
                return response()->json(
                    [
                        'code' => "404",
                        'message' => "L'utilisateur est introuvalbe."
                    ]
                );
            endif;

            //return $current_account;
            $update_user =  User::where('id', $current_account->user_id)->first();

            $update_user->email = $request->email;

            if($update_user->save()) :

                $current_account->role_id = $request->role;
                $current_account->type_accounts = $request->type_accounts;

                if($current_account->save()):
                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => "Ok!, Le compte de a été modifié avec succès."
                        ]
                    );
                endif;
            else:
                return response()->json(
                    [
                        'status' => 'error',
                        'code' => 300,
                        'message' => "Erreur ! Échec de la modification du compte, veuillez réessayer!"
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
                        'message' => "Oupps !, Aucun élément trouvé"
                    ]
                );
            else :
                $current_account =  EmployeeAccountsModels::where('slug', $slug)->first();

                User::where('id', $current_account->user_id)->delete();
                EmployeeAccountsModels::where('slug', $slug)->delete();


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


    public function enable_or_disable_account(string $slug)
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
                $status = EmployeeAccountsModels::where('slug', $slug)->value('status');
                if($status == 1):
                    EmployeeAccountsModels::where('slug', $slug)->update(['status' => 0]);
                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => "Ok ! Compte employé désactivé avec succès"
                        ]
                    );
                else:
                    EmployeeAccountsModels::where('slug', $slug)->update(['status' => 1]);
                    return response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'message' => "Ok ! Compte employé activé avec succès"
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


    public function change_employee_type_account(string $type_account,string $slug)
    {
        try {
            if (!$slug) :
                return response()->json(
                    [
                        'status' => 'erreur',
                        'code' => 404,
                        'message' => "Oupps!, Aucun élément trouvé"
                    ]
                );
            else :
                $is_employee = EmployeeAccountsModels::where('slug', $slug)->first();
                if($is_employee != null):
                    EmployeeAccountsModels::where('slug', $slug)->update(['type_accounts' => $type_account]);
                    return response()->json(
                        [
                            'status' => 'succès',
                            'code' => 200,
                            'message' => "Ok ! Le type de compte a été changé avec succès"
                        ]
                    );
                else:
                    return response()->json(
                        [
                            'status' => 'erreur',
                            'code' => 302,
                            'message' => "Oups ! Le changement du type de compte a échoué."
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
