<h2>News</h2>

<div class="comandi">
    {if $showNewsLatest_addNewsFlag == "true" || $showNewsLatest_langNewsShowOthers != ""}
        {if $showNewsLatest_addNewsFlag == "true"}<img src="{$common_basePath}/bundles/universibolegacy/images/news_new.gif" width="15" height="15" alt="" />
            <a href="{$showNewsLatest_addNewsUri|escape:"htmlall"}">{$showNewsLatest_addNews|escape:"htmlall"|bbcode2html|nl2br}</a>
        {/if}
        {if $showNewsLatest_langNewsShowOthers != ""}<img src="{$common_basePath}/bundles/universibolegacy/images/news_all.gif" width="15" height="15" alt="" />
            <a href="{$showNewsLatest_langNewsShowOthersUri|escape:"htmlall"}">{$showNewsLatest_langNewsShowOthers|escape:"htmlall"|bbcode2html|nl2br}</a>
        {/if}
    {/if}
    {if ($showNewsLatest_rss|default:"") != ""}<img src="{$common_basePath}/bundles/universibolegacy/images/rss.gif" width="15" height="15" alt="" />
        <a href="{$showNewsLatest_rss|escape:"htmlall"}">RSS</a>
    {/if}
</div>

{if $showNewsLatest_langNewsAvailableFlag=="true"}
    {foreach from=$showNewsLatest_newsList item=temp_news}
        {include file="News/news.tpl" id_notizia=$temp_news.id_notizia titolo=$temp_news.titolo notizia=$temp_news.notizia autore=$temp_news.autore autore_link=$temp_news.autore_link id_autore=$temp_news.id_autore data=$temp_news.data modifica=$temp_news.modifica modifica_link=$temp_news.modifica_link elimina=$temp_news.elimina elimina_link=$temp_news.elimina_link nuova=$temp_news.nuova scadenza=$temp_news.scadenza permalink=$temp_news.permalink}
    {/foreach}
{else}
    <p>{$showNewsLatest_langNewsAvailable}</p>
{/if} 
