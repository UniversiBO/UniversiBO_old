{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
{include file=avviso_notice.tpl}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Aggiungi un nuovo link<br />&nbsp;</p></td></tr>
<tr><td>
<form method="post">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="" align="center">

<tr>
<td class="News" align="right" valign="top"><label for="f29_URI">Indirizzo:</label></td>
<td><input type="text" name="f29_URI" id="f29_URI" size="65" maxlength="255"  value="{$f29_URI|escape:"htmlall"}" /></td>
</tr>
		
<tr>
<td class="News" align="right" valign="top"><label class="label" for="f29_Label">Etichetta:</label></td>
<td><input type="text" id="f29_Label" name="f29_Label" size="65" maxlength="130" value="{$f29_Label|escape:"htmlall"}" /></td>
</tr>

<tr>
<td class="News" align="right" valign="top"><label for="f29_Description"><p>Descrizione<br /> del link:<br />(max 1000 caratteri)</p></label></td>
<td colspan="2"><textarea cols="50" rows="10" id="f29_Description" name="f29_Description">{$f29_Description|escape:"htmlall"}</textarea></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" id="" name="f29_submit" size="20" value="Invia" /></td>
</tr>
<!--<tr><td colspan="2" align="center" class="Normal"><a href="">Torna&nbsp;a&nbsp;</a></td></tr>-->
</table>

</form>
</td></tr>
</table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}