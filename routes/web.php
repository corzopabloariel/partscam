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

Route::get('/', 'page\GeneralController@index');
Route::get('empresa', ['uses' => 'page\GeneralController@empresa', 'as' => 'empresa']);
Route::get('contacto', ['uses' => 'page\GeneralController@contacto', 'as' => 'contacto']);

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function() {
    Route::get('/', 'adm\AdmController@index');

    /**
     * CONTENIDO
     */
    Route::group(['prefix' => 'contenido', 'as' => 'contenido'], function() {
        Route::get('{seccion}/index', ['uses' => 'adm\ContenidoController@index', 'as' => '.index']);
        Route::get('{seccion}/edit', ['uses' => 'adm\ContenidoController@edit', 'as' => '.edit']);
        Route::post('{seccion}/update', ['uses' => 'adm\ContenidoController@update', 'as' => 'update']);
    });
    /**
     * SLIDERS
     */
    Route::group(['prefix' => 'slider', 'as' => 'slider'], function() {
        Route::get('{seccion}/index', ['uses' => 'adm\SliderController@index', 'as' => '.index']);
        Route::post('{seccion}/store', ['uses' => 'adm\SliderController@store', 'as' => '.store']);
        Route::get('edit/{id}', ['uses' => 'adm\SliderController@edit', 'as' => '.edit']);
        Route::get('delete/{id}', ['uses' => 'adm\SliderController@destroy', 'as' => '.destroy']);
        Route::post('update/{id}', ['uses' => 'adm\SliderController@update', 'as' => 'update']);
    });
    
    /**
     * FAMILIAS
     */
    Route::group(['prefix' => 'familias', 'as' => 'familias'], function() {
        Route::get('index', ['uses' => 'adm\FamiliaController@index', 'as' => '.index']);
        Route::post('store', ['uses' => 'adm\FamiliaController@store', 'as' => '.store']);
        Route::get('edit/{id}', ['uses' => 'adm\FamiliaController@edit', 'as' => '.edit']);
        Route::get('delete/{id}', ['uses' => 'adm\FamiliaController@destroy', 'as' => '.destroy']);
        Route::post('update/{id}', ['uses' => 'adm\FamiliaController@update', 'as' => 'update']);

        Route::group(['prefix' => 'categorias', 'as' => '.categorias'], function() {
            Route::get('index', ['uses' => 'adm\CategoriaController@index', 'as' => '.index']);
            Route::post('store', ['uses' => 'adm\CategoriaController@store', 'as' => '.store']);
            Route::get('edit/{id}', ['uses' => 'adm\CategoriaController@edit', 'as' => '.edit']);
            Route::get('delete/{id}', ['uses' => 'adm\CategoriaController@destroy', 'as' => '.destroy']);
            Route::post('update/{id}', ['uses' => 'adm\CategoriaController@update', 'as' => 'update']);
            Route::get('familia_categoria/{id}', ['uses' => 'adm\CategoriaController@familia_categoria', 'as' => '.familia_categoria']);
            
            Route::group(['prefix' => 'productos', 'as' => '.productos'], function() {
                Route::get('index', ['uses' => 'adm\ProductoController@index', 'as' => '.index']);
                Route::post('store', ['uses' => 'adm\ProductoController@store', 'as' => '.store']);
                Route::get('edit/{id}', ['uses' => 'adm\ProductoController@edit', 'as' => '.edit']);
                Route::get('delete/{id}', ['uses' => 'adm\ProductoController@destroy', 'as' => '.destroy']);
                Route::post('update/{id}', ['uses' => 'adm\ProductoController@update', 'as' => 'update']);
            });
        });
    });
    /**
     * MARCAS
     */
    Route::group(['prefix' => 'marca', 'as' => 'marca'], function() {
        Route::get('index', ['uses' => 'adm\AdmController@marcas', 'as' => '.index']);
        Route::post('store', ['uses' => 'adm\AdmController@store', 'as' => '.store']);
        Route::get('edit/{id}', ['uses' => 'adm\AdmController@edit', 'as' => '.edit']);
        Route::get('delete/{id}', ['uses' => 'adm\AdmController@destroy', 'as' => '.destroy']);
        Route::post('update/{id}', ['uses' => 'adm\AdmController@update', 'as' => 'update']);
    });
    /**
     * DATOS
     */
    Route::group(['prefix' => 'empresa', 'as' => 'empresa'], function() {
        Route::get('datos', ['uses' => 'adm\EmpresaController@datos', 'as' => '.datos']);
        Route::post('update', ['uses' => 'adm\EmpresaController@update', 'as' => '.update']);

        Route::group(['prefix' => 'metadatos', 'as' => '.metadatos'], function() {
            Route::get('/', ['uses' => 'adm\MetadatosController@index', 'as' => '.index']);
            Route::get('edit/{page}', ['uses' => 'adm\MetadatosController@edit', 'as' => '.edit']);
            Route::post('update/{page}', ['uses' => 'adm\MetadatosController@update', 'as' => '.update']);
            Route::post('store', ['uses' => 'adm\MetadatosController@store', 'as' => '.store']);
            Route::get('delete/{page}', ['uses' => 'adm\MetadatosController@destroy', 'as' => '.destroy']);
        });
    });
});