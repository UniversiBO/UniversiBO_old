{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica il link</h2>
</div>

{include file="Links/single_link.tpl"}

{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data" class="legacy-form">
	<p><label for="f31_URI">Indirizzo:</label>
		<input class="submit" type="text" name="f31_URI" id="f31_URI" size="65" value="{$f31_URI|escape:"htmlall"}" /></p>
	<p><label for="f31_Label">Etichetta:</label>
		<input type="text" class="casella" id="f31_Label" name="f31_Label" size="65" maxlength="130" value="{$f31_Label|escape:"htmlall"}" /></p>
	<p><span><label for="f31_Description"><p>Descrizione<br /> del link:<br />(max 1000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f31_Description" name="f31_Description">{$f31_Description|escape:"htmlall"}</textarea></span></p>
	<p><input class="submit" type="submit" id="" name="f31_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />

{include file="footer_index.tpl"}