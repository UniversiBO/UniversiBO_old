{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

{include file=avviso_notice.tpl}

<table width="95%" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td>
		Questo è il body di un esame!!!
		</td>
	</tr>
</table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}
