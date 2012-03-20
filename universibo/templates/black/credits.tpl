{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

{include file=avviso_notice.tpl}

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td class="Normal" align="center">
	&nbsp;<br />
	<img src="tpl/black/credits_30.gif" width="124" height="39" alt="{$showCredits_langTitleAlt|escape:"htmlall"}" /><br />&nbsp;
</td></tr>
<tr>
	<td colspan="2" class="Normal" align="left">
	{$showCredits_langIntro|escape:"htmlall"|bbcode2html|nl2br}<br />&nbsp;
	</td>
</tr>
<tr><td class="Normal">

	<table width="100%" border="0" cellspacing="0" cellpadding="8" summary="">
	<tr>
	<td class="Normal" width="90">&nbsp;</td>
	<td class="Normal" width="100%">&nbsp;</td>
	<td class="Normal" width="90">&nbsp;</td>
	</tr>
	<tr>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langSO|escape:"htmlall"|bbcode2html|nl2br}</td>
	<td class="Normal" valign="top" width="90"><img src="img/credits/gnu_linux.gif" width="88" height="31" alt="GNU/Linux Logo" /><br />
	<img src="img/credits/slackware.png" width="88" height="31" alt="Slackware Logo" /> </td>
	</tr>
	<tr>
	<td class="Normal" valign="top" width="90"><img src="img/credits/apache_ssl.gif" width="88" height="31" alt="Apache-SSL Logo" vspace="2" /></td>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langApache|escape:"htmlall"|bbcode2html|nl2br}</td>
	</tr>
	<tr>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langPostgres|escape:"htmlall"|bbcode2html|nl2br}</td>
	<td class="Normal" valign="top" width="90"><img src="img/credits/postgresql.gif" width="88" height="31" alt="PostgreSQL Logo" vspace="2" /></td>
	</tr>
	<tr>
	<td class="Normal" valign="top" width="90"><img src="img/credits/php.gif" width="88" height="31" alt="PHP4 Logo" /></td>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langPhp|escape:"htmlall"|bbcode2html|nl2br}</td>
	</tr>
	<tr>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langPhpAccelerator|escape:"htmlall"|bbcode2html|nl2br}</td>
	<td class="Normal" valign="top" width="90"><img src="img/credits/php_acc.png" width="88" height="31" alt="Ion Cube PhpAccelerator Logo" vspace="2" /></td>
	</tr>
	<tr>
	<td class="Normal" valign="top" width="90"><img src="img/credits/smarty.gif" width="88" height="31" alt="Smarty Logo" vspace="2" /></td>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langSmarty|escape:"htmlall"|bbcode2html|nl2br}</td>
	</tr>
	<tr>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langOthers|escape:"htmlall"|bbcode2html|nl2br}</td>
	<td class="Normal" valign="top" width="90"><img src="img/credits/pear.png" width="88" height="31" alt="PEAR Logo" vspace="2" /><br />
	<img src="img/credits/phpmailer.png" width="88" height="31" alt="PhpMailer Logo" vspace="2" /><br />
	<img src="img/credits/phpbb.png" width="88" height="31" alt="PHPBB Logo" vspace="2" /></td>
	</tr>
	<tr>
	<td class="Normal" valign="top" width="90"><img src="img/credits/valid-xhtml10.png" width="88" height="31" alt="Valid XHTML 1.0 Logo" vspace="2" /><br />
	<img src="img/credits/valid-css.png" width="88" height="31" alt="Valid CSS 2.0 Logo" vspace="2" /><br />
	<img src="img/credits/valid-wcag1AA.png" width="88" height="31" alt="Valid WAI-AA Logo" vspace="2" /><br /></td>
	<td class="Normal" valign="top" colspan="2">{$showCredits_langW3c|escape:"htmlall"|bbcode2html|nl2br}</td>
	</tr>
	</table>
	
</td>
</tr>

</table>

<br />


{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}