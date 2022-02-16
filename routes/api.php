<?php

use App\Http\Modules\Article\Controllers\ArticleController;
use App\Http\Modules\Company\Controllers\CompanyController;
use App\Http\Modules\Core\Controllers\InitialDataController;
use App\Http\Modules\Core\Controllers\OtherController;
use App\Http\Modules\Investment\Controllers\InvestmentIdeaController;
use App\Http\Modules\Portal\Controllers\PortalController;
use App\Http\Modules\Portal\Controllers\ViewController;
use App\Http\Modules\Portal\Middleware\AfterViewArticleMiddleware;
use App\Http\Modules\Portal\Middleware\AfterViewIdeaMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'investment-data'], function () {
    Route::get('/get', [PortalController::class, 'getPortalData']);
    Route::get('/news', [PortalController::class, 'getNews']);
    Route::get('/idea/{id}', [InvestmentIdeaController::class, 'getInvestmentIdeaData'])->middleware(AfterViewIdeaMiddleware::class);

});
Route::group(['prefix' => 'init'], function () {
    Route::get('/portal-data', [InitialDataController::class, 'getPortalInit']);
});

Route::group(
    [
        'prefix' => 'idea',
    ],
    function () {
        Route::post('/create-comment', [InvestmentIdeaController::class, 'createComment'])->middleware('auth:sanctum');
        Route::get('/{idea}/comments', [InvestmentIdeaController::class, 'getComments'])->name('get-idea-comments');
//        Route::get('/all-key', [InitialDataController::class, 'getIdeasKey']);
        Route::post('/{idea}/set-rating', [InvestmentIdeaController::class, 'setRating'])->middleware('auth:sanctum')->name('set-rating');
        Route::get('/{idea}/user-rating', [InvestmentIdeaController::class, 'getUserRating']);
        Route::get('/{idea}/company-stats', [InvestmentIdeaController::class, 'getCompanyStats'])->name('company-stats');
        Route::get('/{idea}/rating', [InvestmentIdeaController::class, 'getRating'])->name('get-rating');
        Route::get('/{idea}', [ViewController::class, 'getViewIdea']); //->middleware([AfterViewIdeaMiddleware::class, 'auth:sanctum']);
    });

Route::group(
    [
        'prefix' => 'article',
    ],
    function () {
//        Route::get('/all-key', [InitialDataController::class, 'getArticlesKey']);
        Route::get('/{article}', [ViewController::class, 'getViewArticle'])->middleware(AfterViewArticleMiddleware::class);
        Route::get('/{article}/comments', [ArticleController::class, 'getComments'])->name('get-article-comments');
        Route::get('/{article}/labels', [ArticleController::class, 'getLabels'])->name('get-labels');
        Route::post('/create-comment', [ArticleController::class, 'createComment'])->middleware('auth:sanctum');
    }
);

Route::group(['prefix' => 'other'], function () {
    Route::get('/countries', [OtherController::class, 'getCountries']);
    Route::post('/subscribe-email', [OtherController::class, 'subscribeEmail']);
    Route::post('/upload-file', [OtherController::class, 'uploadFile']);
    Route::get('/roles', [OtherController::class, 'getRoles']);
    Route::get('/quotes', [OtherController::class, 'getQuote']);
});
Route::group(['prefix' => 'profile'], function () {
    Route::get('/{user}', [ViewController::class, 'getViewProfile']);
});

Route::group(['prefix' => 'company'], function() {
   Route::get('/{company}/quote', [CompanyController::class, 'getQuote']);
});

Route::get('/search/{search}', [PortalController::class, 'searchData'])->name('portal-search');
