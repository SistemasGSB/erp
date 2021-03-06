<?php
require_once "../../../controladores/clientes.controlador.php";
require_once "../../../modelos/clientes.modelo.php";
require_once "../../../controladores/proyectos.controlador.php";
require_once "../../../modelos/proyectos.modelo.php";
require_once "../../../controladores/simulador.controlador.php";
require_once "../../../modelos/simulador.modelo.php";
require_once "../../../controladores/proforma.controlador.php";
require_once "../../../modelos/proforma.modelo.php";
require_once "../../../controladores/reserva.controlador.php";
require_once "../../../modelos/reserva.modelo.php";
class facturas{

	public function imprimir_facturas(){
	require_once('tcpdf_include.php');
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->AddPage();	
	//*Formato de Fecha
	date_default_timezone_set('America/Los_Angeles');
	$fecha = date('d')."/".date('n'). "/".date('Y') ;
	$ahora = time();

	//**
	$obtener_proforma = ControladorProforma::ctrMostrarProforma("id",$_GET["id"]);
	$tipo_cambio = $obtener_proforma["tipo_cambio"];
	$fecha_inicial = $obtener_proforma["fecha_inicial"];
	$fecha_pago = $obtener_proforma["fecha_pago"];
	$amortizacion_dos = $obtener_proforma["amortizacion_dos"] * $tipo_cambio;
	$cot_sci_usd = $obtener_proforma["cot_sci_usd"] * $tipo_cambio;
	$item = "dni";
	$valor = $obtener_proforma["dni_cliente"];
	$orden = "id_cliente";	
	//**Fin de obtener DNI
	//**
	$cliente_prospeccion = ControladorClientes::ctrBuscarClientes($item, $valor, $orden);
	$nombres_completos = $cliente_prospeccion["nombre"].' '.$cliente_prospeccion["apellido"];
	$direccion_completa = $cliente_prospeccion["direccion"];
	$distrito_cliente = $cliente_prospeccion["distrito"];
	$celular_cliente = $cliente_prospeccion["celular"];
	$obtener_simulacion = ControladorSimulador::ctrMostrarSimulador("id",$obtener_proforma["id_simulacion"]);
	$obtener_proyecto = ControladorProyectos::ctrMostrarProyectos("id_proyecto",$obtener_proforma["id_proyecto"]);
	$producto = $obtener_proyecto["proyecto"];
	$lote = $obtener_proyecto["terreno"];
	$area = $obtener_proyecto["area"];
	$precio = $obtener_proyecto["precio_lista"] * $tipo_cambio;

	
	$sim_cot_pfd = $obtener_simulacion["sim_cot_pfd"];
	//**saldo a financiar
	$sim_cot_mfd =  $obtener_simulacion["sim_cot_mfd"]* $tipo_cambio;
	//**FIN
	$sim_periocidad = $obtener_simulacion['sim_periocidad'];
	$sim_cot_tcea = $obtener_simulacion['sim_cot_tcea'];
	$sim_per_gracia = $obtener_simulacion['sim_per_gracia'];
	/*Valor Final del lote*/
	$valor_final_lote = $obtener_proforma["valor_final_lote"]* $tipo_cambio;
	/*TASA , INICIAL*/
	$sim_cot_tasa = $obtener_simulacion['sim_cot_tasa'];
	/*CUOTA INICIAL*/
	$sim_cot_tci_usd = $obtener_simulacion['sim_cot_tci_usd']* $tipo_cambio;
	//**
	/*TOTAL DE INTERES*/
	$total_intereses = $obtener_simulacion['sim_total_interes']* $tipo_cambio;
	/*TRAEMOS INFORMACION DE LA RESERVA*/
	$obtener_reserva = ControladorReserva::ctrMostrarReserva("id",$obtener_simulacion["id_reserva"]);
	$fecha_deposito = $obtener_reserva["fecha_deposito"];
	$reserva_separacion = $obtener_reserva["res_sep"]* $tipo_cambio;
	//**
	function payment($apr,$n,$pv,$fv=0.0,$prec=2){
	    
	    if ($apr !=0) {
	        $alpha = 1/(1+$apr);
	        $retval =  round($pv * (1 - $alpha) / $alpha / (1 - pow($alpha,$n)),$prec) ;
	    } else {
	        $retval = round($pv / $n, $prec);
	    }
	    return($retval);

	}
	$calculo_tasa = pow((1+($sim_cot_tcea/100)),($sim_periocidad/12))-1;
	$saldo_estable = payment($calculo_tasa,($sim_cot_pfd-$sim_per_gracia),$sim_cot_mfd,$fv=0.0,$prec=2);
	/*APLICACION DE FORMULAS*/
	$precio_interes = 0;
	$cuota_final = $reserva_separacion+$amortizacion_dos+$cot_sci_usd;
	$amortizacion_final = $reserva_separacion+$amortizacion_dos+$cot_sci_usd;
	$saldo_contable_uno = $precio - $reserva_separacion;
	$saldo_contable_dos = $saldo_contable_uno - $amortizacion_dos;
	$saldo_contable_tres = $saldo_contable_dos- $cot_sci_usd;
	$conversor = str_replace('/','-',$fecha_inicial);
	$nuevafecha = date("d/m/Y", strtotime(" +1 month " , strtotime($conversor)));
	//**
	$html = <<<EOF
	<table style="border: 1px solid #ffffff; text-align:center; line-height: 20px; font-size:10px">
		<tr>
			<td width="540px">PROFORMA DE VENTA</td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">CLIENTE</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">$nombres_completos</td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">DNI</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">$valor</td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Conyuge</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;"></td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">DNI</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;"></td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Co-Propietario</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;"></td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">DNI</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;"></td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Direccion</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">$direccion_completa</td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Distrito</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">$distrito_cliente</td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Telefonos</td>
			<td width="70px" style="border: 1px solid #666; background-color:white;"></td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Oficina</td>
			<td width="100px" style="border: 1px solid #666; background-color:white;"></td>
			<td width="70px" style="border: 1px solid #666; background-color:white;">Celular</td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">$celular_cliente</td>
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Fecha</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">$fecha</td>
			<td width="100px" style="border: 1px solid #666; background-color:white;">Moneda</td>
			<td width="170px" style="border: 1px solid #666; background-color:white;">Nuevos Soles</td>
		</tr>
	</table>

EOF;
for($i=1;$i<=$sim_cot_pfd;$i++) 
{
	if ($i<=$sim_per_gracia) {
		$interes = $sim_cot_mfd * $calculo_tasa;
 		//$interes = number_format($interes,2);
        $precio_interes = $precio_interes + (float)$interes;
        $cuota_final = $cuota_final + (float)$interes;
	}
	else{
		$saldo_capital = payment($calculo_tasa,$sim_cot_pfd,$sim_cot_mfd,$fv=0.0,$prec=2);
		$interes = $sim_cot_mfd * $calculo_tasa;

		//$interes = number_format($interes,2);
		$amortizacion = $saldo_estable - (float)$interes;
		$sim_cot_mfd = $sim_cot_mfd - $amortizacion;
		$precio_interes = $precio_interes + (float)$interes;
		$amortizacion_final = $amortizacion_final + $amortizacion;
		$cuota_final = $cuota_final + $saldo_estable;
		$amortizacion_c = number_format($amortizacion,2);
		$sim_cot_mfd_c = number_format($sim_cot_mfd,2);
	}
	
}
$precio_interes_c = number_format($precio_interes,2);
$sim_cot_mfd =  $obtener_simulacion["sim_cot_mfd"]* $tipo_cambio;
$cuota_final = $reserva_separacion+$amortizacion_dos+$cot_sci_usd;
$amortizacion_final = $reserva_separacion+$amortizacion_dos+$cot_sci_usd;
$sim_cot_mfd_c = number_format($sim_cot_mfd,2);

$precio_c = number_format($precio,2);
$sim_cot_tci_usd_c = number_format($sim_cot_tci_usd,2); 
$valor_final_lote_c = number_format($valor_final_lote,2);
$pdf->writeHTML($html, false, false, false, false, '');
$html2 = <<<EOF
	<table style="border: 1px solid #ffffff; text-align:center; font-size:10px">
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:#00B050;color:#fff">PRODUCTO</td>
			<td width="170px" style="border: 1px solid #666; background-color:#00B050;color:#fff">TERRENO</td>
			<td width="100px" style="border: 1px solid #666; background-color:#00B050;color:#fff">AREA</td>		
			<td width="170px" style="border: 1px solid #666; background-color:#00B050;color:#fff">M2</td>			
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">LOTE</td>
			<td width="170px" style="border: 1px solid #666; background-color:#fff;">$lote</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">TOTAL</td>		
			<td width="170px" style="border: 1px solid #666; background-color:#fff;">$area</td>			
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">Precio Contado</td>
			<td width="70px" style="border: 1px solid #666; background-color:#fff;">S/.</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">$precio_c</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">Cuota Inicial</td>		
			<td width="70px" style="border: 1px solid #666; background-color:#fff;">S/.</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">$sim_cot_tci_usd_c</td>		
		</tr>
		<tr>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">Saldo Financiar</td>
			<td width="70px" style="border: 1px solid #666; background-color:#fff;">S/.</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">$sim_cot_mfd_c</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">Interes</td>		
			<td width="70px" style="border: 1px solid #666; background-color:#fff;">S/.</td>
			<td width="100px" style="border: 1px solid #666; background-color:#fff;">$precio_interes_c</td>		
		</tr>
		<tr>
			<td width="370px" style="border: 1px solid #666; background-color:#00B050;color:#fff">VALOR FINAL DEL LOTE</td>
			<td width="70px" style="border: 1px solid #666; background-color:#00B050;color:#fff">S/.</td>
			<td width="100px" style="border: 1px solid #666; background-color:#00B050;color:#fff">$valor_final_lote_c</td>		
		</tr>
		<tr>
              <td width="540px"style="border: 1px solid #666">* Calculos realizados en base a año de 360 dias</td>
        </tr>
	</table>
EOF;
$precio_interes=0;
$reserva_separacion_c = number_format($reserva_separacion,2);
$saldo_contable_uno_c = number_format($saldo_contable_uno,2);
$amortizacion_dos_c = number_format($amortizacion_dos,2);
$saldo_contable_dos_c = number_format($saldo_contable_dos,2);
$cot_sci_usd_c = number_format($cot_sci_usd,2);
$saldo_contable_tres_c = number_format($saldo_contable_tres,2);
$pdf->writeHTML($html2, false, false, false, false, '');
$html_inicial = <<<EOF
	<table style="border: 1px solid #ffffff; text-align:center; font-size:10px">
			<tr>
              <td width="40px"style="border: 1px solid #666">Plazo</td>
              <td width="100px" style="border: 1px solid #666">Fecha Vto</td>
              <td width="100px" style="border: 1px solid #666">Interes</td>
              <td width="100px" style="border: 1px solid #666">Amortizacion</td>
              <td width="100px" style="border: 1px solid #666">Cuota</td>
              <td width="100px" style="border: 1px solid #666">Saldo Capital</td>
            </tr>
	    	<tr>
              <td width="40px"style="border: 1px solid #666">Inicial</td>
              <td width="100px" style="border: 1px solid #666">$fecha_deposito</td>
              <td width="100px" style="border: 1px solid #666"></td>
              <td width="100px" style="border: 1px solid #666">$reserva_separacion_c</td>
              <td width="100px" style="border: 1px solid #666">$reserva_separacion_c</td>
              <td width="100px" style="border: 1px solid #666">$saldo_contable_uno_c</td>
            </tr>
      		<tr>
              <td width="40px"style="border: 1px solid #666">Inicial</td>
              <td width="100px" style="border: 1px solid #666">$fecha_inicial</td>
              <td width="100px" style="border: 1px solid #666"></td>
              <td width="100px" style="border: 1px solid #666">$amortizacion_dos_c</td>
              <td width="100px" style="border: 1px solid #666">$amortizacion_dos_c</td>
              <td width="100px" style="border: 1px solid #666">$saldo_contable_dos_c</td>
            </tr>
	      	<tr>
              <td width="40px"style="border: 1px solid #666">Inicial</td>
              <td width="100px" style="border: 1px solid #666">$nuevafecha</td>
              <td width="100px" style="border: 1px solid #666"></td>
              <td width="100px" style="border: 1px solid #666">$cot_sci_usd_c</td>
              <td width="100px" style="border: 1px solid #666">$cot_sci_usd_c</td>
              <td width="100px" style="border: 1px solid #666">$saldo_contable_tres_c</td>
            </tr>
	</table>
EOF;
$pdf->writeHTML($html_inicial, false, false, false, false, '');
$inicio_d = str_replace('/','-',$fecha_pago);
$fecha_d = date("Y-m-d", strtotime(" -1 month " , strtotime($inicio_d)));

for($i=1;$i<=$sim_cot_pfd;$i++) 
{
	$nuevafecha2 = date("d/m/Y", strtotime(" +1 month " , strtotime($fecha_d)));
    $fecha_d = str_replace('/','-',$nuevafecha2);
if ($i<=$sim_per_gracia) 
            {
       		$interes = $sim_cot_mfd * $calculo_tasa;
     		$interes_c = number_format($interes,2);
            $precio_interes = $precio_interes +(float)$interes;
            $cuota_final = $cuota_final + (float)$interes;
            $precio_interes_c = number_format($precio_interes);

$html3 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; font-size:10px">
            <tr>
              <td width="40px"style="border: 1px solid #666">$i</td>
              <td width="100px" style="border: 1px solid #666">$nuevafecha2</td>
              <td width="100px" style="border: 1px solid #666">$interes_c</td>
              <td width="100px" style="border: 1px solid #666">--</td>
              <td width="100px" style="border: 1px solid #666">$interes_c</td>
              <td width="100px" style="border: 1px solid #666">$sim_cot_mfd_c</td>
            </tr>
    </table>
EOF;

$pdf->writeHTML($html3, false, false, false, false, '');
}
else
{
$saldo_capital = payment($calculo_tasa,$sim_cot_pfd,$sim_cot_mfd,$fv=0.0,$prec=2);
$interes = $sim_cot_mfd * $calculo_tasa;
$interes_c = number_format($interes,2);
$amortizacion = $saldo_estable - (float)$interes;
$sim_cot_mfd = $sim_cot_mfd - $amortizacion;
$precio_interes = $precio_interes + (float)$interes;
$precio_interes_c = number_format($precio_interes,2);
$amortizacion_final = $amortizacion_final + $amortizacion;
$cuota_final = $cuota_final + $saldo_estable;
$amortizacion_c = number_format($amortizacion,2);
$sim_cot_mfd_c = number_format($sim_cot_mfd,2);
$saldo_estable_c = number_format($saldo_estable,2);
if($i == $sim_cot_pfd)
{
$html4 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; font-size:10px">
            <tr>
              <td width="40px"style="border: 1px solid #666">$i</td>
              <td width="100px" style="border: 1px solid #666">$nuevafecha2</td>
              <td width="100px" style="border: 1px solid #666">$interes_c</td>
              <td width="100px" style="border: 1px solid #666">$amortizacion_c</td>
              <td width="100px" style="border: 1px solid #666">$saldo_estable_c</td>
              <td width="100px" style="border: 1px solid #666">0.00</td>
            </tr>
    </table>
EOF;

$pdf->writeHTML($html4, false, false, false, false, '');
}
else
{
$saldo_estable_c = number_format($saldo_estable,2);
$html5 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; font-size:10px">
            <tr>
              <td width="40px"style="border: 1px solid #666">$i</td>
              <td width="100px" style="border: 1px solid #666">$nuevafecha2</td>
              <td width="100px" style="border: 1px solid #666">$interes_c</td>
              <td width="100px" style="border: 1px solid #666">$amortizacion_c</td>
              <td width="100px" style="border: 1px solid #666">$saldo_estable_c</td>
              <td width="100px" style="border: 1px solid #666">$sim_cot_mfd_c</td>
            </tr>
    </table>
EOF;

$pdf->writeHTML($html5, false, false, false, false, '');
}
}
}
$amortizacion_final = number_format($amortizacion_final,2);
$cuota_final = number_format($cuota_final,2);
$html6 = <<<EOF
	<table style="border: 1px solid #333; text-align:center; font-size:10px">
            <tr>
              <td width="40px"style="border: 1px solid #666;background-color:#00B050">TOTAL</td>
              <td width="100px" style="border: 1px solid #666;background-color:#00B050"></td>
              <td width="100px" style="border: 1px solid #666;background-color:#00B050">$precio_interes_c</td>
              <td width="100px" style="border: 1px solid #666;background-color:#00B050">$amortizacion_final</td>
              <td width="100px" style="border: 1px solid #666;background-color:#00B050">$cuota_final</td>
              <td width="100px" style="border: 1px solid #666;background-color:#00B050"></td>
            </tr>
    </table>
EOF;

$pdf->writeHTML($html6, false, false, false, false, '');
$pdf->Output('proforma.pdf', 'I');
	}
}

$prueba = new facturas();

$prueba -> imprimir_facturas();

?>