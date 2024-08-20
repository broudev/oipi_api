<?php

namespace App\Http\Controllers\API\V1\Auth;

use Illuminate\Http\Request;
use App\Mail\ForgetPasswordMailer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthentificatorController extends Controller
{
    public $employee_contrat_info;

    public function admin_authentificator(Request $request)
    {
        try {


            if(empty($request->email)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'error',
                        'message' => "L'adresse email est obligatoire"
                    ]
                );
            endif;

            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'error',
                        'message' => "L'adresse email est invalide"
                    ]
                );
            endif;

            if(empty($request->password)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'error',
                        'message' => "Le mot de passe est obligatoire"
                    ]
                );
            endif;



            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'error',
                        'message' => "Oups! accès interdit !👺, Email ou mot de passe introuvable"
                    ]
                );
            }

            $users = $this->check_user($request->email);

            if (! $users || ! password_verify($request->password, $users->password))
            {
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Mot de passe incorrect 🤪"
                    ]
                );
            }

            $users_is_logged = $this->get_user_data($request->email);


            if($users_is_logged != null):
                DB::table('users')->where('id', $users->id)->update(['connected' => 1]);

                return response()->json(
                    [
                        'code' => 200,
                        'token' => $token,
                        'users' => $users_is_logged,
                        'status' => "succès",
                        'token_type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * (24* 60),
                        'message' => 'Vous êtes connecté 💚!'
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


    public function un_authorised()
    {
        return response()->json(
            [
                'code' => 401, // code for authorization error
                'status' => 'erreur',
                'message' => "Oups! accès interdit !👺. Le token n'est plus valable ou une connexion est nécessaire"
            ]
        );
    }


    public function logout($id)
    {
        //return $id;

        try {
            DB::table('users')->where('id', $id)->update(['connected' => 0]);
            Session::flush();
            Auth::logout();

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'success',
                    'message' => "Merci ! Vous vous êtes déconnecté"
                ]
            );
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




    private function check_user($email)
    {
        $is_admin_accounts = DB::table('administration_models')
        ->join('users', 'administration_models.user_id', '=', 'users.id')
        ->where('users.email', $email)
        //->where('status','')
        ->first();

        $is_employee_accounts = DB::table('employee_accounts_models')
        ->join('users', 'employee_accounts_models.user_id', '=', 'users.id')
        ->join('employe_information_models', 'employee_accounts_models.employe_matricule', '=', 'employe_information_models.matricule')
        ->where('users.email', $email)
        //->where('status','')
        ->first();


        if($is_admin_accounts != null){
            return  $is_admin_accounts;
        }

        if($is_employee_accounts != null){
            return $is_employee_accounts;
        }

        if($is_employee_accounts == null && $is_admin_accounts == null){
            return null;
        }
    }


    private function get_user_data($email)
    {



        $is_admin_accounts = DB::table('administration_models')
        ->join('users', 'administration_models.user_id', '=', 'users.id')
        ->join('role_models', 'administration_models.role_id', '=', 'role_models.id')
            ->select(
                'users.email',
                'users.connected',
                'role_models.role',
                'administration_models.first_name',
                'administration_models.last_name',
                'administration_models.photo',
                'administration_models.type_accounts',
                'administration_models.user_id',
                'administration_models.id'
            )
        ->where('users.email', $email)
        //->where('status','')
        ->first();

        $is_employee_accounts = DB::table('employee_accounts_models')
        ->join('users', 'employee_accounts_models.user_id', '=', 'users.id')
        ->join('role_models', 'employee_accounts_models.role_id', '=', 'role_models.id')
        ->join('employe_information_models', 'employee_accounts_models.employe_matricule', '=', 'employe_information_models.matricule')
        ->join('employe_contrat_models', 'employee_accounts_models.employe_matricule', '=', 'employe_contrat_models.employe_matricule')
        ->select('users.email','users.connected','role_models.role','employe_information_models.first_name',
            'employe_information_models.last_name',
            'employe_information_models.photo',
            'employe_information_models.matricule as employe_matricule',
            'employee_accounts_models.type_accounts',
            'employee_accounts_models.user_id',
            'employe_information_models.id',
            'employe_contrat_models.service_id'
        )
        ->where('users.email', $email)
        //->where('status','')
        ->first();




        if($is_admin_accounts != null){
            return [
                'accounts_info' => $is_admin_accounts
            ];
        }

        if($is_employee_accounts != null){
            return [
                'accounts_info' => $is_employee_accounts,
            ];
        }

        if($is_employee_accounts == null && $is_admin_accounts == null){
            return null;
        }
    }



    public function checkUserAccount(Request $user) {


        try {
            # code...
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'code' => 302,
                    'message' => $e->getMessage()
                ]
            );
        }

        if(empty($user->email)):
            return response()->json(
                [
                    'code' => 302,
                    'status' => 'erreur',
                    'message' => "L'adresse e-mail est obligatoire."
                ]
            );
        endif;

        if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)):
            return response()->json(
                [
                    'code' => 302,
                    'status' => 'erreur',
                    'message' => "L'adresse e-mail n'est pas valide"
                ]
            );
        endif;

        $is_admin_accounts = DB::table('administration_models')
        ->join('users', 'administration_models.user_id', '=', 'users.id')->where('users.email', $user->email)
        ->first();

        $is_employee_accounts = DB::table('employee_accounts_models')
        ->join('users', 'employee_accounts_models.user_id', '=', 'users.id')->where('users.email', $user->email)
        ->first();


        //$users = DB::table('users')->where('email', $user->email)->where('type', 'user')->first();

        if ($is_admin_accounts == null && $is_employee_accounts == null) {
            return response()->json(
                [
                    'code' => 302,
                    'status' => 'erreur',
                    'message' => "Oups ! Votre compte n'existe pas ou est introuvable"
                ]
            );
        }elseif($is_admin_accounts != null && $is_employee_accounts == null) {
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'succès',
                    'message' => "Ok ! Vous pouvez continuer."
                ]
            );
        }elseif($is_admin_accounts == null && $is_employee_accounts != null) {
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'succès',
                    'message' => "Ok ! Vous pouvez continuer."
                ]
            );
        }


    }




    public function updateUserPassword(Request $user) {
        try {
            if(empty($user->email)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "L'adresse e-mail est obligatoire."
                    ]
                );
            endif;

            if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "L'adresse e-mail n'est pas valide"
                    ]
                );
            endif;

            if(empty($user->password)):
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Le mot de passe est obligatoire."
                    ]
                );
            endif;

            $is_admin_accounts = DB::table('administration_models')
            ->join('users', 'administration_models.user_id', '=', 'users.id')->where('users.email', $user->email)
            ->first();

            $is_employee_accounts = DB::table('employee_accounts_models')
            ->join('users', 'employee_accounts_models.user_id', '=', 'users.id')->where('users.email', $user->email)
            ->first();


            if ($is_admin_accounts == null && $is_employee_accounts == null ) {
                return response()->json(
                    [
                        'code' => 302,
                        'status' => 'erreur',
                        'message' => "Oups ! Votre compte n'existe pas ou est introuvable"
                    ]
                );
            }elseif($is_admin_accounts != null && $is_employee_accounts == null) {

                $password = password_hash($user->password, PASSWORD_BCRYPT );

                $isUpdated = DB::table('users')->where('email', $user->email)->update([
                    'password' => $password
                ]);
                if($isUpdated){

                    $notifiction = "Votre mot de passe a été modifié avec succès." ." ".  "#Adresse email: "." ". $user->email ." "."#Nouveau mot de passe: "." ". $user->password;
                    Mail::to($user->email)
                    ->send(new ForgetPasswordMailer($notifiction));

                    return response()->json(
                        [
                            'code' => 200,
                            'status' => 'succès',
                            'message' => "Ok ! Votre mot de passe a été modifié avec succès. Un mail vous a été envoyé sur votre adresse."
                        ]
                    );
                }
            }elseif($is_admin_accounts == null && $is_employee_accounts != null) {
                $password = password_hash($user->password, PASSWORD_BCRYPT );

                $isUpdated = DB::table('users')->where('email', $user->email)->update([
                    'password' => $password
                ]);
                if($isUpdated){

                    $notifiction = "Votre mot de passe a été modifié avec succès." ." ".  "#Adresse email: "." ". $user->email ." "."#Nouveau mot de passe: "." ". $user->password;
                    Mail::to($user->email)
                    ->send(new ForgetPasswordMailer($notifiction));

                    return response()->json(
                        [
                            'code' => 200,
                            'status' => 'succès',
                            'message' => "Ok ! Votre mot de passe a été modifié avec succès. Un mail vous a été envoyé sur votre adresse."
                        ]
                    );
                }
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

    
    //db_user: c2315462c_oipi_user; db_name: c2315462c_oipi_db; db_password: OIPI@2024

}
