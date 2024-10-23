<?php
require '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$rut = $_POST['rut'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$tiempo = $_POST['tiempo'] ?? '';
$monto = $_POST['monto'] ?? '';
$abrir = $_POST['abrir'] ?? '';
$leer = $_POST['leer'] ?? '';
$escribir = $_POST['escribir'] ?? '';
$cerrar = $_POST['cerrar'] ?? '';
$exportar = $_POST['exportar'] ?? '';

$user = "root";
$password = '';
$bd = "arrendatarios";
$host = "localhost";
$mysqli = new mysqli($host, $user, $password, $bd);

if ($mysqli->connect_error) {
    echo "No se pudo realizar la conexión con el servidor: " . $mysqli->connect_error;
} else {
    echo "Conexión exitosa<br>";
}

if ($abrir === 'abrir') {
    $fp = fopen("arrendatarios.xml", "r");
    if ($fp) {
        echo "Archivo abierto<br>";
    } else {
        echo "No se pudo abrir el archivo<br>";
    }
} elseif ($leer === 'leer') {
    $arrendatarios = simplexml_load_file("arrendatarios.xml");
    foreach ($arrendatarios->persona as $persona) {
        echo "Nombre: " . $persona->nombre . "<br>";
        echo "Apellido: " . $persona->apellido . "<br>";
        echo "Rut: " . $persona->rut . "<br>";
        echo "Dirección: " . $persona->direccion . "<br>";
        echo "Tiempo arriendo: " . $persona->tiempoArriendo . "<br>";
        echo "Monto: " . $persona->monto . "<br>";
    }
} elseif ($escribir === 'escribir') {
    $textoEscribir = 
    "<personas>
        <persona>
            <nombre>$nombre</nombre>
            <apellido>$apellido</apellido>
            <rut>$rut</rut>
            <direccion>$direccion</direccion>
            <tiempoArriendo>$tiempo</tiempoArriendo>
            <monto>$monto</monto>
        </persona>
    </personas>";
    
    $fp = fopen("arrendatarios.xml", "w");
    if ($fp) {
        fwrite($fp, $textoEscribir);
        fclose($fp);
        echo "Datos escritos en el archivo<br>";
    } else {
        echo "No se pudo abrir el archivo para escribir<br>";
    }
} elseif ($cerrar === 'cerrar') {
    if (isset($fp)) {
        fclose($fp);
        echo "Archivo cerrado<br>";
    } else {
        echo "No hay archivo abierto para cerrar<br>";
    }
} elseif ($exportar === 'exportar') {
    $arrendatarios = simplexml_load_file("arrendatarios.xml");
    $html = '<h1>Lista de Arrendatarios</h1><table border="1">';
    $html .= '<tr><th>Nombre</th><th>Apellido</th><th>Rut</th><th>Dirección</th><th>Tiempo Arriendo</th><th>Monto</th></tr>';
    
    foreach ($arrendatarios->persona as $persona) {
        $html .= '<tr>';
        $html .= '<td>' . $persona->nombre . '</td>';
        $html .= '<td>' . $persona->apellido . '</td>';
        $html .= '<td>' . $persona->rut . '</td>';
        $html .= '<td>' . $persona->direccion . '</td>';
        $html .= '<td>' . $persona->tiempoArriendo . '</td>';
        $html .= '<td>' . $persona->monto . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Exportar a PDF
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($html);
    $html2pdf->output('arrendatarios.pdf');
}
?>