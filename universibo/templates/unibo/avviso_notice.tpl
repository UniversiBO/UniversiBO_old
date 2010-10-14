{if $error_notice_present=='true'}

<div id="error">
{foreach from=$error_notice|default:'' item=temp_error_notice}
	<p>{$temp_error_notice|escape:"htmlall"|nl2br}</p>
{ /foreach } 
</div>
{/if}