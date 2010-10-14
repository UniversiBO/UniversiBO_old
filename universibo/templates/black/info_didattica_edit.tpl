{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td class="Normal"><p align="center" class="Titolo">{$infoDid_title|escape:"htmlall"|nl2br}</p>
<p class="Normal" align="center"><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a> </p>

{include file="avviso_notice.tpl"}
<form method="post">
<label for="f18_homepageLink">{$infoDid_langHomepageAlternativaLink|escape:"htmlall"}:&nbsp;<label><br />
<input size="60" type="text" name="f18_homepageLink" id="f18_homepageLink" value="{$f18_homepageLink|escape:"htmlall"}" /><br /><br />


<label for="f18_obiettiviInfo">{$infoDid_langObiettiviInfo|escape:"htmlall"}:&nbsp;</label><br />
<textarea cols="50" rows="5" name="f18_obiettiviInfo" id="f18_obiettiviInfo">{$f18_obiettiviInfo|escape:"htmlall"}</textarea><br />
<label for="f18_obiettiviLink">{$infoDid_langObiettiviLink|escape:"htmlall"}:&nbsp;</label><br />
<input size="60" type="text" name="f18_obiettiviLink" id="f18_obiettiviLink" value="{$f18_obiettiviLink|escape:"htmlall"}" /><br /><br />

<label for="f18_programmaInfo">{$infoDid_langProgrammaInfo|escape:"htmlall"}:&nbsp;</label><br />
<textarea cols="50" rows="5" name="f18_programmaInfo" id="f18_programmaInfo">{$f18_programmaInfo|escape:"htmlall"}</textarea><br />
<label for="f18_programmaLink">{$infoDid_langProgrammaLink|escape:"htmlall"}:&nbsp;</label><br />
<input size="60" type="text" name="f18_programmaLink" id="f18_programmaLink" value="{$f18_programmaLink|escape:"htmlall"}" /><br /><br />
		
<label for="f18_materialeInfo">{$infoDid_langMaterialeInfo|escape:"htmlall"}:&nbsp;</label><br />
<textarea cols="50" rows="5" name="f18_materialeInfo" id="f18_materialeInfo">{$f18_materialeInfo|escape:"htmlall"}</textarea><br />
<label for="f18_materialeLink">{$infoDid_langMaterialeLink|escape:"htmlall"}:&nbsp;</label><br />
<input size="60" type="text" name="f18_materialeLink" id="f18_materialeLink" value="{$f18_materialeLink|escape:"htmlall"}" /><br /><br />
		
<label for="f18_modalitaInfo">{$infoDid_langModalitaInfo|escape:"htmlall"}:&nbsp;</label><br />
<textarea cols="50" rows="5" name="f18_modalitaInfo" id="f18_modalitaInfo">{$f18_modalitaInfo|escape:"htmlall"}</textarea><br />
<label for="f18_modalitaLink">{$infoDid_langModalitaLink|escape:"htmlall"}:&nbsp;</label><br />
<input size="60" type="text" name="f18_modalitaLink" id="f18_modalitaLink" value="{$f18_modalitaLink|escape:"htmlall"}" /><br /><br />
		
<label for="f18_appelliInfo">{$infoDid_langAppelliInfo|escape:"htmlall"}:&nbsp;</label><br />
<textarea cols="50" rows="5" name="f18_appelliInfo" id="f18_appelliInfo">{$f18_appelliInfo|escape:"htmlall"}</textarea><br />
<label for="f18_appelliLink">{$infoDid_langAppelliLink|escape:"htmlall"}:&nbsp;</label><br />
<input size="60" type="text" name="f18_appelliLink" id="f18_appelliLink" value="{$f18_appelliLink|escape:"htmlall"}" /><br /><br />


<label for="f18_orarioIcsLink">{$infoDid_langOrarioLink|escape:"htmlall"}:&nbsp;<label><br />
<input type="text" size="65" name="f18_orarioIcsLink" id="f18_orarioIcsLink" value="{$f18_orarioIcsLink|escape:"htmlall"}" /><br /><br />

<input type="submit" name="f18_submit" id="f18_submit" value="Modifica" /><br />

</form>
</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}