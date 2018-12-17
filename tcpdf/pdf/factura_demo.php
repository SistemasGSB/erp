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
$dueño= " Ccapa Nunonncca Emperatriz Paulina";
$rucUsuario = 10430064563;
$rucCliente =10430064563;
$cliente= " Zapata Carlos";
$guia= 154522;
$cantidad= 10;
$pu=15;
$dcto= 0.00;
$UDM= "UND";
$total = 17965.68;//$cantidad * $pu;
$subTotal = $total/1.18; 
$igv = $total-$subTotal;
$total1=round($total,2);
$subtotal1=round($subTotal,2);
$igv1=round($igv,2);
$numeroFactura= 15340;
#Fin mis variables
$html1 = <<<EOF
 
<table>

  <tr>
   <td style="width:550px;text-align:center;"><img src="../images/logo2.jpg"></td>
  </tr>
</table>
<table>
<tr>
<td></td>
<td style="width:200px;text-align:center;"><H2>FACTURA ELECTRONICA</H2></td>
<td style="width:250px;text-align:center;">N°: $numeroFactura</td>
</tr>
</table>
<br><br>
<table style="border: 1px solid black;border-radius:50px; font-size:10px;">
  <tr style="line-height:15px;">
   <td>
    Fecha de Emisión: $hoy <br>
    Razón Social: $dueño <br>
    R.U.C: $rucUsuario <br>
    Dirección: Av. A. A. Cáceres Stand 19 Siglo XX Pta. 11 -J.L. Bustamante y Rivero <br>
    Condición de Pago: CONTADO. <br>
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

$html2 = <<<EOF

	<table style="border: 1px solid #333; text-align:center; line-height: 20px; font-size:7px">
		<tr>
      <td width="100px" >320040-KKG</td>
      <td width="150px" >ANVIL TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >157.74</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1892.88</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300123-BDT</td>
      <td width="150px" >TRASE TX SE</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >157.74</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1892.88</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300126-BKW</td>
      <td width="150px" >TRASE TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300126-XKRW</td>
      <td width="150px" >TRASE TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADGS300060B-MU2</td>
      <td width="150px" >TRASE TX SE</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADGS300060B-NY0</td>
      <td width="150px" >TRASE TX SE</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADGS300060B-UWP</td>
      <td width="150px" >TRASE TX SE</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300126-MIL</td>
      <td width="150px" >TRASE TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300242-NC2</td>
      <td width="150px" >FLASH 2 TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >104.94</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1259.28</td>
		</tr>
		<tr>
      <td width="100px" >ADYS300126-BT3</td>
      <td width="150px" >TRASE TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >131.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1576.08</td>
		</tr>
		<tr>
      <td width="100px" >320040-PEW</td>
      <td width="150px" >ANVIL TX</td>
      <td width="50px" >$UDM</td>
      <td width="60px" >12</td>
      <td width="70px" >157.34</td>
      <td width="50px" >$dcto</td>
      <td width="60px" >1888.08</td>
		</tr>
  </table>
  
EOF;

$pdf->writeHTML($html2, false, false, false, false, '');  

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
                                                        $total1</td>
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