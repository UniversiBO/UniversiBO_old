{include file=header_index.tpl}

<div id="statistiche">
<h2>{$ShowStatistiche_titolo}</h2>


{foreach name=extern from=$ShowStatistiche_elencoFilePerMese item=row}
{if $smarty.foreach.extern.first}<ul><li class="header"><span class="anno">anno</span>  <span class="mese" > mese</span> <span class=last"> Somma</span></li>{/if}
<li><span class="anno">{$row.anno}</span>  <span class="mese" >{$row.mese}</span> <span class="last"> {$row.somma}</span> </li>
{if $smarty.foreach.extern.last}</ul>{/if}
{foreachelse}<p>Nessun risultato da visualizzare </p>
{/foreach}

<hr />

{foreach name=extern from=$ShowStatistiche_elencoUtentiPerMese item=row}
{if $smarty.foreach.extern.first}<ul><li class="header"><span class="anno">anno</span>  <span class="mese" > mese</span><span class="giorno">Giorno</span><span class="iscritti last">Iscritti</span></li>{/if}
<li><span class="anno">{$row.anno}</span> <span class="mese" >{$row.mese}</span> <span class="giorno">{$row.giorno}</span><span class="iscritti last">{$row.iscritti} </li>
{if $smarty.foreach.extern.last}</ul>{/if}
{foreachelse}<p>Nessun risultato da visualizzare </p>
{/foreach}
</div>


{include file=footer_index.tpl}