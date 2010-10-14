{include file=box_begin.tpl}
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr> 
<td><img src="tpl/black/calendario_18.gif" width="117" height="22" alt="{$common_langCalendar|escape:"htmlall"}" /></td>
</tr>
<tr> 
<td class="Piccolo" align="center">
&nbsp;
<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center" summary="">
 <tr>
 <td colspan="7" class="CalMese" align="center">
 <img src="tpl/black/invisible.gif" width="1" height="4" alt="" /><br />
 {*<a href="{$common_calendarLink.uri|escape:"htmlall"}">{$common_calendarLink.label|escape:"htmlall"}</a><br />*}
 {$common_calendarLink.label|escape:"htmlall"}<br />
 <img src="tpl/black/invisible.gif" width="1" height="4" alt="" /></td>
 </tr>
<tr><td colspan="7"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td></tr>
 <tr bgcolor="#FFFFFF">
 <th class="FerialiD" align="center">L</th>
 <th class="FerialiD" align="center">M</th>
 <th class="FerialiD" align="center">M</th>
 <th class="FerialiD" align="center">G</th>
 <th class="FerialiD" align="center">V</th>
 <th class="FerialiD" align="center">S</th>
 <th class="DomenicheD" align="center">D</th>
 </tr>
<tr><td colspan="7"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td></tr>

{foreach from=$common_calendar item=temp_week}
<tr bgcolor="#FFFFFF">
{foreach from=$temp_week item=temp_day}
<td class="{if $temp_day.tipo=='none'}Piccolo{elseif $temp_day.tipo=='feriale'}Feriali{elseif $temp_day.tipo=='festivo'}Festivi{elseif $temp_day.tipo=='domenica'}Domeniche{/if}" {if $temp_day.today=='true'}bgcolor="#99CcDd" {/if}align="center">{$temp_day.numero|escape:"htmlall"}</td>
{/foreach}
</tr>
{/foreach}
</table>
&nbsp;<br />
</td>
</tr>
</table>
{include file=box_end.tpl}
