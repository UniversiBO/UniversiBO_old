{if $showFileStudentiTitoli_langFileAvailableFlag|default:"false" =="true"}
{include file=box_begin.tpl}
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr><td colspan="3"><img src="tpl/black/files_18.gif" width="57" height="22" alt="Contributi degli studenti" /></td></tr>

{foreach from=$showFileStudentiTitoli_fileList item=temp_categoria}
<tr>
<td><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
<tr><th colspan="8">{$temp_categoria.desc|escape:"htmlall"}</th></tr>
			{foreach name=listafile from=$temp_categoria.file item=temp_file}
				<tr>
				<td class="Menu" width="100%"><img src="tpl/black/pallino1.gif" width="11" height="10" alt="" />&nbsp;<a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="tpl/unibo/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
			{/foreach}
</tr>
{/foreach}
{if $showFileStudentiTitoli_addFileFlag == 'true'}
    <tr><td>&nbsp;</td></tr>
	<tr><td colspan="3" align="center"><a href="{$showFileStudentiTitoli_addFileUri|escape:"htmlall"}">{$showFileStudentiTitoli_addFile|escape:"htmlall"|bbcode2html|nl2br}</a></td></tr>
{/if}
</table>
{include file=box_end.tpl}
{/if}
