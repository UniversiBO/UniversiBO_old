{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica una pagina del tuo MyUniversiBO</h2>
</div>
{include file="avviso_notice.tpl"}

<form method="post">
	<p><label for="f19_livello_notifica">Tipo di notifica:</label>
		<select id="f19_livello_notifica" name="f19_livello_notifica">
		{foreach from=$f19_livelli_notifica item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f19_livello_notifica} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><label for="f19_nome">Voce personalizzata del menu:<br />(opzionale)</label>
		<input type="text" id="f19_nome" name="f19_nome" size="65" maxlength="130" value="{$f19_nome|escape:"htmlall"}" /></p>
	<p><input class="submit" type="submit" id="f19_submit" name="f19_submit" size="20" value="Invia" /></p>
</form>

<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}