<?php

require_once "../controladores/proyectos.controlador.php";
require_once "../modelos/proyectos.modelo.php";
require_once "../modelos/prospeccion.modelo.php";
require_once "../modelos/cotizador.modelo.php";
require_once "../modelos/proforma.modelo.php";
require_once "../modelos/reserva.modelo.php";
require_once "../modelos/simulador.modelo.php";

class AjaxProyectos{

	/*=============================================
	EDITAR Proyectos
	=============================================*/	

	public $idProyecto;

	public function ajaxEditarProyecto(){

		$item = "id_proyecto";
		$valor = $this->idProyecto;

		$respuesta = ControladorProyectos::ctrMostrarProyectos($item, $valor);

		echo json_encode($respuesta);

	}
	/*=============================================
	ACTIVAR USUARIO
	=============================================*/	

	public $activarProyecto;
	public $activarId;


	public function ajaxActivarProyecto(){

		$tabla = "proyectos";

		$item1 = "estado";
		$valor1 = $this->activarProyecto;

		$item2 = "id_proyecto";
		$valor2 = $this->activarId;
		$respuesta = ModeloProyectos::mdlActualizarProyectos($tabla, $item1, $valor1, $item2, $valor2);
		$prospeccion = ModeloProspeccion::mdlMostrarProspeccion("prospeccion","id_proyecto",$valor2);
		$cotizador = ModeloCotizador::mdlMostrarCotizador("cotizacion","id_proyecto",$valor2);
		$reserva = ModeloReserva::mdlMostrarReserva("reserva","id_proyecto",$valor2);
		$simulador = ModeloSimulador::mdlMostrarSimulador("simulacion","id_proyecto",$valor2);
		$proforma = ModeloProforma::mdlMostrarProforma("proforma","id_proyecto",$valor2);
		if(is_array($prospeccion)){
			foreach ($prospeccion as $key => $value) {
				ModeloProspeccion::mdlBorrarProspeccion("prospeccion",$value["id"]);
			}
		}
		if(is_array($cotizador)){
			foreach ($cotizador as $key => $value) {
				ModeloCotizador::mdlBorrarCotizacion("cotizacion",$value["id"]);
			}
		}
		if(is_array($reserva)){
			foreach ($reserva as $key => $value) {
				ModeloReserva::mdlBorrarReserva("reserva",$value["id"]);
			}
		}
		if(is_array($simulador)){
			foreach ($simulador as $key => $value) {
				ModeloSimulador::mdlBorrarSimulacion("simulacion",$value["id"]);
			}
		}
		
		if(is_array($proforma)){
			foreach ($proforma as $key => $value) {
				ModeloProforma::mdlBorrarProforma("proforma",$value["id"]);
			}
		}
		

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idProyecto"])){

	$proyecto = new AjaxProyectos();
	$proyecto -> idProyecto = $_POST["idProyecto"];
	$proyecto-> ajaxEditarProyecto();
}
/*=============================================
ACTIVAR USUARIO
=============================================*/	

if(isset($_POST["activarProyecto"])){

	$activarProyecto = new AjaxProyectos();
	$activarProyecto -> activarProyecto = $_POST["activarProyecto"];
	$activarProyecto -> activarId = $_POST["activarId"];
	$activarProyecto -> ajaxActivarProyecto();

}
