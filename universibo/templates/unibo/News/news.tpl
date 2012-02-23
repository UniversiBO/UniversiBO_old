{* parametri da passare: titolo, notizia, autore, autore_link, id_autore, data, modifica, modifica_link, elimina, elimina_link, nuova, scadenza *}
{* modifica, elimina sono da considerare come boolean, scadenza deve contenere o la stringa "Scade il data" o "scaduta il data"
   tutti e tre i parametri servono per il controllo dei diritti che avviene a livello applicativo *}

<div class="news">
	<h3>::&nbsp;{$titolo|escape:"htmlall"|nl2br}&nbsp;::{if $nuova=="true"}&nbsp;&nbsp;<img src="tpl/unibo/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}{if $scadenza!=""}{$scadenza|escape:"htmlall"|bbcode2html|nl2br}{/if}</h3>
	<p>{$notizia|escape:"htmlall"|nl2br|ereg_replace:"(^|[\s]*|[\n])([a-z]+://(www.\.)?)([-_/=+?&%.;a-zA-Z0-9~]*)(\s+|<br />|$)":"\\1<a href=\"\\2\\4\" target=\"_blank\">\\2\\4</a>\\5"|bbcode2html|ereg_replace:"(^|\s+|\n)(www.[-_/=+?&%.;a-zA-Z0-9]*)(\s+|<br />|$)":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>\\3"|ereg_replace:"[^<>[:space:]]+[[:alnum:]/]@[^<>[:space:]]+[[:alnum:]/]":"<a href=\"mailto:\\0\" target=\"_blank\">\\0</a>"}</p>
	{* TODO capire come togliere tabella *}
	<table><tr><td>
	<span class="actions">
		{if $modifica!="" && $elimina!=""}&nbsp;&nbsp;&nbsp;<img src="tpl/unibo/news_edt.gif" width="15" height="15" alt="modifica" />
		<a href="index.php?do={$modifica_link|escape:"htmlall"}">{$modifica|escape:"htmlall"|nl2br}</a>
		&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<img src="tpl/unibo/file_del.gif" width="15" height="15" alt="elimina" />
		<a href="index.php?do={$elimina_link|escape:"htmlall"}">{$elimina|escape:"htmlall"|bbcode2html|nl2br}</a>
		{/if}
	</span></td><td>
	<div class="piePagina">
		{$data|escape:"htmlall"|nl2br}&nbsp;|&nbsp;<a href="index.php?do={$autore_link|escape:"htmlall"}">{$autore|escape:"htmlall"}</a>&nbsp;|&nbsp;<a href="index.php?do=ShowPermalink&id_notizia={$id_notizia}">permalink</a>
	</div>
	</td></tr></table>
</div>
