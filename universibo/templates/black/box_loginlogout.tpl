{include file=box_begin.tpl}
{if $common_userLoggedIn=='false'}
<form action="v2.php?do=Login" name="form1_a" id="form1_a" method="post">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr> 
<td><img src="tpl/black/login_18.gif" width="69" height="22" alt="Login" /></td>
</tr>
<tr align="center"> 
<td class="Piccolo">&nbsp;<br /> <label for="f1_username">Username:</label><br /><input type="text" id ="f1_username" name="f1_username" size="9" maxlength="25" style="width: 120px" /><br />
<label for="f1_password">Password:</label><br /><input type="password" id="f1_password" name="f1_password" size="9" maxlength="25" style="width: 120px" /><br />
<input type="hidden" name="f1_resolution" value="" />
<input name="f1_submit" type="submit" value="Entra" onclick="document.form1_a.f1_resolution.value = screen.width;" /></td>
</tr>
<tr>
<td class="Menu">&nbsp;<br />
<!--
<script type="text/javascript" language="JavaScript">
document.write("<a href=\"javascript:universiboPopup('v2.php?do=RegStudente&amp;pageType=popup');\" ><font color=\"#FF0000\">Registrazione Studenti<\/font><\/a><br />");
</script>
<noscript>
-->
<a href="v2.php?do=RegStudente"><font color="#FF0000">Registrazione studenti</font></a><br />
<!--
</noscript>
<script type="text/javascript" language="JavaScript">
document.write("<a href=\"javascript:universiboPopup('v2.php?do=NewPasswordStudente&amp;pageType=popup');\">Password smarrita...<\/a><br \/>");
</script>
<noscript>-->
<a href="v2.php?do=RecuperaUsernameStudente">Username smarrito</a><br />
<a href="v2.php?do=NewPasswordStudente">Password smarrita</a><br />
<!--</noscript>-->
</td></tr>
</table></form>
{else}
<form action="v2.php?do=Logout" name="form2" id="form2" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td class="Normal" valign="middle" align="center">&nbsp;<br />
{$common_langWelcomeMsg|escape:"htmlall"|bbcode2html|nl2br} <font class="NormalC">{$common_userUsername|escape:"htmlall"}</font><br />
{$common_langUserLivello|escape:"htmlall"|bbcode2html|nl2br} <font class="NormalC">{foreach from=$common_userLivello item=temp_nomeLivello}{$temp_nomeLivello|escape:"htmlall"} {/foreach}</font><br />
&nbsp;<br />
<input name="f2_submit" type="submit" value="LogOut" /><br />&nbsp;
</td></tr>
</table>
</form>
{/if}
{include file=box_end.tpl}