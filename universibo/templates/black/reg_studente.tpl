{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td><img src="tpl/black/registrazione_18.gif" width="149" height="22" alt="{$regStudente_langRegAlt|escape:"htmall"}}" /></td></tr>
<tr><td class="Normal">
<form action="v2.php?do=RegStudente&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f4" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr><td class="Normal" colspan="2">&nbsp;<br />{$regStudente_langInfoReg|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
<tr>
<td colspan="2" class="Normal" align="center">
{include file=avviso_notice.tpl}
</td></tr><tr>
<td class="Normal" align="right" valign="middle" width="35%">
&nbsp;<br /><label for="f4_ad_user">{$regStudente_langMail|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f4_ad_user" id="f4_ad_user" size="20" maxlength="30" value="{$f4_ad_user|escape:"html"}" />{$regStudente_domain|escape:"htmlall"}
</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f4_password">{$regStudente_langPassword|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="password" name="f4_password" id="f4_password" size="20" maxlength="50" value="{$f4_password|escape:"html"}" /></td>
</tr>
<tr><td class="Normal" colspan="2">&nbsp;<br />&nbsp;<br />{$regStudente_langInfoUsername|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f4_username">{$regStudente_langUsername|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f4_username" id="f4_username" size="20" maxlength="25" value="{$f4_username|escape:"html"}" />
</td></tr>
<tr><td colspan="2" class="Normal" align="center">&nbsp;<br /><label for="f4_regolamento">{$regStudente_langReg|escape:"htmlall"}</label><br />
<textarea name="f4_regolamento" id="f4_regolamento" rows="5" cols="60"  wrap="phisical" readonly="readonly">{$f4_regolamento|escape:"htmlall"}</textarea><br />
&nbsp;<br /><label for="f4_privacy">{$regStudente_langPrivacy|escape:"htmlall"}</label><br />
<textarea name="f4_privacy" id="f4_privacy" rows="5" cols="60" readonly="readonly" >{$f4_privacy|escape:"htmlall"}</textarea><br />
<input type="checkbox" name="f4_confirm" id="f4_confirm" />&nbsp;&nbsp;<label for="f4_confirm"><strong>Confermo di aver letto il regolamento</strong></label><br />&nbsp;<br /></td>
</tr>
<tr><td colspan="2" class="Normal" align="center">
&nbsp;<br /><input type="submit" name="f4_submit" id="f4_submit" value="{$f4_submit|escape:"htmlall"}"></td>
</tr>
<tr><td colspan="2" class="Normal">
&nbsp;<br />{$regStudente_langHelp|escape:"htmlall"|bbcode2html|nl2br}</td>
</tr>
</table>
</form>
</td></tr></table>

&nbsp;
<hr>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}