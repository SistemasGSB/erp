<?php
//require_once "../../controlador/facturacion_controller.php";
//require_once "../../modelo/facturacion_model.php";
class facturas{

	public function imprimir_facturas(){
	require_once('tcpdf_include.php');
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->AddPage();
	$html = <<<EOF
		<table>
		<tr>
			<td style="width:540px"><img src="images/back.jpg"></td>
		</tr>

		<tr>
			<td width="300px"><img src="images/logotipo.jpg"></td>
			<td style="text-align:right;solid #666; color:#333"><p style="text-align:center;">RUC 20602162711<br> FACTURA</p></td>

		</tr>
	</table>
	

	<table style="text-align:right;line-height: 20px; font-size:10px">
		<tr>
			<td style="solid #666; color:#333">Arequipa, 23 de Marzo del 2018</td>
		</tr>
	</table>

	<table style="line-height: 20px; font-size:10px">
		<tr>
			<td width="60px" style="solid #666; color:#333">Señor(es): </td>
			<td width="340px" style="solid #666; color:#333">AMERICAN INSPECTOR S.A.C</td>
			<td width="50px" style="solid #666; color:#333">RUC: </td>
			<td width="90px" style="solid #666; color:#333">10726540913</td>
		</tr>
	</table>

	<table style="line-height: 20px; font-size:10px">
		<tr>
			<td width="60px" style="solid #666; color:#333">Dirección: </td>
			<td width="340px" style="solid #666; color:#333">CAL.ABANCAY NRO. 308 URB. SAN MARTIN (3 CDRAS A LA DERECHA DE LOS TORITOS) AREQUIPA - AREQUIPA - SOCABAYA</td>
			<td width="70px" style="solid #666; color:#333">G. Remision: </td>
			<td width="70px" style="solid #666; color:#333"></td>
		</tr>
	</table>

	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">Cantidad</td>
			<td width="380px" style="border: 1px solid #666; background-color:#333; color:#fff">Detalle</td>
			<td width="60px" style="border: 1px solid #666; background-color:#333; color:#fff">P. Unitario</td>
			<td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">Total</td>
		</tr>
	</table>

EOF;

$pdf->writeHTML($html, false, false, false, false, '');
//$respuesta = facturacion_controlador::mostrarDetallesFactura("detalle_factura");
//foreach ($respuesta as $row => $item) {
$html2 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="50px" style="border: 1px solid #666;">$item[cantidad]</td>
			<td width="380px" style="border: 1px solid #666;">Computador I7</td>
			<td width="60px" style="border: 1px solid #666;"></td>
			<td width="50px" style="border: 1px solid #666;">$item[precio_venta].00</td>
		</tr>
	</table>


EOF;

$pdf->writeHTML($html2, false, false, false, false, '');
} 
$pdf->Output('factura.pdf', 'I');
	}
}

$prueba = new facturas();

$prueba -> imprimir_facturas();

?>