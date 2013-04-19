{if $common_langCanaleMyUniversiBO != '' }
    <div class="comandi">
        <a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">
            {if $common_canaleMyUniversiBO == "remove"}
                <i class="icon-remove"></i>
            {else}
                <i class="icon-bookmark"></i>
            {/if}
            {$common_langCanaleMyUniversiBO|escape:"htmlall"}
        </a>
    </div>
{/if}