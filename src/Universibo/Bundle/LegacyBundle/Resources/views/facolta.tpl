{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<div class="titoloPagina">
	<h2>{$fac_facTitle|escape:"htmlall"} - {$fac_facCodice}</h2>
	<p><a href="{$fac_facLink|escape:"htmlall"}" title="Questo link apre una nuova pagina" target="_blank">{$fac_facLink|escape:"htmlall"}</a></p>
	{if $common_langCanaleMyUniversiBO != '' }
	<div class="comandi">{if $common_canaleMyUniversiBO == "remove"}<img src="{$common_basePath}/bundles/universibolegacy/images/esame_myuniversibo_del.gif" width="15" height="15" alt="" />&nbsp;{else}<img src="{$common_basePath}/bundles/universibolegacy/images/esame_myuniversibo_add.gif" width="15" height="15" alt="" />&nbsp;{/if}<a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">{$common_langCanaleMyUniversiBO|escape:"htmlall"}</a></div>
	{/if}
<p>{$fac_langYear|escape:"htmlall"}</p>
{if $fac_yearBox}
    {$fac_yearBox}
    <p><a href="{$fac_yearAll}">Tutti gli Anni Accademici</a></p>
{/if}
<h4>{$fac_langList|escape:"htmlall"}</h4>
</div>
{foreach from=$fac_list item=temp_fac}
<div class="elenco">
	
	<h3>{$temp_fac.name|escape:"html"|upper}</h3>
	
	{foreach name=elenco_cdl from=$temp_fac.list item=temp_cdl}
	<p class="{if $smarty.foreach.elenco_cdl.iteration%2 == 0}odd{else}even{/if}"><a href="{$temp_cdl.link}">{$temp_cdl.cod|escape:"htmlall"} - {$temp_cdl.name|escape:"htmlall"}</a>{if $temp_cdl.forumUri != ''}&nbsp;&nbsp;<a href="{$temp_cdl.forumUri|escape:"htmlall"}">(<img src="{$common_basePath}/bundles/universibolegacy/images/forum_omini_piccoli.gif" width="11" height="12" alt="Vai al forum" border="0"/>&nbsp;forum)</a>{/if}&nbsp;</p>
  {/foreach} 
</div>
{/foreach}
<hr />
{include file="News/latest_news.tpl"}

{include file="footer_index.tpl"}