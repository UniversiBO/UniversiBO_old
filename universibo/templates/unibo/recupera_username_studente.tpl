{include file="header_index.tpl"}
<h2>Recupera Username</h2>
{include file="avviso_notice.tpl"}

<form action="v2.php?do=RecuperaUsernameStudente&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f32" method="post">
	<p>{$recuperaUsernameStudente_langInfo|escape:"htmlall"|bbcode2html|nl2br}</p>
	<p><label class="label" for="f32_ad_user">{$recuperaUsernameStudente_langMail|escape:"htmlall"}</label>&nbsp;
		<input type="text" name="f32_ad_user" id="f32_ad_user" size="20" maxlength="30" value="{$f32_ad_user|escape:"html"}" tabindex="1"/>{$recuperaUsernameStudente_domain|escape:"htmlall"}</p>
	<p><label class="label" for="f32_password">{$recuperaUsernameStudente_langPassword|escape:"htmlall"}</label>&nbsp;
		<input type="password" name="f32_password" id="f32_password" size="20" maxlength="50" value="{$f32_password|escape:"html"}" tabindex="1"/></p>
	<p><input class="submit" type="submit" name="f32_submit" id="f32_submit" value="{$f32_submit|escape:"htmlall"}" tabindex="1"></p>
	<p>{$recuperaUsernameStudente_langHelp|escape:"htmlall"|bbcode2html|nl2br}</p>
</form>

{include file="footer_index.tpl"}