{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>{$ShowContattiDocenti_titolo}</h2>
<div>
{include file="avviso_notice.tpl"}
<div class="elencoFile">
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
{foreach name=listacontatto from=$ShowContattiDocenti_contatti item=temp_contatto}
	<tr class="{cycle values="even,odd"}" {if $temp_contatto.codStato == 0}style="background-color: #7f5;"{elseif $temp_contatto.codStato == 2}style="background-color: #f33"{elseif $temp_contatto.codStato == 3}style="background-color: #bbb"{elseif $temp_contatto.codStato == 4}style="background-color: #c9c"{/if}>
	<td><a href="{$temp_contatto.URI|escape:"htmlall"}">{$temp_contatto.nome|escape:"htmlall"|nl2br|truncate}</a></td>
	<td>{$temp_contatto.stato|escape:"htmlall"}</td></tr>
{/foreach}
</table>
</div>
{include file="footer_index.tpl"}
