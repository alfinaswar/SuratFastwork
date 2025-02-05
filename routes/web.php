<?php

use App\Http\Controllers\DrafterController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratTerkirimController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikatorController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('log')->group(function () {
        Route::GET('/getLog', [LogController::class, 'getLog'])->name('log.getLog');
    });
    Route::prefix('persetujuan-surat')->group(function () {
        Route::GET('/', [PersetujuanController::class, 'index'])->name('persetujuan-surat.index');
        Route::GET('/show/{id}', [PersetujuanController::class, 'show'])->name('persetujuan-surat.show');
        Route::GET('/approve/{id}', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
        Route::GET('/reject/{id}', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');
    });
    Route::prefix('verifikator')->group(function () {
        Route::GET('/download-preview/{id}', [VerifikatorController::class, 'show'])->name('verifikator.preview');
        Route::GET('/download-preview/{id}', [VerifikatorController::class, 'downloadPreview'])->name('verifikator.download-preview');
    });
    Route::prefix('surat-terkirim')->group(function () {
        Route::GET('/download-surat/{id}', [SuratTerkirimController::class, 'download'])->name('surat-terkirim.download');
    });
    Route::resource('templates', TemplateController::class);
    Route::resource('drafter', DrafterController::class);
    Route::resource('verifikator', VerifikatorController::class);
    Route::resource('kategori-surat', MasterJenisController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('surat-terkirim', SuratTerkirimController::class);

    Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
    Route::get('/generate-word', [MasterJenisController::class, 'generateWord']);
});
