
<div class="contenuto">

{foreach name=listalink from=$showLinksExtended_linksList item=temp_currLink}
{if $smarty.foreach.listalink.first}
<table width="98%" border="0" cellspacing="0" cellpadding="2" align="center" summary="">
	<tr>
	 <td>Indirizzo</td>
	 <td>Descrizione</td>
	 <td>Autore</td>
	 <td>Modifica/Elimina</td>
	</tr>
{/if}
<tr>
{if $temp_currLink.tipo == "interno"}
	<td><img src="{$common_basePath}/tpl/unibo/pallino1.gif" width="12" height="11" alt="->" />&nbsp;<a href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a></p></td>
{else}
 <td><img src="{$common_basePath}/tpl/unibo/freccia.gif" width="11" height="10" alt="" />&nbsp;<a title="Questo link apre una nuova pagina" target="_blank" href="{$temp_currLink.uri|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a></p></td>
 {/if}
 <td>{$temp_currLink.description|escape:"htmlall"}</td>
 <td><a href="{$temp_currLink.userlink|escape:"htmlall"}">{$temp_currLink.user|escape:"htmlall"}</a></td>
 <td>{if $temp_currLink.modifica!=""}<a href="{$temp_currLink.modifica_link_uri|escape:"htmlall"}"><img src="{$common_basePath}/tpl/unibo/news_edt.gif" border="0" width="15" height="15" alt="modifica" hspace="1"/></a>{/if}
	 {if $temp_currLink.elimina!=""}<a href="{$temp_currLink.elimina_link_uri|escape:"htmlall"}"><img src="{$common_basePath}/tpl/unibo/file_del.gif" border="0" width="15" height="15" alt="elimina" hspace="1"/></a>{/if}</td>
 </tr>
 {if $smarty.foreach.listalink.last}</table>{/if}
 {/foreach}
 {if $smarty.foreach.listalink.total == 0}<p>Nessun link Presente</p>{/if}
 
</div>
