 
<form action="{S_LOGIN_ACTION}" method="post" target="_top">

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
  <tr> 
	<th height="25" class="thHead" nowrap="nowrap">{L_ENTER_PASSWORD}</th>
  </tr>
  <tr> 
	<td class="row1"><table border="0" cellpadding="3" cellspacing="1" width="100%">
		  <tr> 
			<td colspan="2" align="center">&nbsp;</td>
		  </tr>
		  <tr> 
			<td width="45%" align="right"><span class="gen">{L_USERNAME}:</span></td>
			<td> 
			 <!-- <input type="text" name="username" size="25" maxlength="40" value="{USERNAME}" />-->
			 <input type="text" name="f1_username" size="25" maxlength="40" value="{USERNAME}" tabindex="1" />
			</td>
		  </tr>
		  <tr> 
			<td align="right"><span class="gen">{L_PASSWORD}:</span></td>
			<td> 
<!--			  <input type="password" name="password" size="25" maxlength="32" />-->
			  <input type="password" name="f1_password" size="25" maxlength="32" tabindex="1" />
			</td>
		  </tr>
		  <tr align="center"> 
			<!--<td colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" /></td>-->
			<td colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="f1_submit" class="mainoption" value="{L_LOGIN}" tabindex="1" /></td>
		  </tr>
		  <tr align="center"> 
			<td colspan="2"><span class="gensmall"><!--<a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a>--></span></td>
		  </tr>
		</table></td>
  </tr>
</table>

</form>
