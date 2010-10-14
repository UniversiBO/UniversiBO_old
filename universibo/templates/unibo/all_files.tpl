{include file=header_index.tpl}

<div class="titoloPagina">
<h2>Tutti i Files Studenti presenti su UniversiBO <br />
{$showAllFilesStudenti_titoloPagina|escape:"htmall"}
</h2>
</div>

{include file=Files/show_all_files_studenti_titoli.tpl}

<p class="comandi">
<a href="{$showAllFilesStudenti_url1|escape:"htmall"}">{$showAllFilesStudenti_lang1|escape:"htmall"}</a><br />
<a href="{$showAllFilesStudenti_url2|escape:"htmall"}">{$showAllFilesStudenti_lang2|escape:"htmall"}</a>
</p>

{include file=footer_index.tpl}
