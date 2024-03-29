<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');

Route::post('/app/sistema/get/departamentos', 'AdminController@departamentos');
Route::post('/app/sistema/get/municipios', 'AdminController@municipios');

Route::get('/calendario', 'HomeController@calendario')->name('calendario');
Route::post('/notificaciones', 'HomeController@notificaciones')->name('notificaciones');
Route::post('/notificaciones/load', 'HomeController@cargar_notificaciones')->name('cargar_notificaciones');
Route::post('/get_procesos_for_day', 'HomeController@get_procesos_for_day');
Route::post('/get_procesos_for_type', 'HomeController@get_procesos_for_type');
Route::post('/get_terceros', 'HomeController@get_terceros');

// RUTAS PARA EL ChAT
Route::get('/chat', 'ChatController@index');
Route::get('/chat/crear', 'ChatController@crear');
Route::get('/chat/mensajes/{id}', 'ChatController@mensajes');
Route::post('/chat/mensajes/enviar', 'ChatController@enviar');

// Rutas para crear procesos
Route::get('/procesos/crear', 'ProcesosController@crear')->name('crear-proceso');
Route::post('/procesos/create', 'ProcesosController@create');
Route::get('/procesos/searh/{id}', 'ProcesosController@search');
Route::get('/procesos/ver/{id}', 'ProcesosController@ver')->name('ver-proceso');
Route::get('/procesos/eliminar/{id}', 'ProcesosController@delete');

Route::group(['middleware' => ['permission:general|penal|civil|familia|laboral|seguridad social|administrativo|universal']], function () {
    Route::post('/procesos/agregar_actuacion', 'ProcesosController@agregar_actuacion');
    Route::get('/buscar/{buscar}', 'ProcesosController@buscar');
    Route::get('/buscar', 'ProcesosController@buscar_view');
    Route::post('/procesos/update', 'ProcesosController@update');
    Route::post('/procesos/delete', 'ProcesosController@delete');
    Route::post('/procesos/delete_actuacion', 'ProcesosController@delete_actuacion');
    Route::post('/procesos/update_actuacion', 'ProcesosController@update_actuacion');
    Route::post('/procesos/update_actuacion_post', 'ProcesosController@update_actuacion_post');
    Route::post('/procesos/add-proceso', 'ProcesosController@add_proceso');
    Route::post('/procesos/agg_audiencia', 'ProcesosController@agg_audiencia');
    Route::post('/procesos/update_audiencia', 'ProcesosController@update_audiencia');
    Route::get('/procesos/searh/demandado/{identificacion}', 'ProcesosController@search_demandado');
    Route::get('/procesos/searh/abogado/{identificacion}', 'ProcesosController@search_abogado');
    Route::post('/procesos/agregar_demandado', 'ProcesosController@agregar_demandado');
    Route::post('/procesos/searh/detalle_proceso', 'ProcesosController@detalle_proceso');
    Route::post('/procesos/delete/detalle_proceso', 'ProcesosController@delete_detalle_proceso');
    Route::post('/procesos/searh/audiencia', 'ProcesosController@detalle_audiencia');
    Route::post('/procesos/delete/audiencia', 'ProcesosController@delete_detalle_audiencia');
    Route::get('/procesos/generar_informe/{id}', 'ProcesosController@generar_informe');
    Route::post('/procesos/juzgado', 'ProcesosController@juzgado');
    Route::post('/procesos/fiscalia', 'ProcesosController@fiscalia');
    Route::get('/procesos/ver/acceso/{id}', 'ProcesosController@acceso');
    Route::post('/procesos/agregar_acceso', 'ProcesosController@agregar_acceso');
    Route::post('/procesos/delete_acceso', 'ProcesosController@delete_acceso');
    Route::post('/procesos/agg_archivos', 'ProcesosController@agg_archivos');
    Route::post('/procesos/delete_archivos', 'ProcesosController@delete_archivos');
    Route::post('/procesos/actuaciones/archivos', 'ProcesosController@get_archivos_actuacion');
    Route::post('/procesos/actuaciones/archivos/add', 'ProcesosController@add_archivos_actuacion');
    Route::post('/procesos/actuaciones/archivos/delete', 'ProcesosController@delete_archivos_actuacion');
});

Route::get('/procesos/cosultas/inbox/{procesos_id}/{estado?}', 'EmailController@indexProceso')->name('consultas-proceso');
Route::get('/procesos/cosultas/create/{procesos_id}', 'EmailController@createFromProceso')->name('create-consultas-proceso');

// Rutas para Clientes
Route::get('/clientes', 'ClientesController@index')->name('clientes')->middleware('cliente');
Route::get('/clientes/ver/{id}', 'ClientesController@ver')->name('ver-cliente');
Route::get('/clientes/cosultas/inbox/{id}/{estado?}', 'EmailController@index')->name('consultas-cliente');
Route::get('/clientes/cosultas/create/{id}', 'EmailController@create')->name('create-consultas-cliente');
Route::post('/clientes/cosultas/store', 'EmailController@store');
Route::post('/clientes/cosultas/store_mensaje', 'EmailController@store_mensaje');
Route::post('/clientes/cosultas/get/media', 'EmailController@get_media');
Route::post('/clientes/cosultas/estado', 'EmailController@cambio_estado');
Route::get('/clientes/cosultas/activar/{id}', 'EmailController@reactivar_email');
Route::get('/clientes/cosultas/desactivar/{id}', 'EmailController@desactivar_email');
Route::get('/clientes/search/{search}', 'ClientesController@search');
Route::get('/clientes/ver/{id}/search', 'ClientesController@search_proceso');

