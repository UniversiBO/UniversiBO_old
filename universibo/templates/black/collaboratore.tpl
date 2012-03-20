{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

<table summary="chi siamo" width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="Normal" align="left">
	{include file=avviso_notice.tpl}
	<div align="center">&nbsp;<br /><a href="{$common_contactsUri|escape:"htmlall"}"><img src="tpl/black/chi_siamo_30.gif" width="179" height="39" border="0" alt="{$collaboratore_langAltTitle|escape:"htmlall"|bbcode2html}" /></a></div>
	<p class="Normal" align="center">
	{$collaboratore_langIntro|escape:"htmlall"|bbcode2html} <font class="NormalC">{$collaboratore_collaboratore.username|escape:"htmlall"}</font>   
	</p>
{include file=tabellina_contatto.tpl collaboratore=$collaboratore_collaboratore}
	<p>&nbsp;</p>	
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}