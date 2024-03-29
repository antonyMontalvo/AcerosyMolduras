<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Rutas iniciales */
Route::get('/login', ['as' => 'login', 'uses' => 'AdminController@index']);
Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/signin', ['as' => 'signin', 'uses' => 'AdminController@signin']);


/* Puesto aquí para que no de problemas con el session, por ahora. */

Route::group(['middleware' => ['session']], function () {
    Route::get('/logout', ['as' => 'logout', 'uses' => 'AdminController@logout']); //cerrar sesión
    Route::get('/home', ['as' => 'home', 'uses' => 'AdminController@home']);
    
    // Almacen
    Route::group(['prefix' => 'store_house'], function () {
        Route::get('/', ['as' => 'store_house.index', 'uses' => 'StoreHouseController@index']);
        Route::get('/create', ['as' => 'store_house.create', 'uses' => 'StoreHouseController@create']);
        Route::post('/', ['as' => 'store_house.store', 'uses' => 'StoreHouseController@store']);
        Route::get('/{id}/edit', ['as' => 'store_house.edit', 'uses' => 'StoreHouseController@edit']);
        Route::put('/', ['as' => 'store_house.update', 'uses' => 'StoreHouseController@update']);
        Route::delete('/destroy', ['as' => 'store_house.destroy', 'uses' => 'StoreHouseController@destroy']);
        Route::post('/info', ['as' => 'store_house.info', 'uses' => 'StoreHouseController@info']);
        Route::post('/products', ['as' => 'store_house.products', 'uses' => 'StoreHouseController@productsByWarehouse']);
        Route::get('/catalog', ['as' => 'store_house.index.catalog', 'uses' => 'StoreHouseController@indexPdf']);
        Route::post('/catalog', ['as' => 'store_house.catalog', 'uses' => 'StoreHouseController@generatePdf']);
        Route::post('/date', ['as' => 'store_house.date', 'uses' => 'StoreHouseController@getProductsByDate']);
    }); 
    
    //Proveedor
    Route::group(['prefix' => 'provider'], function () {
        Route::get('/{email}', ['as' => 'provider.show', 'uses' => 'ProviderController@show']);
        Route::post('/store', ['as' => 'provider.store', 'uses' => 'ProviderController@store']);
    }); 
    
    // Productos
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', ['as' => 'product.index', 'uses' => 'ProductController@index']);
        Route::get('/create', ['as' => 'product.create', 'uses' => 'ProductController@create']);
        Route::post('/save', ['as' => 'product.save', 'uses' => 'ProductController@save']);
        Route::get('/{id}', ['as' => 'product.show', 'uses' => 'ProductController@show']); //info
        Route::post('/store', ['as' => 'product.store', 'uses' => 'ProductController@store']);
        Route::put('/edit', ['as' => 'product.edit', 'uses' => 'ProductController@edit']);
        Route::get('/{id}/update', ['as' => 'product.update', 'uses' => 'ProductController@update']);
        Route::delete('/{id}', ['as' => 'product.destroy', 'uses' => 'ProductController@destroy']);
    });

    //Sale
    Route::group(['prefix' => 'sale'], function () {
        Route::get('/', ['as' => 'sale.index', 'uses' => 'SaleController@index']);
        Route::get('/create', ['as' => 'sale.create', 'uses' => 'SaleController@create']);
        Route::post('/', ['as' => 'sale.store', 'uses' => 'SaleController@store']);
        Route::get('/{id}', ['as' => 'sale.show', 'uses' => 'SaleController@show']); //info
        Route::delete('/{id}', ['as' => 'sale.destroy', 'uses' => 'SaleController@destroy']);
        Route::post('/provinces', ['as' => 'sale.provinces', 'uses' => 'SaleController@getProvinces']);
        Route::post('/districts', ['as' => 'sale.districts', 'uses' => 'SaleController@getDistricts']);
        Route::post('/products', ['as' => 'sale.products', 'uses' => 'SaleController@getProducts']);
    });

    //Buy
    Route::group(['prefix' => 'buy'], function () {
        Route::get('/', ['as' => 'buy.index', 'uses' => 'BuyController@index']);
        Route::get('/create', ['as' => 'buy.create', 'uses' => 'BuyController@create']);
        Route::post('/', ['as' => 'buy.store', 'uses' => 'BuyController@store']);
        Route::get('/{id}', ['as' => 'buy.show', 'uses' => 'BuyController@show']); //info
        Route::delete('/{id}', ['as' => 'buy.destroy', 'uses' => 'BuyController@destroy']);
        Route::post('/products', ['as' => 'buy.products', 'uses' => 'BuyController@getProducts']);
    });

    //Quotation 
    Route::group(['prefix' => 'quotation'], function () {
        Route::get('/', ['as' => 'quotation.index', 'uses' => 'QuotationController@index']);
        Route::get('/{id}', ['as' => 'quotation.show', 'uses' => 'QuotationController@show']);
    }); 


    /* Cualquier ruta externa no funciona */
    Route::fallback(function () {
        return view('error/notFound');
    });
});