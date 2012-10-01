<div class="boxCommento">
	    <p>Voto proposto: {$showFileStudentiCommenti_commento.voto}</p>
		<p>Commento: <div class="commento">{$showFileStudentiCommenti_commento.commento|escape:"htmlall"|bbcode2html|linkify|nl2br}</div></p>
		<p>Autore: <a href="{$showFileStudentiCommenti_commento.userLink|escape:"htmlall"}">{$showFileStudentiCommenti_commento.userNick}</a></p>
</div>
