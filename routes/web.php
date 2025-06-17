<?php

use App\Http\Controllers\DrafterController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KodeProyekController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MasterDepartemenController;
use App\Http\Controllers\MasterFieldsController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\MasterPenerimaEksternalController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratTerkirimController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikatorController;
use App\Models\MasterDepartemen;
use App\Models\SuratMasuk;
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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/surat-digital/{id}', [HomeController::class, 'showDigital'])->name('surat.digital');

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('log')->group(function () {
        Route::GET('/getLog', [LogController::class, 'getLog'])->name('log.getLog');
    });
    Route::prefix('persetujuan-surat')->group(function () {
        Route::GET('/', [PersetujuanController::class, 'index'])->name('persetujuan-surat.index');
        Route::GET('/show/{id}', [PersetujuanController::class, 'show'])->name('persetujuan-surat.show');
        Route::GET('/approve/{id}', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
        Route::GET('/reject/{id}', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');
        Route::GET('/lihat-preview/{id}', [PersetujuanController::class, 'showPreview'])->name('persetujuan.show-surat');
    });

    Route::prefix('verifikator')->group(function () {
        Route::GET('/download-preview/{id}', [VerifikatorController::class, 'show'])->name('verifikator.preview');
        Route::GET('/lihat-preview/{id}', [VerifikatorController::class, 'showPreview'])->name('verifikator.show-surat');
        Route::GET('/download-preview/{id}', [VerifikatorController::class, 'downloadPreview'])->name('verifikator.download-preview');
    });
    Route::prefix('surat-terkirim')->group(function () {
        Route::GET('/download-surat/{id}', [SuratTerkirimController::class, 'download'])->name('surat-terkirim.download');
    });
    Route::prefix('surat-masuk')->group(function () {
        Route::GET('/download-surat/{id}', [SuratTerkirimController::class, 'download'])->name('surat-terkirim.download');
        Route::GET('/read/{id}', [SuratMasukController::class, 'read'])->name('surat-masuk.read');
        Route::DELETE('/delete/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.delete');
    });
    Route::prefix('master-field')->group(function () {
        Route::PUT('/update-field/{id}', [MasterFieldsController::class, 'update'])->name('master-field.update');
    });
    Route::prefix('kategori-surat')->group(function () {
        Route::GET('/get-field/{id}', [MasterJenisController::class, 'getField'])->name('kategori-surat.get-field');
    });
    Route::prefix('users')->group(function () {
        Route::GET('/get-users/{id}', [UserController::class, 'getUsers'])->name('users.get-users');
        Route::GET('/get-users-eks/{id}', [UserController::class, 'getUsersEks'])->name('users.get-users-eks');
    });
    Route::prefix('drafter')->group(function () {
        Route::POST('/ajukan-dokumen/{id}', [DrafterController::class, 'ajukandokumen'])->name('drafter.ajukan');
        Route::POST('/get-penerima-surat', [DrafterController::class, 'getPenerimaSurat'])->name('drafter.getPenerimaSurat');
    });
    Route::resource('templates', TemplateController::class);
    Route::resource('drafter', DrafterController::class);
    Route::resource('verifikator', VerifikatorController::class);
    Route::resource('kategori-surat', MasterJenisController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('surat-terkirim', SuratTerkirimController::class);
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::resource('master-penerima-ext', MasterPenerimaEksternalController::class);
    Route::resource('master-field', MasterFieldsController::class);
    Route::resource('master-departemen', MasterDepartemenController::class);
    Route::resource('master-proyek', KodeProyekController::class);

    Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
    Route::get('/generate-word', [MasterJenisController::class, 'generateWord']);
});
