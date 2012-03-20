{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

{include file=avviso_notice.tpl}


<!--&nbsp;<br /> <img src="tpl/black/corsi_di_laurea_30.gif" width="132" height="39" alt="{$cdl_langTitleAlt|escape:"htmlall"}" />-->
<p align="center" class="Titolo">&nbsp;<br />{$cdl_cdlTitle|escape:"htmlall"} - {$cdl_cdlCodice|escape:"htmlall"}</p>
{if $common_langCanaleMyUniversiBO != ''}
<p align="center">
{if $common_canaleMyUniversiBO == "remove"}
	<img src="tpl/black/esame_myuniversibo_del.gif" width="15" height="15" alt="" />&nbsp;
{else}<img src="tpl/black/esame_myuniversibo_add.gif" width="15" height="15" alt="" />&nbsp;
{/if}<a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">{$common_langCanaleMyUniversiBO|escape:"htmlall"}</a>
</p>
{/if}
<p align="center">{$cdl_langYear|escape:"htmlall"}<br />
<a href="{$cdl_prevYearUri|escape:"htmlall"}">{$cdl_prevYear|escape:"htmlall"}</a>&nbsp;&lt;&lt;
&nbsp;&nbsp;<font color="FF0000">{$cdl_thisYear|escape:"htmlall"}</font>&nbsp;&nbsp;
&gt;&gt;&nbsp;<a href="{$cdl_nextYearUri|escape:"htmlall"}">{$cdl_nextYear|escape:"htmlall"}</a> </p>

<p align="center">{$cdl_langList|escape:"htmlall"}</p>

{foreach from=$cdl_list item=temp_anno}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center"> 
<tr><td bgcolor="#000099">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
	<tr>
	 <td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	 <td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
	 </tr>
	</table>
</td></tr>
<tr><td class="Titolo" align="center" bgcolor="#000050">{$temp_anno.name|escape:"html"|upper}</td></tr>


{foreach from=$temp_anno.list item=temp_ciclo}
<tr bgcolor="#000099"><td><img src="tpl/black/invisible.gif" width="200" height="2" alt="" /></td></tr>
<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="1" summary=""> 
{foreach from=$temp_ciclo.list item=temp_ins}
<tr bgcolor="{cycle values="#000016,#000032"}"><td class="Menu" width="26">&nbsp;{$temp_ciclo.ciclo|escape:"htmlall"}<img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" />&nbsp;</td><td class="Menu">
<a href="{$temp_ins.uri|escape:"htmlall"}">{$temp_ins.name|escape:"htmlall"} - {$temp_ins.nomeDoc|lower|ucwords|escape:"htmlall"}</a> </td><td class="Menu" align="right">{if $temp_ins.forumUri != ''}<a href="{$temp_ins.forumUri|escape:"htmlall"}" title="{$cdl_langGoToForum|escape:"htmlall"}"><img src="tpl/black/forum_omini_piccoli.gif" width="11" height="12" alt="{$cdl_langGoToForum|escape:"htmlall"}" border="0"/></a>{/if}&nbsp;</td></tr>
{/foreach} 
</table>
</td></tr>
{/foreach} 

<tr><td bgcolor="#000099">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
	<tr>
	 <td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	 <td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
	 </tr>
	</table>
</td></tr>
</table> 
<p>&nbsp;</p>
{/foreach}

{include file=News/latest_news.tpl}


{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}