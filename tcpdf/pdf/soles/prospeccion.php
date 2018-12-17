<?php
require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";
require_once "../../../controladores/proyectos.controlador.php";
require_once "../../../modelos/proyectos.modelo.php";
require_once "../../../controladores/prospeccion.controlador.php";
require_once "../../../modelos/prospeccion.modelo.php";
class facturas{

	public function imprimir_facturas(){
	require_once('tcpdf_include.php');
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->AddPage();	
	//*Formato de Fecha
	date_default_timezone_set('America/Los_Angeles');
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$fecha = date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;	
	//**Fin Formato Fecha
	//** Obteniendo los datos DNI
	$item_principal = "id";
	$valor_principal = $_GET["id"];
	$obtener_prospeccion = ControladorProspeccion::ctrBuscarProspeccion($item_principal, $valor_principal);
	$item = "dni";
	$valor = $obtener_prospeccion["dni_cliente"];
	$tipo_cambio = $obtener_prospeccion["tipo_cambio"];
	$orden = "id_cliente";	
	//**Fin de obtener DNI
	//**
	$cliente_prospeccion = ControladorClientes::ctrBuscarClientes($item, $valor, $orden);
	$nombres_completos = $cliente_prospeccion["nombre"].' '. $cliente_prospeccion["apellido"];
	$direccion_completa = $cliente_prospeccion["direccion"].','. $cliente_prospeccion["distrito"];
	$celular = $cliente_prospeccion["celular"];
	$email = $cliente_prospeccion["email"];
	//**
	//**
	$obtener_proyecto = ControladorProyectos::ctrMostrarProyectos("id_proyecto",$obtener_prospeccion["id_proyecto"]);
	$producto = $obtener_proyecto["proyecto"];
	$orden = $obtener_proyecto["etapa"];
	$metraje = $obtener_proyecto["area"];
	$terreno = $obtener_proyecto["terreno"];
	$precio = $obtener_proyecto["precio_lista"]*$tipo_cambio;
	//**
	$html = <<<EOF
		<table>
		<tr>
			<td style="width:540px"><img src="../images/back.jpg"></td>
		</tr>

		<tr>
			<td width="300px"><img src="../images/logotipo.jpg"></td>
			<td style="text-align:right;solid #666; color:#333"><p style="text-align:center;">RUC 20602162711<br> PROSPECCION</p></td>

		</tr>
	</table>
	

	<table style="text-align:right;line-height: 20px; font-size:10px;">
		<tr>
			<td style="solid #666; color:#333">Arequipa, $fecha</td>
		</tr>
	</table>

	<table style="line-height: 20px; font-size:10px">
		<tr>
			<td width="60px" style="solid #666; color:#333">Señor(es): </td>
			<td width="340px" style="solid #666; color:#333">$nombres_completos</td>
			<td width="50px" style="solid #666; color:#333">DNI: </td>
			<td width="90px" style="solid #666; color:#333">$valor</td>
		</tr>
	</table>

	<table style="line-height: 20px; font-size:10px">
		<tr>
			<td width="60px" style="solid #666; color:#333">Dirección: </td>
			<td width="340px" style="solid #666; color:#333">$direccion_completa</td>
			<td width="50px" style="solid #666; color:#333">Telefono: </td>
			<td width="90px" style="solid #666; color:#333">$celular</td>
		</tr>
	</table>

	<table style="line-height: 20px; font-size:10px">
		<tr>
			<td width="60px" style="solid #666; color:#333">E-mail: </td>
			<td width="340px" style="solid #666; color:#333">$email</td>
			<td width="50px" style="solid #666; color:#333">Proyecto: </td>
			<td width="90px" style="solid #666; color:#333">$producto</td>			
		</tr>
	</table>

	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:#333; color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #666; background-color:#333; color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #666; background-color:#333; color:#fff">Área</td>
			<td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">Terreno</td>
			<td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">Precio</td>
		</tr>
	</table>

EOF;
$precio_c = number_format(number,2);
$pdf->writeHTML($html, false, false, false, false, '');
$html2 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #666;">$producto</td>
			<td width="280px" style="border: 1px solid #666;">$orden</td>
			<td width="60px" style="border: 1px solid #666;">$metraje</td>
			<td width="50px" style="border: 1px solid #666;">$terreno</td>
			<td width="50px" style="border: 1px solid #666;">$precio_c</td>
		</tr>
	</table>
EOF;

$pdf->writeHTML($html2, false, false, false, false, '');
$pdf->Output('prospeccion.pdf', 'I');
	}
}

$prueba = new facturas();

$prueba -> imprimir_facturas();

?>