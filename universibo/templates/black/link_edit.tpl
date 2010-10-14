{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
<table width="90%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Modifica il link<br />&nbsp;</p></td></tr>
<tr><td>
{include file=Links/single_link.tpl}

{include file=avviso_notice.tpl}
<form method="post" enctype="multipart/form-data">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="" align="center">

<tr>
<td class="News" align="right" valign="top"><label for="f31_URI">Indirizzo:</label></td>
<td><input type="text" name="f31_URI" id="f31_URI" size="65" maxlength="255"  value="{$f31_URI|escape:"htmlall"}" /></td>
</tr>

<tr>
<td class="News" align="right" valign="top"><label class="label" for="f31_Label">Etichetta:</label></td>
<td><input type="text" id="f31_Label" name="f31_Label" size="65" maxlength="130" value="{$f31_Label|escape:"htmlall"}" /></td>
</tr>

<tr>
<td class="News" align="right" valign="top"><label for="f31_Description"><p>Descrizione<br /> del link:<br />(max 1000 caratteri)</p></label></td>
<td colspan="2"><textarea cols="50" rows="10" id="f31_Description" name="f31_Description">{$f31_Description|escape:"htmlall"}</textarea></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="submit" id="" name="f31_submit" size="20" value="Invia" /></td>
</tr>
</table>
<hr />

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}