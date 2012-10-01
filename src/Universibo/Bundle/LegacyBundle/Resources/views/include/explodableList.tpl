{* parametri attesi:
		- $lista:  array   $key => arrayOfValues dove $key rappresenta la categoria con la quale sono raggruppati i valori di arrayOfValues 
		- $msg:  messaggio di errore
		-$name: valore da usare per l'attributo name degli input*}
<ul class="explodableList">
{foreach name=canaleAnno item=arr from=$lista key=anno}
	<li>{$anno|escape:"htmlall"}
	{foreach name=canali item=item from=$arr key=key}
	{if $smarty.foreach.canali.first}<ul>{/if}<li><input type="checkbox" id="{$name}{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="{$name}[{$key}]" />&nbsp;&nbsp;&nbsp;<label for="{$name}{$smarty.foreach.canali.iteration}">{$item.nome}</label></li>{if $smarty.foreach.canali.last}</ul>{/if}
	{/foreach}
	</li>
{foreachelse}
<p>{$msg|escape:"htmlall"}</p>
{/foreach}
</ul>