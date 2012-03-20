{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Gestione Links</h2>
</div>
<p><a href="{$add_link_uri}">Aggiungi un link</a></p>
{include file=Links/show_links_extended.tpl}

<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

{include file="footer_index.tpl"}