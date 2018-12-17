<?php
require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";
require_once "../../controladores/proyectos.controlador.php";
require_once "../../modelos/proyectos.modelo.php";
require_once "../../controladores/cotizador.controlador.php";
require_once "../../modelos/cotizador.modelo.php";
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
	$ahora = time();
	//**Obteniendo el ID
	$item_principal = "id";
	$valor_principal = $_GET["id"];
	$respuesta_principal = ControladorCotizador::ctrMostrarCotizador($item_principal, $valor_principal);
	//**	
	//**Fin Formato Fecha
	//** Obteniendo los datos DNI 
	$item = "dni";
	$valor = $respuesta_principal["dni_cliente"];
	$asesor_nombre = $respuesta_principal["asesor_nombre"];
	$orden = "id_cliente";	
	//**Fin de obtener DNI
	//**
	$cliente_prospeccion = ControladorClientes::ctrBuscarClientes($item, $valor, $orden);
	$apellidos_completos = $cliente_prospeccion["apellido"];
	$nombres_completos = $cliente_prospeccion["nombre"];
	$direccion_completa = $cliente_prospeccion["direccion"];
	$distrito_completo =  $cliente_prospeccion["distrito"];
	$celular = $cliente_prospeccion["celular"];
	$email = $cliente_prospeccion["email"];
	//**
	//**
	$obtener_proyecto = ControladorProyectos::ctrMostrarProyectos("id_proyecto",$respuesta_principal["id_proyecto"]);
	$producto = $obtener_proyecto["proyecto"];
	$orden = $obtener_proyecto["etapa"];
	$metraje = $obtener_proyecto["area"];
	$terreno = $obtener_proyecto["terreno"];
	$precio = $obtener_proyecto["precio_lista"];

	$obtener_cotizacion = ControladorCotizador::ctrMostrarCotizador("id",$_GET["id"]);
	$pfd = $obtener_cotizacion["cot_pfd"];
	$sep_usd = $obtener_cotizacion["cot_sep_usd"];
	$cot_cis_usd = $obtener_cotizacion["cot_cis_usd"];
	$cot_mfd = $obtener_cotizacion["cot_mfd"];
	$cot_tci = $obtener_cotizacion["cot_tci"];
	$cot_tci_usd = $obtener_cotizacion["cot_tci_usd"];
	$cot_sci_usd= $cot_tci_usd-$cot_cis_usd-$sep_usd;
	$cot_sci = round(($cot_sci_usd/$precio)*100,2);
	$cot_cuotam = round($cot_mfd/$pfd,2);
	$cot_sep=round(($sep_usd/$precio)*100,2);
	$cot_cis=round(($cot_cis_usd/$precio)*100,2);
	$asesor = $obtener_cotizacion["asesor"];
	$telefono_asesor = $obtener_cotizacion["telefono_asesor"];

	//**
	$html = <<<EOF
		<table>
		<tr>
			<td width="540x" style="border: 1px solid #666; font-size:14px ; text-align:center;background-color:#006666; color:#fff">COTIZACION</td>			
		</tr>
		</table>	

	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #666;">FECHA</td>
			<td width="440px" style="border: 1px solid #666;">$fecha</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="540x" style="border: 1px solid #666; background-color:#006666; color:#fff">DATOS DEL CLIENTE</td>			
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">Apellidos</td>
			<td width="440px" style="border: 1px solid #006666; background-color:white;">$apellidos_completos</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">Nombres</td>
			<td width="440px" style="border: 1px solid #006666; background-color:white;">$nombres_completos</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">DNI</td>
			<td width="170px" style="border: 1px solid #006666; background-color:white;">$valor</td>
			<td width="110px" style="border: 1px solid #006666; background-color:white;">DNI CONYUGE</td>
			<td width="160px" style="border: 1px solid #006666; background-color:white;">--</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">DIRECCION</td>
			<td width="440px" style="border: 1px solid #006666; background-color:white;">$direccion_completa</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">DISTRITO</td>
			<td width="170px" style="border: 1px solid #006666; background-color:white;">$distrito_completo</td>
			<td width="110px" style="border: 1px solid #006666; background-color:white;">CIUDAD</td>
			<td width="160px" style="border: 1px solid #006666; background-color:white;">Arequipa</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">TELEFONO</td>
			<td width="170px" style="border: 1px solid #006666; background-color:white;">--</td>
			<td width="110px" style="border: 1px solid #006666; background-color:white;">CELULAR</td>
			<td width="160px" style="border: 1px solid #006666; background-color:white;">$celular</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #006666;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #006666;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #006666;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">CORREO</td>
			<td width="440px" style="border: 1px solid #006666; background-color:white;">$email</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #006666; background-color:white;">CORREO 2</td>
			<td width="440px" style="border: 1px solid #006666; background-color:white;">--</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
	</table>

