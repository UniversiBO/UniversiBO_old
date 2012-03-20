{include file="header_index.tpl"}

<h2>Informazioni file</h2>
{include file=Files/show_info.tpl }
{if $isFileStudente=="true"}
{include file=Files/show_file_studenti_commenti.tpl }
{/if}
{include file="footer_index.tpl"}