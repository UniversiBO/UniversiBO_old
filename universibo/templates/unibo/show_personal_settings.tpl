{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Profilo</h2>
</div>

{include file="avviso_notice.tpl"}

<form id="f20" method="post">
	<p>{$showPersonalSettings_langInfoChangeSettings|escape:"htmlall"|bbcode2html|nl2br}</p>
	<p><label for="f20_email">{$showPersonalSettings_langEmail|escape:"htmlall"}</label>&nbsp;
		<input type="text" name="f20_email" id="f20_email" size="50" maxlength="50" value="{$f20_email|escape:"html"}" /></p>
	<p><label for="f20_cellulare">{$showPersonalSettings_langPhone|escape:"htmlall"}</label>&nbsp;
		<input type="text" name="f20_cellulare" id="f20_cellulare" size="50" maxlength="50" value="{$f20_cellulare|escape:"html"}" /></p>
	<p><label for="f20_livello_notifica">{$showPersonalSettings_langNotifyLevel|escape:"htmlall"}</label>
		<select id="f20_livello_notifica" name="f20_livello_notifica">
		{foreach from=$f20_livelli_notifica item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f20_livello_notifica} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><label for="f20_personal_style">{$showPersonalSettings_langStyle|escape:"htmlall"}</label>
		<select id="f20_personal_style" name="f20_personal_style">
		{foreach from=$f20_stili item=temp_categoria key=temp_key}
			<option value="{$temp_key}" {if $temp_key==$f20_personal_style} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
		{/foreach}
		</select></p>
	<p><input class="submit" type="submit" name="f20_submit" id="f20_submit" value="{$f20_submit|escape:"htmlall"}"></p>
	<p>{$showPersonalSettings_langHelp|escape:"htmlall"|bbcode2html|nl2br}</p>
	<p>Il servizio SMS viene fornito grazie al contributo dell'Alma Mater Studiorum</p>
</form>

{include file="footer_index.tpl"}