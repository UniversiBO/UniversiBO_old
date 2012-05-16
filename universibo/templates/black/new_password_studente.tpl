{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td><img src="tpl/black/recupera_password_18.gif" width="215" height="22" alt="{$newPasswordStudente_langNewPasswordAlt|escape:"htmall"}" /></td></tr>
<tr><td class="Normal">
<form action="v2.php?do=NewPasswordStudente&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f5" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr><td class="Normal" colspan="2">&nbsp;<br />{$newPasswordStudente_langInfoNewPassword|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
<tr>
<td colspan="2" class="Normal" align="center">
{include file=avviso_notice.tpl}
</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f5_username">{$newPasswordStudente_langUsername|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f5_username" id="f5_username" size="20" maxlength="25" value="{$f5_username|escape:"html"}" />
</td></tr>
<tr>
<td class="Normal" align="right" valign="middle" width="35%">
&nbsp;<br /><label for="f5_ad_user">{$newPasswordStudente_langMail|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f5_ad_user" id="f5_ad_user" size="20" maxlength="30" value="{$f5_ad_user|escape:"html"}" />{$newPasswordStudente_domain|escape:"htmlall"}
</td></tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f5_password">{$newPasswordStudente_langPassword|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="password" name="f5_password" id="f5_password" size="20" maxlength="50" value="{$f5_password|escape:"html"}" /></td>
</tr>
<tr><td colspan="2" font class="Normal" align="center">
&nbsp;<br /><input type="submit" name="f5_submit" id="f5_submit" value="{$f5_submit|escape:"htmlall"}"></td>
</tr>
<tr><td colspan="2" class="Normal">
&nbsp;<br />{$newPasswordStudente_langHelp|escape:"htmlall"|bbcode2html|nl2br}</td>
</tr></table>
</form>
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}