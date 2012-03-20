{include file="header_index.tpl"}

<div class="titoloPagina"><h2>{$infoDid_title|escape:"htmlall"|nl2br}</h2></div>
{include file=avviso_notice.tpl}
<form method="post">
<p><label for="f18_homepageLink">{$infoDid_langHomepageAlternativaLink|escape:"htmlall"}&nbsp;<label><br />
<input type="text" size="65" name="f18_homepageLink" id="f18_homepageLink" value="{$f18_homepageLink|escape:"htmlall"}" /></p>

<p><label for="f18_obiettiviInfo">{$infoDid_langObiettiviInfo|escape:"htmlall"}&nbsp;</label><br />
<textarea name="f18_obiettiviInfo" id="f18_obiettiviInfo">{$f18_obiettiviInfo|escape:"htmlall"}</textarea></p>
<p><label for="f18_obiettiviLink">{$infoDid_langObiettiviLink|escape:"htmlall"}&nbsp;</label><br />
<input type="text" size="65" name="f18_obiettiviLink" id="f18_obiettiviLink" value="{$f18_obiettiviLink|escape:"htmlall"}" /></p>

<p><label for="f18_programmaInfo">{$infoDid_langProgrammaInfo|escape:"htmlall"}&nbsp;</label><br />
<textarea name="f18_programmaInfo" id="f18_programmaInfo">{$f18_programmaInfo|escape:"htmlall"}</textarea></p>
<p><label for="f18_programmaLink">{$infoDid_langProgrammaLink|escape:"htmlall"}&nbsp;</label><br />
<input type="text" size="65" name="f18_programmaLink" id="f18_programmaLink" value="{$f18_programmaLink|escape:"htmlall"}" /></p>
		
<p><label for="f18_materialeInfo">{$infoDid_langMaterialeInfo|escape:"htmlall"}&nbsp;</label><br />
<textarea name="f18_materialeInfo" id="f18_materialeInfo">{$f18_materialeInfo|escape:"htmlall"}</textarea></p>
<p><label for="f18_materialeLink">{$infoDid_langMaterialeLink|escape:"htmlall"}&nbsp;</label><br />
<input type="text" size="65" name="f18_materialeLink" id="f18_materialeLink" value="{$f18_materialeLink|escape:"htmlall"}" /></p>
		
<p><label for="f18_modalitaInfo">{$infoDid_langModalitaInfo|escape:"htmlall"}&nbsp;</label><br />
<textarea name="f18_modalitaInfo" id="f18_modalitaInfo">{$f18_modalitaInfo|escape:"htmlall"}</textarea></p>
<p><label for="f18_modalitaLink">{$infoDid_langModalitaLink|escape:"htmlall"}&nbsp;</label><br />
<input type="text" size="65" name="f18_modalitaLink" id="f18_modalitaLink" value="{$f18_modalitaLink|escape:"htmlall"}" /><p>
		
<p><label for="f18_appelliInfo">{$infoDid_langAppelliInfo|escape:"htmlall"}&nbsp;</label><br />
<textarea name="f18_appelliInfo" id="f18_appelliInfo">{$f18_appelliInfo|escape:"htmlall"}</textarea></p>
<p><label for="f18_appelliLink">{$infoDid_langAppelliLink|escape:"htmlall"}&nbsp;</label><br />
<input type="text" size="65" name="f18_appelliLink" id="f18_appelliLink" value="{$f18_appelliLink|escape:"htmlall"}" /></p>

<p><label for="f18_orarioIcsLink">{$infoDid_langOrarioLink|escape:"htmlall"}&nbsp;<label><br />
<input type="text" size="65" name="f18_orarioIcsLink" id="f18_orarioIcsLink" value="{$f18_orarioIcsLink|escape:"htmlall"}" /></p>

<p><input type="submit" class="submit" name="f18_submit" id="f18_submit" value="Modifica" /></p>
</form>

<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>
{include file="footer_index.tpl"}