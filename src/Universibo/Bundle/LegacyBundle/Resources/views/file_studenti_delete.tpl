{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Cancella il file</h2>
</div>
{include file="avviso_notice.tpl"}
Sei sicuro di voler cancellare questo file?
<form method="post">
	<p><fieldset>
	<legend>{$f25_langAction|escape:"htmlall"}</legend>
	{$f25_canale|escape:"htmlall"}
	</fieldset></p>	  
	<p><input class="submit" type="submit" id="" name="f25_submit" size="20" value="Elimina" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome|escape:"htmlall"}</a></p>
{include file="Help/topic.tpl" showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}