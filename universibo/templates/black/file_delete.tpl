{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="90%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">Cancella il file</p></td></tr>
<tr><td class="Normal" align="center">
{include file=avviso_notice.tpl}

</td></tr><tr><td align="center"> 
{*{include file=Files/show_info.tpl}*}
</td></tr><tr><td align="center"> 
<form method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="4" summary="">
{if $fileDelete_flagCanali == 'true'}
<tr><td>
<fieldset>
<legend><span class="Normal">{$f14_langAction|escape:"htmlall"}</span></legend>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
	{foreach name=canali item=item from=$f14_canale}
	<tr class="Normal" valign="center">
	<td width="40">&nbsp;&nbsp;<input type="checkbox" id="f14_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f14_canale[{$item.id_canale}]" />&nbsp;&nbsp;&nbsp;</td><td align="left"><label for="f14_canale{$smarty.foreach.canali.iteration}">{$item.nome_canale}</label></td>
	</tr>
	{/foreach}
	</table>
</fieldset>	  
</tr></td>
{/if}
<tr>
<td align="center">
<input type="submit" id="" name="f14_submit" size="20" value="Elimina" /></td>
</tr>
<tr><td align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome|escape:"htmlall"}</a></td></tr>
</table>

</form>
</td></tr>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td>
{*include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference*}
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}