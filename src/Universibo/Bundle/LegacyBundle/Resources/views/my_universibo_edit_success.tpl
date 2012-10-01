{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<div class="titoloPagina">
<h2>Modifica una nuova del tuo MyUniversiBO</h2>
<h4>La pagina &egrave; stata modificata con successo.</h4>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></p>
<h3><a href="{$showUser|escape:"htmlall"}">Torna&nbsp;alla&nbsp;tua&nbsp;scheda</a></h3>
</div>
<hr />
{include file="Help/topic.tpl" showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}