<div class="box">
<h3>Contributi degli studenti</h3>
	<div class="contenuto">
{foreach name=listacategorie from=$showFileStudentiTitoli_fileList item=temp_categoria}
	   <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
			<tr><th colspan="8">{$temp_categoria.desc|escape:"htmlall"}</th></tr>
			{foreach from=$temp_categoria.file item=temp_file}
				<tr>
				<td><img src="{$common_basePath}/tpl/unibo/pallino1.gif" width="11" height="10" alt="" />&nbsp;<a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="{$common_basePath}/tpl/unibo/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
			{/foreach}
			
		</table>
{/foreach}
{if $smarty.foreach.listacategorie.total == 0}<p>Nessuno contributo disponibile</p>{/if} 
{if $showFileStudentiTitoli_addFileFlag == "true"}
	<p><a href="{$showFileStudentiTitoli_addFileUri|escape:"htmlall"}">{$showFileStudentiTitoli_addFile|escape:"htmlall"|bbcode2html|nl2br}</a></p>
{/if}
	</div>
</div>
