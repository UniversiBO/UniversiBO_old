{include file="header_index.tpl"}

<h2>Registrazione Studenti</h2>

{include file=avviso_notice.tpl}

<form action="v2.php?do=RegStudente&amp;{$common_pageTypeExt|escape:"htmlall"}" id="f4" method="post">
	<p>{$regStudente_langInfoReg|escape:"htmlall"|bbcode2html|nl2br}</p>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" summary="">
	<tr align="left"><td>&nbsp;<label for="f4_ad_user">{$regStudente_langMail|escape:"htmlall"}</label>&nbsp;</td>
		<td><input type="text" name="f4_ad_user" id="f4_ad_user" size="20" maxlength="30" value="{$f4_ad_user|escape:"html"}" tabindex="1"/>{$regStudente_domain|escape:"htmlall"}</td></tr>
	<tr align="left"><td><label for="f4_password">{$regStudente_langPassword|escape:"htmlall"}</label>&nbsp;</td>
		<td><input type="password" name="f4_password" id="f4_password" size="20" maxlength="50" value="{$f4_password|escape:"html"}"tabindex="1" /></td></tr>
	<tr align="left"><td colspan="2">&nbsp;&nbsp;{$regStudente_langInfoUsername|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
	<tr align="left"><td>&nbsp;<label for="f4_username">{$regStudente_langUsername|escape:"htmlall"}</label>&nbsp;</td>
		<td><input type="text" name="f4_username" id="f4_username" size="20" maxlength="25" value="{$f4_username|escape:"html"}" tabindex="1"/></td></tr>
	<tr align="left"><td>&nbsp;<label for="f4_regolamento">{$regStudente_langReg|escape:"htmlall"}</label></td>
		<td><textarea name="f4_regolamento" id="f4_regolamento" rows="5" cols="60"  wrap="phisical" readonly="readonly" tabindex="1">{$f4_regolamento|escape:"htmlall"}</textarea></td></tr>
	<tr align="left"><td>&nbsp;<label for="f4_privacy">{$regStudente_langPrivacy|escape:"htmlall"}</label></td>
		<td><textarea name="f4_privacy" id="f4_privacy" rows="5" cols="60" readonly="readonly" tabindex="1">{$f4_privacy|escape:"htmlall"}</textarea></td></tr>
	<tr align="left"><td><input type="checkbox" name="f4_confirm" id="f4_confirm" tabindex="1" />&nbsp;&nbsp;</td><td><label for="f4_confirm">Confermo di aver letto il regolamento</label>&nbsp;</td></tr>
	<tr align="left"><td colspan="2">&nbsp;<input class="submit" type="submit" name="f4_submit" id="f4_submit" value="{$f4_submit|escape:"htmlall"}" tabindex="1"></td></tr>
	<tr align="left"><td colspan="2">&nbsp;{$regStudente_langHelp|escape:"htmlall"|bbcode2html|nl2br}</td></tr>
	</table>
</form>
<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}