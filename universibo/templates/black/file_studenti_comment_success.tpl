{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
{include file=avviso_notice.tpl}
<table width="95%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Aggiungi il tuo commento<br />&nbsp;</p></td></tr>
<tr><td align="center" class="Normal">{$FileStudentiComment_ris|escape:"htmlall"}</td></tr>
{if $esiste_CommentoItem=="true"}
<tr><td align="center" class="Normal"><a href="{$FilesStudentiComment_modifica|escape:"htmlall"}">Modifica il tuo commento</a></td></tr>
{/if}
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;alle informazioni sul file</a></td></tr>
</table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}