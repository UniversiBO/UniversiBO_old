{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
{include file=avviso_notice.tpl}
<table width="95%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Modifica il tuo commento<br />&nbsp;</p></td></tr>
<tr><td align="center" class="Normal">Il tuo commento é stato modificato correttamente</td></tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;alle informazioni sul file</a></td></tr>
</table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}