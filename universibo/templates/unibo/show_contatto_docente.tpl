{include file=header_index.tpl}

<h2>{$ShowContattoDocente_titolo}</h2>

{include file=avviso_notice.tpl}
<div class="tbl2col" style="width: 100%;margin: 2em 0;">
<table width="100%" border="1" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><th align="center" colspan=2>Info</th></tr>
{foreach from=$ShowContattoDocente_info_docente key=temp_key item=temp_info}
<tr><td>{$temp_key|escape:"htmlall"|ereg_replace:" ":"&nbsp;"}:</td><td>{$temp_info|escape:"htmlall"}</td></tr>
{/foreach}
<tr><td colspan="2">&nbsp;</td></tr>
{foreach from=$ShowContattoDocente_info_ruoli key=temp_key item=temp_ruoli}
<tr><td>{$temp_key|escape:"htmlall"}</td><td>{$temp_ruoli|escape:"htmlall"}</td></tr>
{/foreach}
</table>
</div>
<div class="tbl2col" style="width: 100%;margin: 2em 0;">
<table width="98%" border="1" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><th align="center" colspan=2>Informazioni contatto</th></tr>
{foreach from=$ShowContattoDocente_contatto key=temp_key item=temp_contatto}
<tr><td>{$temp_key|escape:"htmlall"|ereg_replace:" ":"&nbsp;"}:</td><td>{$temp_contatto|escape:"htmlall"|nl2br}</td></tr>
{/foreach}
</table>
</div>
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
  
{include file=footer_index.tpl}