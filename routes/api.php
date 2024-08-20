<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Administration\RoleController;
use App\Http\Controllers\API\V1\Auth\AuthentificatorController;
use App\Http\Controllers\API\V1\RessourceHumaine\BureauController;
use App\Http\Controllers\API\V1\RessourceHumaine\CongesController;
use App\Http\Controllers\API\V1\Statistique\StatistiqueController;
use App\Http\Controllers\API\V1\RessourceHumaine\ServiceController;
use App\Http\Controllers\API\V1\RessourceHumaine\ContratsController;
use App\Http\Controllers\API\V1\RessourceHumaine\EmployesController;
use App\Http\Controllers\API\V1\RessourceHumaine\FonctionController;
use App\Http\Controllers\API\V1\RessourceHumaine\PointageController;
use App\Http\Controllers\API\V1\Administration\TypeAccountsController;
use App\Http\Controllers\API\V1\CourrierManagments\CourrierController;
use App\Http\Controllers\API\V1\RessourceHumaine\PermissionController;
use App\Http\Controllers\API\V1\RessourceHumaine\TypeCongesController;
use App\Http\Controllers\API\V1\UserAccounts\AdministrationController;
use App\Http\Controllers\API\V1\RessourceHumaine\DossierFileController;
use App\Http\Controllers\API\V1\RessourceHumaine\CategorieProController;
use App\Http\Controllers\API\V1\UserAccounts\EmployeeAccountsController;
use App\Http\Controllers\API\V1\Frontend\DemandeCreationCompteController;
use App\Http\Controllers\API\V1\CourrierManagments\SendCourrierController;
use App\Http\Controllers\API\V1\RessourceHumaine\PointageConfigController;
use App\Http\Controllers\API\V1\RessourceHumaine\DossierEmployesController;
use App\Http\Controllers\API\V1\RessourceHumaine\EmployeContratsController;
use App\Http\Controllers\API\V1\RessourceHumaine\CodeAuthorizationController;
use App\Http\Controllers\API\V1\RessourceHumaine\ResponsableAgentsController;
use App\Http\Controllers\API\V1\CourrierManagments\ImputationCourriersAgentController;
use App\Http\Controllers\API\V1\CourrierManagments\ImputationCourriersResponsableServiceController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/welcome', [DemandeCreationCompteController::class, 'index']);


Route::post('/login_admin', [AuthentificatorController::class, 'admin_authentificator']);
Route::post('/store_employees', [DemandeCreationCompteController::class, 'store']);
Route::get('/un_authorised', [AuthentificatorController::class, 'un_authorised'])->name('un_authorised');

Route::post('/check_user_account', [AuthentificatorController::class, 'checkUserAccount']);
Route::post('/update_user_password', [AuthentificatorController::class, 'updateUserPassword']);




// ==================================== LOGOUT 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\Auth',
    ],
    function ($router)
    {
        // LOGOUT 💚
        Route::get('/logout_users/{user_id}', [AuthentificatorController::class, 'logout']);
    }
);

/**
 * @Route("/")
 * ====================== START RESSOURCE HUMAINE =====================
 * +++++++++++++++++++++++***********************++++++++++++++++++++++
*/


// ======= BUREAU 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(BureauController::class)->group(function () {
            // BUREAU 💚
            Route::get('/get_bureau', 'index');
            Route::post('/store_bureau', 'store');
            Route::post('/update_bureau/{slug}', 'update');
            Route::get('/destroy_bureau/{slug}', 'destroy');
        });
    }
);

// ====== FONCTION 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(FonctionController::class)->group(function () {
            // FONCTION 💚
            Route::get('/get_fonctions', 'index');
            Route::post('/store_fonctions', 'store');
            Route::post('/update_fonctions/{slug}', 'update');
            Route::get('/destroy_fonctions/{slug}', 'destroy');
        });

    }
);

// ======= SERVICES 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(ServiceController::class)->group(function () {
            // SERVICES 💚
            Route::get('/get_services', 'index');
            Route::post('/store_services', 'store');
            Route::post('/update_services/{slug}', 'update');
            Route::get('/destroy_services/{slug}', 'destroy');
        });

    }
);

// ======= CONTRATS 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(ContratsController::class)->group(function () {
            // CONTRATS 💚
            Route::get('/get_contrats', 'index');
            Route::post('/store_contrats', 'store');
            Route::post('/update_contrats/{slug}', 'update');
            Route::get('/destroy_contrats/{slug}', 'destroy');
        });

    }
);


