{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Cancella il file</h2>
</div>
{include file="avviso_notice.tpl"}

<form method="post">
	{if $fileDelete_flagCanali == 'true'}
	<p><fieldset>
	<legend>{$f14_langAction|escape:"htmlall"}</legend>
		{foreach name=canali item=item from=$f14_canale}
			<p><input type="checkbox" id="f14_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f14_canale[{$item.id_canale}]" />&nbsp;&nbsp;&nbsp;<label for="f14_canale{$smarty.foreach.canali.iteration}">{$item.nome_canale}</label></p>
		{/foreach}
	</fieldset></p>	  
	{/if}
	<p><input class="submit" type="submit" id="" name="f14_submit" size="20" value="Elimina" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome|escape:"htmlall"}</a></p>

{include file="footer_index.tpl"}