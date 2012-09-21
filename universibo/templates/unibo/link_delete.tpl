{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Elimina il link</h2>
</div>
{include file="avviso_notice.tpl"}

{include file=Links/single_link.tpl}

<form method="post">
	<p><input class="submit" type="submit" id="f30_submit" name="f30_submit" size="20" value="Elimina questo link" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

<hr />

{include file="footer_index.tpl"}