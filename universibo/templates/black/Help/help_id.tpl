{* showHelpId_langArgomento     array di argomenti (id, titolo, contenuto) passato da ShowHelpId, 
   indice 		boolean per mostrare o meno l'indice,
   idsu			stringa contenente l'id dell'ancora a inizio pagina [campo obbligatorio] *}
<table id="table" width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
{if $indice}
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    <a id="index" /><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
<tr><td>	
	<table width="100%" border="0" cellspacing="0" cellpadding="3" summary="">
	
	{foreach from=$showHelpId_langArgomento item=temp_helpid}
	<tr><td class="Normal" bgcolor="{cycle name=index values="#000032,#000016"}">&nbsp;<img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" />
	<a href="#{$temp_helpid.id|escape:"htmlall"}"> {$temp_helpid.titolo|escape:"htmlall"}</a></td></tr>
	{/foreach}
	 
	</table>
</td></tr>
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
<tr><td class="Normal">&nbsp;<br />&nbsp;</td></tr>	
{/if}
<tr><td class="Normal">&nbsp;</td></tr>	

<tr><td>	
	<table width="100%" border="0" cellspacing="0" cellpadding="4" summary="">
	{foreach from=$showHelpId_langArgomento item=temp_helpid}
	
	<tr bgcolor="#000032"><td id="{$temp_helpid.id|escape:"htmlall"}" class="NormalC">&nbsp;<img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" />{$temp_helpid.titolo|escape:"htmlall"}</td>
	<td align="right" class="Piccolo"><a href="#{$idsu}">torna su</a> </td></tr>
	<tr><td colspan="2" class="Normal" bgcolor="#000016">{$temp_helpid.contenuto|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
	<tr><td>&nbsp;</td></tr>
	{/foreach} 
	</table>

</td></tr>
</table>