// ======= CONFIGURATION POINTAGE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(PointageConfigController::class)->group(function () {
            // CONFIGURATION POINTAGE 💚
            Route::get('/get_pointage_config', 'index');
            Route::post('/store_pointage_config', 'store');
            Route::post('/update_pointage_config/{slug}', 'update');
            Route::get('/destroy_pointage_config/{slug}', 'destroy');
        });

    }
);

// END CONFIGURATION POINTAGE


// =======  POINTAGE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(PointageController::class)->group(function () {
            // POINTAGE 💚
            Route::get('/get_pointage', 'index');
            Route::get('/get_employe_not_pointng', 'get_employe_not_pointng');
            Route::get('/get_current_pointage', 'get_current_pointage');
            Route::get('/get_customer_attendance/{employe_matricule}', 'get_customer_attendance');
            Route::get('get_customer_attendance_statistique/{employe_matricule}', 'get_customer_attendance_statistique');
            Route::post('/store_pointage', 'store');
            Route::post('/update_pointage/{slug}', 'update');
            Route::get('/destroy_pointage/{slug}', 'destroy');
        });

    }
);
// ======= END POINTAGE 💚====================


// =======  CODE AUTHORIZATION 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(CodeAuthorizationController::class)->group(function () {
            // CODE AUTHORIZATION 💚
            Route::get('/get_code_authorization/{type_accounts}', 'index');
            Route::get('/refresh_code_authorization/{slug}', 'refresh_code');
            Route::post('/check_code_authorization', 'check_code_authorization');

        });

    }
);
// ======= END POINTAGE 💚====================



// ======= TYPE DE CONGES 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(TypeCongesController::class)->group(function () {
            // TYPE DE CONGES 💚
            Route::get('/get_type_conge', 'index');
            Route::post('/store_type_conge', 'store');
            Route::post('/update_type_conge/{slug}', 'update');
            Route::get('/destroy_type_conge/{slug}', 'destroy');
        });

    }
);


// ======= RESPONSABLE AND AGENTS 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(ResponsableAgentsController::class)->group(function () {
            // RESPONSABLE AND AGENTS 💚
            Route::get('/get_responsable_agents', 'index');

            Route::get('/get_all_responsable', 'get_all_responsable');
            Route::get('/get_agents_by_employee_service/{employee_service}', 'get_agents_by_employee_service');

            Route::post('/store_responsable_agents', 'store');
            Route::post('/update_responsable_agents/{slug}', 'update');
            Route::get('/destroy_responsable_agents/{slug}', 'destroy');
        });

    }
);



// ======= CONGES 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(CongesController::class)->group(function () {
            // CONGES 💚
            Route::get('get_conge/{employe_matricule}', 'index');

            Route::get('get_all_conge', 'get_all_conge');


            Route::get('/customers_conge/{employe_matricule}', 'customers_conges');
            Route::post('/store_conge', 'store');
            Route::get('/edit_conge/{slug}', 'edit');
            Route::post('/update_conge/{slug}', 'update');
            Route::get('/destroy_conge{slug}', 'destroy');
            Route::post('/approved_or_rejected_conge', 'approved_or_rejected_conge');
            Route::get('/consulted_conge/{slug}', 'consulted_conge');
        });

    }
);

// ========PERMISSIONS 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {

        Route::controller(PermissionController::class)->group(function () {
            // PERMISSIONS 💚

            Route::get('get_all_permissions', 'get_all_permissions');


            Route::get('get_permissions/{customer_id}', 'index');
            Route::get('/customers_permissions/{employe_matricule}', 'customers_permissions');
            Route::post('/store_permission', 'store');
            Route::get('/edit_permission/{slug}', 'edit');
            Route::post('/update_permission/{slug}', 'update');
            Route::get('/destroy_permission{slug}', 'destroy');
            Route::post('/approved_or_rejected_permission', 'approved_or_rejected_permission');
            Route::get('/consulted_permission/{slug}', 'consulted_permission');
        });

    }
);



