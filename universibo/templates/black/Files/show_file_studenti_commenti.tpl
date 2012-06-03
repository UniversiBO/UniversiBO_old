<br />
	{if $isFileStudente == 'true'}
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
	 <tr><td class="Normal"><span class="NormalC">Voto&nbsp;medio:</span></td><td class="Normal" valign="middle" width="100%">&nbsp;{$showFileInfo_voto|escape:"htmlall"}</td></tr>
	 <br />
 	<tr><td class="Normal" colspan="2"><a href="{$showFileInfo_addComment|escape:"htmlall"}">Aggiungi il tuo commento!</a></td></tr>
 	</table>
 	<br />
 	{/if}
{if $showFileStudentiCommenti_langCommentiAvailableFlag == "true"}
	{foreach from=$showFileStudentiCommenti_commentiList item=temp_commenti}
	<table width="90%" align="center" border="0" cellspacing="0" cellpadding="0" summary="">
	<tr bgcolor="#000099">
	<td>
  		<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  			<tr>
				<td align="left"><img src="tpl/black//rule_piccoloL.gif" width="200" height="2" alt="" /></td>
  				<td align="right"><img src="tpl/black//rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  			</tr>
  		</table>
	</td></tr>
	<tr bgcolor="#000032">
	<td>
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" summary="">
	    <tr><td class="Normal" colspan="3"><span class="NormalC">Voto:</span> {$temp_commenti.voto}</td></tr>
		<tr><td class="Normal" width="10%"><span class="NormalC">Commento: </span>
		<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" summary="">
			<tr><td width="8%">&nbsp;</td><td class="Normal" valign="center" align="left" colspan="2">{$temp_commenti.commento|escape:"htmlall"|bbcode2html|linkify|nl2br}</td></td></tr>
		</table></td></tr>
		<tr><td class="Normal" colspan="3"><span class="NormalC">Autore:</span>&nbsp;<a href="{$temp_commenti.userLink|escape:"htmlall"}">{$temp_commenti.userNick}</a></td></tr>
		{if $temp_commenti.dirittiCommento=="true"}
		<tr><td class="Normal" colspan="3"><span>
			<a href="{$temp_commenti.editCommentoLink|escape:"htmlall"}">Modifica il commento</a>&nbsp;
			<a href="{$temp_commenti.deleteCommentoLink|escape:"htmlall"}">Cancella il commento</a>
		</span></td></tr>
		{/if}
	</table>
	</td>
	</tr>
	<tr bgcolor="#000099">
	<td>
  	<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  			<tr>
				<td align="left"><img src="tpl/black//rule_piccoloL.gif" width="200" height="2" alt="" /></td>
  				<td align="right"><img src="tpl/black//rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  			</tr>
  		</table>
		</td></tr>
		</table>
	<br />
	{/foreach}
	
{else}
<p> Non esistono commenti per questo file.</p>
{/if}