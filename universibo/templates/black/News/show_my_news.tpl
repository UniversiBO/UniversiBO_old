{foreach from=$showMyNews_newsList item=showMyNews_notizia}
{include file=News/news.tpl titolo=$showMyNews_notizia.titolo notizia=$showMyNews_notizia.notizia autore=$showMyNews_notizia.autore autore_link=$showMyNews_notizia.autore_link id_autore=$showMyNews_notizia.id_autore data=$showMyNews_notizia.data modifica=$showMyNews_notizia.modifica modifica_link=$showMyNews_notizia.modifica_link elimina=$showMyNews_notizia.elimina elimina_link=$showMyNews_notizia.elimina_link nuova='false' scadenza=$showMyNews_notizia.scadenza}
<table cellpadding="0" cellspacing="0" summary="">
{foreach from=$showMyNews_notizia.nuova item=temp_new}
{if $temp_new.nuova=="true"}
<table>
<tr><td align="left">
&nbsp;<img src="tpl/black/icona_new.gif" alt="nuova" height="9" width="21" />
</td></tr>
</table>
{/if}
{/foreach}
{foreach from=$showMyNews_notizia.canali item=temp_canale}
<tr><td align="left" class="Normal">
<a href={$temp_canale.link|escape:"htmlall"}>{$temp_canale.titolo|escape:"htmlall"} </a>
</td></tr>
{/foreach}
</table>
&nbsp;<br />
{/foreach}