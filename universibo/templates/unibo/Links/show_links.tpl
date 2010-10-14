<div class="box"> {* blocchetto links*}
<h3>Links</h3>
	<div class="contenuto">
{foreach name=linkpagina from=$showLinks_linksList item=temp_currLink}<p>
 {if $temp_currLink.tipo == "interno"}
	<img src="tpl/unibo/pallino1.gif" width="12" height="11" alt="->" />&nbsp;<a href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a></p>
{else}
 <img src="tpl/unibo/freccia.gif" width="11" height="10" alt="" />&nbsp;<a title="Questo link apre una nuova pagina" target="_blank" href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a></p>
 {/if}{/foreach}
 
 {if $smarty.foreach.linkpagina.total == 0}<p>Nessun link presente</p>{/if}

{if $showLinks_linksPersonalizza == 'true'}
	<p><a href="{$showLinks_linksAdminUri|escape:"htmlall"}">{$showLinks_linksAdminLabel|escape:"htmlall"}</a></p>
{/if}
	</div>
</div>
