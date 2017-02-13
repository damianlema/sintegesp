<?php

$string = '123456';

$sizeof = strlen($string) +1;
		$result = '';
		for($x = $sizeof; $x >= 0; $x--){
			$result .= $string[$x];
		}
		$result = md5($result);
		echo $result;


/*
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
*/
?>