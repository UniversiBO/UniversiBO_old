{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Gestione file</h2>
</div>
{include file=avviso_notice.tpl}
<form method="post" >
	<fieldset>
		<legend>Seleziona i file da copiare</legend>
		<ul class="explodableList">
		{foreach name=canaleFile item=category from=$f40_files key=key}
			<li>{$key|escape:"htmlall"}
			{foreach name=files item=item from=$category key=id}		
			{if $smarty.foreach.files.first}<ul>{/if}<li><input type="checkbox" id="f40_files{$smarty.foreach.canaleFile.iteration}-{$smarty.foreach.files.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f40_files[{$id}]" />&nbsp;&nbsp;&nbsp;<label for="f40_files{$smarty.foreach.files.iteration}">{$item.nome}</label></li>{if $smarty.foreach.files.last}</ul>{/if}
			{/foreach}
			</li>
		{/foreach}
		</ul>
	</fieldset>
	<fieldset>
		<legend>Seleziona le pagine in cui inserire i file selezionati:</legend>
		<ul class="explodableList">
		{foreach name=canaleAnno item=arr from=$f40_canale key=anno}
			<li>{$anno|escape:"htmlall"}
			{foreach name=canali item=item from=$arr key=key}
			{if $smarty.foreach.canali.first}<ul>{/if}<li><input type="checkbox" id="f40_canale{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f40_canale[{$key}]" />&nbsp;&nbsp;&nbsp;<label for="f40_canale{$smarty.foreach.canali.iteration}">{$item.nome}</label></li>{if $smarty.foreach.canali.last}</ul>{/if}
			{/foreach}
			</li>
		{foreachelse}
		<p>Non si è referente di alcuna pagina attiva</p>
		{/foreach}
		</ul>
	</fieldset>
	<p><input class="submit" type="submit" id="" name="f40_submit" size="20" value="Esegui" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

{*
<hr />
<p>{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}</p>
*}
{include file=footer_index.tpl}