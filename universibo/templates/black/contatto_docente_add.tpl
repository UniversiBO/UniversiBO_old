{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />{$ContattoDocenteAdd_titolo}<br />&nbsp;</p></td></tr>
<tr><td align="left">
{include file=avviso_notice.tpl}
<p>{$ContattoDocenteAdd_esito|escape:"htmlall"}</p>
{if $common_canaleURI != ''}<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>{/if}
</td></tr>
</table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}