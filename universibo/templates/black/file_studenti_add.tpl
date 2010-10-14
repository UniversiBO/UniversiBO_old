{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Aggiungi un nuovo file<br />&nbsp;</p></td></tr>
<tr><td align="left">
{include file=avviso_notice.tpl}
<form method="post" enctype="multipart/form-data">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="" align="center">
<tr>
<td class="News" align="right" valign="top"><label for="f23_file">File:</label></td>
<td>
 <input type="file" name="f23_file" id="f23_file" size="30" value="{$f23_file|escape:"htmlall"}">
 <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f23_titolo">Titolo:</label></td>
<td><input type="text" id="f23_titolo" name="f23_titolo" size="65" maxlength="130" value="{$f23_titolo|escape:"htmlall"}" /></td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f23_abstract">Abstract/descrizione<br /> del file:<br />(max 3000 caratteri)</label></td>
<td colspan="2"><textarea cols="50" rows="10" id="f23_abstract" name="f23_abstract">{$f23_abstract|escape:"htmlall"}</textarea></td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f23_parole_chiave">Parole chiave<br />(una per riga, max 4 parole)</label></td>
<td colspan="2"><textarea cols="50" rows="4" id="f23_parole_chiave" name="f23_parole_chiave">{foreach from=$f23_parole_chiave item=temp_parola}{$temp_parola|escape:"htmlall"}
{/foreach}</textarea></td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f23_categoria">Categoria:</label></td>
<td>
<select id="f23_categoria" name="f23_categoria">
{foreach from=$f23_categorie item=temp_categoria key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f23_categoria} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
{/foreach}
</select>
</td>
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
	<label for="f23_data_ins_gg">Giorno:</label>&nbsp;
	<input type="text" id="f23_data_ins_gg" name="f23_data_ins_gg" size="2" maxlength="2" value="{$f23_data_ins_gg|escape:"htmlall"}" />
	</td><td>
	<label for="f23_data_ins_mm">Mese:</label>&nbsp;
	<input type="text" id="f23_data_ins_mm" name="f23_data_ins_mm" size="2" maxlength="2" value="{$f23_data_ins_mm|escape:"htmlall"}" />
	</td><td>
	<label for="f23_data_ins_aa">Anno:</label>&nbsp;
	<input type="text" id="f23_data_ins_aa" name="f23_data_ins_aa" size="4" maxlength="4" value="{$f23_data_ins_aa|escape:"htmlall"}" />
	</td><td>
	<label for="f23_data_ins_ora">Ore:</label>&nbsp;
	<input type="text" id="f23_data_ins_ora" name="f23_data_ins_ora" size="2" maxlength="2" value="{$f23_data_ins_ora|escape:"htmlall"}" />
	</td><td>
	<label for="f23_data_ins_min">Minuti:</label>&nbsp;
	<input type="text" id="f23_data_ins_min" name="f23_data_ins_min" size="2" maxlength="2" value="{$f23_data_ins_min|escape:"htmlall"}" />
	</td></tr>
	</table>
	</fieldset>
</td></tr>
</table>
</td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f23_permessi_download">Permessi download:</label></td>
<td>
<select id="f23_permessi_download" name="f23_permessi_download">
<option value="127" selected="selected">Tutti</option>
<option value="126" selected="selected">Solo iscritti</option>
</select>
<input type="hidden" id="f23_permessi_visualizza" name="f23_permessi_visualizza" value="127" />
</td>
</tr>
<tr><td colspan="2">
{if $fileAdd_flagCanali == 'true'}
<tr><td colspan="2">
<fieldset>
<legend><span class="Normal">Il file verr&agrave; inserito nella pagina:</span></legend>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
	<tr class="Normal" valign="center" align="center">
	<td width="40">&nbsp;&nbsp;<label for="f23_canale">{$f23_canale}</label></td>
	</tr>
	</table>
</fieldset>
</td></tr>
{/if}
<tr>
<td colspan="2" align="center">
<input type="submit" id="" name="f23_submit" size="20" value="Invia" /></td>
</tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
&nbsp;<br />
&nbsp;<br />
<hr width="90%" />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

</form>
</td></tr>
</table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}