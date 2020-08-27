<?php

use Illuminate\Http\Request;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/
Route::post('v1/users/auth','UserController@auth')->middleware('cors');
 Route::post('v1/auth/users/create','UserController@create');
  Route::post('v1/auth/users/update','UserController@update');
    Route::delete('v1/auth/users/delete/{idUser}','UserController@delete');


Route::group(['middleware' => ['cors']], function () {// Todo lo que esta adentro de este middleware requeire auteticacion

    Route::post('suma', 'SoapController@show');
	Route::get('ObtenerCodigoAutenticacion', 'SoapController@ObtenerCodigoAutenticacion');
	Route::post('ObtenerDiasVacacionesPendientes', 'SoapController@ObtenerDiasVacacionesPendientes');
	Route::post('ObtenerFichadasPorEmpresa', 'SoapController@ObtenerFichadasPorEmpresa');
	Route::post('ObtenerJornadasLaboralesPorEmpresaPorDispositivo', 'SoapController@ObtenerJornadasLaboralesPorEmpresaPorDispositivo');
	Route::post('ObtenerNovedadesMobile', 'SoapController@ObtenerNovedadesMobile');
	Route::post('ObtenerMotivosFirmaNoConformeMobile', 'SoapController@ObtenerMotivosFirmaNoConformeMobile');
	Route::post('ObtenerNotificacionesPorDispositivo', 'SoapController@ObtenerNotificacionesPorDispositivo');
	Route::post('MarcarNotificacionComoLeida', 'SoapController@MarcarNotificacionComoLeida');
	Route::post('ObtenerVacacionesPorEmpresa', 'SoapController@ObtenerVacacionesPorEmpresa');
	Route::post('ObtenerArchivosPorDispositivo', 'SoapController@ObtenerArchivosPorDispositivo');
	Route::post('ObtenerRecibosDeSueldosPorDispositivo', 'SoapController@ObtenerRecibosDeSueldosPorDispositivo');
	Route::post('GuardarFichada', 'SoapController@GuardarFichada');
	Route::post('BorrarFichadasHuerfanas', 'SoapController@BorrarFichadasHuerfanas');
	Route::post('CambiarEstadoDeReciboDeSueldo', 'SoapController@CambiarEstadoDeReciboDeSueldo');
	Route::post('CambiarEstadoDeArchivo', 'SoapController@CambiarEstadoDeArchivo');
	Route::post('ConfirmarRegistracionDispositivo', 'SoapController@ConfirmarRegistracionDispositivo');
	Route::post('DescargarDispositivosPorEmpresa', 'SoapController@DescargarDispositivosPorEmpresa');
	Route::post('DescargarFichadasPorDispositivo', 'SoapController@DescargarFichadasPorDispositivo');
	Route::post('DescargarFichadasPorEmpresa', 'SoapController@DescargarFichadasPorEmpresa');
	Route::post('DesregistrarDispositivo', 'SoapController@DesregistrarDispositivo');
	Route::post('GuardarArchivo', 'SoapController@GuardarArchivo');
	Route::post('GuardarLicencia', 'SoapController@GuardarLicencia');
	Route::post('ActualizarLicencia', 'SoapController@ActualizarLicencia');
	Route::post('GuardarReciboDeSueldo', 'SoapController@GuardarReciboDeSueldo');
	Route::post('GuardarVacacion', 'SoapController@GuardarVacacion');
	Route::post('ActualizarVacacion', 'SoapController@ActualizarVacacion');
	Route::post('MarcarFichadasComoDescargadas', 'SoapController@MarcarFichadasComoDescargadas');
	Route::post('MarcarVacacionesLicenciasComoDescargadas', 'SoapController@MarcarVacacionesLicenciasComoDescargadas');
	Route::post('ObtenerFechaVencimiento', 'SoapController@ObtenerFechaVencimiento');
	Route::post('RegistrarDispositivo', 'SoapController@RegistrarDispositivo');
	Route::post('RegistrarDispositivoRemoto', 'SoapController@RegistrarDispositivoRemoto');
	Route::post('RegistrarEmpresa', 'SoapController@RegistrarEmpresa');
	Route::post('RegistrarEmpresaMasDatos', 'SoapController@RegistrarEmpresaMasDatos');
	Route::post('RenovarLicencia', 'SoapController@RenovarLicencia');
	Route::post('UploadFile', 'SoapController@UploadFile');
	Route::post('VerificarRegistracionDispositivo', 'SoapController@VerificarRegistracionDispositivo');
	Route::post('ObtenerDispositivo', 'SoapController@ObtenerDispositivo');
	Route::post('Login', 'SoapController@Login');
	Route::post('ObtenerDatosPersonales', 'SoapController@ObtenerDatosPersonales');



});
