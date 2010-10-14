{* parametri da passare: titolo, notizia, autore, autore_link, id_autore, data, modifica, modifica_link, elimina, elimina_link, nuova, scadenza *}
{* modifica, elimina sono da considerare come boolean, scadenza deve contenere o la stringa "Scade il data" o "scaduta il data"
   tutti e tre i parametri servono per il controllo dei diritti che avviene a livello applicativo *}

<table cellspacing="0" cellpadding="0" width="100%" summary="">
<tr bgcolor="#000099"> 
<td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr>
	<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  </tr>
  </table>
</td></tr>
<tr bgcolor="#000050"> 
<td class="Titolo" align="left">&nbsp;::&nbsp;{$titolo|escape:"htmlall"|nl2br}&nbsp;::{if $nuova=="true"}&nbsp;&nbsp;<img src="tpl/black/icona_new.gif" width="21" height="9" alt="!NEW!" />{/if}</td>
</tr>
<tr bgcolor="#000099"> 
<td><img src="tpl/black/invisible.gif" height="2" width="1" alt="" /></td>
</tr>
<tr bgcolor="#000032"> 
<td>
  <table border="0" width="100%" cellspacing="0" cellpadding="4" summary="">
  <tr> 
  <td class="News" align="left">{$notizia|escape:"htmlall"|nl2br|ereg_replace:"(^|[\s]*|[\n])([a-z]+://(www.\.)?)([-_/=+?&%.;a-zA-Z0-9~]*)(\s+|<br />|$)":"\\1<a href=\"\\2\\4\" target=\"_blank\">\\2\\4</a>\\5"|bbcode2html|ereg_replace:"(^|\s+|\n)(www.[-_/=+?&%.;a-zA-Z0-9]*)(\s+|<br />|$)":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>\\3"|ereg_replace:"[^<>[:space:]]+[[:alnum:]/]@[^<>[:space:]]+[[:alnum:]/]":"<a href=\"mailto:\\0\" target=\"_blank\">\\0</a>"}</td></tr>
  <tr> 
  
  <td class="News" align="right">{$data|escape:"htmlall"|nl2br}<br />
<a href="index.php?do={$autore_link|escape:"htmlall"}">{$autore|escape:"htmlall"}</a>
</td></tr></table>
</td>
</tr>

<tr bgcolor="#000099"> 
<td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr>
	<td align="left"><img src="tpl/black/rule_piccoloL.gif" width="200" height="2" alt="" /></td>
	<td align="right"><img src="tpl/black/rule_piccoloR.gif" width="200" height="2" alt="" /></td>
  </tr>
  </table>
</td></tr>

<tr> 
<td> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
  <tr> 

<td class="Piccolo"  align="left" valign="top">  
  {if $modifica!=""}&nbsp;&nbsp;&nbsp;<img src="tpl/black/news_edt.gif" width="15" height="15" alt="modifica" />
<a href="index.php?do={$modifica_link|escape:"htmlall"}">{$modifica|escape:"htmlall"|nl2br}</a>
{/if}
{if $elimina!=""}&nbsp;&nbsp;&nbsp;<img src="tpl/black/news_del.gif" width="15" height="15" alt="elimina" />
<a href="index.php?do={$elimina_link|escape:"htmlall"}">{$elimina|escape:"htmlall"|bbcode2html|nl2br}</a>
{/if}&nbsp;
</td>

<td class="Piccolo" align="left">
{if $scadenza!=""}
{$scadenza|escape:"htmlall"|bbcode2html|nl2br}
{/if}
  </td>
  <td class="Piccolo" align="right">&nbsp;</td>
  </tr>
  </table>
</td>
</tr>
</table>