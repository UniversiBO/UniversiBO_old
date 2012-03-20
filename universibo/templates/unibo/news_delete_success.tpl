{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>Cancella la notizia</h2>
</div>
{include file=avviso_notice.tpl}
<h4>{$NewsDelete_langSuccess|escape:"htmlall"}</h4>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></p>

{include file="footer_index.tpl"}