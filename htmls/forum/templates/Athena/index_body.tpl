<!-- BEGIN switch_xs_enabled -->
<?php

// This code will be visible only if eXtreme Styles mod is installed.

// highlight private message info if there is a new message
global $userdata;
if(!empty($userdata['user_new_privmsg']) && !empty($this->vars['PRIVATE_MESSAGE_INFO']))
{
	$this->vars['PRIVATE_MESSAGE_INFO'] = '<b>' . $this->vars['PRIVATE_MESSAGE_INFO'] . '</b>';
}

?>
<!-- END switch_xs_enabled -->
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" valign="bottom"><span class="gensmall">
	<!-- BEGIN switch_user_logged_in -->
	{PRIVATE_MESSAGE_INFO}<br />
	{LAST_VISIT_DATE}<br />
	<!-- END switch_user_logged_in -->
	{CURRENT_TIME}<br />
	{S_TIMEZONE}<br />
	</span><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	<td align="right" valign="bottom" class="gensmall">
		<!-- BEGIN switch_user_logged_in -->
		<a href="{U_SEARCH_NEW}" class="gensmall">{L_SEARCH_NEW}</a><br /><a href="{U_SEARCH_SELF}" class="gensmall">{L_SEARCH_SELF}</a><br />
		<!-- END switch_user_logged_in -->
		<a href="{U_SEARCH_UNANSWERED}" class="gensmall">{L_SEARCH_UNANSWERED}</a><br />
		<a href="{U_MARK_READ}" class="gensmall">{L_MARK_FORUMS_READ}</a></td>
  </tr>
</table>

<!-- BEGIN catrow -->
{TPL_HDR1}<span class="cattitle">&nbsp;<a href="javascript:ShowHide('cat_{catrow.CAT_ID}','cat2_{catrow.CAT_ID}','catrow_{catrow.CAT_ID}');" class="cattitle">{catrow.CAT_DESC}</a>&nbsp;</span>{TPL_HDR2}<div id="cat_{catrow.CAT_ID}" style="display: ''; position: relative;"><table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
<tr> 
	<th colspan="2" class="thCornerL" height="26" nowrap="nowrap">&nbsp;{L_FORUM}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
	<th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_POSTS}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
</tr>
<!-- BEGIN forumrow -->
<tr> 
	<td class="row3" align="center" valign="middle" width="30" height="30"><img src="{catrow.forumrow.FORUM_FOLDER_IMG}" width="27" height="24" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}" /></td>
	<td class="row1" width="100%" {C_ONMOUSEOVER}="this.style.backgroundColor='{C_ROW1_OVER}';" {C_ONMOUSEOUT}="this.style.backgroundColor='{C_ROW1}';" {C_ONCLICK}="window.location.href='{catrow.forumrow.U_VIEWFORUM}'"><span class="forumlink"> <a href="{catrow.forumrow.U_VIEWFORUM}" class="forumlink">{catrow.forumrow.FORUM_NAME}</a><br />
	  </span> <span class="genmed">{catrow.forumrow.FORUM_DESC}<br />
	  </span><span class="gensmall">{catrow.forumrow.L_MODERATOR} {catrow.forumrow.MODERATORS}</span></td>
	<td class="row2" align="center" valign="middle"><span class="gensmall">{catrow.forumrow.TOPICS}</span></td>
	<td class="row2" align="center" valign="middle"><span class="gensmall">{catrow.forumrow.POSTS}</span></td>
	<td class="row3" align="center" valign="middle" nowrap="nowrap"> <span class="gensmall">{catrow.forumrow.LAST_POST}</span></td>
</tr>
<!-- END forumrow -->
</table></div>{TPL_FTR}
<script language="javascript" type="text/javascript">
<!--
tmp = 'catrow_{catrow.CAT_ID}';
if(GetCookie(tmp) == '2')
{
	ShowHide('cat_{catrow.CAT_ID}','cat2_{catrow.CAT_ID}','catrow_{catrow.CAT_ID}');
}
//-->
</script>
<!-- END catrow -->

{TPL_HDR1}<span class="cattitle">&nbsp;<a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a>&nbsp;</span>{TPL_HDR2}<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<tr> 
	<td class="row3" align="center" valign="middle" rowspan="2"><img src="{T_TEMPLATE_PATH}/images/whosonline.gif" alt="{L_WHO_IS_ONLINE}" /></td>
	<td class="row1" align="left" width="100%"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}</span>
	</td>
</tr>
<tr> 
	<td class="row1" align="left"><span class="gensmall">{TOTAL_USERS_ONLINE} &nbsp; [ {L_WHOSONLINE_ADMIN} ] &nbsp; [ {L_WHOSONLINE_MOD} ]<br />{RECORD_USERS}<br />{LOGGED_IN_USER_LIST}<br />{L_ONLINE_EXPLAIN}</span></td>
</tr>
</table>{TPL_FTR}

<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
{TPL_HDR1}<a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span>{TPL_HDR2}<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<tr> 
	<td class="row1" align="center" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
		<input class="post" type="text" name="username" size="10" />
		&nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
		<input class="post" type="password" name="password" size="10" />
		&nbsp;&nbsp; &nbsp;&nbsp;{L_AUTO_LOGIN} 
		<input class="text" type="checkbox" name="autologin" checked="checked" />
		&nbsp;&nbsp;&nbsp; 
		<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
		</span> </td>
</tr>
</table>{TPL_FTR}
<!-- END switch_user_logged_out -->

<table cellspacing="3" border="0" align="center" cellpadding="0">
  <tr> 
	<td width="20" align="center"><img src="{T_TEMPLATE_PATH}/images/folder_new_big.gif" alt="{L_NEW_POSTS}"/></td>
	<td><span class="gensmall">{L_NEW_POSTS}</span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><img src="{T_TEMPLATE_PATH}/images/folder_big.gif" alt="{L_NO_NEW_POSTS}" /></td>
	<td><span class="gensmall">{L_NO_NEW_POSTS}</span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><img src="{T_TEMPLATE_PATH}/images/folder_locked_big.gif" alt="{L_FORUM_LOCKED}" /></td>
	<td><span class="gensmall">{L_FORUM_LOCKED}</span></td>
  </tr>
</table>
