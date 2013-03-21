{if $showMyFileTitoli_langFileAvailableFlag=="true"}

	<span>
	<div class="elencoFile">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
		{foreach name=showmyfiletitoli from=$showMyFileTitoli_fileList item=temp_file}
			<tr><th colspan="8">{$temp_file.desc|escape:"htmlall"}</th></tr><tr>
				<td>&nbsp;&nbsp;{$temp_file.data|escape:"htmlall"}&nbsp;&nbsp;</td>
				<td><a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
				<td><a href="{$temp_file.autore_link|escape:"htmlall"}">{$temp_file.autore|escape:"htmlall"}</a></td>
				<td>&nbsp;&nbsp;{$temp_file.dimensione|escape:"htmlall"}&nbsp;kB&nbsp;&nbsp;</td>
				<td>{if $temp_file.modifica!=""}<a href="{$temp_file.modifica_link|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibodesign/images/news_edt.gif" border="0" width="15" height="15" alt="modifica" hspace="1"/></a>{/if}</td>
				<td>{if $temp_file.elimina!=""}<a href="{$temp_file.elimina_link|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibodesign/images/file_del.gif" border="0" width="15" height="15" alt="elimina" hspace="1"/></a>{/if}</td>
				<td><a href="{$temp_file.download_uri|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibodesign/images/file_download.gif" border="0" width="15" height="15" alt="scarica il file" vspace="2" hspace="1"/></a>&nbsp;</td></tr>
				{foreach from=$temp_file.canali item=temp_canale}
		<tr><td class="comandi" colspan="7"><a href={$temp_canale.link|escape:"htmlall"}>{$temp_canale.titolo|escape:"htmlall"} </a></td></tr>
	{/foreach}
		{/foreach}
	
	
		</table>
	</div>
	</span>
&nbsp;<br />

{else}
<p>{$showMyFileTitoli_langFileAvailable|escape:htmlall}</p>
{/if}
