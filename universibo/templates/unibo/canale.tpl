{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>{$showCanale_titolo|escape:"htmlall"}</h2>
{if $common_langCanaleMyUniversiBO != '' }
	<div class="comandi">
	{if $common_canaleMyUniversiBO == "remove"}<img src="tpl/unibo/esame_myuniversibo_del.gif" width="15" height="15" alt="" />&nbsp;{else}<img src="tpl/unibo/esame_myuniversibo_add.gif" width="15" height="15" alt="" />&nbsp;{/if}<a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">{$common_langCanaleMyUniversiBO|escape:"htmlall"}</a></div>
{/if}
</div>

{if $showCanale_newsFlag == 'true'}
	{include file="News/latest_news.tpl"}
{/if}
{if $showCanale_filesFlag == 'true'}
	{include file="Files/show_file_titoli.tpl"}
{/if}

{include file="footer_index.tpl"}