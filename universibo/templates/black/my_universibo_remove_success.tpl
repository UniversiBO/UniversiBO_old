{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

{include file=avviso_notice.tpl}

<table width="95%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Rimuovi una pagina dal tuo MyUniversiBO<br />&nbsp;</p></td></tr>
<tr><td align="center" class="Normal">La pagina &egrave; stata rimossa con successo.</td></tr>
<tr><td align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></td></tr>
<tr><td align="center" class="Normal"><a href="{$showUser|escape:"htmlall"}">Torna&nbsp;alla&nbsp;tua&nbsp;scheda</a></td></tr>
</table>

<br />
<hr width="90%" align="center"/>
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}