{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Cancella il link<br />&nbsp;</p></td></tr>
<tr><td>

{include file=avviso_notice.tpl}

{include file=Links/single_link.tpl}

<form method="post">
	<tr><td class="News" align="center" valign="top"><input class="submit" type="submit" id="f30_submit" name="f30_submit" size="20" value="Elimina questo link" /></td></tr>
</form>
<tr><td><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

<hr />

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}