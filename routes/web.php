<?php

use App\Http\Controllers\Archieve\DocumentationController;
use App\Http\Controllers\Archieve\GalleryController;
use App\Http\Controllers\Archieve\GiatAnevController;
use App\Http\Controllers\Archieve\GiatKampungTertibController;
use App\Http\Controllers\Archieve\IncomingMailController;
use App\Http\Controllers\Archieve\MouController;
use App\Http\Controllers\Archieve\OutgoingMailController;
use App\Http\Controllers\Archieve\StatementLetterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\ClassificationController;
use App\Http\Controllers\Master\InstitutionController;
use App\Http\Controllers\Master\TypeMailContentController;
use App\Http\Controllers\User\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Home Route
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Auth Route
 */
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('authentication', [AuthController::class, 'authentication'])->name('authentication');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/**
 * Owner Route Access
 */
Route::group(['middleware' => ['role:admin']], function () {
    /**
     * Master Module
     */
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        /**
         * Route Institution Module
         */
        Route::group(['controller' => InstitutionController::class, 'prefix' => 'institution', 'as' => 'institution.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('institution', InstitutionController::class, ['except' => ['store']])->parameters(['institution' => 'id']);

        /**
         * Route Type Mail Content Module
         */
        Route::group(['controller' => TypeMailContentController::class, 'prefix' => 'type-mail-content', 'as' => 'type-mail-content.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('type-mail-content', TypeMailContentController::class)->parameters(['type-mail-content' => 'id']);

        /**
         * Route Classification Module
         */
        Route::group(['controller' => ClassificationController::class, 'prefix' => 'classification', 'as' => 'classification.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('classification', ClassificationController::class)->parameters(['classification' => 'id']);
    });

    /**
     * Route User Management Module
     */
    Route::group(['controller' => UserManagementController::class, 'prefix' => 'user-management', 'as' => 'user-management.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('user-management', UserManagementController::class)->parameters(['user-management' => 'id']);
});

/**
 * User Route Access
 */
Route::group(['middleware' => ['role:user']], function () {});

/**
 * Admin and User Route Access
 */
Route::group(['middleware' => ['role:admin|user']], function () {
    /**
     * Master Module
     */
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        /**
         * Institution Module (Only Store Record)
         */
        Route::resource('institution', InstitutionController::class, ['except' => ['index', 'create', 'show', 'edit', 'update', 'destroy']])->parameters(['institution' => 'id']);
    });

    /**
     * Archieve Module
     */
    Route::group(['prefix' => 'archieve', 'as' => 'archieve.'], function () {
        /**
         * Route Giat Anev Module
         */
        Route::group(['controller' => GiatKampungTertibController::class, 'prefix' => 'giat-kampung-tertib', 'as' => 'giat-kampung-tertib.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('giat-kampung-tertib', GiatKampungTertibController::class)->parameters(['giat-kampung-tertib' => 'id']);

        /**
         * Route Giat Kampung Tertib Module
         */
        Route::group(['controller' => GiatAnevController::class, 'prefix' => 'giat-anev', 'as' => 'giat-anev.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('giat-anev', GiatAnevController::class)->parameters(['giat-anev' => 'id']);

        /**
         * Route Incoming Mail Module
         */
        Route::group(['controller' => IncomingMailController::class, 'prefix' => 'incoming-mail', 'as' => 'incoming-mail.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('incoming-mail', IncomingMailController::class)->parameters(['incoming-mail' => 'id']);

        /**
         * Route Outgoing Mail Module
         */
        Route::group(['controller' => OutgoingMailController::class, 'prefix' => 'outgoing-mail', 'as' => 'outgoing-mail.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('outgoing-mail', OutgoingMailController::class)->parameters(['outgoing-mail' => 'id']);

        /**
         * Route MOU Module
         */
        Route::group(['controller' => MouController::class, 'prefix' => 'mou', 'as' => 'mou.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('mou', MouController::class)->parameters(['mou' => 'id']);

        /**
         * Route Statement Letter Module
         */
        Route::group(['controller' => StatementLetterController::class, 'prefix' => 'statement-letter', 'as' => 'statement-letter.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('statement-letter', StatementLetterController::class)->parameters(['statement-letter' => 'id']);

        /**
         * Route Documentation Video Module
         */
        Route::group(['controller' => DocumentationController::class, 'prefix' => 'documentation', 'as' => 'documentation.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('documentation', DocumentationController::class)->parameters(['documentation' => 'id']);

        /**
         * Route Gallery Module
         */
        Route::group(['controller' => GalleryController::class, 'prefix' => 'gallery', 'as' => 'gallery.'], function () {
            Route::get('datatable', 'dataTable')->name('dataTable');
        });
        Route::resource('gallery', GalleryController::class)->parameters(['gallery' => 'id']);
    });

    /**
     * Route Global Institution Module
     */
    Route::group(['controller' => Controller::class, 'prefix' => 'institution', 'as' => 'institution.'], function () {
        Route::get('get-institution/{level}/{global}', 'getInstitution')->name('getInstitution');
    });
});
