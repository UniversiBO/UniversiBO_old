{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table align="center" cellspacing="0" cellpadding="0" width="95%" summary="" align="center">
<tr><td>
<img src="tpl/black/registrazione_18.gif" width="149" height="22" alt="{$regStudente_langRegAlt|escape:"htmall"}}" />
</td></tr>
<tr><td class="Normal">
{include file=avviso_notice.tpl}
&nbsp;<br />
<p>{$regStudente_thanks|escape:"htmlall"|bbcode2html|nl2br}</p>
</td></tr></table>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}