{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Aggiungi un nuovo file</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><label class="label" for="f23_file">File:</label>
		<input class="submit" type="file" name="f23_file" id="f23_file" size="65" value="{$f23_file|escape:"htmlall"}" />
 		<input type="hidden" name="MAX_FILE_SIZE" value="20971520" /></p>
	<p><label class="label" for="f23_titolo">Titolo:</label>
		<input type="text" class="casella" id="f23_titolo" name="f23_titolo" size="65" maxlength="130" value="{$f23_titolo|escape:"htmlall"}" /></p>
	<p><span><label for="f23_abstract"><p>Abstract/descrizione<br /> del file:<br />(max 3000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f23_abstract" name="f23_abstract">{$f23_abstract|escape:"htmlall"}</textarea></span></p>
	<p><span><label for="f23_parole_chiave"><p>Parole chiave<br />(una per riga, max 4 parole)</p></label>
		{* non indentare il foreach nella textarea *} 
		<textarea cols="50" rows="4" id="f23_parole_chiave" name="f23_parole_chiave">{foreach from=$f23_parole_chiave item=temp_parola}{$temp_parola|escape:"htmlall"}
{/foreach}</textarea></span></p>
	<p><label for="f23_categoria">Categoria:</label>
		<select id="f23_categoria" name="f23_categoria">
		{foreach from=$f23_categorie item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f23_categoria} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
		<p><span><label for="f23_data_ins_gg">Giorno:</label>&nbsp;
			<input type="text" id="f23_data_ins_gg" name="f23_data_ins_gg" size="2" maxlength="2" value="{$f23_data_ins_gg|escape:"htmlall"}" />
		<label for="f23_data_ins_mm">Mese:</label>&nbsp;
			<input type="text" id="f23_data_ins_mm" name="f23_data_ins_mm" size="2" maxlength="2" value="{$f23_data_ins_mm|escape:"htmlall"}" />
		<label for="f23_data_ins_aa">Anno:</label>&nbsp;
			<input type="text" id="f23_data_ins_aa" name="f23_data_ins_aa" size="4" maxlength="4" value="{$f23_data_ins_aa|escape:"htmlall"}" />
		<label for="f23_data_ins_ora">Ore:</label>&nbsp;
			<input type="text" id="f23_data_ins_ora" name="f23_data_ins_ora" size="2" maxlength="2" value="{$f23_data_ins_ora|escape:"htmlall"}" />
		<label for="f23_data_ins_min">Minuti:</label>&nbsp;
			<input type="text" id="f23_data_ins_min" name="f23_data_ins_min" size="2" maxlength="2" value="{$f23_data_ins_min|escape:"htmlall"}" />
	</span></p>
	</fieldset>
	<p><label for="f23_permessi_download">Permessi download:</label>
		<select id="f23_permessi_download" name="f23_permessi_download">
			<option value="127" selected="selected">Tutti</option>
			<option value="126" selected="selected">Solo iscritti</option>
		</select>
		<input type="hidden" id="f23_permessi_visualizza" name="f23_permessi_visualizza" value="127" /></p>
	{if $fileAdd_flagCanali == 'true'}
	<fieldset>
		<legend>Il file verr&agrave; inserito nella pagina:</legend>
			<p><label for="f23_canale">{$f23_canale}</label></p>
	</fieldset>
	{/if}
	<p><input class="submit" type="submit" id="" name="f23_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}