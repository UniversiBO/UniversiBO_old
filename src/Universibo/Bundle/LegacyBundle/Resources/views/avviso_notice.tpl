{if $error_notice_present=='true'}

<div class="flash-error-container">
{foreach from=$error_notice|default:'' item=temp_error_notice}
	<p class="flash-error">{$temp_error_notice|escape:"htmlall"|nl2br}</p>
{/foreach} 
</div>
{/if}