{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="90%" border="0" cellspacing="10" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />{$ShowContattiDocenti_titolo}<br />&nbsp;</p></td></tr>
<tr><td>
{include file=avviso_notice.tpl}
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
{foreach name=listacontatto from=$ShowContattiDocenti_contatti item=temp_contatto}
	<tr class="Normal" align="center" bgcolor="{cycle values="#000016,#000032"}" {if $temp_contatto.codStato == 0}style="background-color: #1b0;"{elseif $temp_contatto.codStato == 2}style="background-color: #f33"{elseif $temp_contatto.codStato == 3}style="background-color: #686868"{/if}>
	<td><a href="{$temp_contatto.URI|escape:"htmlall"}">{$temp_contatto.nome|escape:"htmlall"|nl2br|truncate}</a></td>
	<td>{$temp_contatto.stato|escape:"htmlall"}</td></tr>
{/foreach}
</table>
</td></tr></table>
{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}