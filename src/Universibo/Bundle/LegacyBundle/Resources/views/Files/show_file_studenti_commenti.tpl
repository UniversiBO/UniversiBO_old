<div class="boxCommenti">
	<h2>Commenti:</h2>
	{if $isFileStudente == 'true'}
	 <p>&nbsp;Voto medio:&nbsp;{$showFileInfo_voto|escape:"htmlall"}</p>
	 <p>&nbsp;<a href="{$showFileInfo_addComment|escape:"htmlall"}">Aggiungi il tuo commento!</a></p>
	{/if}
{if $showFileStudentiCommenti_langCommentiAvailableFlag == "true"}
	{foreach from=$showFileStudentiCommenti_commentiList item=temp_commenti}
	<div class="boxCommento">
	    <p>Voto proposto: {$temp_commenti.voto}</p>
		<p>Commento:</p><div class="commento"> {$temp_commenti.commento|escape:"htmlall"|bbcode2html|linkify|nl2br}<br /></div>
		<p>Autore: <a href="{$temp_commenti.userLink|escape:"htmlall"}">{$temp_commenti.userNick}</a></p>
		{if $temp_commenti.dirittiCommento=="true"}
		<p><span>
			<a href="{$temp_commenti.editCommentoLink|escape:"htmlall"}">Modifica il commento</a>&nbsp;
			<a href="{$temp_commenti.deleteCommentoLink|escape:"htmlall"}">Cancella il commento</a>
		</span></p>
		{/if}
	</div>
	{/foreach}
	</div>
{else}
<p> Non esistono commenti per questo file.</p>
{/if}