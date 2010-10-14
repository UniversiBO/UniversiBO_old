{include file=header_index.tpl}

<div class="titoloPagina">
<h2>Cancella il file</h2>
<p>{$fileDelete_langSuccess|escape:"htmlall"}</p>
</div>
{include file=avviso_notice.tpl}
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome|escape:"htmlall"}</a></p>

{include file=footer_index.tpl}