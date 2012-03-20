{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Modifica una pagina del tuo MyUniversiBO<br />&nbsp;</p></td></tr>
<tr><td align="center"></td></tr>
</table>
{include file=avviso_notice.tpl}
<form method="post">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="">
<tr>
<td class="News" align="right" valign="top"><label for="f19_livello_notifica">Tipo di notifica:</label></td>
<td>
<select id="f19_livello_notifica" name="f19_livello_notifica">
{foreach from=$f19_livelli_notifica item=temp_categoria key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f19_livello_notifica} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
{/foreach}
</select>
</td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f19_nome">Voce personalizzata del menu:<br />(opzionale)</label></td>
<td><input type="text" id="f19_nome" name="f19_nome" size="65" maxlength="130" value="{$f19_nome|escape:"htmlall"}" /></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" id="f19_submit" name="f19_submit" size="20" value="Invia" /></td>
</tr>
</table>
</form>

<br />
<hr width="90%" align="center"/>
<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>


{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}