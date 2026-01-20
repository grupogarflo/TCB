<?php

use App\Http\Controllers\DestinationController;
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

/*
es el ok
Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->get('/users/me', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
*/

//public
Route::post('/getTourList', 'SiteApiController@getTourList');
Route::post('/getTourListHome', 'SiteApiController@getTourListHome');
Route::post('/getTourListSelect', 'SiteApiController@getTourListSelect');
Route::post('/getTourData', 'SiteApiController@getTourData');
Route::post('/getTotal', 'SiteApiController@getTotal');
Route::post('/getPromoCode', 'SiteApiController@getPromoCode');
Route::post('/getBannerHome', 'SiteApiController@getBannerHome');

Route::post('/addPayment', 'PaymentController@addPayment');
Route::post('/updatePayment', 'PaymentController@updatePayment');
Route::post('/getDataPayment', 'PaymentController@getDataPayment');
Route::post('/paymentProcess', 'PaymentController@paymentProcess');
Route::post('/eventUrlBook', 'PaymentController@eventUrlBook');

Route::post('/paymentTransferBank', 'PaymentController@paymentTransferBank');
Route::post('/responseHook', 'PaymentController@responseHook');

//bloqueo fechas, dias
Route::post('/getBlockDates', 'SiteApiController@getBlockDates');

//teste envio de correo
Route::post('/sendEmail', 'PaymentController@test');
Route::post('/preBookEmail', 'PaymentController@preBookEmail');
Route::post('/emailContacto', 'PaymentController@emailContacto');



//generar las url dinamicas para routes nuxt
Route::post('/getListUrlGenerate', 'SiteApiController@getListUrlGenerate');

//recuperar las categorias
Route::post('/getAllCategories', 'SiteApiController@getAllCategories');
Route::post('/getCategory', 'SiteApiController@getCategory');
Route::post('/getTourByCategory', 'SiteApiController@getTourByCategory');
Route::post('/getAllTours', 'SiteApiController@getAllTours');



/// route to validate id url is category, destination or tour

Route::post('/pageType', 'DetailController@pageType');


/// add destinations

Route::post('/getDestinationsAll', 'DestinationController@getDestinationsAll');
Route::post('/getDestination', 'DestinationController@getDestinationToFront');
Route::post('/getTourByDestination', 'DestinationController@getTourByDestination');

Route::post('/getPaxtRange', 'SiteApiController@getPaxtRange');


//test
Route::post('/login', 'AuthController@login');

