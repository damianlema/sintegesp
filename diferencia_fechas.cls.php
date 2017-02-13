<?php
class diferenciaFechas extends DateTime {
/**
* Returna fecha en formato iso
*
* @return String
*/
public function __toString() {
return $this->format('d-m-Y H:i');
}
/**
* Returna diferencia entre fecha actual y pasada
*
* @param Datetime|String $ahora
* @return DateInterval
*/
public function diferencia($ahora = 'NOW') {
if(!($ahora instanceOf DateTime)) {
$ahora = new DateTime($ahora);
}
return parent::diff($ahora);
}
/**
* Returna Diferencia en años con la fecha actual.
*
* @param Datetime|String $ahora
* @return Integer
*/
public function getAnnos($ahora = 'NOW') {
return $this->diferencia($ahora)->format('%y');
}
}
?>