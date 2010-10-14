{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr> 
<td class="Normal"><img src="tpl/black/login_18.gif" width="69" height="22" alt="Login" /></td>
</tr>
<tr><td class="Normal" align="center">
<form action="{$common_receiverUrl}?do=Login" name="form1" method="post">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr> 
<td align="center">{include file=avviso_notice.tpl}</td>
</tr>
<tr align="center"> 
<td class="Piccolo">&nbsp;<br />Username:<br /><input type="text" name="f1_username" size="9" maxlength="25" style="width: 120px" value="{$f1_username_value|escape:"htmlall"}" /><br />
Password:<br /><input type="password" name="f1_password" size="9" maxlength="25" style="width: 120px" value="{$f1_password_value|escape:"htmlall"}" /><br />
<input type="hidden" name="f1_resolution" value="" />
<input type="hidden" name="f1_referer" value="{$f1_referer_value|escape:"htmlall"}" />
<input name="f1_submit" type="submit" value="Entra" onclick="document.form1.f1_resolution.value = screen.width;" /></td>
</tr>
</table></form>

</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}
