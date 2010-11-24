
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	</tr>
</table>

{TPL_HDR1}<span class="cattitle">{L_FAQ_TITLE}</span>{TPL_HDR2}<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
<tr>
	<td class="row1">
		<!-- BEGIN faq_block_link -->
		<span class="gen"><b>{faq_block_link.BLOCK_TITLE}</b></span><br />
		<!-- BEGIN faq_row_link -->
		<span class="gen"><a href="{faq_block_link.faq_row_link.U_FAQ_LINK}" class="postlink">{faq_block_link.faq_row_link.FAQ_LINK}</a></span><br />
		<!-- END faq_row_link -->
		<br />
		<!-- END faq_block_link -->
	</td>
</tr>
</table>{TPL_FTR}

<!-- BEGIN faq_block -->
{TPL_HDR1}<span class="cattitle">{faq_block.BLOCK_TITLE}</span>{TPL_HDR2}<table class="forumline" width="100%" cellspacing="1" cellpadding="3" border="0" align="center">
<!-- BEGIN faq_row -->  
<tr> 
	<td class="{faq_block.faq_row.ROW_CLASS}" align="left" valign="top"><span class="postbody"><a name="{faq_block.faq_row.U_FAQ_ID}"></a><b>{faq_block.faq_row.FAQ_QUESTION}</b></span><br /><span class="postbody">{faq_block.faq_row.FAQ_ANSWER}<br /><a class="postlink" href="#Top">{L_BACK_TO_TOP}</a></span></td>
</tr>
<tr>
	<td class="spacerow" height="1"><img src="{T_TEMPLATE_PATH}/images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
<!-- END faq_row -->
</table>
<br />

<!-- END faq_block -->

<table width="100%" cellspacing="2" border="0" align="center">
	<tr>
		<td align="right" valign="middle" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><br />{JUMPBOX}</td> 
	</tr>
</table>
