{include file=header_index.tpl}

<div class="titoloPagina">
<h2>{$infoDid_title|escape:"htmlall"|nl2br}</h2>
</div>
{if $infoDid_obiettiviInfo!=""}
	<h4>{$infoDid_langObiettiviInfo|escape:"htmlall"|nl2br}</h4>
	<p>{$infoDid_obiettiviInfo|escape:"htmlall"}</p>
{/if}
{if $infoDid_obiettiviLink!=""}
	<h4>{$infoDid_langObiettiviLink|escape:"htmlall"|nl2br}</h4>
	<p><a href="{$infoDid_obiettiviLink|escape:"htmlall"|nl2br}">{$infoDid_obiettiviLink|escape:"htmlall"|nl2br}</a> </p>
{/if}

{if $infoDid_programmaInfo!=""}
	<h4>{$infoDid_langProgrammaInfo|escape:"htmlall"|nl2br}</h4>
	<p>{$infoDid_programmaInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_programmaLink!=""}
	<h4>{$infoDid_langProgrammaLink|escape:"htmlall"|nl2br}</h4>
	<p><a href="{$infoDid_programmaLink|escape:"htmlall"|nl2br}">{$infoDid_programmaLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_materialeInfo!=""}
	<h4>{$infoDid_langMaterialeInfo|escape:"htmlall"|nl2br}</h4>
	<p>{$infoDid_materialeInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_materialeLink!=""}
	<h4>{$infoDid_langMaterialeLink|escape:"htmlall"|nl2br}</h4>
	<p><a href="{$infoDid_materialeLink|escape:"htmlall"|nl2br}">{$infoDid_materialeLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_modalitaInfo!=""}
	<h4>{$infoDid_langModalitaInfo|escape:"htmlall"|nl2br}</h4>
	<p>{$infoDid_modalitaInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_modalitaLink!=""}
	<h4>{$infoDid_langModalitaLink|escape:"htmlall"|nl2br}</h4>
	<p><a href="{$infoDid_modalitaLink|escape:"htmlall"|nl2br}">{$infoDid_modalitaLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
		
{if $infoDid_appelliInfo!=""}
	<h4>{$infoDid_langAppelliInfo|escape:"htmlall"|nl2br}</h4>
	<p>{$infoDid_appelliInfo|escape:"htmlall"|nl2br}</p>
{/if}
{if $infoDid_appelliLink!=""}
	<h4>{$infoDid_langAppelliLink|escape:"htmlall"|nl2br}</h4>
	<p><a href="{$infoDid_appelliLink|escape:"htmlall"|nl2br}">{$infoDid_appelliLink|escape:"htmlall"|nl2br}</a> </p>
{/if}
--
<p>{$infoDid_langAppelliUniwex|escape:"htmlall"|nl2br}</p>

{include file=footer_index.tpl}