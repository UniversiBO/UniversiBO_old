{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td colspan="2">
{include file=avviso_notice.tpl}
</td></tr>
<tr align="center" ><td colspan="2"><img src="tpl/black/news_30.gif" width="100" height="39" alt="News" /><br />
</td></tr>
<tr class="piccolo" valign="bottom"><td align="left">{if $NewsShowCanale_addNewsFlag == "true"}<img src="tpl/black/news_new.gif" width="15" height="15" alt="" />
<a href="{$NewsShowCanale_addNewsUri|escape:"htmlall"}">{$NewsShowCanale_addNews|escape:"htmlall"|bbcode2html|nl2br}</a>&nbsp;&nbsp;&nbsp;{/if}</td>
<td align="right">
{if $NewsShowCanale_numPagineFlag == "true"}
{foreach name=newspage item=news_curr_page key=news_curr_key from=$NewsShowCanale_numPagine}
	{if $smarty.foreach.newspage.first}
		Pagine:&nbsp;
		{if $news_curr_page.current == 'false'}<a href="{$news_curr_page.URI}">{$news_curr_key}</a>
		{else}{$news_curr_key}
		{/if}
	{else}
		{if $news_curr_page.current == 'false'}&nbsp;|&nbsp;<a href="{$news_curr_page.URI}">{$news_curr_key}</a>
		{else}&nbsp;|&nbsp;{$news_curr_key}
		{/if}
	{/if}
{/foreach}
{/if}
</td></tr>
<tr class="piccolo"><td>&nbsp;</td></tr>
<tr><td colspan="2">
{include file=News/show_news.tpl}
</td></tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

&nbsp;<br />
<hr width="90%" align="center" />
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}