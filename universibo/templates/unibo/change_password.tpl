{include file=header_index.tpl}
{include file=avviso_notice.tpl}
<h2>Modifica Password</h2>

<form action="index.php?do=ChangePassword&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f6" method="post">
	<p>{$changePassword_langInfoChangePassword|escape:"htmlall"|bbcode2html|nl2br}</p>
{*	<p><label for="f6_username">{$changePassword_langUsername|escape:"htmlall"}</label>&nbsp;
		<input type="text" name="f6_username" id="f6_username" size="20" maxlength="25" value="{$f6_username|escape:"html"}" /></p>*}
	<p><label for="f6_old_password">{$changePassword_langOldPassword|escape:"htmlall"}</label>&nbsp;
		<input type="password" name="f6_old_password" id="f6_old_password" size="20" maxlength="50" value="{$f6_old_password|escape:"html"}" /></p>
	<p><label for="f6_new_password1">{$changePassword_langNewPassword|escape:"htmlall"}</label>&nbsp;
		<input type="password" name="f6_new_password1" id="f6_new_password1" size="20" maxlength="50" value="{$f6_new_password1|escape:"html"}" /></p>
	<p><label for="f6_new_password2">{$changePassword_langReNewPassword|escape:"htmlall"}</label>&nbsp;
		<input type="password" name="f6_new_password2" id="f6_new_password2" size="20" maxlength="50" value="{$f6_new_password2|escape:"html"}" /></p>
	<p><input class="submit" type="submit" name="f6_submit" id="f6_submit" value="{$f6_submit|escape:"htmlall"}"></p>
	<p>{$changePassword_langHelp|escape:"htmlall"|bbcode2html|nl2br}</p>
</form>

{include file=footer_index.tpl}