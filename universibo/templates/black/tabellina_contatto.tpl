{* collaboratore.username collaboratore.intro collaboratore.ruolo collaboratore.email collaboratore.recapito collaboratore.obiettivi collaboratore.foto collaboratore.id_utente *}

<table summary="{$collaboratore.username|escape:"html"}" align="center" width="90%" border="0" cellspacing="0" cellpadding="0" title="tabella con le informazioni su {$collaboratore.username|escape:"htmlall"}">
	<tr bgcolor="#000099"> 
	<td colspan="2">
		<table summary="" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
		<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
		</tr>
		</table>
	</td>
	</tr>
	<tr bgcolor="#000050"> 
		<td colspan="2" align="center" class="Titolo">{$collaboratore.username|escape:"htmlall"}</td>
	</tr>
	<tr bgcolor="#000099" align="center"> 
		<td colspan="2"><img src="tpl/black/invisible.gif" width="1" height="2" alt="" /></td>
	</tr>
	<tr bgcolor="#000032">
		<td class="Normal" align="center">&nbsp;<br /><img src="{$contacts_path}{$collaboratore.foto|escape:"htmlall"}" alt="foto di {$collaboratore.username|escape:"htmlall"}" width="60" height="80" /><br />&nbsp;</td>
  		<td class="Normal">&nbsp;<br />{$collaboratore.intro|escape:"html"}<br />&nbsp;</td>
	</tr>
	<tr bgcolor="#000099" align="center"> 
		<td colspan="2"><img src="tpl/black/invisible.gif" width="1" height="2" alt="" /></td>
	</tr>

	<tr bgcolor="#000032">
		<td width="30%" class="NormalC" valign="top" align="right">&nbsp;<br />ruolo principale:&nbsp;</td>
		<td class="Normal">&nbsp;<br />{$collaboratore.ruolo|escape:"htmlall"}<br />&nbsp;</td>
	</tr>
	<tr bgcolor="#000032">
		<td width="30%" class="NormalC" valign="top" align="right">e-mail:&nbsp;</td>
		<td class="Normal"><a href="mailto:{$collaboratore.email|escape:"htmlall"}">{$collaboratore.email|escape:"htmlall"}</a><br />&nbsp;</td>
	</tr>
	<tr bgcolor="#000032">
		<td width="30%" class="NormalC" valign="top" align="right">recapito telefonico:&nbsp;</td>
		<td class="Normal">{$collaboratore.recapito|escape:"htmlall"}<br />&nbsp;</td>
	</tr>
	<tr bgcolor="#000032">
		<td width="30%" class="NormalC" valign="top" align="right">about me:&nbsp;</td>			
        <td class="Normal">{$collaboratore.obiettivi|escape:"htmlall"|bbcode2html|nl2br}</td>
  	</tr>
	<tr bgcolor="#000032"><td colspan="2">&nbsp;</td></tr>

	<tr bgcolor="#000099"> 
	<td colspan="2">
		<table summary="" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
		<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
		</tr>
		</table>
	</td>
	</tr>

</table>
