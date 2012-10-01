{include file="header_index.tpl"}
{include file="avviso_notice.tpl"}
<div class="titoloPagina">
<h2>{$ruoliAdminEdit_langAction|escape:"htmlall"|nl2br}</h2>
</div>
<p>{$ruoliAdminEdit_langSuccess|escape:"htmlall"|nl2br}</p>
<p><a href="{$ruoliAdminEdit_userUri|escape:"htmlall"}">{$ruoliAdminEdit_username|escape:"htmlall"}</a></p>
<form method="post">
	<p><fieldset>
		<legend>Livello diritti nella pagina</legend>
			<p><input name="f17_livello" id="f17_livello_0" type="radio" {if $ruoliAdminEdit_userLivello == 'none'}checked="checked"{/if} value="none" />
			<label for="f17_livello_0">Nessuno</label></p>
			<p><input name="f17_livello" id="f17_livello_1" type="radio" {if $ruoliAdminEdit_userLivello == 'M'}checked="checked"{/if} value="M" />
			<label for="f17_livello_1">Moderatore</label></p>
			<p><input name="f17_livello" id="f17_livello_2" type="radio" {if $ruoliAdminEdit_userLivello == 'R'}checked="checked"{/if} value="R" />
			<label for="f17_livello_2">Referente</label></p>
	</fieldset></p>
<p><input class="submit" name="f17_submit" id="f17_submit" type="submit" value="Modifica" /></p>
</form>

<p><a href="{$common_canaleURI|escape:"htmlall"|nl2br}">Torna {$common_langCanaleNome|escape:"htmlall"|nl2br}</a></p>

{include file="footer_index.tpl"}