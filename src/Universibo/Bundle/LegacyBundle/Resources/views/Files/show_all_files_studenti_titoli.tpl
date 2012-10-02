{if $showAllFilesStudentiTitoli_langFileAvailableFlag=="true"}

	<div class="elencoFile">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
		{foreach name=showmyfiletitoli from=$showAllFilesStudentiTitoli_fileList item=temp_file}
			<tr class="{if ($smarty.foreach.showmyfiletitoli.iteration % 2) == 0}odd{else}even{/if}">
				<td>&nbsp;&nbsp;{$temp_file.data|escape:"htmlall"}&nbsp;&nbsp;</td>
				<td><a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="{$common_basePath}/bundles/universibolegacy/images/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
				<td><a href="{$temp_file.autore_link|escape:"htmlall"}">{$temp_file.autore|escape:"htmlall"}</a></td>
				<td>&nbsp;&nbsp;{$temp_file.dimensione|escape:"htmlall"}&nbsp;kB&nbsp;&nbsp;</td>
				<td>Voto medio: {$temp_file.voto_medio}</td>
				<td>{if $temp_file.modifica!=""}<a href="{$temp_file.modifica_link|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/news_edt.gif" border="0" width="15" height="15" alt="modifica" hspace="1"/></a>{/if}</td>
				<td>{if $temp_file.elimina!=""}<a href="{$temp_file.elimina_link|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/file_del.gif" border="0" width="15" height="15" alt="elimina" hspace="1"/></a>{/if}</td>
				<td><a href="{$temp_file.download_uri|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/file_download.gif" border="0" width="15" height="15" alt="scarica il file" vspace="2" hspace="1"/></a>&nbsp;</td>
				<tr><td class="{if ($smarty.foreach.showmyfiletitoli.iteration % 2) == 0}odd{else}even{/if}" colspan="8" align="center"><a href={$temp_file.canaleLink|escape:"htmlall"}>{$temp_file.canaleTitolo|escape:"htmlall"} </a></td></tr>
			</tr>
			{/foreach}
		</table>
	</div>
&nbsp;<br />

{else}
<p>{$showAllFilesStudentiTitoli_langFileAvailable|escape:htmlall}</p>
{/if}