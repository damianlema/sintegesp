<?
extract($_POST);
extract($_GET);
?>
<table width="100%" style="">
    <tr><td colspan="2"><hr width="450" size="1" /></td></tr>
    <tr>
        <td align="right">Nómina: </td>
        <td align="right">
            <select name="nomina" id="<?=$nomina?>" style="width:300px;">
                <option value="<?=$nomina?>"><?=htmlentities($nomina_texto)?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td align="right">Periodo: </td>
        <td align="right">
            <select name="periodo" style="width:300px;">
                <option value="<?=$periodo?>"><?=$periodo_texto?></option>
            </select>
        </td>
    </tr>
</table>