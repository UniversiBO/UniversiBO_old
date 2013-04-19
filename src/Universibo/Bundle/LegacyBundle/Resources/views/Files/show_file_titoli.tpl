<h2>Files</h2>
{if $showFileTitoli_addFileFlag == "true"}
<div class="comandi">
    <i class="icon-upload"></i>
	<a href="{$showFileTitoli_addFileUri|escape:"htmlall"}">{$showFileTitoli_addFile|escape:"htmlall"|bbcode2html|nl2br}</a>
    <i class="icon-th-list"></i>
    <a href="{$showFileTitoli_adminFileUri|escape:"htmlall"}">{$showFileTitoli_adminFile|escape:"htmlall"|bbcode2html|nl2br}</a>
</div>
{/if}
{if $showFileTitoli_langFileAvailableFlag=="true"}
{foreach name=listacategorie from=$showFileTitoli_fileList item=temp_categoria}
	<div class="elencoFile">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="" class="table">
			<tr><th colspan="8">{$temp_categoria.desc|escape:"htmlall"}</th></tr>
			{foreach name=listafile from=$temp_categoria.file item=temp_file}
				<tr class="{cycle values="even,odd"}">
				<td>&nbsp;&nbsp;{$temp_file.data|escape:"htmlall"}&nbsp;&nbsp;</td>
				<td><a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
				<td><a href="{$temp_file.autore_link|escape:"htmlall"}">{$temp_file.autore|escape:"htmlall"}</a></td>
				<td>&nbsp;&nbsp;{$temp_file.dimensione|escape:"htmlall"}&nbsp;kB&nbsp;&nbsp;</td>
				<td>{if $temp_file.modifica!=""}<a href="{$temp_file.modifica_link|escape:"htmlall"}"><i class="icon-edit" title="Modifica"></i>{/if}</td>
				<td>{if $temp_file.elimina!=""}<a href="{$temp_file.elimina_link|escape:"htmlall"}"><i class="icon-trash" title="Elimina"></i></a>{/if}</td>
				<td><a href="{$temp_file.download_uri|escape:"htmlall"}"><i class="icon-download" title="Scarica"></i></a></td></tr>
			{/foreach}
		</table>
</div>
{/foreach}
{else}
<p>{$showFileTitoli_langFileAvailable|escape:htmlall}</p>
{/if}
