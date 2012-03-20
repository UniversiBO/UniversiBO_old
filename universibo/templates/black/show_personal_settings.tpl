{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td align="center"><p class="Titolo">&nbsp;<br />
<img src="tpl/black/impostazioni_personali.gif" width="200" height="22" alt="Impostazioni personali" /></p>
</td></tr>
<tr align="center"><td class="Normal">
<form action="index.php?do=ShowPersonalSettings&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f20" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr><td class="Normal" colspan="2">&nbsp;<br />{$showPersonalSettings_langInfoChangeSettings|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
<tr>
<td colspan="2" class="Normal" align="center">
{include file=avviso_notice.tpl}
</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f20_email">{$showPersonalSettings_langEmail|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f20_email" id="f20_email" size="50" maxlength="50" value="{$f20_email|escape:"html"}" />
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f20_cellulare">{$showPersonalSettings_langPhone|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f20_cellulare" id="f20_cellulare" size="50" maxlength="50" value="{$f20_cellulare|escape:"html"}" /></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="News" align="right" valign="top"><label for="f20_livello_notifica">{$showPersonalSettings_langNotifyLevel|escape:"htmlall"}</label></td>
<td>
<select id="f20_livello_notifica" name="f20_livello_notifica">
{foreach from=$f20_livelli_notifica item=temp_categoria key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f20_livello_notifica} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
{/foreach}
</select>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="News" align="right" valign="top"><label for="f20_personal_style">{$showPersonalSettings_langStyle|escape:"htmlall"}</label></td>
<td>
<select id="f20_personal_style" name="f20_personal_style">
{foreach from=$f20_stili item=temp_categoria key=temp_key}
<option value="{$temp_key}" {if $temp_key==$f20_personal_style} selected="selected"{/if}>{$temp_categoria|escape:"htmlall"}</option>
{/foreach}
</select>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" font class="Normal" align="center">
&nbsp;<br /><input type="submit" name="f20_submit" id="f20_submit" value="{$f20_submit|escape:"htmlall"}"></td>
</tr>
<tr><td colspan="2" class="Normal">
<p>{$showPersonalSettings_langHelp|escape:"htmlall"|bbcode2html|nl2br}</p>
<p>Il servizio SMS viene fornito grazie al contributo dell'Alma Mater Studiorum</p>
</td>
</tr></table>
</form>
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}