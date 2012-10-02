 
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td align="center"><span class="th2">{L_SEARCH_MATCHES}</span></td>
 </tr>
</table>
<br />

<!-- BEGIN searchresults -->
{TPL_HDR1}<a class="cattitle" href="{searchresults.U_TOPIC}">{searchresults.TOPIC_TITLE}</a>{TPL_HDR2}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="forumline">
<tr>
	<td class="th" align="center" valign="middle"><table border="0" cellspacing="0" cellpadding="2" width="100%">
	<tr height="26">
		<td align="left" valign="middle" nowrap="nowrap"><img src="{searchresults.MINI_POST_IMG}" width="12" height="9" alt="{searchresults.L_MINI_POST_ALT}" title="{searchresults.L_MINI_POST_ALT}" border="0" /><span class="postdetails">{L_FORUM}:&nbsp;<b><a href="{searchresults.U_FORUM}" class="postdetails">{searchresults.FORUM_NAME}</a></b>&nbsp; &nbsp;{L_POSTED}: {searchresults.POST_DATE}&nbsp; &nbsp;{L_SUBJECT}: <b><a href="{searchresults.U_POST}">{searchresults.POST_SUBJECT}</a></b></span></td>
		<td align="right" valign="middle" nowrap="nowrap"></td>
	</tr></table></td>
</tr>
<tr>
	<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- main table start -->
	<tr>
		<td width="150" align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%"><!-- left row table start -->
		<tr>
			<td width="100%" align="left" valign="top" background="{T_TEMPLATE_PATH}/images/post_bg.gif"><table border="0" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top"><table border="0" cellspacing="0" cellpadding="0">
				<tr><td nowrap="nowrap"><span class="name"><b>{searchresults.POSTER_NAME}</b></span></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td nowrap="nowrap"><span class="postdetails">{L_REPLIES}: {searchresults.TOPIC_REPLIES}</span></td></tr>
				<tr><td nowrap="nowrap"><span class="postdetails">{L_VIEWS}: {searchresults.TOPIC_VIEWS}</span></td></tr>
				</table></td>
			</tr>
			</table><br /><br /></td>
			<td width="5" background="{T_TEMPLATE_PATH}/images/post_right.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="5" height="1" border="0" /></td>
		</tr>
		<tr>
			<td height="10" background="{T_TEMPLATE_PATH}/images/post_bottom.gif"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" width="1" height="10" border="0" /></td>
			<td width="5" height="10"><img src="{T_TEMPLATE_PATH}/images/post_corner.gif" width="5" height="10" border="0" /></td>
		</tr>
		<!-- left row table end --></table><br /><br /></td>
		<td class="row1" align="left" valign="top" width="100%"><table border="0" cellspacing="0" cellpadding="5" width="100%"><!-- right top row table start -->
		<tr>
			<td width="100%"><span class="postbody">{searchresults.MESSAGE}</span></td>
		</tr>
		<!-- right top row table end --></table></td>
	</tr>
	</table></td>
</tr>
</table>{TPL_FTR}
<!-- END searchresults -->

<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
  <tr> 
	<td align="left" valign="top"><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td>
  </tr>
</table>

<table width="100%" cellspacing="2" border="0" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
