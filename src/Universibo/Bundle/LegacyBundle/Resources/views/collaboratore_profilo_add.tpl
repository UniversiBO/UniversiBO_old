{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>Inserisci il tuo profilo</h2>
</div>
{include file="avviso_notice.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><label for="f36_ruolo">Ruolo:</label>
		<input type="text" class="casella" id="f36_ruolo" name="f36_ruolo" size="65" maxlength="130" value="{$f36_ruolo|escape:"htmlall"}" />
	</p>
	<p><label for="f36_email">Email:</label>
		<input type="text" class="casella" id="f36_email" name="f36_email" size="65" maxlength="130" value="{$f36_email|escape:"htmlall"}" />
	</p>
	<p><label for="f36_recapito">Recapito:</label>
		<input type="text" class="casella" id="f36_recapito" name="f36_recapito" size="65" maxlength="130" value="{$f36_recapito|escape:"htmlall"}" />
	</p>
	
	<p><label for="f36_foto">Foto:</label>
        <input class="submit" type="file" name="f36_foto" id="f36_foto" size="65" value="{$f36_foto|escape:"htmlall"}" />	
	</p>
	
	<p><span>
	   <label for="f36_intro">
	   <p> Intro:<br />(max 2500 <br/>caratteri)
	   </p>
	   </label>
		<textarea cols="50" rows="10" id="f36_intro" name="f36_intro">{$f36_intro|escape:"htmlall"}</textarea>
    </span></p>
	
	<p><span><label for="f36_obiettivi"><p> Obiettivi:<br />(max 2500 <br />caratteri)</p></label>
		<textarea cols="50" rows="10" id="f36_obiettivi" name="f36_obiettivi">{$f36_obiettivi|escape:"htmlall"}</textarea></span></p>
	
	<p><input class="submit" type="submit" id="f36_submit" name="f36_submit" size="20" value="Invia"/></p>
</form>

{include file="footer_index.tpl"}
