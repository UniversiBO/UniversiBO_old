{include file=header_index.tpl}

<div class="titoloPagina">
<h2>{$ShowPersonalFiles_langTitle|escape:"htmall"}
</h2>
</div>

{foreach name=files from=$ShowPersonalFiles_listaFile item=item}
{cycle name=t_class values="even,odd" print=false advance=false}
<div class="elenco">
	<p class="{cycle name=t_class}">&nbsp;{$item.data|escape:"htmlall"} | {$item.nome|escape:"htmlall"} | {$item.dimensione|escape:"htmlall"} KB | 
	<a href="{$item.editUri|escape:"htmlall"}"><img src="tpl/unibo/news_edt.gif" border="0" width="15" height="15" alt="modifica" hspace="1"/></a></p>
</div> 
{/foreach}


{*
<p class="comandi">
<a href="{$showAllFilesStudenti_url1|escape:"htmall"}">{$showAllFilesStudenti_lang1|escape:"htmall"}</a><br />
<a href="{$showAllFilesStudenti_url2|escape:"htmall"}">{$showAllFilesStudenti_lang2|escape:"htmall"}</a>
</p>
*}

{include file=footer_index.tpl}
