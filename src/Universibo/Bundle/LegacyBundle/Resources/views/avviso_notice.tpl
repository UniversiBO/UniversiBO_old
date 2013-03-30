{if $error_notice_present=='true'}
{foreach from=$error_notice|default:'' item=temp_error_notice}
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {$temp_error_notice|escape:"htmlall"|nl2br}
    </div>
{/foreach} 
{/if}