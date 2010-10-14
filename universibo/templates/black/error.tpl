{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="98%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td class="Normal" align="center">
 <table width="90%"  border="0" cellspacing="0" cellpadding="0" align="center" summary="">
 <tr> 
 <td>&nbsp;<br /><img src="tpl/black/errore_18.gif" width="80" height="22" alt="Errore" /></td>
 </tr>
 <tr align="center"> 
 <td class="Normal">&nbsp;<br />
  <table width="70%" border="0" cellspacing="1" cellpadding="3" summary="">
  <tr><td class="Normal" bgcolor="#FF0000">
  {$error_default|escape:"htmlall"}
  </td></tr>
  </table>
 </td></tr>
 </table>
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}
