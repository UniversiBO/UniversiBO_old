{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}
{include file=avviso_notice.tpl}
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Utente: {$showUserNickname}<br />&nbsp;</p></td></tr>
<tr><td>
<tr><td<span>Email: <a href="mailto:{$showEmailFirstPart}(at){$showEmailSecondPart}">{$showEmailFirstPart}<img src="tpl/black/chiocciola.gif" border="0" width="16" height="16" alt="(at)" />{$showEmailSecondPart}</a>
{if $showDiritti == 'true'}
	&nbsp;<a href="{$showSettings}">Modifica</a>
{/if}
</span></td></tr>
<tr><td>&nbsp</td></tr>
<tr><td><p>Livello: {$showUserLivelli|escape:"htmlall"}</p></td></tr>
<tr><td>&nbsp</td></tr>
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center"> 
			    <tr><td bgcolor="#000099">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
						<tr>
						 <td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
						 <td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
						 </tr>
						</table>
						</td></tr>
				<tr><td class="Titolo" align="center" bgcolor="#000050">Ruoli</td></tr>
     			<tr bgcolor="#000099"><td><img src="tpl/black/invisible.gif" width="200" height="2" alt="" /></td></tr>
</table> 
<table width="90%" border="0" cellspacing="0" cellpadding="1" summary="" align="center">
	{foreach from=$showCanali item=temp_currLink name=showCanali}
	<tr><td class="Menu" bgcolor="{if $smarty.foreach.showCanali.iteration%2 == 0}#000032{else}#000016{/if}">&nbsp;<img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" />
	<a href="{$temp_currLink.uri}">{$temp_currLink.label|escape:"htmlall"}</a></td>
	<td class="Menu" bgcolor="{if $smarty.foreach.showCanali.iteration%2 == 0}#000032{else}#000016{/if}">
	{if $temp_currLink.ruolo=="R"}<img src="tpl/black/icona_3_r.gif" width="9" height="9" alt="Referente" />
	{/if}
	{if $temp_currLink.ruolo=="M"}<img src="tpl/black/icona_3_m.gif" width="9" height="9" alt="Moderatore" />
	{/if}
	{if $temp_currLink.ruolo!="M" && $temp_currLink.ruolo!="R"}&nbsp;&nbsp;&nbsp;{/if}
	{if $showDiritti == 'true'}
	&nbsp;<a href="{$temp_currLink.modifica}"><img src="tpl/black/esame_myuniversibo_edit.gif" border="0" width="15" height="15" alt="Modifica" /></a>
	&nbsp;<a href="{$temp_currLink.rimuovi}"><img src="tpl/black/esame_myuniversibo_del.gif" border="0" width="15" height="15" alt="Rimuovi dal tuo MyUniversiBO" /></a>
	{/if}
	</td></tr>
	{/foreach}
	{if $smarty.foreach.showCanali.total == 0}<tr><td class="Menu" bgcolor="#000032">&nbsp;Nessun ruolo</td></tr>{/if}
	
</table>
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center"><tr><td bgcolor="#000099" align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table> 
</td></tr>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}