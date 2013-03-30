{include file="News/single_news.tpl" showNews_notizia=$news}
<h4>Canali</h4>
<ul class="nav nav-pills">
    {foreach from=$channels item=channel}
        <li><a href="{$channel.uri|escape:"htmlall"}">{$channel.name|escape:"htmlall"}</a></li>
    {/foreach}
</ul>