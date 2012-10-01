{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<div class="titoloPagina">
<h2>{$rules_langTitle|escape:"htmlall"|bbcode2html}</h2>
<h4>{$rules_langFacSubtitle|escape:"htmlall"|bbcode2html}</h4>
</div>
<p> {$rules_langServicesRules|escape:"htmlall"|bbcode2html|nl2br} </p>
<h4>{$rules_langPrivacySubTitle|escape:"htmlall"}</h4>
<p> {$rules_langPrivacy|escape:"htmlall"|bbcode2html|nl2br} </p>
<h4>{$rules_langForum|escape:"htmlall"}</h4>
<p> {$rules_langForumRules|escape:"htmlall"|bbcode2html} </p>
{include file="footer_index.tpl"}

