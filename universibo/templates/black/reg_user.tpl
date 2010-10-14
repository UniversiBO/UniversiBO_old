{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

{include file=avviso_notice.tpl}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">Registrazione Utenti</p></td></tr>
</table>
<br />
<form id="f34" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="2" summary="">
		<tr align="left"><td>&nbsp;<label for="f34_email">e-mail</label>&nbsp;</td>
		<td><input type="text" name="f34_email" id="f34_email" size="20" maxlength="50" value="{$f34_email|escape:"html"}" /></td></tr>
	<tr align="left"><td>&nbsp;<label for="f34_username">username</label>&nbsp;</td>
		<td><input type="text" name="f34_username" id="f34_username" size="20" maxlength="25" value="{$f34_username|escape:"html"}" /></td></tr>
	<tr align="left"><td>&nbsp;<label for="f34_livello">livello</label></td>
		<td><select name="f34_livello" id="f34_livello">
			{foreach from=$f34_livelli key=temp_key item=temp_livello}
			 <option value="{$temp_key}">{$temp_livello|escape:"htmlall"}</option>
			{/foreach}
			</select></td></tr>
	<tr align="left"><td colspan="2">&nbsp;<input class="submit" type="submit" name="f34_submit" id="f34_submit" value="{$f34_submit|escape:"htmlall"}"></td></tr>
	</table>
</form>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}
