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

Route::get('/', 'page\GeneralController@index')->name('index');
Route::get('empresa', ['uses' => 'page\GeneralController@empresa', 'as' => 'empresa']);
Route::get('contacto', ['uses' => 'page\GeneralController@contacto', 'as' => 'contacto']);

Route::post('buscador/{tipo}', ['uses' => 'page\GeneralController@buscador', 'as' => 'buscador']);
Route::get('carrito', ['uses' => 'page\GeneralController@carrito', 'as' => 'carrito']);
Route::get('confirmar/{tipo}', ['uses' => 'page\GeneralController@confirmar', 'as' => 'confirmar']);
Route::post('order',['uses' => 'page\GeneralController@order', 'as' => 'order']);
Route::group(['prefix' => 'productos', 'as' => 'productos'], function() {
    Route::get('/', ['uses' => 'page\GeneralController@productos', 'as' => 'productos']);
    Route::get('ofertas', ['uses' => 'page\GeneralController@ofertas', 'as' => 'ofertas']);
    Route::get('familia/{id}/{order?}', ['uses' => 'page\GeneralController@familia', 'as' => '.familia']);
    Route::get('familia/{familia_id}/modelo/{modelo_id}/{tipo}/{order?}', ['uses' => 'page\GeneralController@modelo', 'as' => '.modelo']);
    Route::get('familia/{familia_id}/modelo/{modelo_id}/categoria/{categoria_id}/{tipo}/{order?}', ['uses' => 'page\GeneralController@categoria', 'as' => '.categoria']);
    Route::get('familia/{familia_id}/modelo/{modelo_id}/categoria/{categoria_id}/subcategoria/{scategoria_id}/{tipo}/{order?}', ['uses' => 'page\GeneralController@scategoria', 'as' => '.scategoria']);
    //Route::get('categoria/{modelo_id}/{id}', ['uses' => 'page\GeneralController@categoria', 'as' => '.categoria']);
    Route::get('producto/{id}/{modelo_id?}', ['uses' => 'page\GeneralController@producto', 'as' => '.producto']);
    Route::get('show/{id}', ['uses' => 'adm\ProductoController@show', 'as' => 'show']);
});
Route::get('pagos', ['uses' => 'page\CosasController@pagos', 'as' => 'pagos']);
Route::get('terminos', ['uses' => 'page\CosasController@terminos', 'as' => 'terminos']);
Route::get('localidad/{provincia_id}', ['uses' => 'page\GeneralController@localidad', 'as' => 'localidad']);
Route::get('persona/{tipo}/{value}', ['uses' => 'page\GeneralController@persona', 'as' => 'persona']);
Route::get('p', ['uses' => 'page\GeneralController@getCreatePreference', 'as' => 'getCreatePreference']);
Route::get('pedido/{tipo}', ['uses' => 'page\GeneralController@pedido', 'as' => 'pedido']);
Route::post('form/{seccion}', ['uses' => 'page\FormController@index', 'as' => 'form']);

Route::get('payment-success', 'page\PaymentController@success')->name('payment.success');
Route::get('payment-failure', 'page\PaymentController@failure')->name('payment.failure');
Route::get('payment-pending', 'page\PaymentController@pending')->name('payment.pending');

