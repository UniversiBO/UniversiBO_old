{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Download file</p></td></tr>
<tr><td align="center" class="Normal">
{include file=avviso_notice.tpl}
<p>Il download del file è permesso solo agli utenti registrati facenti parte dell'università di bologna.<br />
Esegui il login utilizzando il form sulla destra.<br />
Se non possiedi username e password per registrarti segui il link &quot;Registrazione Studenti&quot;.</p>
<p>La sessione potrebbe essere scaduta.</p>
</td></tr>
<tr><td align="center" class="Normal"><a href="{$fileDownload_InfoURI|escape:"htmlall"}">Torna&nbsp;indietro</a></td></tr>
</table>

&nbsp;
<hr align="center" />
<table width="90%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}