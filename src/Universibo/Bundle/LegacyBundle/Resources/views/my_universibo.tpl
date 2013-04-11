{include file="header_index.tpl"}
{include file="avviso_notice.tpl"}
<p>Vuoi provare in anteprima il nuovo UniversiBO? <a href="https://staging.universibo.unibo.it/beta/" target="_blank">Richiedi un invito!</a></p>
<div class="titoloPagina">
<h2>My UniversiBO</h2>
<p>Modifica <a href="{$showMyScheda|escape:"htmlall"}">MyUniversiBO</a></p>
</div>
<h2>My News</h2>
<p>Le tue ultime 5 notizie</p>
{include file="News/show_my_news.tpl"}
<h2>My Files</h2>
<p>I tuoi ultimi 5 files</p>
{include file="Files/show_my_file_titoli.tpl"}
{include file="footer_index.tpl"}
