{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="90%" border="0" cellspacing="10" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />{$ShowContattoDocente_titolo}<br />&nbsp;</p></td></tr>
<tr><td>

{include file=avviso_notice.tpl}
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
<tr bgcolor="#000050"><th align="center" colspan=2>Info</th></tr>
<tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
{foreach from=$ShowContattoDocente_info_docente key=temp_key item=temp_info}
<tr bgcolor="#000032"><td>{$temp_key|escape:"htmlall"|preg_replace:"/ /":"&nbsp;"}:</td><td>{$temp_info|escape:"htmlall"}</td></tr>
<tr><td colspan="2" align="center" bgcolor="#000099"><img src="tpl/black/invisible.gif" width="1" height="" alt="" /></td></tr>
{/foreach}
<tr bgcolor="#000032"><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center" bgcolor="#000099"><img src="tpl/black/invisible.gif" width="1" height="" alt="" /></td></tr>
{foreach from=$ShowContattoDocente_info_ruoli key=temp_key item=temp_ruoli}
<tr bgcolor="#000032"><td>{$temp_key|escape:"htmlall"}</td><td>{$temp_ruoli|escape:"htmlall"}</td></tr>
<tr><td colspan="2" align="center" bgcolor="#000099"><img src="tpl/black/invisible.gif" width="1" height="" alt="" /></td></tr>
{/foreach}
</table></td></tr>
<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
<tr bgcolor="#000050"><th align="center" colspan="2">Informazioni contatto</th></tr>
<tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
{foreach from=$ShowContattoDocente_contatto key=temp_key item=temp_contatto}
<tr bgcolor="#000032"><td>{$temp_key|escape:"htmlall"|preg_replace:"/ /":"&nbsp;"}:</td><td>{$temp_contatto|escape:"htmlall"|nl2br}</td></tr>
<tr><td colspan="2" align="center" bgcolor="#000099"><img src="tpl/black/invisible.gif" width="1" height="" alt="" /></td></tr>
{/foreach}

</table>
<form method="post">
<p><label for="f35_id_username">Assegna il task a:</label>
<select id="f35_id_username" name="f35_id_username">
{foreach from=$f35_collab_list item=temp_user key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f35_id_username} selected="selected"{/if}>{$temp_user|escape:"htmlall"}</option>
{/foreach}
</select></p>
<p><label for="f35_stato">Cambia stato:</label>
<select id="f35_stato" name="f35_stato">
{foreach from=$f35_stati item=temp_state key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f35_stato} selected="selected"{/if}>{$temp_state|escape:"htmlall"}</option>
{/foreach}
</select></p>
<div><p><label for="f35_report">Inserisci il report (NB eventuali annotazioni sul docente e/o compiti eseguiti o da eseguire):</label></p>
<p><textarea cols="50" rows="4" id="f35_report" name="f35_report">{$f35_report|escape:"htmlall"}</textarea></p>
<p><input class="submit" type="submit" id="f35_submit_report" name="f35_submit_report" size="20" value="Aggiorna il report" /></p>
</div>
</form>
</td></tr></table>
{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}