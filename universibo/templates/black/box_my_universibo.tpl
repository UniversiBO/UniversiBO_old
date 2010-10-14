{include file=box_begin.tpl}
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr><td colspan="3"><a href="{$common_myUniversiBOUri|escape:"htmlall"}" title="Vai alla pagina MyUniversiBO"><img border="0" src="tpl/black/my_universibo_18s.gif" width="140" height="22" alt="{$common_langMyUniversibo|escape:"htmlall"}" /></a></td></tr>
{if $common_myLinksAvailable=="true"}
{foreach name=my_universibo from=$common_myLinks item=temp_currLink}
<tr>
<td valign="top"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
<td><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
<td class="Menu" width="100%"><a href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a>
{if $temp_currLink.ruolo == "R"}&nbsp;<img src="tpl/black/icona_3_r.gif" width="9" height="9" alt="Referente" title="Referente" />{/if}
{if $temp_currLink.ruolo == "M"}&nbsp;<img src="tpl/black/icona_3_m.gif" width="9" height="9" alt="Moderatore" title="Moderatore" />{/if}
{if $temp_currLink.new == "true"}&nbsp;<img src="tpl/black/icona_new.gif" width="21" height="9" alt="!NEW!" title="!NEW!" />{/if}</td>
</tr>
{/foreach}
{if $smarty.foreach.my_universibo.total == 0}<tr><td class="Menu" align="center" width="100%">Non hai pagine in MyUniversiBO</td></tr>{/if}
{else}<tr><td>I servizi personalizzati sono disponibili solo agli utenti che hanno effettuato il login</td></tr>
{/if}
</table>
{include file=box_end.tpl}
