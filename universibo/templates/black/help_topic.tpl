{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

{include file=avviso_notice.tpl}

<table summary="help" width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="Normal" align="left">
		<div align="center">&nbsp;<br /><a href="{$common_helpUri|escape:"htmlall"}"><img id="help" src="tpl/black/help_30.gif" border="0" width="84" height="39" alt="{$showHelpTopic_langAltTitle|escape:"htmlall"}" /></a></div>
</td></tr>
{if $showHelpTopic_index == "true"}
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    <a id="index" /><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
	{*foreach from=$showHelpTopic_langReferences item=temp_ref*}
	{foreach from=$showHelpTopic_topics item=temp_ref}
	<tr><td cellpadding="3" class="Titolo" bgcolor="{cycle name=index values="#000032,#000016"}">&nbsp;<img src="tpl/black/elle_begin.gif" width="10" height="12" alt="" />
	<a href="#{$temp_ref.reference|escape:"htmlall"}"> {$temp_ref.titolo|escape:"htmlall"}</a></td></tr>
	{/foreach}
<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" summary=""><tr><td bgcolor="#000099" align="left">
    <img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td><td bgcolor="#000099" align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td></tr></table></td></tr>
{/if}<tr><td>&nbsp</td></tr>
<tr><td>
	{foreach from=$showHelpTopic_topics item=temp_topic}
	{include file=Help/topic.tpl showTopic_topic=$temp_topic idsu=$temp_topic.reference}
	{/foreach}
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}