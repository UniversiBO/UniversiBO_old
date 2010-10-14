<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
<tr align="center" ><td>
{if $titleSize|default:"small" == "big"}
<img src="tpl/black/files_30.gif" width="100" height="39" alt="News" /><br />
{else}
<img src="tpl/black/files_18.gif" width="57" height="22" alt="News" /><br />
{/if}
</td></tr>
<tr><td class="piccolo">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td class="piccolo">
&nbsp;{if $showFileTitoli_addFileFlag == "true"}<img src="tpl/black/file_new.gif" width="15" height="15" alt="Nuovo File" />
<a href="{$showFileTitoli_addFileUri|escape:"htmlall"}">{$showFileTitoli_addFile|escape:"htmlall"|bbcode2html|nl2br}</a>
&nbsp;&nbsp;&nbsp;{/if}<br />
</td>
</tr></table>
<tr>
<td class="Normal" align="center">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
{if $showFileTitoli_langFileAvailableFlag=="true"}
<tr class="piccolo"><td>&nbsp;</td></tr>
<tr>
{foreach name=listacategorie from=$showFileTitoli_fileList item=temp_categoria}
<td class="Normal" align="center" bgcolor="#000099"  colspan="9">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr>
	<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  </tr>
	</table>
</td></tr>
<tr><td class="Titolo" align="center" bgcolor="#000050"  colspan="9">
{$temp_categoria.desc|escape:"htmlall"}
</td></tr>
<td class="Normal" align="center" bgcolor="#000099"  colspan="9">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr>
	<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  </tr>
	</table>
</td></tr>


{foreach name=listafile from=$temp_categoria.file item=temp_file}
{*&nbsp;
	{if $smarty.foreach.listafile.iteration is odd}
{include file=Files/file_titolo.tpl titolo=$temp_file.titolo autore=$temp_file.autore autore_link=$temp_file.autore_link id_autore=$temp_file.id_autore data=$temp_file.data modifica=$temp_file.modifica modifica_link=$temp_file.modifica_link elimina=$temp_file.elimina elimina_link=$temp_file.elimina_link nuova=$temp_file.nuova dimensione=$temp_file.dimensione download_uri=$temp_file.download_uri background="#000032" show_info_uri=$temp_file.show_info_uri }
	{else}
{include file=Files/file_titolo.tpl titolo=$temp_file.titolo autore=$temp_file.autore autore_link=$temp_file.autore_link id_autore=$temp_file.id_autore data=$temp_file.data modifica=$temp_file.modifica modifica_link=$temp_file.modifica_link elimina=$temp_file.elimina elimina_link=$temp_file.elimina_link nuova=$temp_file.nuova dimensione=$temp_file.dimensione download_uri=$temp_file.download_uri background='#000016' show_info_uri=$temp_file.show_info_uri}
	{/if}*}
<tr valign="center" bgcolor="{cycle values="#000016,#000032"}" > 
<td align="left"><img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" hspace="1"/></td>
<td class="Normal" width="30">{$temp_file.data|escape:"htmlall"}&nbsp;&nbsp;
</td><td class="Normal" align="left"><a href="{$temp_file.show_info_uri|escape:"htmlall"}">{$temp_file.titolo|escape:"htmlall"|nl2br|truncate}</a>&nbsp;{if $temp_file.nuova=="true"}&nbsp;&nbsp;<img src="tpl/black/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
<td class="Normal" >
&nbsp;&nbsp;<a href="{$temp_file.autore_link|escape:"htmlall"}">{$temp_file.autore|escape:"htmlall"}</a></td>
<td class="Normal" align="right">&nbsp;&nbsp;{$temp_file.dimensione|escape:"htmlall"}&nbsp;kB&nbsp;&nbsp;</td>
<td valign="center" align="right">{if $temp_file.modifica!=""}<a href="{$temp_file.modifica_link|escape:"htmlall"}"><img src="tpl/black/file_edt.gif" border="0" width="15" height="15" alt="modifica" hspace="1"/></a>{/if}</td>
<td valign="center" align="right">{if $temp_file.elimina!=""}<a href="{$temp_file.elimina_link|escape:"htmlall"}"><img src="tpl/black/file_del.gif" border="0" width="15" height="15" alt="elimina" hspace="1"/></a>{/if}</td>
<td valign="center" align="right"><a href="{$temp_file.download_uri|escape:"htmlall"}"><img src="tpl/black/file_download.gif" border="0" width="15" height="15" alt="scarica il file" vspace="2" hspace="1"/></a></td>
</tr>
{/foreach}
{/foreach}

<tr bgcolor="#000099"> 
<td  colspan="9">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr>
	<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  </tr>
  </table>
</td></tr>

{else}
<tr>
<td class="Normal" align="center">
{$showFileTitoli_langFileAvailable}
{/if}
</td>
</tr></table>
</td>
</tr>
</table> 
