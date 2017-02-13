<?
$fec_emision = date("Y-m-d");
$can_dias = 90;
$fec_vencimi= date("Y-m-d", strtotime("$fec_emision + $can_dias days"));  
echo "FECHA DE VENCIMIENTO: ".$fec_vencimi;
?>