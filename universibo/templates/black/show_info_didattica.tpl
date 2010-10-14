{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td class="Normal"><p align="center" class="Titolo">{$infoDid_title|escape:"htmlall"|nl2br}</p>

{if $infoDid_homepageAlternativaLink!=""}
<p class="Normal" align="center">{$infoDid_langHomepageAlternativaLink|escape:"htmlall"|nl2br}<br />
<a href="{$infoDid_homepageAlternativaLink|escape:"htmlall"|nl2br}">{$infoDid_homepageAlternativaLink|escape:"htmlall"|nl2br}</a></p>
{/if}


{if $infoDid_obiettiviInfo!=""}
<p class="NormalC">{$infoDid_langObiettiviInfo|escape:"htmlall"|nl2br}</p>
<p class="Normal">{$infoDid_obiettiviInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_obiettiviLink!=""}
<p class="NormalC">{$infoDid_langObiettiviLink|escape:"htmlall"|nl2br}</p>
<p class="Normal"><a href="{$infoDid_obiettiviLink|escape:"htmlall"|nl2br}">{$infoDid_obiettiviLink|escape:"htmlall"|nl2br}</a> </p>
{/if}

{if $infoDid_programmaInfo!=""}
<p class="NormalC">{$infoDid_langProgrammaInfo|escape:"htmlall"|nl2br}</p>
<p class="Normal">{$infoDid_programmaInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_programmaLink!=""}
<p class="NormalC">{$infoDid_langProgrammaLink|escape:"htmlall"|nl2br}</p>
<p class="Normal"><a href="{$infoDid_programmaLink|escape:"htmlall"|nl2br}">{$infoDid_programmaLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_materialeInfo!=""}
<p class="NormalC">{$infoDid_langMaterialeInfo|escape:"htmlall"|nl2br}</p>
<p class="Normal">{$infoDid_materialeInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_materialeLink!=""}
<p class="NormalC">{$infoDid_langMaterialeLink|escape:"htmlall"|nl2br}</p>
<p class="Normal"><a href="{$infoDid_materialeLink|escape:"htmlall"|nl2br}">{$infoDid_materialeLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_modalitaInfo!=""}
<p class="NormalC">{$infoDid_langModalitaInfo|escape:"htmlall"|nl2br}</p>
<p class="Normal">{$infoDid_modalitaInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_modalitaLink!=""}
<p class="NormalC">{$infoDid_langModalitaLink|escape:"htmlall"|nl2br}</p>
<p class="Normal"><a href="{$infoDid_modalitaLink|escape:"htmlall"|nl2br}">{$infoDid_modalitaLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_appelliInfo!=""}
<p class="NormalC">{$infoDid_langAppelliInfo|escape:"htmlall"|nl2br}</p>
<p class="Normal">{$infoDid_appelliInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_appelliLink!=""}
<p class="NormalC">{$infoDid_langAppelliLink|escape:"htmlall"|nl2br}</p>
<p class="Normal"><a href="{$infoDid_appelliLink|escape:"htmlall"|nl2br}">{$infoDid_appelliLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
--
<p class="Normal" align="center">{$infoDid_langAppelliUniwex|escape:"htmlall"|nl2br}</p>

</td></tr></table>

{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}