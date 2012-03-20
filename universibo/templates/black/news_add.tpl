{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
{include file=avviso_notice.tpl}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Aggiungi una nuova notizia<br />&nbsp;</p></td></tr>
<tr><td>
<form method="post">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="" align="center">
<tr>
<td class="News" align="right" valign="top"><label for="f7_titolo">Titolo:</label></td>
<td><input type="text" id="f7_titolo" name="f7_titolo" size="65" maxlength="130" value="{$f7_titolo|escape:"htmlall"}" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="News" align="left">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr class="News"><td>
	<fieldset>
	<legend>Data Inserimento:</legend>
	<table width="98%" cellspacing="0" cellpadding="0" border="0" summary="">
	<tr class="News"><td>
	<label for="f7_data_ins_gg">Giorno:</label>&nbsp;
	<input type="text" id="f7_data_ins_gg" name="f7_data_ins_gg" size="2" maxlength="2" value="{$f7_data_ins_gg|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_ins_mm">Mese:</label>&nbsp;
	<input type="text" id="f7_data_ins_mm" name="f7_data_ins_mm" size="2" maxlength="2" value="{$f7_data_ins_mm|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_ins_aa">Anno:</label>&nbsp;
	<input type="text" id="f7_data_ins_aa" name="f7_data_ins_aa" size="4" maxlength="4" value="{$f7_data_ins_aa|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_ins_ora">Ore:</label>&nbsp;
	<input type="text" id="f7_data_ins_ora" name="f7_data_ins_ora" size="2" maxlength="2" value="{$f7_data_ins_ora|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_ins_min">Minuti:</label>&nbsp;
	<input type="text" id="f7_data_ins_min" name="f7_data_ins_min" size="2" maxlength="2" value="{$f7_data_ins_min|escape:"htmlall"}" />
	</td></tr>
	</table>
	</fieldset>
</td></tr>
</table>
</td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f7_testo"> Notizia:<br />(max 2500 <br />caratteri)</label></td>
<td colspan="2"><textarea cols="50" rows="10" id="f7_testo" name="f7_testo">{$f7_testo|escape:"htmlall"}</textarea></td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="News" align="left" valign="top"><label for="f7_scadenza"><input type="checkbox" id="f7_scadenza" name="f7_scadenza" {if $f7_scadenza=='true'}checked="checked"{/if} />&nbsp;Attiva Scadenza</label></td>
</tr>
<tr>

<td>&nbsp;</td>
<td class="News">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr class="News"><td>
	<fieldset>
	<legend>Data Scadenza:</legend>
	<table width="98%" cellspacing="0" cellpadding="0" border="0" summary="" align="center">
	<tr class="News"><td>
	<label for="f7_data_scad_gg">Giorno:</label>&nbsp;
	<input type="text" id="f7_data_scad_gg" name="f7_data_scad_gg" size="2" maxlength="2" value="{$f7_data_scad_gg|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_scad_mm">Mese:</label>&nbsp;
	<input type="text" id="f7_data_scad_mm" name="f7_data_scad_mm" size="2" maxlength="2" value="{$f7_data_scad_mm|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_scad_aa">Anno:</label>&nbsp;
	<input type="text" id="f7_data_scad_aa" name="f7_data_scad_aa" size="4" maxlength="4" value="{$f7_data_scad_aa|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_scad_ora">Ore:</label>&nbsp;
	<input type="text" id="f7_data_scad_ora" name="f7_data_scad_ora" size="2" maxlength="2" value="{$f7_data_scad_ora|escape:"htmlall"}" />
	</td><td>
	<label for="f7_data_scad_min">Minuti:</label>&nbsp;
	<input type="text" id="f7_data_scad_min" name="f7_data_scad_min" size="2" maxlength="2" value="{$f7_data_scad_min|escape:"htmlall"}" />
	</td></tr>
	</table>
	</fieldset>
</td></tr>
</table>	
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="News" align="left" valign="top"><input type="checkbox" id="f7_urgente"  name="f7_urgente" {if $f7_urgente=='true'}checked="checked"{/if}/>&nbsp;Invia il messaggio come urgente (invia la notifica anche via sms)</td>
</tr>
<tr><td colspan="2">
<fieldset>
<legend><span class="Normal">La notizia verr&agrave; inserita nelle pagine:</span></legend>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
	{foreach name=canali item=item from=$f7_canale}
	<tr class="Normal" valign="center" align="center">
	<td width="40">&nbsp;&nbsp;<input type="checkbox" id="f7_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f7_canale[{$item.id_canale}]" />&nbsp;&nbsp;&nbsp;</td><td align="left"><label for="f7_canale{$smarty.foreach.canali.iteration}">{$item.nome_canale}</label></td>
	</tr>
	{/foreach}
	</table>
</fieldset>
</tr></td>
<tr>
<td colspan="2" align="center">
<input type="submit" id="" name="f7_submit" size="20" value="Invia" /></td>
</tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

</form>
</td></tr>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}