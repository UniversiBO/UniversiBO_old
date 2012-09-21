{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Aggiungi una nuova pagina al tuo MyUniversiBO</h2>
</div>
{include file="avviso_notice.tpl"}

<form method="post">
	<p><label for="f15_livello_notifica">Tipo di notifica:</label>
		<select id="f15_livello_notifica" name="f15_livello_notifica">
		{foreach from=$f15_livelli_notifica item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f15_livello_notifica} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><label for="f15_nome">Voce personalizzata del menu:<br />(opzionale)</label>
		<input type="text" id="f15_nome" name="f15_nome" size="65" maxlength="130" value="{$f15_nome|escape:"htmlall"}" /></p>
	<p><input class="submit" type="submit" id="f15_submit" name="f15_submit" size="20" value="Invia" /></p>
</form>

<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}