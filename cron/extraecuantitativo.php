<?php

header('Content-Type: text/html; charset=utf-8');

# Se define la zona horaria que corresponda
date_default_timezone_set('Europe/Madrid');

# Se define las locales para que saque mes, tres letras, en castellano
setlocale(LC_ALL, 'es_ES');

# Importamos librería para parsear
require 'simple_html_dom.php';

# En el día actual proveen los datos del día anterior
$epocaunix = strtotime('-1 day');
$ano = date('y', $epocaunix);
$mes = date('m', $epocaunix);
$dia = date('d', $epocaunix);
$mesletras = strtolower(strftime('%b', $epocaunix));
	
echo $ano.$mes.$dia.$mesletras;

#####$urljun = 'http://juntadeandalucia.es/medioambiente/atmosfera/informes_siva/'. $mesletras.$ano .'/nhu'. $ano.$mes.$dia .'.htm';	
$urljun = 'datos.html';
	
echo $urljun;
	
$html = file_get_html($urljun);

# El script se ejecuta a las 9 de la mañana, si en esa hora no hay se hacen intentos(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23)
if (!is_object($html)) {
# Esperamos 1 hora para hacer la siguiente comprobación
	sleep(3600);
	$i = 0;
	while($i < 2) {
		$html = file_get_html($url);
		if (is_object($html)) {
			break;
        }
		else {
			echo "no en línea". date("h:i:s");
			sleep(3600);
		}
		$i++;
	}
}

# variable de control para armar geojson o no
$control = 0;

function parsearTablas($estacion, $posicion) {
	global $ano;
	global $mes;
	global $dia;
	global $conexion;
	global $html;
	global $urljun;
	global $control;
	global $geojson;
	$tabla = $html->find('table', $posicion)->find('tr');
	echo $estacion;
	$ultima = count($tabla) - 1;
	echo "La última es: ". $ultima .".......";
	$o = 0;
	foreach($tabla as $row) {
		$tds = $row->find('td');
		if ( $o > 0 && $o < $ultima ) {
	                $tds = $row->find('td');
			$hora = $tds[0]->innertext;
			$estado = $tds[1]->innertext;
			echo $o;
                        $hora = mysqli_real_escape_string($conexion, $hora);
                        $estado = mysqli_real_escape_string($conexion, $estado);
                        $sentencia = "INSERT INTO cuantitativos(estacion,hora,estado,fecha) VALUES('". $estacion ."','". $hora ."','". $estado ."', '". $ano ."-". $mes ."-". $dia ."')";
                        mysqli_query($conexion,$sentencia);
		}
		$o++;
	}

	
	
	# Buscando el pico de so2 y la hora en que se dio
	$maximo = "SELECT hora, estado FROM cuantitativos ORDER BY estado DESC LIMIT 1";
	$sentenciamaximo = mysqli_query($conexion,$maximo);
	$valormaximo = mysqli_fetch_array($sentenciamaximo, MYSQLI_NUM);
	mysqli_free_result($sentenciamaximo);
	if ($valormaximo[1] >= 20 && $valormaximo[1] != "") {
		# si el control está a 0 iniciamos por primera vez el array geojson.
		if ($control == 0) {
			$geojson = array(
				'type'      => 'FeatureCollection',
				'features'  => array()
			);
			$control = 1;			
		}
		
		print_r($geojson);
		
		echo "control vale ............... $control";
		
		# Sacamos latitud y longitud de la estación
		$posicion = "SELECT latitud, longitud FROM estaciones WHERE nombre='". $estacion ."'";
		$sentenciaposicion = mysqli_query($conexion, $posicion);
		$posiciones = mysqli_fetch_array($sentenciaposicion, MYSQLI_NUM);
		mysqli_free_result($sentenciaposicion);
		
		
		#Comenzamos el array de propiedades
		$propiedades = array(
			'estacion' => $estacion,
			'so2' => $valormaximo[1],
			'hora' => $valormaximo[0],
			'peligro' => NULL,
			'serepite' => NULL,
			'laliga' => $urljun
		);
		
		
		# peligro array 0 o 1
		if ($valormaximo[1] >= 125) {
			$propiedades['peligro'] = 1;
		}
		else {
			$propiedades['peligro'] = 0;
		}
		
		$repeticiones = "SELECT COUNT(*) FROM cuantitativos WHERE estado=$valormaximo[1]";
		$sentenciarepeticiones = mysqli_query($conexion,$repeticiones);
		$serepite = mysqli_fetch_array($sentenciarepeticiones, MYSQLI_NUM);
		mysqli_free_result($sentenciarepeticiones);
		
		# serepite 0 o número de repeticiones
		if ($serepite[0] > 1 && $serepite[0] != "") {
			$propiedades['serepite'] = $serepite[0];
		}
		else {
			$propiedades['serepite'] = 0;
		}
		
		print_r($propiedades);
		
		
		$feature = array(
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => array(
					$posiciones[0],
					$posiciones[1]
				)
			),
			'properties' => $propiedades
		);
    

    
		# añadir al array geojson.
		array_push($geojson['features'], $feature);




	}

	$borrartabla = "DELETE FROM cuantitativos";
	$sentenciaborrartabla = mysqli_query($conexion,$borrartabla);

}



$conexion = mysqli_connect('localhost','*****','*********','gaseame');



if ($conexion) {
	parsearTablas('La Rábida',14);
	#parsearTablas('Mazagón',34);
	parsearTablas('Marismas del Titán',6);
	parsearTablas('Pozo Dulce',8);
	parsearTablas('Los Rosales',4);
	parsearTablas('La Orden',2);
	parsearTablas('Campus del Carmen',26);
	parsearTablas('Palos', 16);
	parsearTablas('Punta Umbría', 18);
	parsearTablas('San Juan del Puerto', 20);
}

	




mysqli_close($conexion);

# Aquí construimos el archivo geojson.

if (isset($geojson)) {

		print_r($geojson);

		#si ya existe el archivo se mueve a pasado.json
		$presente = '../www/presente.json';
		if (file_exists($presente)) {
			#renombrar a pasado.json
			$pasado = '../www/pasado.json';
			rename($presente, $pasado);
		}
		
		$geojson = json_encode($geojson, JSON_NUMERIC_CHECK);
		file_put_contents($presente, $geojson);
		
}

# gaseame: 
## cuantitativos: id, estacion, hora, estado, fecha
## estaciones: id, nombre, latitud, longitud

?>