Route::group(['prefix' => 'mercado-pago'], function () {
    Route::post('getToken', 'MercadoPagoController@getPreferenceId');
    Route::post('process_payment', 'MercadoPagoController@process_payment');
});
//Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
Route::group([
    'middleware' => 'auth:api'
], function () {
    //users
    //Route::get('/user', 'AuthController@user');
    Route::get('/users/me', 'AuthController@user');
    Route::get('/getAllUsers', 'UserController@getAllUsers');
    Route::post('/addUsers', 'UserController@addUsers');
    Route::post('/deleteUsers', 'UserController@deleteUsers');
    Route::post('/upateUsers', 'UserController@upateUsers');
    Route::post('/logoout', 'AuthController@logout');

    //permisos
    Route::post('/getLevelModule', 'UserController@getLevelModule');
    Route::post('/addRemoveLevelUser', 'UserController@addRemoveLevelUser');
    Route::post('/getMeLevelUser', 'UserController@getMeLevelUser');



    //languages
    Route::get('/getAllLanguage', 'LanguageController@getAllLanguage');
    Route::post('/addLanguage', 'LanguageController@addLanguage');
    Route::post('/deleteLanguage', 'LanguageController@deleteLanguage');
    Route::post('/updateLanguage', 'LanguageController@updateLanguage');


    //categories
    Route::get('/getAllCategories', 'CategoryController@getAllCategories');
    Route::post('/addCategories', 'CategoryController@addCategories');
    Route::post('/deleteCategories', 'CategoryController@deleteCategories');
    Route::post('/updateCategories', 'CategoryController@updateCategories');
    Route::post('/getCategoriesInfo', 'CategoryController@getCategoriesInfo');

    //destination
    Route::get('/getAllDestination', 'DestinationController@getAllDestination');
    Route::post('/addDestination', 'DestinationController@addDestination');
    Route::post('/deleteDestination', 'DestinationController@deleteDestination');
    Route::post('/updateDestination', 'DestinationController@updateDestination');
    Route::post('/getDestinationInfo', 'DestinationController@getDestinationInfo');
    Route::post('/addImgDestination', 'DestinationController@addDestinationImg');

    //exchangeRate
    Route::get('/getAllRate', 'ExchangeRateController@getAllRate');
    Route::post('/addRate', 'ExchangeRateController@addURate');
    Route::post('/deleteRate', 'ExchangeRateController@deleteRate');
    Route::post('/updateRate', 'ExchangeRateController@upateRate');

    //tours
    Route::get('/getAllTour', 'TourController@getAllTour');
    Route::post('/addTour', 'TourController@addTour');
    Route::post('/deleteTour', 'TourController@deleteTour');
    Route::post('/updateTour', 'TourController@updateTour');
    Route::post('/getTourInfo', 'TourController@getTourInfo');
    Route::post('/addImgTour', 'TourController@addImgTour');
    Route::post('/addMapTour', 'TourController@addMapTour');
    Route::post('/addGaleriaTour', 'TourController@addGaleriaTour');
    Route::post('/getCategoriaTour', 'TourController@getCategoriaTour');
    Route::post('/addRemoveCatTour', 'TourController@addRemoveCatTour');
    Route::post('/removePublic', 'TourController@removePublic');
    Route::post('/getIcons', 'TourController@getIcons');
    Route::post('/addRemoveIconTour', 'TourController@addRemoveIconTour');
    Route::post('/addImgTourBanner', 'TourController@addImgTourBanner');
    Route::post('/addGalleyTour', 'TourController@addGalleyTour');
    Route::post('/getGalleyTour', 'TourController@getGalleyTour');
    Route::post('/deleteGalleyTour', 'TourController@deleteGalleyTour');
    Route::post('/addRemoveCheckinPayment', 'TourController@addRemoveCheckinPayment');

    //precio de los tours
    Route::post('/getPriceTour', 'TourController@getPriceTour');
    Route::post('/addPriceTour', 'TourController@addPriceTour');
    Route::post('/updatePriceTour', 'TourController@updatePriceTour');


    //video de los tours
    Route::post('/getVideoTour', 'TourController@getVideoTour');
    Route::post('/updateVideoTour', 'TourController@updateVideoTour');


    //categoria/destinos
    Route::get('/getAllCategoriDestination', 'CategoryDestinationContents@getAllCategoriDestination');
    Route::post('/addCategoriDestination', 'CategoryDestinationContents@addCategoriDestination');
    Route::post('/deleteCategoriDestination', 'CategoryDestinationContents@deleteCategoriDestination');
    Route::post('/updateCategoriDestination', 'CategoryDestinationContents@updateCategoriDestination');
    Route::post('/getCategoriDestinationInfo', 'CategoryDestinationContents@getCategoriDestinationInfo');
    Route::get('/getCategory', 'CategoryDestinationContents@getCategori');
    Route::get('/getDestination', 'CategoryDestinationContents@getDestination');


    //// destinations only

    Route::post('/getDestinationsCMS', 'DestinationController@getAllDestinationCMS');
    Route::post('/addRemoveDestinationTour', 'DestinationController@addRemoveDestinationTour');


    //payment process
    //Route::post('/addPayment', 'PaymentController@addPayment');
    //Route::post('/updatePayment', 'PaymentController@updatePayment');
    //Route::post('/getDataPayment', 'PaymentController@getDataPayment');

    //reporte de ventas en el cms
    Route::post('/getAllReservation', 'PaymentController@getAllReservation');
    Route::post('/getDetailReservation', 'PaymentController@getDetailReservation');
    Route::post('/cvsReservation', 'PaymentController@cvsReservation');
    Route::post('/cancelTour', 'PaymentController@cancelTour');
    Route::post('/editTourBook', 'PaymentController@editTourBook');
    Route::post('/refundTourBook', 'PaymentController@refundTourBook');
    Route::post('/resendEmail', 'PaymentController@reSendEmail');

    //bloqueo de dias o fecha
    Route::get('/getAllTourBlock', 'CloseDatesController@getAllTourBlock');
    Route::post('/addBlock', 'CloseDatesController@addBlock');
    Route::post('/editBlock', 'CloseDatesController@editBlock');
    Route::post('/deleteBlock', 'CloseDatesController@deleteBlock');
    Route::post('/getInfoBlock', 'CloseDatesController@getInfoBlock');


    //promocode
    Route::get(
        '/getAllPromoCode',
        'PromocodeController@getAllPromoCode'
    );
    Route::post(
        '/getAllToursPromocode',
        'PromocodeController@getAllToursPromocode'
    );
    Route::post('/addPromoCode', 'PromocodeController@addPromoCode');
    Route::post('/editPromoCode', 'PromocodeController@editPromoCode');
    Route::post('/deletePromoCode', 'PromocodeController@deletePromoCode');
    Route::post('/getInfoPromo', 'PromocodeController@getInfoPromo');

    //catalogo de icons
    Route::get(
        '/getAllIcon',
        'SuggestionController@getAllIcon'
    );
    Route::post('/addIcon', 'SuggestionController@addIcon');
    Route::post('/updateIcon', 'SuggestionController@updateIcon');
    Route::post('/deleteIcon', 'SuggestionController@deleteIcon');
    Route::post('/getIconInfo', 'SuggestionController@getIconInfo');


    //bannerHome
    Route::get('/getAll', 'BannerHomeController@getAll');
    Route::post('/addBannerHome', 'BannerHomeController@addBannerHome');
    Route::post('/editBannerHome', 'BannerHomeController@editBannerHome');
    Route::post('/deleteBannerHome', 'BannerHomeController@deleteBannerHome');



    Route::post('/export-tours', 'TourController@exportTours');
});

/*
//funcionamiento anterior

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('refresh-token', 'AuthController@refreshAccessToken');
    });
});
*/

/*
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
*/
