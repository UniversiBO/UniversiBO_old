{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

{include file=avviso_notice.tpl}

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td class="Normal">
<p align="center"><img src="tpl/black/regolamento_30.gif" width="234" height="39" alt="{$rules_langTitleAlt|escape:"htmlall"|bbcode2html}" /><br /></p>
<font class="NormalC">{$rules_langTitle|escape:"htmlall"|bbcode2html}</font>
<p>{$rules_langFacSubtitle|escape:"htmlall"|bbcode2html}&nbsp;<br /></p>
<p>{$rules_langServicesRules|escape:"htmlall"|bbcode2html|nl2br}&nbsp;<br />&nbsp;<br /></p>
<p class="NormalC">{$rules_langPrivacySubTitle|escape:"htmlall"}</p>
<p>{$rules_langPrivacy|escape:"htmlall"|bbcode2html|nl2br}&nbsp;<br />&nbsp;<br /></p>
<p class="NormalC">{$rules_langForum|escape:"htmlall"}</p>
<p>{$rules_langForumRules|escape:"htmlall"|bbcode2html}&nbsp;<br />&nbsp;<br /></p>
</td></tr></table>
{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}
