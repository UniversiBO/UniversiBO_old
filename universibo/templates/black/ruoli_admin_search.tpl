{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="98%" border="0" cellspacing="0" cellpadding="2" align="center" summary="">
<tr><td colspan="4" align="center">
<p class="Titolo">{$ruoliAdminSearch_langAction|escape:"htmlall"|nl2br}</p>
</td></tr>
{foreach from=$ruoliAdminSearch_users key=temp_key item=temp_currGroup}
<tr><td colspan="4" class="Normal">&nbsp;&nbsp;{$temp_key|escape:"htmlall"}</td></tr>
{foreach from=$temp_currGroup item=temp_currLink}
<tr>
<td width="4"><img src="tpl/black/invisible.gif" width="4" height="2" alt="" /></td>
<td class="Normal" width="100"><a href="{$temp_currLink.utente_link|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a></td>
<td class="Normal" width="100">{if $temp_currLink.ruolo=="R"}&nbsp;<img src="tpl/black/icona_3_r.gif" width="9" height="9" alt="Referente" />&nbsp;Referente{/if}
{if $temp_currLink.ruolo=="M"}&nbsp;<img src="tpl/black/icona_3_m.gif" width="9" height="9" alt="Moderatore" />&nbsp;Moderatore{/if}
</td>
<td class="Normal" align="right">&nbsp;&nbsp;<a href="{$temp_currLink.edit_link|escape:"htmlall"}">Modifica</a> </td>
</tr>
{/foreach}
{/foreach}
<tr><td colspan="4" align="center"><p class="Titolo">{$ruoliAdminSearch_langSearch|escape:"htmlall"}</p>
{include file=avviso_notice.tpl}
</td></tr>
<form method="post">
<tr><td colspan="3" align="right">
<label for="f16_username">Cerca per username: </label>
</td>
<td><input name="f16_username" id="f16_username" type="text" value="" />
</td></tr>
<tr><td colspan="3" align="right">
<label for="f16_email">Cerca per e-mail: </label>
</td>
<td><input name="f16_email" id="f16_email" type="text" value="" />
</td></tr>
<tr><td colspan="4" align="center">
<input name="f16_submit" id="f16_submit" type="submit" value="Cerca" />
</td></tr>
</form>
</tr>
<tr><td colspan="4" align="center">
<p class="Normal"><a href=" Torna {$common_langCanaleUri|escape:"htmlall"|nl2br}">Torna {$common_langCanaleNome|escape:"htmlall"|nl2br}</a></p>
</td></tr>
</table>

<hr />
<p>{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}</p>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}