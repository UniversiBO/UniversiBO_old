{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

{include file=avviso_notice.tpl}


<p align="center" class="Normal">
&nbsp;<br /><img src="tpl/black/mypage_30.gif" width="138" height="39" alt="{$showSettings_langTitleAlt|escape:"htmlall"}" />
</p>
<p>
{$showSettings_langIntro|escape:"htmlall"|bbcode2html}</p>
 &nbsp;<br />
 <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
 <tr bgcolor="#000099"> 
 <td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
 <td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
 </tr>
 </table>
 &nbsp;<br />
 &nbsp;<br />
 <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
 <tr><td>
  <img src="tpl/black/preferences_18.gif" width="131" height="22" alt="Preferences" /><br />
	&nbsp;<br />
  {include file=tabellina_due_colonne.tpl arrayToShow=$showSettings_langPreferences} 
 </td></tr>
 </table> 
 &nbsp;<br />
 &nbsp;<br />
 <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
 <tr bgcolor="#000099"> 
 <td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
 <td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
 </tr>
 </table>
 &nbsp;<br />
 &nbsp;<br />
{if $showSettings_showAdminPanel == "true"} 
 <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
 <tr><td>
  <img src="tpl/black/admin_18.gif" width="76" height="22" alt="Admin" /><br />
	&nbsp;<br />
  {include file=tabellina_due_colonne.tpl arrayToShow=$showSettings_langAdmin}
  
 </td></tr>
 </table> 
{/if}


{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}
