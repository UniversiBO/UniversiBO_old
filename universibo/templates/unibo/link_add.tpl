{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Aggiungi un nuovo link</h2>
</div>
{include file=avviso_notice.tpl}
<form method="post" enctype="multipart/form-data">
	<p><label class="label" for="f29_URI">Indirizzo:</label>
		<input class="submit" type="text" name="f29_URI" id="f29_URI" size="65" value="{$f29_URI|escape:"htmlall"}" />
	<p><label class="label" for="f29_Label">Etichetta:</label>
		<input type="text" class="casella" id="f29_Label" name="f29_Label" size="65" maxlength="130" value="{$f29_Label|escape:"htmlall"}" /></p>
	<p><span><label for="f29_Description"><p>Descrizione<br /> del link:<br />(max 1000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f29_Description" name="f29_Description">{$f29_Description|escape:"htmlall"}</textarea></span></p>
		</select></p>
	<p><input class="submit" type="submit" id="" name="f29_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />

{include file=footer_index.tpl}