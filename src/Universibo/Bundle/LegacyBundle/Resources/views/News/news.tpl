{* parametri da passare: titolo, notizia, autore, autore_link, id_autore, data, modifica, modifica_link, elimina, elimina_link, nuova, scadenza *}
{* modifica, elimina sono da considerare come boolean, scadenza deve contenere o la stringa "Scade il data" o "scaduta il data"
   tutti e tre i parametri servono per il controllo dei diritti che avviene a livello applicativo *}
<div class="news">
	<h3>{$titolo|escape:"htmlall"|nl2br}{if $nuova=="true"}&nbsp;&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}{if $scadenza!=""}{$scadenza|escape:"htmlall"|bbcode2html|nl2br}{/if}</h3>
    <hr/>
	<p>{$notizia|escape:"htmlall"|nl2br|regex_replace:"/(^|[\s]*|[\n])([a-z]+:\/\/(www.\.)?)([-_\/=+?&%.;a-zA-Z0-9~]*)(\s+|<br \/>|$)/":"\\1<a href=\"\\2\\4\" target=\"_blank\">\\2\\4</a>\\5"|bbcode2html|linkify}</p>
    <hr/>
    <div clas="bottom-line">
        <span class="actions">
            {if $modifica!="" && $elimina!=""}
                <i class="icon-edit"></i>
                <a href="{$modifica_link|escape:"htmlall"}">{$modifica|escape:"htmlall"|nl2br}</a>
                <i class="icon-trash"></i>
                <a href="{$elimina_link|escape:"htmlall"}">{$elimina|escape:"htmlall"|bbcode2html|nl2br}</a>
            {/if}
        </span>
        <span class="news-info">
            {$data|escape:"htmlall"|nl2br}&nbsp;|&nbsp;<a href="{$autore_link|escape:"htmlall"}">{$autore|escape:"htmlall"}</a>&nbsp;|&nbsp;<a href="{$permalink}">permalink</a>
        </span>
    </div>
</div>