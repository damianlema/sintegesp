<?
session_start();
include("../../../conf/conex.php");
$conection_db = conectarse();
include("../../../funciones/funciones.php");




?>
<script type="text/javascript" src="../../../js/funciones.js"></script>
<script type="text/javascript" src="../../../js/function.js"></script>
<link href="../../../css/theme/green/main.css" rel="stylesheet" type="text/css">
  <style type="text/css"> @import url("../../../css/theme/calendar-win2k-cold-1.css"); </style>   
     <h2 align="center">Beneficiarios</h2>
     <br />
     	<h2 class="sqlmVersion"></h2>
<br>
     
       <form action="" method="post"> 
       <table width="350" border="0" align="center">
         <tr>
            <td width="68%">Buscar a:</td>
<td width="17%"><label>
              <input type="text" name="nombre_cedula" id="nombre_cedula" />
            </label></td>
<td width="15%"><label>
              <input type="submit" name="buscar" id="buscar" value="Buscar" class="button"/>
            </label></td>
         </tr>
        </table>
       </form>
        
        <br />
        <table border="0" align="center" class="Browse" cellpadding="0" cellspacing="0" width="96%">
          <thead>
          <tr>
            <td width="66%" class="Browse"><div align="center">Nombre</div></td>
            <td width="10%" class="Browse"><div align="center">RIF</div></td>
            <td width="14%" class="Browse"><div align="center">Telefonos</div></td>
            <td width="4%" class="Browse"><div align="center">Acciones</div></td>
          </tr>
          </thead>
          <? 
		  if($_POST["buscar"]){
			$sql_beneficiarios = mysql_query("select * from beneficiarios where nombre like '%".$_POST["nombre_cedula"]."%' || rif like '%".$_POST["nombre_cedula"]."%' order by nombre ASC");
		  
          
          while($bus_beneficiarios = mysql_fetch_array($sql_beneficiarios)){
          ?>
          <tr bgcolor='#e7dfce' onMouseOver="setRowColor(this, 0, 'over', '#e7dfce', '#EAFFEA', '#FFFFAA')" onMouseOut="setRowColor(this, 0, 'out', '#e7dfce', '#EAFFEA', '#FFFFAA')" onclick="
          
          <?
            if($_GET["destino"] == "documentos"){
			?>
            opener.location.href='../../../principal.php?modulo=3&accion=84&cedula=<?=$bus_beneficiarios["rif"]?>', window.close()
            <?
            }else if($_GET["destino"] == "beneficiario"){
			?>
			opener.document.form1.buscarBeneficiario.value='<?=$bus_beneficiarios["rif"]?>', opener.document.form1.submit(), window.close()
			<?
			}else if($_GET["destino"] == "cotizaciones"){
			?>
			opener.document.form1.nombreBeneficiario.value='<?=$bus_beneficiarios["nombre"]?>', opener.document.form1.rifBeneficiario.value='<?=$bus_beneficiarios["rif"]?>',opener.document.form1.idBeneficiario.value='<?=$bus_beneficiarios["idbeneficiarios"]?>', window.close()
			<?
			}else if($_GET["destino"] == "ordenes"){
			?>
			opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', opener.document.getElementById('proceso').disabled=false,window.onUpload = window.opener.refrescarListaSolicitudes('<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('tipo_orden').value, opener.document.getElementById('id_orden_compra').value), window.close()
			<?
			}else if($_GET["destino"] == "rendicion"){
			?>
			opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()
			<?
			}else if($_GET["destino"] == "rendicion_factura"){
			?>
			opener.document.getElementById('nombre_beneficiario_factura').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('idbeneficiario_factura').value='<?=$bus_beneficiarios["idbeneficiarios"]?>',opener.document.getElementById('contribuyente_ordinario_factura').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()
			<?
			}else if($_GET["destino"] == "requisicion"){
			?>
			opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', opener.document.getElementById('proceso').disabled=false,window.onUpload = window.opener.refrescarListaSolicitudes('<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('tipo_orden').value, opener.document.getElementById('id_requisicion').value), window.close()
			<?
			}else if($_GET["destino"] == "compromisos_rrhh"){
			?>
			opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()
			<?
			}else if($_GET["destino"] == "retencion"){
			?>
			opener.document.getElementById('beneficiario').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiario').value='<?=$bus_beneficiarios["idbeneficiarios"]?>' , window.close()
			<?
			}else if($_GET["destino"] == "almacen"){
			?>
			opener.document.getElementById('proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('idbeneficiario').value='<?=$bus_beneficiarios["idbeneficiarios"]?>' , window.close()
			<?
			}
			?>
          " style="cursor:pointer">
            <td class='Browse' align='left'><?=$bus_beneficiarios["nombre"]?>&nbsp;</td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["rif"]?>&nbsp;</td>
            <td class='Browse' align='left'><?=$bus_beneficiarios["telefonos"]?>&nbsp;</td>
            
            <?
            if($_GET["destino"] == "documentos"){
			?>
            <td class='Browse' align="center"><a href="#" onclick="opener.location.href='../../../principal.php?modulo=3&accion=84&cedula=<?=$bus_beneficiarios["rif"]?>', window.close()"><img src="../../../imagenes/validar.png"></a></td>
            <?
            }else if($_GET["destino"] == "beneficiario"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.form1.buscarBeneficiario.value='<?=$bus_beneficiarios["rif"]?>', opener.document.form1.submit(), window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "cotizaciones"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.form1.nombreBeneficiario.value='<?=$bus_beneficiarios["nombre"]?>', opener.document.form1.rifBeneficiario.value='<?=$bus_beneficiarios["rif"]?>',opener.document.form1.idBeneficiario.value='<?=$bus_beneficiarios["idbeneficiarios"]?>', window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "ordenes"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', opener.document.getElementById('proceso').disabled=false,window.onUpload = window.opener.refrescarListaSolicitudes('<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('tipo_orden').value, opener.document.getElementById('id_orden_compra').value), window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "rendicion"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "rendicion_factura"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_beneficiario_factura').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('idbeneficiario_factura').value='<?=$bus_beneficiarios["idbeneficiarios"]?>',opener.document.getElementById('contribuyente_ordinario_factura').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "requisicion"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', opener.document.getElementById('proceso').disabled=false,window.onUpload = window.opener.refrescarListaSolicitudes('<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('tipo_orden').value, opener.document.getElementById('id_requisicion').value), window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "compromisos_rrhh"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('nombre_proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiarios').value='<?=$bus_beneficiarios["idbeneficiarios"]?>', opener.document.getElementById('contribuyente_ordinario').value='<?=$bus_beneficiarios["contribuyente_ordinario"]?>', window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "retencion"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('beneficiario').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('id_beneficiario').value='<?=$bus_beneficiarios["idbeneficiarios"]?>' , window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}else if($_GET["destino"] == "almacen"){
			?>
			<td width="2%" align="center" class='Browse'><a href="#" onclick="opener.document.getElementById('proveedor').value='<?=$bus_beneficiarios["nombre"]?>', opener.document.getElementById('idbeneficiario').value='<?=$bus_beneficiarios["idbeneficiarios"]?>' , window.close()"><img src="../../../imagenes/validar.png"></a></td>
			<?
			}
			?>
            
          </tr>
          <?
          }
		  }
		  
          ?>
          
        </table>
        <?
        //registra_transaccion("Listar Beneficiarios",$login,$fh,$pc,'orden_compra_servicios');
		?>