{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica Didattica</h2>
</div>
{include file="avviso_notice.tpl"}
<form name="nav" method="post" >
	{*<fieldset>
		<legend>Navigazione:</legend>
		{foreach name=facAnno item=item1 from=$f41_fac key=id}
		{if $smarty.foreach.facAnno.first}<ul class="explodableList">{/if}
	*}	{* NB senza il &nbsp; la lista javascript non funzia bene *}
	{*	<li {if $item1.spunta=="true"} class="selectedItem"{/if}>&nbsp;<a href="{$DidatticaGestione_baseUrl}&amp;id_fac={$id}" >{$item1.nome|escape:"htmlall"}</a>
		{if $item1.spunta=="true"}
			{foreach name=cdlAnno item=item2 from=$f41_cdl key=id2}
			{if $smarty.foreach.cdlAnno.first}<ul>{/if}
			<li {if $item2.spunta=="true"} class="selectedItem"{/if}>&nbsp;<a href="{$DidatticaGestione_baseUrl}&amp;id_fac={$id}&amp;id_cdl={$id2}" >{$item2.nome|escape:"htmlall"}</a>
			{if $item2.spunta=="true"}
				{foreach name=canaleAnno item=arr from=$f41_canale key=anno}
				{if $smarty.foreach.canaleAnno.first}<ul>{/if}
					<li>{$anno|escape:"htmlall"}
					{foreach name=canali item=item from=$arr key=key}
					{if $smarty.foreach.canali.first}<ul>{/if}<li {if $item.spunta=="true"} class="selectedItem"{/if}>&nbsp;<a href="{$DidatticaGestione_baseUrl}&amp;id_fac={$id}&amp;id_cdl={$id2}&amp;id_canale={$key}">{$item.nome}</a></li>{if $smarty.foreach.canali.last}</ul>{/if}
					{/foreach}
				{if $smarty.foreach.canaleAnno.last}</ul>{/if}			
				{/foreach}
			{/if}
			</li>
			{if $smarty.foreach.cdlAnno.last}</ul>{/if}
			{/foreach}
		{/if}
		</li>
		{if $smarty.foreach.facAnno.last}</ul>{/if}			
		{foreachelse}
		<p>Nessuna facolt� visualizzabile</p>
		{/foreach}
	</fieldset> 
*}	{if $f41_cur_sel != ''}
		<div class="elenco">
		<h3>Insegnamento selezionato:</h3>
		{foreach name=sel item=item from=$f41_cur_sel key=key}
		{cycle name=t_class values="even,odd" print=false advance=false}
		<p class="{cycle name=t_class}"><span class="label">{$key|escape:"htmlall"}:</span>  {$item|escape:"htmlall"}</p>
		{/foreach}
		</div>
	{/if}
	{if $DidatticaGestione_edit == 'true'}
	<fieldset>
		<legend>Modifica:</legend>
		{foreach name=edit item=val key=key from=$f41_edit_sel}
			<p><label class="label" for="{$key}">{$key|escape:"htmlall"}:</label>
		<input class="casella" type="text" name="f41_edit_sel[{$key}]" id="{$key}" size="65" value="{$val|escape:"htmlall"}" /></p>

{*			<p><label class="label" for="f41_codDoc">Codice docente:</label>
		<input class="casella" type="text" name="f41_codDoc" id="f41_codDoc" size="65" value="{$f41_codDoc|escape:"htmlall"}" /></p>
	<p><label class="label" for="f41_ciclo">Ciclo:</label>
		<input type="text" class="casella" id="f41_ciclo" name="f41_ciclo" size="65" maxlength="130" value="{$f41_ciclo|escape:"htmlall"}" /></p>
	<p><span><label for="f41_Description"><p>Descrizione<br /> del link:<br />(max 1000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f41_Description" name="f41_Description">{$f41_Description|escape:"htmlall"}</textarea></span></p>*}
		{/foreach}
		
		<p>NB: <br/>ciclo -> 1,2,3,E<br/>anno -> 0,1,2,3,4,5</p>

	</fieldset>
	{if $DidatticaGestione_docenteEdit}
	<fieldset>
	<legend>Ricerca docente</legend>
		<p><label class="label" for="f41_username">per&nbsp;username: </label>
		<input name="f41_username" id="f41_username" type="text" value="" /></p>
	<p><label class="label" for="f41_email">per e-mail: </label>
		<input name="f41_email" id="f41_email" type="text" value="" /></p>
	<p><input class="submit" name="f41_search" id="f41_search" type="submit" value="Cerca" /></p>
	{if $DidatticaGestione_docs != ''}
	<p><strong>Docenti trovati</strong></p>
	{foreach item=d from=$DidatticaGestione_docs}
	<p>{$d.nome}&nbsp;&nbsp;&nbsp;&nbsp;{$d.codice}</p>
	{/foreach}
	{/if}
	</fieldset>
		{/if}
	
	{if $f41_alts != ''}
	<fieldset>
		<legend><span>Applica la modifica anche ai seguenti insegnamenti:</span></legend>
			{foreach name=canali item=item from=$f41_alts}
				<p><input type="checkbox" id="f41_alts{$smarty.foreach.canali.iteration}" {if $item.spunta=="true"}checked="checked" {/if} name="f41_alts[{$item.id}]" />&nbsp;&nbsp;&nbsp;<label for="f41_alts{$smarty.foreach.canali.iteration}"><a {if $item.status == ''}class="evidenzia" {/if} href="{$item.uri}">{$item.nome}, {$item.doc}, cdl {$item.cdl}, ciclo {$item.ciclo}, anno {$item.anno}{if $item.status != ''}, {$item.status}{/if} </a></label></p>
			{/foreach}
		</fieldset>
			
		{/if}
	{/if}
	<p><input class="submit" type="submit" id="" name="f41_submit" size="20" value="Esegui" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>


<hr />
<p>{include file="Help/topic.tpl" showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}</p>

{include file="footer_index.tpl"}