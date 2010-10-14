{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Modifica la notizia</h2>
</div>
{include file=News/show_news.tpl}

{include file=avviso_notice.tpl}

<form method="post">
	<p><label class="label" for="f8_titolo">Titolo:</label>
		<input type="text" class="casella" id="f8_titolo" name="f8_titolo" size="65" maxlength="130" value="{$f8_titolo|escape:"htmlall"}" /></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
		<p><span><label for="f8_data_ins_gg">Giorno:</label>&nbsp;
			<input type="text" id="f8_data_ins_gg" name="f8_data_ins_gg" size="2" maxlength="2" value="{$f8_data_ins_gg|escape:"htmlall"}" />
		<label for="f8_data_ins_mm">Mese:</label>&nbsp;
			<input type="text" id="f8_data_ins_mm" name="f8_data_ins_mm" size="2" maxlength="2" value="{$f8_data_ins_mm|escape:"htmlall"}" />
		<label for="f8_data_ins_aa">Anno:</label>&nbsp;
			<input type="text" id="f8_data_ins_aa" name="f8_data_ins_aa" size="4" maxlength="4" value="{$f8_data_ins_aa|escape:"htmlall"}" />
		<label for="f8_data_ins_ora">Ore:</label>&nbsp;
			<input type="text" id="f8_data_ins_ora" name="f8_data_ins_ora" size="2" maxlength="2" value="{$f8_data_ins_ora|escape:"htmlall"}" />
		<label for="f8_data_ins_min">Minuti:</label>&nbsp;
			<input type="text" id="f8_data_ins_min" name="f8_data_ins_min" size="2" maxlength="2" value="{$f8_data_ins_min|escape:"htmlall"}" /></span></p>
		</fieldset></p>
	<p><span><label for="f8_testo"><p> Notizia:<br />(max 3000 <br />caratteri)</p></label>
		<textarea cols="50" rows="10" id="f8_testo" name="f8_testo">{$f8_testo|escape:"htmlall"}</textarea></span></p>
	<p><label for="f8_scadenza"><input type="checkbox" id="f8_scadenza" name="f8_scadenza" {if $f8_scadenza=='true'}checked="checked"{/if} />&nbsp;Attiva Scadenza</label></p>
	<p><fieldset>
		<legend>Data Scadenza:</legend>
		<p><span><label for="f8_data_scad_gg">Giorno:</label>&nbsp;
			<input type="text" id="f8_data_scad_gg" name="f8_data_scad_gg" size="2" maxlength="2" value="{$f8_data_scad_gg|escape:"htmlall"}" />
		<label for="f8_data_scad_mm">Mese:</label>&nbsp;
			<input type="text" id="f8_data_scad_mm" name="f8_data_scad_mm" size="2" maxlength="2" value="{$f8_data_scad_mm|escape:"htmlall"}" />
		<label for="f8_data_scad_aa">Anno:</label>&nbsp;
			<input type="text" id="f8_data_scad_aa" name="f8_data_scad_aa" size="4" maxlength="4" value="{$f8_data_scad_aa|escape:"htmlall"}" />
		<label for="f8_data_scad_ora">Ore:</label>&nbsp;
			<input type="text" id="f8_data_scad_ora" name="f8_data_scad_ora" size="2" maxlength="2" value="{$f8_data_scad_ora|escape:"htmlall"}" />
		<label for="f8_data_scad_min">Minuti:</label>&nbsp;
			<input type="text"id="f8_data_scad_min" name="f8_data_scad_min" size="2" maxlength="2" value="{$f8_data_scad_min|escape:"htmlall"}" /></spam></p>
		</fieldset></p>
	<p><input type="checkbox" id="f8_urgente"  name="f8_urgente" {if $f8_urgente=='true'}checked="checked"{/if}/>&nbsp;Invia il messaggio come urgente</p>
	<p><fieldset>
		<legend>La notizia &egrave; presente e verr&agrave; modificata nelle seguenti pagine:</legend>
			{foreach name=canali item=item from=$f8_canale}
				<p>{$item.nome_canale|escape:"htmlall"}</p>
			{/foreach}
	</fieldset></p>
	<p><input class="submit" type="submit" id="" name="f8_submit" size="20" value="Modifica" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></p>

<hr />

{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file=footer_index.tpl}