{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Password file<br />&nbsp;</p></td></tr>
<tr><td align="center">
<p class="Normal">Il file richiesto è stato protetto dall'autore con una password,<br />
Per per proseguire con il download è necessario inserirla nel seguente form.</p>
{include file=avviso_notice.tpl}
<form method="post">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="">
<tr>
<td class="Normal" align="center"><label for="f11_file_password">Password:</label> <input type="password" id="f11_file_password" name="f11_file_password" size="20" maxlength="130" value="" /></td>
</tr>
<tr>
<td align="center">
<input type="submit" id="" name="f11_submit" size="20" value="Invia" /></td>
</tr>
<tr><td align="center" class="Normal"><a href="{$fileDownload_InfoURI|escape:"htmlall"}">Torna&nbsp;indietro</a></td></tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

</form>
</td></tr>
</table>

&nbsp;
<hr>
<table width="90%" border="0" cellspacing="0" cellpadding="4" summary="" align="center">
<tr><td>
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}
</td></tr></table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}