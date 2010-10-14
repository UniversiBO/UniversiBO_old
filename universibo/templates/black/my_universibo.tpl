{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

{include file=avviso_notice.tpl}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br /><img src="tpl/black/my_universibo_18s.gif" alt="My UniversiBO" height="22" width="140" /><br />&nbsp;</p></td></tr>
<tr><td align="center" class="Normal"><a href="{$showMyScheda|escape:"htmlall"}">Modifica&nbsp;MyUniversiBO</a></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center">
<img src="tpl/black/news_18.gif" alt="News" width="64" height="22" />
</td></tr>
<tr><td>{include file=News/show_my_news.tpl}</td></tr>
<tr><td align="center">
<img src="tpl/black/files_18.gif" alt="Files" width="57" height="22" />
<tr><td>{include file=Files/show_my_file_titoli.tpl}</td></tr>
</td></tr>
</table>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}