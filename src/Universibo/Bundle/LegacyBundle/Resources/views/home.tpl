{include file="header_index.tpl"}

<div id="home">
<h1>{$home_langWelcome|escape:"htmlall"}</h1>
<p>{$home_langWhatIs|escape:"htmlall"}</p>
<p>{$home_langMission|escape:"htmlall"}</p>
{include file="bookmark_channel.tpl"}
</div>
<hr />
{include file="News/latest_news.tpl"}

{include file="footer_index.tpl"}
