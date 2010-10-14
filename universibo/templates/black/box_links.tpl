{if $showLinks_linksListAvailable|default:"false" =="true"}
{include file=box_begin.tpl}
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr><td colspan="3"><img src="tpl/black/links_18.gif" width="62" height="22" alt="{$common_langLinksCanale|escape:"htmlall"}" /></td></tr>

{foreach from=$showLinks_linksList item=temp_currLink}
<tr>
<td valign="top"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
<td><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
<td class="Menu" width="100%"><a href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a>

</td>
</tr>
{/foreach}
{if $smarty.foreach.linkpagina.total == 0}<tr><td>Nessun link presente</td></tr>{/if}
{if $showLinks_linksPersonalizza == 'true'}
	<tr><td colspan="3" align="center">&nbsp;<br />
<a href="{$showLinks_linksAdminUri|escape:"htmlall"}">{$showLinks_linksAdminLabel|escape:"htmlall"}</a></td></tr>
{/if}
</table>
{include file=box_end.tpl}
{/if}
