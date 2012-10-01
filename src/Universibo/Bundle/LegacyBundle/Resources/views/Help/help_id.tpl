{if $indice}
	<div class="elencoTopic">
	{foreach from=$showHelpId_langArgomento item=temp_helpid}
	<p class="{cycle name=index values="even,odd"}">&nbsp<a href="#{$temp_helpid.id|escape:"htmlall"}"> {$temp_helpid.titolo|escape:"htmlall"}</a></p>
	{/foreach}
	</div>
{/if}
{foreach from=$showHelpId_langArgomento item=temp_helpid}
<div class="help_generale">
	<h3 id="{$temp_helpid.id|escape:"htmlall"}">&nbsp;{$temp_helpid.titolo|escape:"htmlall"}</h3>
	<p>{$temp_helpid.contenuto|escape:"htmlall"|bbcode2html|nl2br}</p>
	<div><a href="#content">torna su</a></div>
</div>
{/foreach}

