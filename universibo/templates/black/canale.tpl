{if $common_pageType == "index"}
{include file="header_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="header_popup.tpl"}
{/if}

<p align="center"><img src="tpl/black/{$showCanale_img|escape:"htmlall"}.gif" alt="{$showCanale_titolo|escape:"htmlall"}"></p>

{if $common_langCanaleMyUniversiBO != '' }
<p align="center"><a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">{$common_langCanaleMyUniversiBO|escape:"htmlall"}</a></p>
{/if}

{if $showCanale_newsFlag == 'true'}
{include file=News/latest_news.tpl}
{/if}
</td></tr>
<tr><td class="Normal">&nbsp;<br />&nbsp;<br />
{if $showCanale_filesFlag == 'true'}
{include file=Files/show_file_titoli.tpl}
{/if}

{if $common_pageType == "index"}
{include file="footer_index.tpl"}
{elseif $common_pageType == "popup"}
{include file="footer_popup.tpl"}
{/if}