<?
mysql_connect("localhost", "root", "1234");
mysql_select_db("gestion_alcaldia_2010");

		$sql_consulta = mysql_query("show tables");
		$i=1;
		while($bus_consulta = mysql_fetch_array($sql_consulta)){
			
			echo "<strong>".$i."</strong>&nbsp;".$bus_consulta[0]."<br>";
			$i++;
			
		}
?>