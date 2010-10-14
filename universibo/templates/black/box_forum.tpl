{if $common_newPostsAvailable|default:"false" =="true"}
{include file=box_begin.tpl}
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr><td colspan="3"><img src="tpl/black/forum_18.gif" width="74" height="22" alt="{$common_langContactsCanale|escape:"htmlall"}" /></td></tr>

{section loop=$common_newPostsList name=temp_currPost max=10}
	<tr><td valign="top"><img src="tpl/black/freccia_4.gif" width="12" height="11" alt="" /></td>
	<td><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
	<td><a title="Questo link apre una nuova pagina" href="{$common_newPostsList[temp_currPost].URI|escape:"htmlall"}" target="_blank">{$common_newPostsList[temp_currPost].desc|escape:"htmlall"}</a></td></tr>
{sectionelse}<tr><td colspan="3">Nessun post nel forum</td></tr>
{/section}
</table>
{include file=box_end.tpl}
{/if}
