{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>Aggiungi una nuova notizia</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post">
	<p><label for="f7_titolo">Titolo:</label>
		<input type="text" class="casella" id="f7_titolo" name="f7_titolo" size="65" maxlength="130" value="{$f7_titolo|escape:"htmlall"}" /></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
			<span><label for="f7_data_ins_gg">Giorno:</label>&nbsp;
				<input type="text" id="f7_data_ins_gg" name="f7_data_ins_gg" size="2" maxlength="2" value="{$f7_data_ins_gg|escape:"htmlall"}" />
			<label for="f7_data_ins_mm">Mese:</label>&nbsp;
				<input type="text" id="f7_data_ins_mm" name="f7_data_ins_mm" size="2" maxlength="2" value="{$f7_data_ins_mm|escape:"htmlall"}" />
			<label for="f7_data_ins_aa">Anno:</label>&nbsp;
				<input type="text" id="f7_data_ins_aa" name="f7_data_ins_aa" size="4" maxlength="4" value="{$f7_data_ins_aa|escape:"htmlall"}" />
			<label for="f7_data_ins_ora">Ore:</label>&nbsp;
				<input type="text" id="f7_data_ins_ora" name="f7_data_ins_ora" size="2" maxlength="2" value="{$f7_data_ins_ora|escape:"htmlall"}" />
			<label for="f7_data_ins_min">Minuti:</label>&nbsp;
				<input type="text" id="f7_data_ins_min" name="f7_data_ins_min" size="2" maxlength="2" value="{$f7_data_ins_min|escape:"htmlall"}" />
			</span>
	</fieldset></p>
	<p><span><label for="f7_testo"><p> Notizia:<br />(max 2500 <br />caratteri)</p></label>
		<textarea cols="50" rows="10" id="f7_testo" name="f7_testo">{$f7_testo|escape:"htmlall"}</textarea></span></p>
	<p><label for="f7_scadenza"><input type="checkbox" id="f7_scadenza" name="f7_scadenza" {if $f7_scadenza=='true'}checked="checked"{/if} />&nbsp;Attiva Scadenza</label></p>
	<p><fieldset>
	<legend>Data Scadenza:</legend>
		<span><label for="f7_data_scad_gg">Giorno:</label>&nbsp;
			<input type="text" id="f7_data_scad_gg" name="f7_data_scad_gg" size="2" maxlength="2" value="{$f7_data_scad_gg|escape:"htmlall"}" />
		<label for="f7_data_scad_mm">Mese:</label>&nbsp;
			<input type="text" id="f7_data_scad_mm" name="f7_data_scad_mm" size="2" maxlength="2" value="{$f7_data_scad_mm|escape:"htmlall"}" />
		<label for="f7_data_scad_aa">Anno:</label>&nbsp;
			<input type="text" id="f7_data_scad_aa" name="f7_data_scad_aa" size="4" maxlength="4" value="{$f7_data_scad_aa|escape:"htmlall"}" />
		<label for="f7_data_scad_ora">Ore:</label>&nbsp;
			<input type="text" id="f7_data_scad_ora" name="f7_data_scad_ora" size="2" maxlength="2" value="{$f7_data_scad_ora|escape:"htmlall"}" />
		<label for="f7_data_scad_min">Minuti:</label>&nbsp;
			<input type="text" id="f7_data_scad_min" name="f7_data_scad_min" size="2" maxlength="2" value="{$f7_data_scad_min|escape:"htmlall"}" />
		</span>
	</fieldset></p>
	
	<p><input type="checkbox" id="f7_urgente"  name="f7_urgente" {if $f7_urgente=='true'}checked="checked"{/if}/>&nbsp;<label for="f7_uregente">Invia il messaggio come urgente (invia la notifica anche via sms)</label></p>
	
	<p><fieldset>
		<legend><span>La notizia verr&agrave; inserita nelle pagine:</span></legend>
{include file="include/explodableList.tpl" lista=$f7_canale msg="Non si è referente di alcuna pagina attiva" name="f7_canale"}
		</fieldset></p>
	<p><input class="submit" type="submit" id="f7_submit" name="f7_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></p>
<hr />
{include file="Help/topic.tpl" showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
<hr />

{include file="footer_index.tpl"}