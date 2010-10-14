{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Informazioni file<br />&nbsp;</p></td></tr>
<tr><td class="Normal">

{include file=Files/show_info.tpl }

</td></tr>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Commenti degli studenti<br />&nbsp;</p></td></tr>
</table>

{if $isFileStudente=="true"}
{include file=Files/show_file_studenti_commenti.tpl }
{/if}



{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}