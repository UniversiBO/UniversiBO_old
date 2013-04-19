{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>{$showCanale_titolo|escape:"htmlall"}</h2>
{include file="bookmark_channel.tpl"}
</div>

{if $showCanale_newsFlag == 'true'}
	{include file="News/latest_news.tpl"}
{/if}
{if $showCanale_filesFlag == 'true'}
	{include file="Files/show_file_titoli.tpl"}
{/if}

{include file="footer_index.tpl"}