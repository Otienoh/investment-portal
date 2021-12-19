<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Modules\Admin\Controllers\ArticleAdminController;
use App\Http\Modules\Admin\Controllers\CreateIdeaController;
use App\Http\Modules\Admin\Controllers\InvestmentDataController;
use App\Http\Modules\Admin\Controllers\SmartAnalyticController;
use App\Http\Modules\Article\Controllers\ArticleActionsController;
use App\Http\Modules\Auth\Controllers\UserActionController;
use App\Http\Modules\Core\Controllers\OtherController;
use App\Http\Modules\Core\Controllers\UserController;
use App\Http\Modules\Core\Middleware\BeforeGetUserAuth;
use App\Http\Modules\Investment\Controllers\InvestmentController;
use App\Http\Modules\Investment\Controllers\InvestmentIdeaController;
use App\Http\Modules\Portal\Controllers\ViewController;
use App\Http\Modules\Portal\Middleware\AfterViewArticleMiddleware;
use App\Http\Modules\Portal\Middleware\AfterViewIdeaMiddleware;
use App\Http\Modules\Profile\Controllers\ProfileController;
use App\Http\Modules\Profile\Middleware\BeforeGetAuthUserId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth-register:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/authentication', [AuthController::class, 'authentication']);
    Route::post('/notice/view', [UserController::class, 'viewNotice']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/profile/{id}', [UserController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfileData'])->middleware(BeforeGetAuthUserId::class);
    Route::get('/exit', [UserActionController::class, 'exitUser']);
    Route::post('/forgot-password', [UserActionController::class, 'forgotPassword']);
    Route::post('/recovery-password', [UserActionController::class, 'recoveryPassword']);
});

Route::group(['prefix' => 'news'], function () {
    Route::get('/all', [NewsController::class, 'getAllNews']);
});
Route::group(['prefix' => 'admin'], function () {
    Route::get('/investment-data', [InvestmentDataController::class, 'getInvestmentData']);
    Route::get('/companies/{query}', [InvestmentDataController::class, 'getCompanies']);
    Route::post('/classification-news', [CreateIdeaController::class, 'predictNews']);
    Route::group(['prefix' => 'smart-analytic'], function () {
        Route::get('/data', [SmartAnalyticController::class, 'getAnalyticData']);
        Route::get('/last-news', [SmartAnalyticController::class, 'getNewsForAnalyze']);
        Route::post('/train-news-classifier', [SmartAnalyticController::class, 'trainNewsClassifier']);
    });
    Route::group(['prefix' => 'article'], function () {
        Route::post('/create', [ArticleAdminController::class, 'createArticle'])->middleware(BeforeGetAuthUserId::class);
        Route::post('/update', [ArticleAdminController::class, 'updateArticle'])->middleware(BeforeGetAuthUserId::class);
        Route::get('/get/{page}', [ArticleAdminController::class, 'getArticlesByPage'])->middleware(BeforeGetAuthUserId::class);
        Route::post('/delete', [ArticleAdminController::class, 'deleteArticle'])->middleware(BeforeGetAuthUserId::class);
    });
});
Route::group(['prefix' => 'investment-data'], function () {
    Route::get('/get', [InvestmentController::class, 'getData']);
    Route::get('/portal', [InvestmentController::class, 'getPortalData']);
    Route::get('/news', [InvestmentController::class, 'getNews']);
    Route::get('/idea/{id}', [InvestmentIdeaController::class, 'getInvestmentIdeaData'])->middleware(AfterViewIdeaMiddleware::class);
});

Route::group(['prefix' => 'investment-idea'], function () {
    Route::post('/create-comment', [InvestmentIdeaController::class, 'createComment'])->middleware(BeforeGetUserAuth::class);
});
Route::group(['prefix' => 'article'], function () {
    Route::get('/get/{id}', [ViewController::class, 'getViewArticle'])->middleware(AfterViewArticleMiddleware::class);
    Route::post('/create-comment', [ArticleActionsController::class, 'createComment'])->middleware(BeforeGetUserAuth::class);
});
Route::group(['prefix' => 'other'], function () {
    Route::get('/countries', [OtherController::class, 'getCountries']);
    Route::post('/subscribe-email', [OtherController::class, 'subscribeEmail']);
});
Route::get('/test', [OtherController::class, 'test']);

