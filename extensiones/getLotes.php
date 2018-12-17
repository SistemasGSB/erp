<?php
$conexion = mysqli_connect("localhost","root","","erp");

$proyecto = $_POST['proyecto'];
$etapa = $_POST['etapa'];
$query = $conexion->query("SELECT * FROM proyectos WHERE proyecto = '$proyecto' AND etapa ='$etapa' AND estado= '0' ");

echo '<option value="0">Seleccione</option>';

while ( $row = $query->fetch_assoc() )
{
	echo '<option value="' . $row['terreno']. '">' . $row['terreno'] . '</option>' . "\n";
}
