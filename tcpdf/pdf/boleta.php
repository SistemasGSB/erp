<?php
require_once('../../controlador/boletaController.php');
require_once('../../modelo/boletaModel.php');
require_once('tcpdf_include.php');
date_default_timezone_set('America/Lima');

class ImpresionFactura{

public function imprimirFactura(){



$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
ob_clean();
$pdf->AddPage();
#mis variables

$hoy = date("Y-m-d");
$hora = date('h:i:s A');
$dueño= "Ccapa Nunonncca Emperatriz Paulina";
$rucUsuario = 10430064563;
$cantidad= 1;
#Fin mis variables
$numeracion = boletaModel::NumeracionBoleta("boleta");
$numeroboleta = $numeracion["num_boleta"];
++$numeroboleta;
$html1 = <<<EOF
 
<table>

  <tr>
  <td width="120px;"></td>
   <td style="width:300px;text-align:center">
   <H1>IMPORT SHOES CENTER
   <H5>R.U.C: $rucUsuario</h5>
   <h3>BOLETA DE VENTA</h3>
   <h4>N°: 000000$numeroboleta</h4></H1></td>
  </tr>
</table>
<table>
<tr>
  <td width="120px;"></td>
   <td style="width:300px;text-align:center">
    Fecha de Emisión: $hoy <br>
    Hora: $hora <br>
    Razón Social: $dueño <br>
    Dirección: Av. A.A. Cáceres Stand 19 Siglo XX Pta. 11<br>
    Celular: 977549527
   </td>
   <td></td>
  </tr>
</table>
<br><br>
<table>
<tr>
      <td width="120px;"></td>
      <td width="140px" style="border-bottom: 2px solid #ddd;border-collapse: collapse;">DESCRIPCIÓN</td>
      <td width="80px" style="border-bottom: 2px solid #ddd;border-collapse: collapse; ">CANTIDAD</td>
      <td width="80px" style="border-bottom: 2px solid #ddd; border-collapse: collapse;">TOTAL</td>
</tr>
</table>
EOF;
$pdf->writeHTML($html1, false, false, false, false, '');

#LLAMAR CLASES 
$consulta = boletaController::CargarProductosBoleta();

foreach ($consulta as $row => $item) {
$html2 = <<<EOF

  <table>
<tr>
      <td width="120px;"></td>
      <td width="140px" >$item[descripcion_tmp]</td>
      <td width="80px" >$cantidad</td>
      <td width="80px" >$item[precio_tmp]</td>
</tr>
</table>

EOF;

$pdf->writeHTML($html2, false, false, false, false, '');  
}
$total_boleta = boletaController::CargarTotalBoleta();
$html3=<<<EOF
<br><br>
<table>
<tr>
      <td width="300px;"></td>
      <td>Total:S/. $total_boleta[total]</td>
</tr>
</table>
EOF;
$pdf->writeHTML($html3, false, false, false, false, '');  
#FIN DE LLAMAR CLASES
$pdf->Output('comprobante.pdf');
}
}
$a = new ImpresionFactura();
$a -> imprimirFactura();
?>