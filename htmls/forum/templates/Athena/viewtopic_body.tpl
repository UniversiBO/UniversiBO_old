<!-- BEGIN switch_xs_enabled -->
<?php

// This code will be visible only with eXtreme Styles mod

global $board_config;
$avatar_search = '<img ';
$avatar_replace = '<img onload="if(this.width > ' . $board_config['avatar_max_width'] . ') { this.width = ' . $board_config['avatar_max_width'] . '; }" ';
$avatar_noreplace = 'onload=';
$profile_noreplace = '<';

$postrow_count = ( isset($this->_tpldata['postrow.']) ) ? sizeof($this->_tpldata['postrow.']) : 0;
for ($postrow_i = 0; $postrow_i < $postrow_count; $postrow_i++)
{
	$postrow_item = &$this->_tpldata['postrow.'][$postrow_i];
	if(!empty($postrow_item['PROFILE']))
	{
		// add search button if user isn't guest
		$postrow_item['SEARCH_IMG2'] = str_replace('%s', htmlspecialchars($postrow_item['POSTER_NAME']), $postrow_item['SEARCH_IMG']);
		// replace username with link to user profile
		$search = array($lang['Read_profile'], '<a ');
		$replace = array($postrow_item['POSTER_NAME'], '<a class="name" ');
		if(strpos($postrow_item['POSTER_NAME'], $profile_noreplace) === false)
		{
			$postrow_item['POSTER_NAME'] = str_replace($search, $replace, $postrow_item['PROFILE']);
		}
		// check avatar size
		if(strpos($postrow_item['POSTER_AVATAR'], $avatar_noreplace) === false)
		{
			$postrow_item['POSTER_AVATAR'] = str_replace($avatar_search, $avatar_replace, $postrow_item['POSTER_AVATAR']);
		}

	}
	// change view of joined, posts, location items
	$data = array();
	$data[] = isset($postrow_item['POSTER_JOINED']) ? $postrow_item['POSTER_JOINED'] : '';
	$data[] = isset($postrow_item['POSTER_POSTS']) ? $postrow_item['POSTER_POSTS'] : '';
	$data[] = isset($postrow_item['POSTER_FROM']) ? $postrow_item['POSTER_FROM'] : '';
	$show_data = false;
	$str = '<table width="100%" cellspacing="1" cellpadding="3" class="profileline">';
	for($i=0; $i<count($data); $i++)
	{
		$arr = explode(': ', $data[$i], 2);
		if(count($arr) == 2)
		{
			$str .= '<tr><td align="left" valign="middle" class="profile" width="40%"><b>' . $arr[0] . '</b></td><td align="left" valign="middle" class="profile">' . $arr[1] . '</td></tr>';
			$show_data = true;
		}
	}
	$str .= '</table>';
	$postrow_item['XS_DATA_START'] = $str . '<!-- ' . htmlspecialchars($str1);
	$postrow_item['XS_DATA_END'] = ' -->';
}
unset($postrow_item);

?>
<!-- END switch_xs_enabled -->
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td align="left" valign="middle"><span class="nav">
	  <a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
	  &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a>
	  &raquo; <a class="nav" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a></span></td>
	 <td align="right" valign="middle"><span class="nav"><b>{PAGINATION}</b></span></td>
  </tr>
  <tr>
    <td align="left" valign="middle"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" align="middle" /></a>&nbsp;&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></td>
	<td align="right" valign="middle"><span class="nav">
	<a href="{U_VIEW_OLDER_TOPIC}" class="nav">{L_VIEW_PREVIOUS_TOPIC}</a> :: <a href="{U_VIEW_NEWER_TOPIC}" class="nav">{L_VIEW_NEXT_TOPIC}</a>&nbsp;
	</span></td>
  </tr>
</table>

{POLL_DISPLAY} 

<!-- BEGIN postrow -->
<a name="{postrow.U_POST_ID}"></a>
{TPL_HDR1}<span class="cattitle">{postrow.POST_SUBJECT}</span>{TPL_HDR2}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="forumline">
<tr>
	<td class="th" align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="2" width="100%">
	<tr height="26">
		<td align="left" valign="middle" nowrap="nowrap"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" border="0" /></a><span class="genmed">{L_POSTED}: {postrow.POST_DATE}</span></td>
		<td align="right" valign="bottom" nowrap="nowrap">{postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.DELETE_IMG} {postrow.IP_IMG} </td>
	</tr></table></td>
