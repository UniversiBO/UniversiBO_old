{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

<table align="center" cellspacing="0" cellpadding="0" width="95%" summary="">
<tr><td>
<img src="tpl/black/modifica_password_18.gif" width="200" height="22" alt="{$changePassowrd_langChangePassowrdAlt|escape:"htmall"}}" />
</td></tr>
<tr><td class="Normal">
{include file=avviso_notice.tpl}
&nbsp;<br />
<p>{$changePassword_thanks|escape:"htmlall"|bbcode2html|nl2br}</p>
</td></tr></table>
{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}