<?php

require_once "../../controlador/facturaController.php";
require_once "../../modelo/facturaModel.php";
require_once('tcpdf_include.php');
date_default_timezone_set('America/Lima');
class ImpresionFactura{


public function imprimirFactura(){

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
ob_clean();
$pdf->AddPage();
#mis variables
$codigo= 35.54566221;
$hoy = date("Y-m-d");
$dueño= "Ccapa Nunonncca Emperatriz Paulina";
$rucUsuario = 10430064563;
$cliente= " Chacon Chacon Carlos Adrian";
$guia= 154522;
$cantidad= 1;
$pu=15;
$dcto= 0.00;
$UDM= "UND";
$total = 6841.19;//$cantidad * $pu;
$subTotal = $total/1.18; 
$igv = $total-$subTotal;
$total1=round($total,2);
$subtotal1=round($subTotal,2);
$igv1=round($igv,2);
#Fin mis variables
$numeracion = facturaModel::NumeracionFactura("facturacion");
$numerofactura = $numeracion["num_factura"];
++$numerofactura;
$html1 = <<<EOF
 
<table>

  <tr>
   <td style="width:550px;text-align:center;"><img src="../images/logo1.jpg"></td>
  </tr>
</table>
<table>
<tr>
<td></td>
<td style="width:200px;text-align:center;"><H2>FACTURA ELECTRONICA</H2></td>
<td style="width:250px;text-align:center;">N°: 0000000$numerofactura</td>
</tr>
</table>
<br><br>
<table style="border: 1px solid black;border-radius:50px; font-size:10px;">
  <tr style="line-height:15px;">
   <td>
    Fecha de Emisión: $hoy <br>
    Razón Social: $dueño <br>
    R.U.C: $rucUsuario <br>  
    Dirección: Av. A. A. Cáceres Stand 19 Siglo XX Pta. 11<br>
    Condición de Pago: CONTADO<br>
    Vendedor: $cliente <br>
   </td>
  </tr>
</table>
<br><br>
<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:10px">
		<tr>
      <td width="100px" style="border: 1px solid #666; background-color:#333; color:#fff">CODIGO</td>
			<td width="150px" style="border: 1px solid #666; background-color:#333; color:#fff">DESCRIPCIÓN</td>
      <td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">U.D.M</td>
      <td width="60px" style="border: 1px solid #666; background-color:#333; color:#fff">CANTIDAD</td>
			<td width="70px" style="border: 1px solid #666; background-color:#333; color:#fff">V.U</td>
      <td width="50px" style="border: 1px solid #666; background-color:#333; color:#fff">DCTO</td>
			<td width="60px" style="border: 1px solid #666; background-color:#333; color:#fff">TOTAL</td>
		</tr>
	</table>
EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

#LLAMAR CLASES 
$consulta = facturaController::CargarProductosFactura();

foreach ($consulta as $row => $item) {
$html2 = <<<EOF

	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:7px">
		<tr>
      <td width="100px" >$item[id_producto]</td>
      <td width="150px" >$item[descripcion_tmp]</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >$cantidad</td>
      <td width="70px" >$item[precio_tmp]</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >$item[precio_tmp]</td>
		</tr>
  </table>
  
EOF;

$pdf->writeHTML($html2, false, false, false, false, '');  
}
$total_factura = facturaController::CargarTotalFactura();
$total = $total_factura["total"];
$subTotal = $total/1.18; 
$igv = $total-$subTotal;
$total1=round($total,2);
$subtotal1=round($subTotal,2);
$igv1=round($igv,2);
$html3=<<<EOF

  <br><br>
  <table style=" text-align:center; line-height: 20px; font-size:10px">
  <tr>
    <td></td>
    <td width="220px"></td>
    <td width="100px" style="border: 1px solid #666;">SUB TOTAL<BR>
                                                      I.G.V  %<BR>
                                                      TOTAL </td>
    <td width="80px" style="border: 1px solid #666;">  $subtotal1<br>
                                                        $igv1<br>
                                                        $total</td>
  </tr>
</table>

EOF;
$pdf->writeHTML($html3, false, false, false, false, '');

$pdf->Output('factura.pdf');
}
}
$a = new ImpresionFactura();
$a -> imprimirFactura();
?>