</tr>
<tr>
	<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- main table start -->
	<tr>
		<td width="150" align="left" valign="top" rowspan="2"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- left row table start -->
		<tr>
			<td width="100%" align="left" valign="top" background="{T_TEMPLATE_PATH}/images/post_bg.gif"><table border="0" cellspacing="0" cellpadding="4" width="100%">
			<tr>
				<td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr><td><span class="name"><b>{postrow.POSTER_NAME}</b></span></td></tr>
				<tr><td><span class="postdetails">{postrow.POSTER_RANK}</span></td></tr>
				<tr><td><span class="postdetails">{postrow.RANK_IMAGE}{postrow.POSTER_AVATAR}</span></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td nowrap="nowrap">{postrow.XS_DATA_START}<table width="100%" cellspacing="1" cellpadding="3" class="profileline">
					<tr><td align="left" class="profile" nowrap="nowrap">{postrow.POSTER_JOINED}</td></tr>
					<tr><td align="left" class="profile" nowrap="nowrap">{postrow.POSTER_POSTS}</td></tr>
					<tr><td align="left" valign="middle" class="profile">{postrow.POSTER_FROM}</td></tr>
				</table>{postrow.XS_DATA_END}</td></tr>
				</table></td>
			</tr>
			</table></td>
			<td width="5" background="{T_TEMPLATE_PATH}/images/post_right.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="1" border="0" /></td>
		</tr>
		<tr>
			<td height="10" background="{T_TEMPLATE_PATH}/images/post_bottom.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="130" height="10" border="0" /></td>
			<td width="5" height="10"><img src="{T_TEMPLATE_PATH}/images/post_corner.gif" width="5" height="10" border="0" /></td>
		</tr>
		<!-- left row table end --></table><br /><br /></td>
		<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right top row table start -->
		<tr>
			<td width="100%"><span class="postbody">{postrow.MESSAGE}</span></td>
		</tr>
		<!-- right top row table end --></table></td>
	</tr>
	<tr>
		<td class="row1" align="left" valign="bottom" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right bottom row start -->
		<tr>
			<td width="100%"><span class="postbody"><span class="gensmall">{postrow.EDITED_MESSAGE}</span>{postrow.SIGNATURE}</span></td>
		</tr>
		<!-- right bottom row end --></table></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td height="28" valign="bottom" class="catBottom"><table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td width="130"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="130" height="1" border="0" /></td>
		<td width="100%" align="left" valign="middle" nowrap="nowrap">{postrow.PROFILE_IMG} {postrow.SEARCH_IMG2} {postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.WWW_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.MSN_IMG} {postrow.ICQ_IMG}</td>
	</tr></table></td>
</tr>
</table>{TPL_FTR}
<!-- END postrow -->

{TPL_HDR1}<a class="cattitle" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a>{TPL_HDR2}<table class="forumline" width="100%" cellspacing="1" cellpadding="" border="0">
<tr>
	<td class="row1" align="left" valign="top">
	<span class="nav">&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span><br />
	<table border="0" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td align="left" valign="top">
			<span class="gensmall">{S_AUTH_LIST}</span>
		</td>
		<td align="right" valign="top">
			<span class="gensmall">{S_TIMEZONE}&nbsp;&nbsp;<br />
			{PAGE_NUMBER}&nbsp;&nbsp;</span>
			<span class="nav"><b>{PAGINATION}</b></span><br />
			<span class="gensmall">{S_WATCH_TOPIC}</span>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td class="catBottom" align="center" valign="middle" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="2" width="100%">
	<tr>
		<form method="post" action="{S_POST_DAYS_ACTION}"><td align="left" valign="middle" nowrap="nowrap">{S_SELECT_POST_DAYS}&nbsp;{S_SELECT_POST_ORDER}&nbsp;<input type="submit" value="{L_GO}" class="liteoption" name="submit" /></td></form>
		<td align="right" valign="middle" nowrap="nowrap">{JUMPBOX}</td>
	</tr>
	</table>
	</td>
</tr>
</table>{TPL_FTR}

<table border="0" cellspacing="0" cellpadding="5" width="100%">
<tr>
	<td align="left" valign="top">&nbsp;<a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" align="middle" /></a>&nbsp;&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></td>
	<td align="right" valign="top">{S_TOPIC_ADMIN}&nbsp;</td>
</tr>
</table>
<br />