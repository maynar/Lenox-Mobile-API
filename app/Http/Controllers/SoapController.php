<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Log;

class SoapController extends Controller
{
	// Este es el webservice que vamos a consumir
	public $wsdl = "http://mobile.sistemaslenox.com.ar:48980/MobileFichadasServiceSql.svc?wsdl";
	public $codigo = "5ZBquta2Pk9/h+Eq4B/5gA==";

	public function ObtenerCodigoAutenticacion(){
		$codAuth = [
			"codigoAutenticacion" => $this->codigo
		];
		return json_encode($codAuth);
	}

	public function show(Request $request){

    	$datos = json_decode($request->getContent(),true);
    	// Este es el webservice que vamos a consumir
		$wsdlp = 'http://www.dneonline.com/calculator.asmx?wsdl';

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($wsdlp);
		$resultado = $cliente->Add($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function ObtenerFichadasPorEmpresa(Request $request){

    	$datos = json_decode($request->getContent(),true);
    	
		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerFichadasPorEmpresa($datos);

		// Finalmente muestras la respuesta 
		$this->utf8_encode_deep($resultado);
		return response()->json($resultado); 
    }

    public function ObtenerDiasVacacionesPendientes(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerDiasVacacionesPendientes($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function ObtenerJornadasLaboralesPorEmpresaPorDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña
		
		Log::error("ObtenerJornadasLaboralesPorEmpresaPorDispositivo");
    	Log::error($datos);

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerJornadasLaboralesPorEmpresaPorDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function ObtenerNovedadesMobile(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña
		
		 Log::error("ObtenerNovedadesMobile");
    	 Log::error($datos);

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerNovedadesMobile($datos);
	//	Log::error("REQUEST:\n" . $cliente->__getLastRequest() . "\n");



		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function ObtenerMotivosFirmaNoConformeMobile(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerMotivosFirmaNoConformeMobile($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function ObtenerVacacionesPorEmpresa(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		 Log::error($datos);

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerVacacionesPorEmpresa($datos);
		 Log::error("listar");
	//	Log::error("\nDumping request headers:\n" . $cliente->__getLastRequestHeaders());
	//	Log::error("\nDumping request:\n" . $cliente->__getLastRequest());
	///	Log::error("\nDumping response headers:\n" . $cliente->__getLastResponseHeaders());
	//	Log::error("\nDumping response:\n" . $cliente->__getLastResponse());

    	  
		foreach ($resultado as $object) {

			$blog= @get_object_vars($object);
		     //Log::error($blog);

			$ar= get_object_vars($blog['vacaciones']);
			// var_dump($blog);
			if(count($ar)>0){
				// var_dump($blog['archivos']);
				if(is_array($ar['VacacionMobile'])){
					if(count($ar['VacacionMobile'])>0){
						// var_dump($ar['VacacionMobile']);
						foreach($ar['VacacionMobile'] as $key => $recibos){
							$rr = get_object_vars($recibos);
							
							
							// var_dump($recibos->Nombre);					
							if($rr['Archivo'] != null && $rr['Archivo']->FileContent){
						// 	// var_dump($recibos->FileContent);						
								Storage::disk('local')->put('\\public\\'.$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre, $rr['Archivo']->FileContent);
								$rr['Archivo']->FileContent = "";
								$rr['Archivo']->urlFile=url('/').'/storage/'.$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre;
								$rr['Archivo']->nameFile=$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre;
								$exp =  (explode("_",$rr['Archivo']->Nombre));
								$exp1 = end($exp);
								$nombreArchivo = (explode(".",$exp1));
								$rr['Archivo']->nombreArchivo=$nombreArchivo[0];
								$rr['Archivo']->img64= base64_encode($rr['Archivo']->urlFile);   

							}
						}
					}
				}else{
					// var_dump($ar['VacacionMobile']);
					$rr = get_object_vars($ar['VacacionMobile']);
					// $res = get_object_vars($rr);
					// var_dump($rr);
					if($rr['Archivo'] != null && $rr['Archivo']->FileContent){
					    //var_dump($rr['Archivo']->CodigoDispositivo);						
						 Storage::disk('local')->put('\\public\\'.$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre, $rr['Archivo']->FileContent);
						$rr['Archivo']->FileContent = "";
						$rr['Archivo']->urlFile=url('/').'/storage/'.$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre;
						$rr['Archivo']->nameFile=$rr['Archivo']->Id.'_'.$rr['Archivo']->Nombre;
						$exp =  (explode("_",$rr['Archivo']->Nombre));
						$exp1 = end($exp);
						$nombreArchivo = (explode(".",$exp1));
						$rr['Archivo']->nombreArchivo=$nombreArchivo[0];
						$rr['Archivo']->img64= base64_encode($rr['Archivo']->urlFile);


						
					}
				}

			}
			
			
		}
		
		
		// Finalmente muestras la respuesta 
		//$this->utf8_encode_deep($resultado);
		return response()->json($resultado); 
    }

    public function ObtenerArchivosPorDispositivo(Request $request){
        $datos = json_decode($request->getContent());

        // Creamos el cliente SOAP que hará la solicitud, generalmente están 
        // protegidos por un usuario y una contraseña

        $cliente = new \SoapClient($this->wsdl);
        $resultado = $cliente->ObtenerArchivosPorDispositivo($datos);
        
       	/*Log::error("\nDumping request headers:\n" . $cliente->__getLastRequestHeaders());
	    Log::error("\nDumping request:\n" . $cliente->__getLastRequest());
		Log::error("\nDumping response headers:\n" . $cliente->__getLastResponseHeaders());
		Log::error("\nDumping response:\n" . $cliente->__getLastResponse());*/
	
		// dd($resultado);
		// $res = @get_object_vars($resultado);
		// var_dump($res);
		
		foreach ($resultado as $object) {
			$blog= @get_object_vars($object);
			$ar = @get_object_vars($blog['archivos']);		// 	$namFile=time();
		// 	$url = $request->url();
						// $test= get_object_vars($blog['ObtenerArchivosPorDispositivoResult']);
			if(count($ar)>0){
				// var_dump($blog['archivos']);
				if(is_array($ar['ArchivoMobile'])){
					if(count($ar['ArchivoMobile'])>0){
						foreach($ar['ArchivoMobile'] as $key => $recibos){
							// var_dump($recibos->Nombre);					
							if($recibos->FileContent){
							// var_dump($recibos->FileContent);						
								Storage::disk('local')->put('\\public\\'.$recibos->Id.'_'.$recibos->Nombre, $recibos->FileContent);
								$recibos->FileContent = "";
								$recibos->urlFile=url('/').'/storage/'.$recibos->Id.'_'.$recibos->Nombre;
								$recibos->nameFile=$recibos->Id.'_'.$recibos->Nombre;
							}
						}
					}
				}else{
					if($ar['ArchivoMobile']->FileContent){
					// var_dump($ar['ArchivoMobile']->Nombre);						
						Storage::disk('local')->put('\\public\\'.$ar['ArchivoMobile']->Id.'_'.$ar['ArchivoMobile']->Nombre, $ar['ArchivoMobile']->FileContent);
						$ar['ArchivoMobile']->FileContent = "";
						$ar['ArchivoMobile']->urlFile=url('/').'/storage/'.$ar['ArchivoMobile']->Id.'_'.$ar['ArchivoMobile']->Nombre;
						$ar['ArchivoMobile']->nameFile=$ar['ArchivoMobile']->Id.'_'.$ar['ArchivoMobile']->Nombre;
					}
				}

			}
			
		}
		$this->utf8_encode_deep($resultado);
		return response()->json($resultado); 
    }

	public function ObtenerRecibosDeSueldosPorDispositivo(Request $request){
        $datos = json_decode($request->getContent());

        // Creamos el cliente SOAP que hará la solicitud, generalmente están 
        // protegidos por un usuario y una contraseña

        $cliente = new \SoapClient($this->wsdl);
        $resultado = $cliente->ObtenerRecibosDeSueldosPorDispositivo($datos);
		// dd($resultado);
		// var_dump($resultado);
		
		foreach ($resultado as $object) {
			$blog= @get_object_vars($object);
			$namFile=time();
			$url = $request->url();
			$test= get_object_vars($blog['recibosSueldos']);
		
			if(count($test)>0){
				foreach($blog['recibosSueldos']->ReciboSueldoMobile as $key => $recibos){
					// var_dump($recibos->Archivo);					
					if($recibos->Archivo){					
						Storage::disk('local')->put('\\public\\'.$recibos->Id.'_'.$recibos->Archivo->Nombre, $recibos->Archivo->FileContent);
						$recibos->Archivo->FileContent = "";
						$recibos->urlFile=url('/').'/storage/'.$recibos->Id.'_'.$recibos->Archivo->Nombre;
						$recibos->nameFile=$recibos->Id.'_'.$recibos->Archivo->Nombre;
					}
				}
			}
			
		}
		$this->utf8_encode_deep($resultado);
		return response()->json($resultado); 
    }
    
    function utf8_encode_deep(&$input) {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                self::utf8_encode_deep($value);
            }
    
            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));
    
            foreach ($vars as $var) {
                self::utf8_encode_deep($input->$var);
            }
        }
    }

    public function GuardarFichada(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		 Log::error("GuardarFichada");
		 Log::error($datos);


		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->GuardarFichada($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function BorrarFichadasHuerfanas(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->BorrarFichadasHuerfanas($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function CambiarEstadoDeReciboDeSueldo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->CambiarEstadoDeReciboDeSueldo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function CambiarEstadoDeArchivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->CambiarEstadoDeArchivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function ConfirmarRegistracionDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ConfirmarRegistracionDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function DescargarDispositivosPorEmpresa(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->DescargarDispositivosPorEmpresa($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function DescargarFichadasPorDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->DescargarFichadasPorDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function DescargarFichadasPorEmpresa(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->DescargarFichadasPorEmpresa($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function DesregistrarDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);
		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->DesregistrarDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function GuardarArchivo(Request $request){
    	$r = json_decode($request->getContent(),true);
    	$datos = [
			"codigoDispositivo"=> $r['codigoDispositivo'],
		    "codigoArchivoTipo"=>$r['codigoArchivoTipo'],
		    "nombre"=>$r['nombre'],
		    "estado"=>$r['estado'],
		    "fileContent"=>base64_decode($r['archivo']),
		    "codigoAutenticacion" => $r['codigoAutenticacion']
    	];
		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->GuardarArchivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function GuardarLicencia(Request $request){
    	$r = json_decode($request->getContent(),true);
    	$nombre_foto = "";
    	$archivo = "";
    	if($r['nombreArchivo']){
    		$nombre_foto=$r['nombreArchivo'].'.png';
    	}
    	 if($r['archivo']){
    		$archivo=base64_decode($r['archivo']);
    	}

    	$datos = [
			"codigoDispositivo"=> $r['codigoDispositivo'],
			"fechaDesdeStr"=>$r['fechaDesdeStr'],
		    "fechaHastaStr"=>$r['fechaHastaStr'],
		    "comentarioEmpleado"=>$r['comentarioEmpleado'],
		    "novedad"=>$r['novedad'],
		    "codigoArchivoTipo"=>"CERTIFICADO",
		    "nombreArchivo"=>$nombre_foto,
		    "fileContent"=>$archivo,
		    "codigoAutenticacion" => $r['codigoAutenticacion']
    	];
    	  Log::error("creando");
    	  Log::error($datos);
    	// $namFile=time();
    	// Storage::disk('local')->put($namFile.'.png', $datos['fileContent']);
    	// Mail::send('Mail.activate',$datos, function($msj){
     //            $msj->subject('Activación de cuenta');
     //            $msj->to('edgaralexander1008@gmail.com');
     //    });
    	// var_dump($datos);
		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->GuardarLicencia($datos);
    	 
    	Log::error(json_encode($resultado));

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
		// dd(base64_decode($r['fileContent']));
    }
    public function ActualizarLicencia(Request $request){
    	$r = json_decode($request->getContent(),true);
    	
    	
    	$nombre_foto = "";
    	$archivo = "";
    	if($r['nombreArchivo']){
    		$nombre_foto=$r['nombreArchivo'].'.png';
    	}
    	 if($r['archivo']){
    		$archivo=base64_decode($r['archivo']);
    	}
    	
    	  
    
    	$datos = [
    		"idLicencia"=>$r['idLicencia'],
			"codigoDispositivo"=> $r['codigoDispositivo'],
			"fechaDesdeStr"=>$r['fechaDesdeStr'],
		    "fechaHastaStr"=>$r['fechaHastaStr'],
		    "comentarioEmpleado"=>$r['comentarioEmpleado'],
		    "novedad"=>$r['novedad'],
		    "codigoArchivoTipo"=>"CERTIFICADO",
		    "nombreArchivo"=>$nombre_foto,
		    "name"=>$nombre_foto,
		    "fileContent"=>$archivo,
		    "codigoAutenticacion" => $r['codigoAutenticacion']
    	];
    	  Log::error("ActualizarLicencia");

    	  Log::error($datos);

    	    	    	

    	
    	
    	// $namFile=time();
    	// Storage::disk('local')->put($namFile.'.png', $datos['fileContent']);
    	// Mail::send('Mail.activate',$datos, function($msj){
     //            $msj->subject('Activación de cuenta');
     //            $msj->to('edgaralexander1008@gmail.com');
     //    });
    	// var_dump($datos);
		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ActualizarLicencia($datos);
		

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
		// dd(base64_decode($r['fileContent']));
    }
    public function GuardarReciboDeSueldo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->GuardarReciboDeSueldo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function GuardarVacacion(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->GuardarVacacion($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function ActualizarVacacion(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ActualizarVacacion($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function MarcarFichadasComoDescargadas(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->MarcarFichadasComoDescargadas($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function MarcarVacacionesLicenciasComoDescargadas(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->MarcarVacacionesLicenciasComoDescargadas($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function ObtenerFechaVencimiento(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerFechaVencimiento($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function RegistrarDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña
		Log::error("qr");
    	Log::error($datos);
		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->RegistrarDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function RegistrarDispositivoRemoto(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->RegistrarDispositivoRemoto($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function RegistrarEmpresa(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->RegistrarEmpresa($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function RegistrarEmpresaMasDatos(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->RegistrarEmpresaMasDatos($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function RenovarLicencia(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->RenovarLicencia($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function UploadFile(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->UploadFile($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    public function VerificarRegistracionDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->VerificarRegistracionDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

    public function ObtenerNotificacionesPorDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña
		
	    Log::error("Notificacion");


		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->ObtenerNotificacionesPorDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    
    public function ObtenerDispositivo(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña
		
	    Log::error("ObtenerDispositivo");
	    Log::error($datos);


		$cliente = new \SoapClient($this->wsdl, array('cache_wsdl' => WSDL_CACHE_NONE));
		$resultado = $cliente->ObtenerDispositivo($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }
    
    
    
    
    
    public function MarcarNotificacionComoLeida(Request $request){
    	$datos = json_decode($request->getContent(),true);

		// Creamos el cliente SOAP que hará la solicitud, generalmente están 
		// protegidos por un usuario y una contraseña

		$cliente = new \SoapClient($this->wsdl);
		$resultado = $cliente->MarcarNotificacionComoLeida($datos);

		// Finalmente muestras la respuesta 
		return json_encode($resultado);
    }

}
