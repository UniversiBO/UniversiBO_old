<tr>
<td class="Normal" align="center">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
	{if $showMyFileTitoli_langFileAvailableFlag=="true"}
	<tr class="piccolo"><td>&nbsp;</td></tr>
	<tr>
	{foreach name=showmyfiletitoli from=$showMyFileTitoli_fileList item=temp_file}
		<td class="Normal" align="center" bgcolor="#000099"  colspan="9">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  			<tr>
			<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
			<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  			</tr>
		</table>
		</td></tr>{*dove il tr?*}
		<tr><td class="Titolo" align="center" bgcolor="#000050"  colspan="9">
		{$temp_file.desc|escape:"htmlall"}
		</td></tr>
		<td class="Normal" align="center" bgcolor="#000099"  colspan="9">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  			<tr>
			<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
			<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  			</tr>
		</table>
		</td></tr>
		<tr valign="center" bgcolor="#000032" > 
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
		{foreach from=$temp_file.canali item=temp_canale}
			<tr><td align="left" class="Normal">
			<a href={$temp_canale.link|escape:"htmlall"}>{$temp_canale.titolo|escape:"htmlall"} </a>
			</td></tr>
		{/foreach}
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
{/foreach}</tr>
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
{$showMyFileTitoli_langFileAvailable}
{/if}
</td>
</tr></table>
</td></tr>
