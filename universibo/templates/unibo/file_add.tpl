{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Aggiungi un nuovo file</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><label class="label" for="f12_file">File:</label>
		<input class="submit" type="file" name="f12_file" id="f12_file" size="65" value="{$f12_file|escape:"htmlall"}" />
 		<input type="hidden" name="MAX_FILE_SIZE" value="20971520" /></p>
	<p><label class="label" for="f12_titolo">Titolo:</label>
		<input type="text" class="casella" id="f12_titolo" name="f12_titolo" size="65" maxlength="130" value="{$f12_titolo|escape:"htmlall"}" /></p>
	<p><span><label for="f12_abstract"><p>Abstract/descrizione<br /> del file:<br />(max 3000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f12_abstract" name="f12_abstract">{$f12_abstract|escape:"htmlall"}</textarea></span></p>
	<p><span><label for="f12_parole_chiave"><p>Parole chiave<br />(una per riga, max 4 parole)</p></label>
		{* non indentare il foreach nella textarea *}
		<textarea cols="50" rows="4" id="f12_parole_chiave" name="f12_parole_chiave">{foreach from=$f12_parole_chiave item=temp_parola}{$temp_parola|escape:"htmlall"}
{/foreach}</textarea></span></p>
	<p><label for="f12_categoria">Categoria:</label>
		<select id="f12_categoria" name="f12_categoria">
		{foreach from=$f12_categorie item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f12_categoria} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><fieldset>
		<legend>Data Inserimento:</legend>
		<p><span><label for="f12_data_ins_gg">Giorno:</label>&nbsp;
			<input type="text" id="f12_data_ins_gg" name="f12_data_ins_gg" size="2" maxlength="2" value="{$f12_data_ins_gg|escape:"htmlall"}" />
		<label for="f12_data_ins_mm">Mese:</label>&nbsp;
			<input type="text" id="f12_data_ins_mm" name="f12_data_ins_mm" size="2" maxlength="2" value="{$f12_data_ins_mm|escape:"htmlall"}" />
		<label for="f12_data_ins_aa">Anno:</label>&nbsp;
			<input type="text" id="f12_data_ins_aa" name="f12_data_ins_aa" size="4" maxlength="4" value="{$f12_data_ins_aa|escape:"htmlall"}" />
		<label for="f12_data_ins_ora">Ore:</label>&nbsp;
			<input type="text" id="f12_data_ins_ora" name="f12_data_ins_ora" size="2" maxlength="2" value="{$f12_data_ins_ora|escape:"htmlall"}" />
		<label for="f12_data_ins_min">Minuti:</label>&nbsp;
			<input type="text" id="f12_data_ins_min" name="f12_data_ins_min" size="2" maxlength="2" value="{$f12_data_ins_min|escape:"htmlall"}" />
	</span></p>
	</fieldset>
	<p><label for="f12_permessi_download">Permessi download:</label>
		<select id="f12_permessi_download" name="f12_permessi_download">
			<option value="127" selected="selected">Tutti</option>
			<option value="126" selected="selected">Solo iscritti</option>
		</select>
		<input type="hidden" id="f12_permessi_visualizza" name="f12_permessi_visualizza" value="127" /></p>
	<p><label for="f12_password">Password:</label>
		<input type="password" id="f12_password" name="f12_password" size="30" maxlength="130" value="{$f12_password|escape:"htmlall"}" />
	</p>
	<p><label for="f12_password_confirm">Conferma password:</label>
		<input type="password" id="f12_password_confirm" name="f12_password_confirm" size="30" maxlength="130" value="{$f12_password|escape:"htmlall"}" />
		</p>
	{if $fileAdd_flagCanali == 'true'}
	<fieldset>
		<legend>Il file verr&agrave; inserito nelle pagine:</legend>
{*		{foreach name=canali item=item from=$f12_canale}
			<p><input type="checkbox" id="f12_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f12_canale[{$item.id_canale}]" />&nbsp;&nbsp;&nbsp;<label for="f12_canale{$smarty.foreach.canali.iteration}">{$item.nome_canale}</label></p>
		{/foreach}  *}
		{include file=include/explodableList.tpl lista=$f12_canale msg="Non si � referente di alcuna pagina attiva" name="f12_canale"}
	</fieldset>
	{/if}
	<p><input class="submit" type="submit" id="" name="f12_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />
<p>{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}</p>

{include file="footer_index.tpl"}