// ======== INFORMATION EMPLOYES 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {

        Route::controller(EmployesController::class)->group(function () {
            // EMPLOYES 💚
            Route::get('get_employe', 'index');
            Route::get('get_employe_info/{employe_matricule}', 'get_employe_info');
            Route::get('get_employe_info_with_status_0', 'get_employe_info_with_status_0');

            Route::get('get_employee_where_is_responsible_and_is_agent_equal_zero', 'get_employee_where_is_responsible_and_is_agent_equal_zero');

            Route::get('get_responsable_service', 'get_responsable_service');


            Route::get('confirm_employees_infos/{slug}', 'confirm_employees_infos');
            //Route::post('/store_employees', 'store');
            Route::get('/show_employe/{slug}', 'show');
            Route::post('/update_employe/{slug}', 'update');
            Route::get('/destroy_employe/{slug}', 'destroy');
            Route::get('/create_employe_accounts/{slug}', 'create_employe_accounts');

            Route::get('enable_or_disable_is_responsible/{employee_matricule}', 'enable_or_disable_is_responsible');
            Route::get('enable_or_disable_is_agents/{employee_matricule}', 'enable_or_disable_is_agents');

            Route::get('filter_on_employe_with_query/{query}', 'filter_on_employe_with_query');
            Route::get('filter_on_employe_with_status/{status}', 'filter_on_employe_with_status');
            Route::get('filter_on_employe_with_service/{service}', 'filter_on_employe_with_service');

            Route::post('/update_employee_photo/{slug}', 'update_employee_photo');
            Route::post('/update_employee_matricule/{slug}', 'update_employee_matricule');
        });

    }
);

// ======== CONTRAT EMPLOYES 💚====================

Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {

        Route::controller(EmployeContratsController::class)->group(function () {
            // EMPLOYES CONTRATs 💚
            Route::post('/store_employe_contrats', 'store');
            Route::post('/update_employe_contrats/{employee_matricule}', 'update');
        });

    }
);


// ======= CATEGORIE PROFESSIONNELLE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(CategorieProController::class)->group(function () {
            // CATEGORIE PROFESSIONNELLE 💚
            Route::get('/get_categorie_pro', 'index');
            Route::post('/store_categorie_pro', 'store');
            Route::post('/update_categorie_pro/{slug}', 'update');
            Route::get('/destroy_categorie_pro/{slug}', 'destroy');
        });

    }
);

// ======= DOSSIER EMPLOYE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(DossierEmployesController::class)->group(function () {
            // DOSSIER EMPLOYE 💚
            Route::get('/get_dossiers', 'index');
            Route::get('/customers_dossiers/{employe_matricule}', 'customers_dossiers');
            Route::get('/get_show_dossiers/{slug}', 'show');
            Route::get('/get_customers_documents/{dossiers_code}', 'get_customers_documents');
            Route::post('/store_dossiers', 'store');
            Route::post('/update_dossiers/{slug}', 'update');
            Route::get('/destroy_dossiers/{slug}', 'destroy');
        });

    }
);

// ======== DOSSIERS FILE 💚====================

Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\RessourceHumaine',
    ],
    function ($router)
    {
        Route::controller(DossierFileController::class)->group(function () {
            // DOSSIERS FILE 💚
            Route::post('/store_dossiers_file', 'store');
            Route::post('/update_dossiers_file/{slug}', 'update');
            Route::get('/destroy_dossiers_file/{slug}', 'destroy');
            Route::post('/send_notify', 'confirmedOrRejectedFile');
        });

    }
);





// ==================================== ROLES 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\Administration',
    ],
    function ($router)
    {
        // ROLES 💚
        Route::get('/get_role', [RoleController::class, 'index']);
        Route::post('/store_role', [RoleController::class, 'store']);
        Route::post('/update_role/{slug}', [RoleController::class, 'update']);
        Route::get('/delete_role/{slug}', [RoleController::class, 'destroy']);


    }
);

// ==================================== TYPE DE COMPTE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\Administration',
    ],
    function ($router)
    {
        // TYPE DE COMPTE 💚
        Route::get('/get_type_accounts', [TypeAccountsController::class, 'index']);
        Route::post('/store_type_accounts', [TypeAccountsController::class, 'store']);
        Route::post('/update_type_accounts/{slug}', [TypeAccountsController::class, 'update']);
        Route::get('/destroy_type_accounts/{slug}', [TypeAccountsController::class, 'destroy']);


    }
);


