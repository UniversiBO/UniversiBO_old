<div class="file_info">
<h3>Titolo:&nbsp;{$showFileInfo_titolo|escape:"htmlall"}</h3>
<p>&nbsp;Inserito da:&nbsp;<a href="{$showFileInfo_userLink|escape:"htmlall"}">{$showFileInfo_username|escape:"htmlall"}</a></p>
<p>&nbsp;Inserito il:&nbsp;{$showFileInfo_dataInserimento|escape:"htmlall"}</p>
<p>&nbsp;Titolo:&nbsp;{$showFileInfo_titolo|escape:"htmlall"}</p>
<p>&nbsp;Descrizione/abstract:&nbsp;{$showFileInfo_descrizione|escape:"htmlall"}</p>
<p>&nbsp;Parole chiave:&nbsp;{foreach from=$showFileInfo_paroleChiave item=temp_parola}{$temp_parola|escape:"htmlall"} {/foreach}</p>
<p>&nbsp;Categoria:&nbsp;{$showFileInfo_categoria|escape:"htmlall"}</p>
<p>&nbsp;Dimensione:&nbsp;{$showFileInfo_dimensione|escape:"htmlall"} kB</p>
<p>&nbsp;Scaricato:&nbsp;{$showFileInfo_download|escape:"htmlall"} volte</p>
<p>&nbsp;Formato file:<img src="{$common_basePath}/bundles/universibolegacy/images/icone_file/{$showFileInfo_icona|escape:"htmlall"}" width="32" height="32" alt="{$showFileInfo_tipo|escape:"htmlall"}" border="0" />&nbsp;{$showFileInfo_info|escape:"htmlall"|nl2br|bbcode2html}</p>
<p>&nbsp;Hash MD5:&nbsp;{$showFileInfo_hash|escape:"htmlall"}</p>
<p>&nbsp;Presente in:<br />
	{foreach from=$showFileInfo_canali item=temp_canale}&nbsp;&nbsp;<a href="{$temp_canale.uri|escape:"htmlall"}">{$temp_canale.titolo|escape:"htmlall"}</a><br />{/foreach}</p>
<p>&nbsp;{$showFileInfo_langDownload|escape:"htmlall"}:&nbsp;<a href="{$showFileInfo_downloadUri|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/file_download_32.gif" width="32" height="32" alt="{$showFileInfo_langDownload|escape:"htmlall"}" border="0" align="top" /></a></p>
 {if $showFileInfo_editFlag == 'true'}
 <p>&nbsp;{$showFileInfo_langEdit|escape:"htmlall"}:&nbsp;<a href="{$showFileInfo_editUri|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/file_edit_32.gif" width="32" height="32" alt="{$showFileInfo_langEdit|escape:"htmlall"}" border="0" align="top" /></a></p>
 {/if}
 {if $showFileInfo_deleteFlag == 'true'}
 <p>&nbsp;{$showFileInfo_langDelete|escape:"htmlall"}:&nbsp;<a href="{$showFileInfo_deleteUri|escape:"htmlall"}"><img src="{$common_basePath}/bundles/universibolegacy/images/file_del_32.gif" width="32" height="32" alt="{$showFileInfo_langDelete|escape:"htmlall"}" border="0" align="top" /></a></p>
 {/if}
</div>
<p class="comandi"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome|escape:"htmlall"}</a></p>

