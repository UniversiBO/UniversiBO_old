
{TPL_HDR1}<span class="cattitle">{L_PENDING_MEMBERS}</span>{TPL_HDR2}<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
<tr> 
	<th class="thCornerL" height="25">{L_PM}</th>
	<th class="thTop">{L_USERNAME}</th>
	<th class="thTop">{L_POSTS}</th>
	<th class="thTop">{L_FROM}</th>
	<th class="thTop">{L_EMAIL}</th>
	<th class="thTop">{L_WEBSITE}</th>
	<th class="thCornerR">{L_SELECT}</th>
</tr>
<!-- BEGIN pending_members_row -->
<tr> 
	<td class="row3" align="center"> {pending_members_row.PM_IMG} </td>
	<td class="row1" align="center" {C_ONMOUSEOVER}="this.style.backgroundColor='{C_ROW1_OVER}';" {C_ONMOUSEOUT}="this.style.backgroundColor='{C_ROW1}';" {C_ONCLICK}="window.location.href='{pending_members_row.U_VIEWPROFILE}'"><span class="gen"><a href="{pending_members_row.U_VIEWPROFILE}" class="gen">{pending_members_row.USERNAME}</a></span></td>
	<td class="row2" align="center"><span class="gen">{pending_members_row.POSTS}</span></td>
	<td class="row1" align="center"><span class="gen">{pending_members_row.FROM}</span></td>
	<td class="row2" align="center"><span class="gen">{pending_members_row.EMAIL_IMG}</span></td>
	<td class="row1" align="center"><span class="gen">{pending_members_row.WWW_IMG}</span></td>
	<td class="row2" align="center"><span class="gensmall"> <input type="checkbox" name="pending_members[]" value="{pending_members_row.USER_ID}" checked="checked" /></span></td>
</tr>
<!-- END pending_members_row -->
<tr> 
	<td class="catBottom" colspan="8" align="right"><span class="gen"> 
		<input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="mainoption" />
		&nbsp; 
		<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="liteoption" />
	</span></td>
</tr>
</table>{TPL_FTR}