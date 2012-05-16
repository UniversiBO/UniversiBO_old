<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td align="center"><a name="news"></a>
{if $titleSize|default:"small" == "big"}
<img src="tpl/black/news_30.gif" width="100" height="39" alt="News" /><br />
{else}
<img src="tpl/black/news_18.gif" width="64" height="22" alt="News" /><br />
{/if}
</td></tr>
<tr><td class="piccolo" align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td class="piccolo" align="left">
&nbsp;{if $showNewsLatest_addNewsFlag == "true"}<img src="tpl/black/news_new.gif" width="15" height="15" alt="" />
<a href="{$showNewsLatest_addNewsUri|escape:"htmlall"}">{$showNewsLatest_addNews|escape:"htmlall"|bbcode2html|nl2br}</a>
&nbsp;&nbsp;&nbsp;{/if}
</td><td class="piccolo" align="right">
&nbsp;{if $showNewsLatest_langNewsShowOthers != ""}<img src="tpl/black/news_all.gif" width="15" height="15" alt="" />
{*<script type="text/javascript" language="JavaScript"><!--
document.write("<a href=\"javascript:universiboPopup('{$showNewsLatest_langNewsShowOthersUri|escape:"htmlall"|nl2br}&amp;pageType=popup');\">{$showNewsLatest_langNewsShowOthers|escape:"htmlall"|bbcode2html|nl2br}<\/a>");
--></script>
<noscript><a href="v2.php?do={$showNewsLatest_langNewsShowOthersUri|escape:"htmlall"}" target="_popup">{$showNewsLatest_langNewsShowOthers|escape:"htmlall"|bbcode2html|nl2br}</a></noscript>*}
<a href="{$showNewsLatest_langNewsShowOthersUri|escape:"htmlall"}">{$showNewsLatest_langNewsShowOthers|escape:"htmlall"|bbcode2html|nl2br}</a>
{/if}
</td>
</tr></table>
</td></tr>
<tr>
{if $showNewsLatest_langNewsAvailableFlag=="true"}
<td class="Normal" align="left">
{foreach from=$showNewsLatest_newsList item=temp_news}
&nbsp;
{include file=News/news.tpl titolo=$temp_news.titolo notizia=$temp_news.notizia autore=$temp_news.autore autore_link=$temp_news.autore_link id_autore=$temp_news.id_autore data=$temp_news.data modifica=$temp_news.modifica modifica_link=$temp_news.modifica_link elimina=$temp_news.elimina elimina_link=$temp_news.elimina_link nuova=$temp_news.nuova scadenza=$temp_news.scadenza }
{/foreach}
{else}
<td class="Normal" align="center">
{$showNewsLatest_langNewsAvailable}
{/if}
</td>
</tr>
</table> 
