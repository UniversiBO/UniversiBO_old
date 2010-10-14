{* dato un array di link in formato bbcode lo dispone in una tabella di due colonne*}

{*parametro: array $arrayToShow*}

<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td align="center">
<table width="75%" border="0" cellspacing="0" cellpadding="0" summary="">

{section loop=$arrayToShow name=allitem} 

{if $smarty.section.allitem.last && $smarty.section.allitem.index is even}
	<tr bgcolor="#000032">
 	<td width="50%" align="center" class="Titolo">&nbsp;<br />{$arrayToShow[allitem]|escape:"htmlall"|bbcode2html}<br />&nbsp;<br /></td>
 	<td bgcolor="#000099" width="1"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
 	<td width="50%" align="center" class="Titolo">&nbsp;<br /></td>
 	</tr>
	<tr><td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
{elseif $smarty.section.allitem.last && $smarty.section.allitem.index is odd}
	<td align="center" class="Titolo">&nbsp;<br />{$arrayToShow[allitem]|escape:"htmlall"|bbcode2html}<br />&nbsp;<br /></td>
 	</tr>
	<tr><td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    	<img id="index" src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
{else}
	{if $smarty.section.allitem.first}
		<tr><td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
	    	<a name="index" id="index"></a><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
		<tr bgcolor="#000032">
 		<td width="50%" align="center" class="Titolo">&nbsp;<br />{$arrayToShow[allitem]|escape:"htmlall"|bbcode2html}<br />&nbsp;<br /></td>
 		<td bgcolor="#000099" width="1"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
	{elseif $smarty.section.allitem.index is odd}
	 	<td width="50%" align="center" class="Titolo">&nbsp;<br />{$arrayToShow[allitem]|escape:"htmlall"|bbcode2html}<br />&nbsp;<br /></td>
	 	</tr>
	 	<tr>
	 	<td colspan="3" align="center" bgcolor="#000099"><img src="tpl/black/invisible.gif" width="2" height="" alt="" /></td>
	 	</tr>
	{elseif $smarty.section.allitem.index is even}
		<tr bgcolor="#000032">
	 	<td width="50%" align="center" class="Titolo">&nbsp;<br />{$arrayToShow[allitem]|escape:"htmlall"|bbcode2html}<br />&nbsp;<br /></td>
	 	<td bgcolor="#000099" width="1"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
	{/if}
	
{/if}
{/section}

</table></td></tr>
</table>
