{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td>
<img src="tpl/black/modifica_password_18.gif" width="200" height="22" alt="{$changePassowrd_langChangePassowrdAlt|escape:"htmall"}}" />
</td></tr>
<tr align="center"><td class="Normal">
<form action="v2.php?do=ChangePassword&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f6" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0" summary="">
<tr><td class="Normal" colspan="2">&nbsp;<br />{$changePassword_langInfoChangePassword|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
<tr>
<td colspan="2" class="Normal" align="center">
{include file=avviso_notice.tpl}
</td></tr>
{*<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f6_username">{$changePassword_langUsername|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="text" name="f6_username" id="f6_username" size="20" maxlength="25" value="{$f6_username|escape:"html"}" />
</td></tr>*}
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f6_old_password">{$changePassword_langOldPassword|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="password" name="f6_old_password" id="f6_old_password" size="20" maxlength="50" value="{$f6_old_password|escape:"html"}" /></td>
</tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f6_new_password1">{$changePassword_langNewPassword|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="password" name="f6_new_password1" id="f6_new_password1" size="20" maxlength="50" value="{$f6_new_password1|escape:"html"}" /></td>
</tr>
<tr>
<td class="Normal" align="right" valign="middle">&nbsp;<br /><label for="f6_new_password2">{$changePassword_langReNewPassword|escape:"htmlall"}</label>&nbsp;</td>
<td class="Normal">&nbsp;<br /><input type="password" name="f6_new_password2" id="f6_new_password2" size="20" maxlength="50" value="{$f6_new_password2|escape:"html"}" /></td>
</tr>
<tr><td colspan="2" font class="Normal" align="center">
&nbsp;<br /><input type="submit" name="f6_submit" id="f6_submit" value="{$f6_submit|escape:"htmlall"}"></td>
</tr>
<tr><td colspan="2" class="Normal">
&nbsp;<br />{$changePassword_langHelp|escape:"htmlall"|bbcode2html|nl2br}</td>
</tr></table>
</form>
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}