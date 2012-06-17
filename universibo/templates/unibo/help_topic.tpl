{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<h2>Help</h2>
{if $showHelpTopic_index == "true"}
    <a id="index" />
    <div class="elencoTopic">
	{foreach from=$showHelpTopic_topics item=temp_ref}
	<p class="{cycle name=index values="even,odd"}">&nbsp;
	<a href="#{$temp_ref.reference|escape:"htmlall"}"> {$temp_ref.titolo|escape:"htmlall"}</a></p>
	{/foreach}
	</div>
{/if}

{foreach from=$showHelpTopic_topics item=temp_topic}
	{include file="Help/topic.tpl" showTopic_topic=$temp_topic idsu=$temp_topic.reference}
{/foreach}
{include file="footer_index.tpl"}