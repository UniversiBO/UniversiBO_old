{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="98%" border="0" cellspacing="0" cellpadding="2" align="center" summary="">
<tr><td colspan="2" align="center">
<p class="Titolo">{$ruoliAdminEdit_langAction|escape:"htmlall"|nl2br}</p>
</td></tr>
<tr><td colspan="2" align="center">
<p class="Normal">{$ruoliAdminEdit_langSuccess|escape:"htmlall"|nl2br}</p>
</td></tr>

<tr><td colspan="2" class="Normal">&nbsp;&nbsp;<a href="{$ruoliAdminEdit_userUri|escape:"htmlall"}">{$ruoliAdminEdit_username|escape:"htmlall"}</a></td></tr>
<form method="post">
<tr><td colspan="2" class="Normal">
{include file=avviso_notice.tpl}
<fieldset>
<legend>Livello diritti nella pagina</legend>
<table width="98%" border="0" cellspacing="0" cellpadding="2" align="center" summary="">
<tr><td><input name="f17_livello" id="f17_livello_0" type="radio" {if $ruoliAdminEdit_userLivello == 'none'}checked="checked"{/if} value="none" />
</td><td width="100%" class="Normal">
<label for="f17_livello_0">Nessuno</label>
</td>
</tr>
<tr><td><input name="f17_livello" id="f17_livello_1" type="radio" {if $ruoliAdminEdit_userLivello == 'M'}checked="checked"{/if} value="M" />
</td><td width="100%" class="Normal">
<label for="f17_livello_1">Moderatore</label>
</td>
</tr>
<tr><td><input name="f17_livello" id="f17_livello_2" type="radio" {if $ruoliAdminEdit_userLivello == 'R'}checked="checked"{/if} value="R" />
</td><td width="100%" class="Normal">
<label for="f17_livello_2">Referente</label>
</td>
</tr>
</table>
</fieldset>
</td>
</tr>
<tr><td colspan="4" align="center">
<input name="f17_submit" id="f17_submit" type="submit" value="Modifica" />
</td></tr>
</form>
</td></tr>
</tr>

<tr><td colspan="2" align="center">
<p class="Normal"><a href="{$common_canaleURI|escape:"htmlall"|nl2br}">Torna {$common_langCanaleNome|escape:"htmlall"|nl2br}</a></p>
</td></tr>
</table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}