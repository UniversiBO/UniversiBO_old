{if $showMyNews_langNewsAvailableFlag == 'true'}
{foreach name=showmynews from=$showMyNews_newsList item=showMyNews_notizia}
	{include file="News/news.tpl" titolo=$showMyNews_notizia.titolo notizia=$showMyNews_notizia.notizia autore=$showMyNews_notizia.autore autore_link=$showMyNews_notizia.autore_link id_autore=$showMyNews_notizia.id_autore data=$showMyNews_notizia.data modifica=$showMyNews_notizia.modifica modifica_link=$showMyNews_notizia.modifica_link elimina=$showMyNews_notizia.elimina elimina_link=$showMyNews_notizia.elimina_link nuova='false' scadenza=$showMyNews_notizia.scadenza permalink=$showMyNews_notizia.permalink}
	{if $showMyNews_notizia.nuova=="true"}
            &nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/icona_new.gif" alt="nuova" height="9" width="21" />
	{/if}
	<span>
	{foreach from=$showMyNews_notizia.canali item=temp_canale}
		<p class="comandi"><a href={$temp_canale.link|escape:"htmlall"}>{$temp_canale.titolo|escape:"htmlall"}</a><p>
	{/foreach}
	</span>
&nbsp;<br />
{/foreach}
{else}
<p>{$showMyNews_langNewsAvailable|escape:"htmlall"}</p>
{/if}