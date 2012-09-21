{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Download file</h2>
</div>
{include file="avviso_notice.tpl"}

<p>Il download del file � permesso solo agli utenti registrati facenti parte dell'universit� di bologna.<br />
Esegui il login utilizzando il form sulla destra.<br />
Se non possiedi username e password per registrarti segui il link &quot;Registrazione Studenti&quot;.</p>
<p>La sessione potrebbe essere scaduta.</p>
<p> <a href="{$fileDownload_InfoURI|escape:"htmlall"}">Torna&nbsp;indietro</a></p>

<hr />

{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}