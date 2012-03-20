{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center"><p class="Titolo">&nbsp;<br />Modifica il tuo commento</p></td></tr>
<tr><td align="left">
{include file=avviso_notice.tpl}
{include file=Files/show_file_studenti_commento.tpl}
<form method="post" enctype="multipart/form-data">
<table width="95%" cellspacing="0" cellpadding="4" border="0" summary="" align="center">
<tr>
<td class="News" align="right" valign="top"><label for="f27_commento">Il tuo commento/descrizione <br /> sul file:</label></td>
<td colspan="2"><textarea cols="50" rows="10" id="f27_commento" name="f27_commento">{$f27_commento|escape:"htmlall"}</textarea></td>
</tr>
<tr>
<td class="News" align="right" valign="top"><label for="f27_voto">Il tuo voto (da 0 a 5):</label></td>
<td><select id="f27_voto" name="f27_voto">
			<option value =""></option>
			<option value ="0">0</option>
  			<option value ="1">1</option>
  			<option value ="2">2</option>
  			<option value ="3">3</option>
  			<option value ="4">4</option>
  			<option value ="5">5</option>
			</select></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" id="" name="f27_submit" size="20" value="Invia" /></td>
</tr>
<tr><td colspan="2" align="center" class="Normal"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></td></tr>
</table>

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td>
&nbsp;<br />
&nbsp;<br />
<hr width="90%" />
{*{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}*}
</td></tr></table>

</form>
</td></tr>
</table>

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}