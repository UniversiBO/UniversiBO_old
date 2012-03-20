{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

<table align="center" cellspacing="0" cellpadding="0" width="90%" summary="" align="center">
<tr>
<td class="Normal" align="center">&nbsp;<br />&nbsp;<br />
<img src="tpl/black/questionario_18.gif" width="144" height="22" alt="{$question_TitleAlt|escape:"htmlall"}" />
&nbsp;<br />
{include file=avviso_notice.tpl}
<p align="center" class="Normal">{$question_thanks|escape:"htmlall"|bbcode2html|nl2br}</p>
</td></tr></table>
{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}