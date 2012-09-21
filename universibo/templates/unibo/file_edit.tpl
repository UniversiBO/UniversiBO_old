{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica il file</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><label class="label" for="f13_titolo">Titolo:</label>
		<input type="text" class="casella" id="f13_titolo" name="f13_titolo" size="65" maxlength="130" value="{$f13_titolo|escape:"htmlall"}" /></p>
	<p><span><label for="f13_abstract"><p>Abstract/descrizione<br /> del file:<br />(max 3000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f13_abstract" name="f13_abstract">{$f13_abstract|escape:"htmlall"}</textarea></span></p>
	<p><span><label for="f13_parole_chiave"><p>Parole chiave<br />(una per riga, max 4 parole)</p></label>
{* non indentare il foreach nella textarea *}
		<textarea cols="50" rows="4" id="f13_parole_chiave" name="f13_parole_chiave">{foreach from=$f13_parole_chiave item=temp_parola}{$temp_parola|escape:"htmlall"}
{/foreach}</textarea></span></p>
	<p><label for="f13_categoria">Categoria:</label>
		<select id="f13_categoria" name="f13_categoria">
		{foreach from=$f13_categorie item=temp_categoria key=temp_key}
		<option value="{$temp_key}" {if $temp_key==$f13_categoria} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
			<p><span><label for="f13_data_ins_gg">Giorno:</label>&nbsp;
				<input type="text" id="f13_data_ins_gg" name="f13_data_ins_gg" size="2" maxlength="2" value="{$f13_data_ins_gg|escape:"htmlall"}" />
			<label for="f13_data_ins_mm">Mese:</label>&nbsp;
				<input type="text"id="f13_data_ins_mm" name="f13_data_ins_mm" size="2" maxlength="2" value="{$f13_data_ins_mm|escape:"htmlall"}" />
			<label for="f13_data_ins_aa">Anno:</label>&nbsp;
				<input type="text" id="f13_data_ins_aa" name="f13_data_ins_aa" size="4" maxlength="4" value="{$f13_data_ins_aa|escape:"htmlall"}" />
			<label for="f13_data_ins_ora">Ore:</label>&nbsp;
				<input type="text" id="f13_data_ins_ora" name="f13_data_ins_ora" size="2" maxlength="2" value="{$f13_data_ins_ora|escape:"htmlall"}" />
			<label for="f13_data_ins_min">Minuti:</label>&nbsp;
				<input type="text" id="f13_data_ins_min" name="f13_data_ins_min" size="2" maxlength="2" value="{$f13_data_ins_min|escape:"htmlall"}" /></span></p>
		</fieldset></p>
	<p><label for="f13_tipo">Tipo file:</label>
		<select id="f13_tipo" name="f13_tipo">
		{foreach from=$f13_tipi item=temp_tipo key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f13_tipo} selected="selected"{/if}>{$temp_tipo|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><label for="f13_permessi_download">Permessi download:</label>
		<select id="f13_permessi_download" name="f13_permessi_download">
		<option value="127" {if "127"==$f13_permessi_download}selected="selected"{/if}>Tutti</option>
		<option value="126" {if "126"==$f13_permessi_download}selected="selected"{/if}>Solo iscritti</option>
		</select>
		<input type="hidden" id="f13_permessi_visualizza" name="f13_permessi_visualizza" value="127" /></p>
	<p><label for="f13_password_enable">Abilita password:</label>
		<input type="checkbox" id="f13_password_enable" name="f13_password_enable" {if $f13_password_enable=="true"}checked="checked"{/if} /></p>
	<p>Lasciare vuoto per non modificare la password corrente</p>
	<p><label for="f13_password">Password:</label>
		<input type="password" id="f13_password" name="f13_password" size="30" maxlength="130" value="{$f13_password|escape:"htmlall"}" /></p>
	<p><label for="f13_password_confirm">Conferma password:</label>
		<input type="password" id="f13_password_confirm" name="f13_password_confirm" size="30" maxlength="130" value="{$f13_password|escape:"htmlall"}" /></p>
	{if $fileEdit_flagCanali == 'true'}
	<p><fieldset>
		<legend>Il file verr&agrave; modificato nelle seguenti pagine:</legend>
			{foreach name=canali item=item from=$f13_canale}
				<p>{$item.nome_canale|escape:"htmlall"}</p>
			{/foreach}
	</fieldset></p>
	{/if}
	<p><input class="submit" type="submit" id="" name="f13_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$fileEdit_fileUri|escape:"htmlall"}">Torna&nbsp;ai&nbsp;dettagli&nbsp;del&nbsp;file</a></p>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}