Route::group(['middleware' => ['permission:general|universal']], function () {
    Route::get('/clientes/crear', 'ClientesController@crear');
    Route::post('/clientes/create', 'ClientesController@create');
    Route::post('/clientes/update', 'ClientesController@update');
    Route::post('/clientes/delete', 'ClientesController@delete');
    Route::post('/clientes/enviar_mensaje', 'ClientesController@enviar_mensaje');

    Route::post('/clientes/add-cedula', 'ClientesController@add_cedula');
    Route::post('/clientes/add-contrato', 'ClientesController@add_contrato');
});

// Rutas para Demandados
Route::group(['middleware' => ['permission:general|demandados|universal']], function () {
    Route::get('/demandados', 'DemandadosController@index')->name('demandados');
    Route::get('/demandados/ver/{id}', 'DemandadosController@ver')->name('ver-demandado');
    Route::post('/demandados/agregar_demandado', 'DemandadosController@agregar_demandado');
    Route::post('/demandados/delete', 'DemandadosController@delete');
    Route::post('/demandados/update', 'DemandadosController@update');
});

// Rutas para Consultas
Route::get('/consultas', 'ConsultasController@index')->name('consultas');
Route::get('/consultas/contestadas', 'ConsultasController@contestadas');
Route::get('/consultas/conversaciones', 'ConsultasController@contestadas');
Route::get('/consultas/ver/{id}', 'ConsultasController@ver')->name('ver-consulta');
Route::post('/consultas/responder', 'ConsultasController@responder');
Route::get('/consultas/correo/prueba', 'ConsultasController@prueba');

// Rutas para Administradores
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/administrador/usuarios', 'AdminController@usuarios')->name('usuarios');
    Route::post('/administrador/usuarios/create', 'AdminController@usuarios_create');
    Route::get('/administrador/documentacion_legal', 'AdminController@documentacion')->name('documentacion');
    Route::post('/administrador/agg_documentacion', 'AdminController@agg_documentacion')->name('agg_documentacion');
    Route::post('/administrador/delete_documento', 'AdminController@delete_documento');
    Route::get('/administrador/personal', 'AdminController@personal')->name('personal');
    Route::post('/administrador/crear_personal', 'AdminController@crearPersonal')->name('crear_personal');
    Route::post('/administrador/cargar_contratos_personal', 'AdminController@caragar_contratos');
    Route::post('/administrador/crear_contratos_personal', 'AdminController@crear_contrato');
    Route::post('/administrador/eliminar_contrato_personal', 'AdminController@eliminar_contrato');
    Route::post('/administrador/editar_contrato_personal', 'AdminController@editar_contrato');
    Route::post('/administrador/agg_otro_si', 'AdminController@agg_otro_si');
    Route::get('/administrador/otro_si/print/{id}', 'AdminController@print_otrosi');
    Route::get('/administrador/contrato/print/{id}', 'AdminController@print_contrato');
    Route::get('/administrador/certificado-laboral/print/{id}', 'AdminController@print_certificado');
    Route::post('/administrador/agg_documento', 'AdminController@agg_documento');
    Route::post('/administrador/eliminar_documento', 'AdminController@eliminar_documento');
    Route::post('/administrador/editar_documento', 'AdminController@editar_documento');
    Route::post('/administrador/cargar_documentos_all', 'AdminController@cargar_documentos_all');
    Route::post('/administrador/exportar_documentos', 'AdminController@exportar_documentos');
    Route::post('/administrador/cargar_documentos', 'AdminController@cargar_documentos');
    Route::get('/administrador/personal/ver/{id}', 'AdminController@ver_personal')->name('ver_personal');
    Route::post('/administrador/delete_personal', 'AdminController@delete_personal')->name('delete_personal');
    Route::get('/administrador/pagina-web', 'AdminController@pagina_web')->name('pagina-web');
    Route::post('/administrador/pagina-web', 'AdminController@pagina_web_update');
    Route::get('/administrador/sincronizacion', 'AdminController@sincronizacion')->name('sincronizacion');
    Route::get('/administrador/sincronizacion/{id}', 'AdminController@sincronizacion_ver');
    Route::get('/administrador/formatos', 'FormatosController@formatos')->name('formatos');
    Route::get('/administrador/get_formatos', 'FormatosController@getFormatos');
    Route::post('/administrador/agg_formatos', 'FormatosController@agg_formatos')->name('agg_formatos');
    Route::post('/administrador/delete_formatos', 'FormatosController@delete_formatos');
});

Route::post('/sincronizar-procesos', 'ProcesosController@sincronizar_procesos');
Route::get('/testjob', 'ProcesosController@sincronizar_procesos');

// Rutas Procesos
Route::get('/solicitudes-procesos', 'SolicitudesProcesosController@index');
