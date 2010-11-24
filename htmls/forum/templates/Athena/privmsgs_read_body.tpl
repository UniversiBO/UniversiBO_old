<table cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td valign="middle">{INBOX_IMG}</td>
	<td valign="middle"><span class="th2">{INBOX} &nbsp;</span></td>
	<td valign="middle">{SENTBOX_IMG}</td>
	<td valign="middle"><span class="th2">{SENTBOX} &nbsp;</span></td>
	<td valign="middle">{OUTBOX_IMG}</td>
	<td valign="middle"><span class="th2">{OUTBOX} &nbsp;</span></td>
	<td valign="middle">{SAVEBOX_IMG}</td>
	<td valign="middle"><span class="th2">{SAVEBOX}</span></td>
  </tr>
</table>

<br />

<form method="post" action="{S_PRIVMSGS_ACTION}">
{S_HIDDEN_FIELDS}
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	  <td valign="middle">{REPLY_PM_IMG}</td>
	  <td width="100%"><span class="nav">&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table>

{TPL_HDR1}<span class="cattitle">{POST_SUBJECT}</span>{TPL_HDR2}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="forumline">
<tr>
	<td class="th" align="right" valign="middle"><table border="0" cellspacing="0" cellpadding="2">
	<tr height="26">
		<td align="right" valign="bottom" nowrap="nowrap" height="26">{QUOTE_PM_IMG} {EDIT_PM_IMG}</td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- main table start -->
	<tr>
		<td width="150" align="left" valign="top" rowspan="2"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- left row table start -->
		<tr>
			<td width="100%" align="left" valign="top" background="{T_TEMPLATE_PATH}/images/post_bg.gif"><table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="3" height="3"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="3" height="3" border="0" /></td><td width="100%"></td>
			</tr>
			<tr>
				<td></td><td><table border="0" cellspacing="1" cellpadding="3" class="profileline">
			<tr>
				<td align="left" nowrap="nowrap" class="profile"><b>{L_FROM}</b></td>
				<td align="left" nowrap="nowrap" class="profile">{MESSAGE_FROM}</td>
			</tr>
			<tr>
				<td align="left" nowrap="nowrap" class="profile"><b>{L_TO}</b></td>
				<td align="left" nowrap="nowrap" class="profile">{MESSAGE_TO}</td>
			</tr>
			<tr>
				<td align="left" nowrap="nowrap" class="profile"><b>{L_POSTED}</b></td>
				<td align="left" nowrap="nowrap" class="profile">{POST_DATE}</td>
			</tr>
			<tr>
				<td align="left" valign="middle" nowrap="nowrap" class="profile"><b>{L_SUBJECT}</b></td>
				<td align="left" valign="middle" class="profile">{POST_SUBJECT}</td>
			</tr>
			</table></td></tr></table></td>
			<td width="5" background="{T_TEMPLATE_PATH}/images/post_right.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="5" height="1" border="0" /></td>
		</tr>
		<tr>
			<td height="10" background="{T_TEMPLATE_PATH}/images/post_bottom.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="10" border="0" /></td>
			<td width="5" height="10"><img src="{T_TEMPLATE_PATH}/images/post_corner.gif" width="5" height="10" border="0" /></td>
		</tr>
		<!-- left row table end --></table><br /><br /></td>
		<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right row table start -->
		<tr>
			<td width="100%"><span class="postbody">{MESSAGE}</span></td>
		</tr>
		<!-- right row table end --></table></td>
	</tr>
	<tr>
		<td class="row1" align="right" valign="bottom" nowrap="nowrap">
			<input type="submit" name="save" value="{L_SAVE_MSG}" class="liteoption" /><input type="submit" name="delete" value="{L_DELETE_MSG}" class="liteoption" />
		</td>
	</tr>
	</table></td>
</tr>
<tr>
	<td height="28" align="center" valign="bottom" class="catBottom"><table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td width="170"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="170" height="1" border="0" /></td>
		<td width="100%" align="left" valign="middle" nowrap="nowrap">{PROFILE_IMG} {PM_IMG} {EMAIL_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {ICQ_IMG}</td>
	</tr></table></td>
</tr>
</table>{TPL_FTR}
  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td>{REPLY_PM_IMG}</td>
	  <td align="right" valign="top" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span></td>
	</tr>
</table>
</form>



<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
  <tr> 
	<td valign="top" align="right"><span class="gensmall">{JUMPBOX}</span></td>
  </tr>
</table>
