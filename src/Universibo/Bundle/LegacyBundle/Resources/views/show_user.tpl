{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>Utente: {$showUserNickname}</h2>
</div>
<p><span>Email: <a href="mailto:{$showEmailFirstPart}(at){$showEmailSecondPart}">{$showEmailFirstPart}<img src="{$common_basePath}/bundles/universibodesign/images/chiocciola.gif" width="16" height="16" alt="(at)" />{$showEmailSecondPart}</a>
{if $showDiritti == 'true'}
	&nbsp;<a href="{$showSettings}">Vai alle impostazioni personali</a>
{/if}
</span></p>
<p>Livello: {$showUserLivelli|escape:"htmlall"}
{if $showUser_UserHomepage != ''}<br />Vai alla <a href="{$showUser_UserHomepage|escape:"htmlall"}">homepage del docente</a> sul portale di Ateneo{/if}</p>

<div class="elenco">
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
	<tr><td colspan="3"><h3>Ruoli</h3></td></tr>
	{foreach name=ruoli from=$showCanali item=temp_currLink}<tr align="left">
		<td class="{if ($smarty.foreach.ruoli.iteration % 2) == 0}odd{else}even{/if}"><p class="{if $smarty.foreach.ruoli.iteration%2 == 0}odd{else}even{/if}"><a href="{$temp_currLink.uri}">{$temp_currLink.label|escape:"htmlall"}</a></td>
		<td class="{if $smarty.foreach.ruoli.iteration%2 == 0}odd{else}even{/if}"><span>{if $temp_currLink.ruolo=="R"}<img src="{$common_basePath}/bundles/universibodesign/images/icona_r.gif" width="9" height="9" alt="Referente" />{/if}{if $temp_currLink.ruolo=="M"}<img src="{$common_basePath}/bundles/universibodesign/images/icona_m.gif" width="9" height="9" alt="Moderatore" />{/if}</span></td>
		<td class="{if $smarty.foreach.ruoli.iteration%2 == 0}odd{else}even{/if}"><span>
			{if $showDiritti == 'true'}&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/esame_myuniversibo_edit.gif" width="15" height="15" alt="" />&nbsp;<a href="{$temp_currLink.modifica}">modifica</a>
			&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/esame_myuniversibo_del.gif" width="15" height="15" alt="" />&nbsp;<a href="{$temp_currLink.rimuovi}">rimuovi</a>{/if}</span>
		</td>
	</tr>{/foreach}
	{if $smarty.foreach.ruoli.total == 0}<tr><td>Nessun ruolo</td></tr>{/if}
</table>
	</div>
</p>
{include file="footer_index.tpl"}