Route::get('ipn', 'page\PaymentController@ipn')->name('ipn');

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function() {
    Route::get('/', 'adm\AdmController@index');
    Route::get('logout', ['uses' => 'adm\AdmController@logout' , 'as' => 'adm.logout']);

    Route::match(['get', 'post'], 'familias/categorias/productos/index',['uses' => 'adm\ProductoController@index'])->name('productoIndex');

    Route::get('compras', ['uses' => 'adm\ProductoController@compras' , 'as' => 'compras']);
    Route::get('transaccion/{id}', ['uses' => 'adm\ProductoController@transaccion' , 'as' => 'transaccion']);
    Route::get('transaccion/estado/{id}/{estado}', ['uses' => 'adm\ProductoController@transaccionEstado' , 'as' => 'transaccionEstado']);
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
        //Route::get('sin', ['uses' => 'adm\FamiliaController@sin', 'as' => '.sin']);

        Route::match(['get', 'post'], '/sin', ['uses' => 'adm\FamiliaController@sin', 'as' => '.sin']);

        Route::group(['prefix' => 'modelos', 'as' => '.modelos'], function() {
            Route::get('index', ['uses' => 'adm\ModelosController@index', 'as' => '.index']);
            Route::post('store', ['uses' => 'adm\ModelosController@store', 'as' => '.store']);
            Route::get('edit/{id}', ['uses' => 'adm\ModelosController@edit', 'as' => '.edit']);
            Route::get('delete/{id}', ['uses' => 'adm\ModelosController@destroy', 'as' => '.destroy']);
            Route::post('update/{id}', ['uses' => 'adm\ModelosController@update', 'as' => 'update']);
        });
        
        Route::get('carga', ['uses' => 'adm\ProductoController@carga', 'as' => '.carga']);
        Route::post('carga', ['uses' => 'adm\CargaController@index', 'as' => '.carga']);

        Route::group(['prefix' => 'categorias', 'as' => '.categorias'], function() {
            Route::get('index', ['uses' => 'adm\CategoriaController@index', 'as' => '.index']);
            Route::post('store', ['uses' => 'adm\CategoriaController@store', 'as' => '.store']);
            Route::get('edit/{id}', ['uses' => 'adm\CategoriaController@edit', 'as' => '.edit']);
            Route::get('show/{id}/{tipo}', ['uses' => 'adm\CategoriaController@show', 'as' => '.show']);
            Route::get('delete/{id}', ['uses' => 'adm\CategoriaController@destroy', 'as' => '.destroy']);
            Route::post('update/{id}', ['uses' => 'adm\CategoriaController@update', 'as' => 'update']);
            Route::get('familia/{id}', ['uses' => 'adm\CategoriaController@familia', 'as' => '.familia']);
            Route::get('categoria/{id}', ['uses' => 'adm\CategoriaController@categoria', 'as' => '.categoria']);
            
            Route::group(['prefix' => 'subcategorias', 'as' => '.subcategorias'], function() {
                Route::get('index', ['uses' => 'adm\SubcategoriaController@index', 'as' => '.index']);
                Route::post('store', ['uses' => 'adm\SubcategoriaController@store', 'as' => '.store']);
                Route::get('edit/{id}', ['uses' => 'adm\SubcategoriaController@edit', 'as' => '.edit']);
                Route::get('delete/{id}', ['uses' => 'adm\SubcategoriaController@destroy', 'as' => '.destroy']);
                Route::post('update/{id}', ['uses' => 'adm\SubcategoriaController@update', 'as' => 'update']);
            });

            Route::group(['prefix' => 'productos', 'as' => '.productos'], function() {
                Route::post('store', ['uses' => 'adm\ProductoController@store', 'as' => '.store']);
                Route::get('edit/{id}', ['uses' => 'adm\ProductoController@edit', 'as' => '.edit']);
                Route::get('familia_categoria/{familia_id}/{tipo}', ['uses' => 'adm\ProductoController@familia_categoria', 'as' => '.familia_categoria']);
                Route::get('delete/{id}', ['uses' => 'adm\ProductoController@destroy', 'as' => '.destroy']);
                
                Route::get('select', ['uses' => 'adm\ProductoController@select', 'as' => 'select']);
                Route::post('update/{id}', ['uses' => 'adm\ProductoController@update', 'as' => 'update']);
                Route::post('updateModal/{id}', ['uses' => 'adm\ProductoController@updateModal', 'as' => 'updateModal']);
            });

            Route::group(['prefix' => 'ofertas', 'as' => '.ofertas'], function() {
                Route::get('index', ['uses' => 'adm\OfertaController@index', 'as' => '.index']);
                Route::post('store', ['uses' => 'adm\OfertaController@store', 'as' => '.store']);
                Route::get('edit/{id}', ['uses' => 'adm\OfertaController@edit', 'as' => '.edit']);
                Route::get('delete/{id}', ['uses' => 'adm\OfertaController@destroy', 'as' => '.destroy']);
                Route::post('update/{id}', ['uses' => 'adm\OfertaController@update', 'as' => 'update']);
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
        Route::get('terminos', ['uses' => 'adm\EmpresaController@terminos', 'as' => '.terminos']);
        Route::post('update', ['uses' => 'adm\EmpresaController@update', 'as' => '.update']);

        Route::group(['prefix' => 'metadatos', 'as' => '.metadatos'], function() {
            Route::get('/', ['uses' => 'adm\MetadatosController@index', 'as' => '.index']);
            Route::get('edit/{page}', ['uses' => 'adm\MetadatosController@edit', 'as' => '.edit']);
            Route::post('update/{page}', ['uses' => 'adm\MetadatosController@update', 'as' => '.update']);
            Route::post('store', ['uses' => 'adm\MetadatosController@store', 'as' => '.store']);
            Route::get('delete/{page}', ['uses' => 'adm\MetadatosController@destroy', 'as' => '.destroy']);
        });
        Route::group(['prefix' => 'usuarios', 'as' => '.usuarios'], function() {
            Route::get('/', ['uses' => 'adm\UserController@index', 'as' => '.index']);
            Route::get('datos', ['uses' => 'adm\UserController@datos', 'as' => '.datos']);
            Route::get('edit/{id}', ['uses' => 'adm\UserController@edit', 'as' => '.edit']);
            Route::post('update/{id}', ['uses' => 'adm\UserController@update', 'as' => '.update']);
            Route::post('store', ['uses' => 'adm\UserController@store', 'as' => '.store']);
            Route::get('delete/{id}', ['uses' => 'adm\UserController@destroy', 'as' => '.destroy']);
        });
    });
});