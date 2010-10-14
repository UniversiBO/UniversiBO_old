{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table align="center" cellspacing="0" cellpadding="0" width="95%" summary="">
<tr><td align="center"><p class="Titolo">&nbsp;<br />
<img src="tpl/black/impostazioni_personali_18.gif" width="200" height="22" alt="Impostazioni Personali" /></p>
</td></tr>
<tr><td class="Normal">
{include file=avviso_notice.tpl}
&nbsp;<br />
<p>{$showPersonalSettings_thanks|escape:"htmlall"|bbcode2html|nl2br}</p>
</td></tr></table>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}