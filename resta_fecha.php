<?

function mesesEntreFecha($fecha_vencimiento){
$date = date("Y-m-d");
$activationdate = date("Y-m-d", strtotime ($fecha_vencimiento));
$years= date("Y", strtotime("now")) - date("Y", strtotime($activationdate));
// 

if (date ("Y", strtotime($date)) == date ("Y", strtotime($activationdate))){
$months = date ("m", strtotime($date)) - date ("m", strtotime($activationdate));
}
elseif ($years == "1"){
$months = (date ("m", strtotime("December")) - date ("m", strtotime($activationdate))) + (date ("m"));
}
elseif($years >= "2"){
$months = ($years*12) + (date ("m", strtotime("now")) - date ("m", strtotime($activationdate)));
}
return $months;
}


echo mesesEntreFecha('2011-03-01');
?>
