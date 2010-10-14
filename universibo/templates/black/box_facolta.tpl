{include file=box_begin.tpl}
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr><td colspan="3"><img src="tpl/black/facolta_18.gif" width="89" height="22" alt="{$common_fac}" /></td></tr>
{foreach from=$common_facLinks item=temp_currLink}
<tr>
<td valign="top"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
<td><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
<td class="Menu" width="100%"><a href="{$temp_currLink.uri|escape:"htmlall"}" >{$temp_currLink.label|lower|capitalize|escape:"htmlall"}</a></td>
</tr>
{/foreach}
</table>
{include file=box_end.tpl}