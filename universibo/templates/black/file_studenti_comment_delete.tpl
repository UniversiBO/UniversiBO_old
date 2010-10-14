{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="90%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">Cancella il tuo commento</p></td></tr>
<tr><td class="Normal">
{include file=avviso_notice.tpl}
{include file=Files/show_file_studenti_commento.tpl}
</td></tr><tr><td align="center"> 
{*{include file=Files/show_info.tpl}*}
</td></tr><tr><td align="center"> 
<form method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="4" summary="">
<tr>
<td align="center">
<input type="submit" id="" name="f28_submit" size="20" value="Elimina" /></td>
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