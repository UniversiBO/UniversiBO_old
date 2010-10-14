&nbsp;<br />
<a name="gotof7" />

{include file=avviso_notice.tpl}
&nbsp;<br />
<table align="center" cellspacing="0" cellpadding="0" width="90%" summary="">
<tr>
<td class="Normal">

<form action="index.php?do=pAddNews#gotof7" id="f7" method="post">

<table width="90%" align="center" class="Normal" summary="">
<tr>
<td><label for="f7_titolo">{$plAddNews_titolo|escape:"htmlall"}</label></td>
<td><input id="f7_titolo" maxlength="150" size="65" name="f7_titolo" value="{$f7_titolo|escape:"htmlall"}" /></td>
</tr>
<tr>
<td colspan="2"><label for="f7_ggIns">{$plAddNews_ggIns|escape:"htmlall"}</label>
&nbsp;<input id="f7_ggIns" maxlength="2" size="2" name="f7_ggIns" value="{$f7_ggIns|escape:"htmlall"}" />
&nbsp;<label for="f7_mmIns">{$plAddNews_mm|escape:"htmlall"}</label>
&nbsp;<input id="f7_mmIns" maxlength="2" size="2" name="f7_mmIns" value="{$f7_mmIns|escape:"htmlall"}" />
&nbsp;<label for="f7_aaIns">{$plAddNews_aa|escape:"htmlall"}</label>
&nbsp;<input id="f7_aaIns" maxlength="4" size="4" name="f7_aaIns" value="{$f7_aaIns|escape:"htmlall"}" /></td>
</tr>
<tr>
<td colspan="2"><label for="f7_oraIns">{$plAddNews_oraIns|escape:"htmlall"}</label>
&nbsp;<input id="f7_oraIns" maxlength="2" size="2" name="f7_oraIns" value="{$f7_oraIns|escape:"htmlall"}" />
&nbsp;<label for="f7_minIns">{$plAddNews_minIns|escape:"htmlall"}</label>
&nbsp;<input id="f7_minIns" maxlength="2" size="2" name="f7_minIns" value="{$f7_minIns|escape:"htmlall"}" /></td>
</tr>
<tr>
<td><label for="f7_scadenza">{$plAddNews_scadenza|escape:"htmlall"}</label></td>
<td><input type="checkbox" id="f7_scadenza" name="f7_scadenza" /></td>
</tr>
<tr>
<td colspan="2"><label for="f7_ggScad">{$plAddNews_ggScad|escape:"htmlall"}</label>
&nbsp;<input id="f7_ggScad" maxlength="2" size="2" name="f7_ggScad" value="{$f7_ggScad|escape:"htmlall"}" />
&nbsp;<label for="f7_mmScad">{$plAddNews_mm|escape:"htmlall"}</label>
&nbsp;<input id="f7_mmScad" maxlength="2" size="2" name="f7_mmScad" value="{$f7_mmScad|escape:"htmlall"}" />
&nbsp;<label for="f7_aaScad">{$plAddNews_aa|escape:"htmlall"}</label>
&nbsp;<input id="f7_aaScad" maxlength="4" size="4" name="f7_aaScad" value="{$f7_aaScad|escape:"htmlall"}" /></td>
</tr>
<tr>
<td colspan="2"><label for="f7_oraScad">{$plAddNews_oraScad|escape:"htmlall"}</label>
&nbsp;<input id="f7_oraScad" maxlength="2" size="2" name="f7_oraScad" value="{$f7_oraScad|escape:"htmlall"}" />
&nbsp;<label for="f7_minScad">{$plAddNews_minScad|escape:"htmlall"}</label>
&nbsp;<input id="f7_minScad" maxlength="2" size="2" name="f7_minScad" value="{$f7_minScad|escape:"htmlall"}" /></td>
</tr>
<tr>
<td><label for="f7_notizia">{$plAddNews_notizia|escape:"htmlall"}</label></td>
<td><textarea id="f7_notizia" onKeyDown="textlimit(this.form.news_notizia,2500);" onKeyUp="textlimit(this.form.news_notizia,2500);" cols="50" rows="10" name="$f7_notizia"></textarea></td>
</tr>
<tr>
<td><label for="f7_urgente">{$plAddNews_urgente|escape:"htmlall"}</label></td>
<td><input type="checkbox" id="f7_urgente" name="f7_urgente" /></td>
</tr>
</table>
<p align="center">&nbsp;<input type="submit" value="{$plAddNews_Send|escape:"htmlall"}" name="f7_submit" /></p>
</form>
</td></tr></table>