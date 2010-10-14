{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Cancella questo commento</h2>
</div>
{include file=avviso_notice.tpl}
{include file=Files/show_file_studenti_commento.tpl}
<form method="post" enctype="multipart/form-data">
	<p><input class="submit" type="submit" id="" name="f28_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

{include file=footer_index.tpl}