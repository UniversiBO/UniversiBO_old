{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Cancella la notizia</p></h2>
</div>
{include file="avviso_notice.tpl"}
 
{include file=News/show_news.tpl}

<form method="post">
	<p><fieldset>
		<legend>{$f9_langAction|escape:"htmlall"}</legend>
			{foreach name=canali item=item from=$f9_canale}
				<p>&nbsp;&nbsp;<input type="checkbox" id="f9_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f9_canale[{$item.id_canale}]" />&nbsp;&nbsp;&nbsp;<label for="f9_canale{$smarty.foreach.canali.iteration}">{$item.nome_canale}</label></p>
			{/foreach}
		</fieldset></p>	  
	<p><input class="submit" type="submit" id="" name="f9_submit" size="20" value="Elimina" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;a&nbsp;{$common_langCanaleNome}</a></p>

<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file="footer_index.tpl"}