// ==================================== COMPTE ADMIN 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\UserAccounts',
    ],
    function ($router)
    {
        Route::controller(AdministrationController::class)->group(function () {
            // COMPTE ADMIN 💚
            Route::get('get_admin', 'index');
            Route::post('/store_admin', 'store');
            Route::post('/store_employe', 'store_employe_account_data');
            Route::get('/show_admin/{slug}', 'show');
            Route::post('/update_admin/{slug}', 'update');
            Route::get('/delete_admin/{slug}', 'destroy');
            Route::get('/enable_or_disable_admin_account/{slug}', 'enable_or_disable_account');
            Route::post('/update_administration_photo', 'update_administration_photo');
        });

    }

);


// ==================================== COMPTE EMPLOYEE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\UserAccounts',
    ],
    function ($router)
    {
        Route::controller(EmployeeAccountsController::class)->group(function () {

            // COMPTE EMPLOYEE 💚
            Route::get('get_employee_accounts', 'index');
            Route::post('/store_employee_accounts', 'store');
            Route::get('/show_employee_accounts/{slug}', 'show');
            Route::post('/update_employee_accounts/{slug}', 'update');
            Route::get('/delete_employee_accounts/{slug}', 'destroy');
            Route::get('/enable_or_disable_employee_accounts_account/{slug}', 'enable_or_disable_account');
            Route::get('/change_employee_type_account/{type_account}/{slug}', 'change_employee_type_account');
            Route::post('/update_employee_accounts_photo', 'update_employee_accounts_photo');
        });

    }

);


/**
 ************************** COURRIER MANAGMENTS ****************************
 *
 */


// ==================================== COURRIER 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\CourrierManagments',
    ],
    function ($router)
    {
        Route::controller(CourrierController::class)->group(function () {

            // COURRIER 💚
            Route::get('/get_courrier', 'index');
            Route::get('/get_courrier_imputed', 'get_courrier_imputed');
            Route::post('/store_courrier', 'store');
            Route::post('/update_courrier/{slug}', 'update');
            Route::get('/destroy_courrier/{slug}', 'destroy');
            Route::get('filter_on_courrier/{query}', 'filter_on_courrier');
        });

    }
);


// ==================================== COURRIER SENDING 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\CourrierManagments',
    ],
    function ($router)
    {
        Route::controller(SendCourrierController::class)->group(function () {

            // COURRIER SENDING 💚
            Route::get('/get_send_courrier', 'index');
            //Route::get('/get_courrier_imputed', 'get_courrier_imputed');
            Route::post('/store_send_courrier', 'store');
            Route::post('/update_send_courrier/{slug}', 'update');
            Route::get('/destroy_send_courrier/{slug}', 'destroy');
            Route::get('filter_on_send_courrier/{query}', 'filter_on_send_courrier');
        });

    }
);



// ==================================== IMPUTATION DE COURRIER RESPONSABLE DE SERVICE 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\CourrierManagments',
    ],
    function ($router)
    {
        Route::controller(ImputationCourriersResponsableServiceController::class)->group(function () {

            // IMPUTATION DE COURRIER RESPONSABLE DE SERVICE 💚
            Route::post('/store_imputed_courrier_to_responsable_service_data', 'store');
            Route::post('/update_imputed_courrier_to_responsable_service_data/{slug}', 'update');



            Route::get('/get_courrier_impted_by_employee/{employee_matricule}', 'get_courrier_impted_by_employee');
        });

    }
);


// ==================================== IMPUTATION DE COURRIER AGENT 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\CourrierManagments',
    ],
    function ($router)
    {
        Route::controller(ImputationCourriersAgentController::class)->group(function () {
            // IMPUTATION DE COURRIER AGENT 💚
            Route::post('/store_imputed_courrier_to_agent_service_data', 'store');
            Route::post('/update_imputed_courrier_to_agent_service_data/{slug}', 'update');
        });
    }
);

/**
 ************************** END COURRIER MANAGMENTS ****************************
 *
 */



// ======= STATISTIQUE ADMIN 💚====================
Route::group(
    [
        'middleware' => 'api',
        'namespace' => 'App\Http\Controllers\API\V1\Statistique',
    ],
    function ($router)
    {
        Route::controller(StatistiqueController::class)->group(function () {

            // STATISTIQUE ADMIN 💚

            Route::get('get_admin_statistique', 'get_admin_statistique');

            Route::get('get_rh_statistique', 'get_rh_statistique');

            Route::get('get_attendance_statistique', 'get_attendance_statistique');

            Route::get('get_admin_courriers_statistique', 'get_admin_courriers_statistique');
        });

    }
);
