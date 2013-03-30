{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica il file</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><label for="f24_titolo">Titolo:</label>
		<input type="text" class="casella" id="f24_titolo" name="f24_titolo" size="65" maxlength="130" value="{$f24_titolo|escape:"htmlall"}" /></p>
	<p><span><label for="f24_abstract"><p>Abstract/descrizione<br /> del file:<br />(max 3000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f24_abstract" name="f24_abstract">{$f24_abstract|escape:"htmlall"}</textarea></span></p>
	<p><span><label for="f24_parole_chiave"><p>Parole chiave<br />(una per riga, max 4 parole)</p></label>
	{* non indentare il foreach nella textarea *}
		<textarea cols="50" rows="4" id="f24_parole_chiave" name="f24_parole_chiave">{foreach from=$f24_parole_chiave item=temp_parola}{$temp_parola|escape:"htmlall"}
{/foreach}</textarea></span></p>
	<p><label for="f24_categoria">Categoria:</label>
		<select id="f24_categoria" name="f24_categoria">
		{foreach from=$f24_categorie item=temp_categoria key=temp_key}
		<option value="{$temp_key}" {if $temp_key==$f24_categoria} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
			<p><span><label for="f24_data_ins_gg">Giorno:</label>&nbsp;
				<input type="text" id="f24_data_ins_gg" name="f24_data_ins_gg" size="2" maxlength="2" value="{$f24_data_ins_gg|escape:"htmlall"}" />
			<label for="f24_data_ins_mm">Mese:</label>&nbsp;
				<input type="text"id="f24_data_ins_mm" name="f24_data_ins_mm" size="2" maxlength="2" value="{$f24_data_ins_mm|escape:"htmlall"}" />
			<label for="f24_data_ins_aa">Anno:</label>&nbsp;
				<input type="text" id="f24_data_ins_aa" name="f24_data_ins_aa" size="4" maxlength="4" value="{$f24_data_ins_aa|escape:"htmlall"}" />
			<label for="f24_data_ins_ora">Ore:</label>&nbsp;
				<input type="text" id="f24_data_ins_ora" name="f24_data_ins_ora" size="2" maxlength="2" value="{$f24_data_ins_ora|escape:"htmlall"}" />
			<label for="f24_data_ins_min">Minuti:</label>&nbsp;
				<input type="text" id="f24_data_ins_min" name="f24_data_ins_min" size="2" maxlength="2" value="{$f24_data_ins_min|escape:"htmlall"}" /></span></p>
		</fieldset></p>
	<p><label for="f24_tipo">Tipo file:</label>
		<select id="f24_tipo" name="f24_tipo">
		{foreach from=$f24_tipi item=temp_tipo key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f24_tipo} selected="selected"{/if}>{$temp_tipo|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><label for="f24_permessi_download">Permessi download:</label>
		<select id="f24_permessi_download" name="f24_permessi_download">
		<option value="127" {if "127"==$f24_permessi_download}selected="selected"{/if}>Tutti</option>
		<option value="126" {if "126"==$f24_permessi_download}selected="selected"{/if}>Solo iscritti</option>
		</select>
		<input type="hidden" id="f24_permessi_visualizza" name="f24_permessi_visualizza" value="127" /></p>
	<p><input class="submit" type="submit" id="" name="f24_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$fileEdit_fileUri|escape:"htmlall"}">Torna&nbsp;ai&nbsp;dettagli&nbsp;del&nbsp;file</a></p>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />
{include file="Help/topic.tpl" showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}