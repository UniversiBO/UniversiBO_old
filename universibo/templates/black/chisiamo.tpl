{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

<table summary="chi siamo" width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="Normal" align="left">
	{include file=avviso_notice.tpl}
	<div align="center">&nbsp;<br /><img src="tpl/black/chi_siamo_30.gif" width="179" height="39" alt="{$contacts_langAltTitle|escape:"htmlall"|bbcode2html}" /></div>
	<p>
	{$contacts_langIntro|escape:"htmlall"|bbcode2html|ereg_replace:"[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]":"<a href=\"\\0\" target=\"_blank\">\\0</a>"|ereg_replace:"[^<>[:space:]]+[[:alnum:]/]@[^<>[:space:]]+[[:alnum:]/]":"<a href=\"mailto:\\0\" target=\"_blank\">\\0</a>"|nl2br}      
	</p>
{foreach from=$contacts_langPersonal item=temp_curr_people}
	{include file=tabellina_contatto.tpl collaboratore=$temp_curr_people}
	<p>&nbsp;</p>	
{/foreach}
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}