EOF;
$precio_c = number_format($precio,2);
$cot_sep_c = number_format($cot_sep,2);
$sep_usd_c = number_format($sep_usd,2);
$cot_cis_c = number_format($cot_cis,2);
$cot_cis_usd_c = number_format($cot_cis_usd,2);
$cot_sci_c = number_format($cot_sci,2);
$cot_sci_usd_c= number_format($cot_sci_usd,2);
$cot_tci_c = number_format($cot_tci,2);
$cot_tci_usd_c = number_format($cot_tci_usd,2);
$cot_mfd_c = number_format($cot_mfd,2);
$cot_cuotam_c = number_format($cot_cuotam,2);
$pdf->writeHTML($html, false, false, false, false, '');
$html2 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666; color:#fff">MANZANA / LOTE</td>
			<td width="270x" style="border: 1px solid #666; background-color:#006666; color:#fff">ÁREA TOTAL</td>			
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#fff;">$terreno</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">M2</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$metraje</td>		
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">PRECIO LISTA</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$precio_c</td>		
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">PRECIO VENTA</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$precio_c</td>		
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 8px; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
	</table>
	<table style="border: 1px solid #333; text-align:center; line-height: 20px;font-weight:700; font-size:11px">
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">SEPARACION</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">%</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_sep_c</td>

		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff"></td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$sep_usd_c</td>		
		</tr>		
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">CUOTA INICIAL SEP.</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">%</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_cis_c</td>

		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff"></td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_cis_usd_c</td>		
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">SALDO CUOTA INICIAL</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">%</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_sci_c</td>

		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff"></td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_sci_usd_c</td>		
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">TOTAL CUOTA INICIAL</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">%</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_tci_c</td>

		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff"></td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_tci_usd_c</td>		
		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">MONTO FINANCIAMIENTO DIRECTO</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_mfd_c</td>

		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">PLAZO FINANCIAMIENTO DIRECTO</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">MESES</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$pfd</td>

		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">Nº CUOTAS</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">LETRAS</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$pfd</td>

		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">CUOTA MENSUAL (VALOR ESTIMADO)</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">US$</td>
			<td width="135x" style="border: 1px solid #666; background-color:#fff;">$cot_cuotam_c</td>

		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">ASESOR DE VENTAS</td>
			<td width="270x" style="border: 1px solid #666; background-color:#fff;">$asesor_nombre</td>

		</tr>
		<tr style="border: 0px solid #333; text-align:center; line-height: 8px; font-size:10px">
			<td width="100px" style="border: 1px solid #fff;color:#fff">Producto</td>
			<td width="280px" style="border: 1px solid #fff;color:#fff">Etapa</td>
			<td width="60px" style="border: 1px solid #fff;color:#fff">Área</td>
			<td width="100px" style="border: 1px solid #fff;color:#fff">Terreno</td>
		</tr>
		<tr>
			<td width="270x" style="border: 1px solid #666; background-color:#006666;color:#fff">TELEFONO ASESOR VENTAS</td>
			<td width="270x" style="border: 1px solid #666; background-color:#fff;">$telefono_asesor</td>

		</tr>
	</table>
	<p style="text-align:center;font-size:10px">Nota: La presente cotización tiene una vigencia de 03 días a partir de la fecha de emisión. No es una opción de venta, por lo que las condiciones y precios podrán variar en cualquier momento sin previo aviso.</p>
EOF;

$pdf->writeHTML($html2, false, false, false, false, '');
$pdf->Output('cotizador.pdf', 'I');
	}
}

$prueba = new facturas();

$prueba -> imprimir_facturas();

?>