{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>Login errato!</h2>
</div>

{include file=avviso_notice.tpl}

<form action="{$common_receiverUrl}?do=Login" name="form1" method="post">
	<p><label class="label" for="f1_username">Username:</label><input type="text" id="f1_username" name="f1_username" size="9" maxlength="25" style="width: 120px" value="{$f1_username_value|escape:"htmlall"}" tabindex="1"/></p>
	<p><label class="label"for="f1_password">Password:</label><input type="password" id="f1_password" name="f1_password" size="9" maxlength="25" style="width: 120px" value="{$f1_password_value|escape:"htmlall"}"  tabindex="1"/></p>
	<input type="hidden" name="f1_resolution" value="" />
	<input type="hidden" name="f1_referer" value="{$f1_referer_value|escape:"htmlall"}" />
	<p><input class="submit" name="f1_submit" type="submit" value="Entra" onsubmit="document.form1.f1_resolution.value = screen.width;" /></p>
</form>
{* valutare se mettere nell'help *}
<p>Se non ricordi piu' il tuo username, <a href="index.php?do=RecuperaUsernameStudente">recupera il tuo username</a>
<br />Se non ricordi piu' la tua password, <a href="index.php?do=NewPasswordStudente">ripristina una nuova password</a><p>
		
{include file="footer_index.tpl"}