{include file="header_index.tpl"}

<div class="titoloPagina">
<h2>{$showAccessibility_langTitleAlt|escape:"htmlall"}</h2>
{* Decommentare questa parte se si vuole che sia possibile aggiungere questa pagina al my universibo NB il comando corrispondente deve ereditare da canale command
{if $common_langCanaleMyUniversiBO != '' }
	<div class="comandi">
	{if $common_canaleMyUniversiBO == "remove"}<img src="tpl/unibo/esame_myuniversibo_del.gif" width="15" height="15" alt="" />&nbsp;{else}<img src="tpl/unibo/esame_myuniversibo_add.gif" width="15" height="15" alt="" />&nbsp;{/if}<a href="{$common_canaleMyUniversiBOUri|escape:"htmlall"}">{$common_langCanaleMyUniversiBO|escape:"htmlall"}</a></div>
{/if}
*}
</div>

<p>Nello sviluppo di UniversiBO, si &egrave; posta particolare attenzione nel
renderlo il pi&ugrave; <strong>accessibile</strong> possibile, poich&eacute;
convinti che le informazioni debbano essere fruibili da chiunque a
prescindere dalle proprie capacit&agrave; o strumenti a disposizione.</p>
<p>In tale ottica, per facilitare la navigazione sono stati definiti
degli <strong><span lang="en">accesskey</span></strong>, ovvero dei tasti di accesso rapido
che permettono di accedere direttamente a determinate parti delle pagine.
</p><p>Ecco la lista dei tasti implementati:</p>
<table id="accesskeys">
<tr><th>acceskey</th><th>funzione</th></tr>
<tr><td>1</td><td>vai all'homepage</td></tr>
<tr><td>2</td><td>vai al forum</td></tr>
<tr><td>3</td><td>vai al contenuto principale della pagina</td></tr>
<tr><td>4</td><td>vai al myuniversibo</td></tr>
<tr><td>5</td><td>vai al men&ugrave; di navigazione tra le sezioni</td></tr>
<tr><td>0</td><td>vai a questa pagina sull'accessibilit&agrave;</td></tr>
</table>
<p>
L'attivazione di questi tasti di accesso dipende sia dal vostro
sistema operativo che dal vostro <span lang="en">browser</span>, per esempio:
</p>
<table id="browserkeys">
<tr><th>browser</th><th>attivazione acceskey</th></tr>
<tr><td>Internet Explorer</td><td>Alt + [accesskey] + Invio </td></tr>
<tr><td>Mozilla, Netscape 6+, FireFox Windows</td><td>Alt+[accesskey]</td></tr>
<tr><td>Opera 7</td><td>Esc + Shift e [accesskey]</td></tr>
<tr><td>Safari 1.2 Macintosh</td><td>Ctrl e [accesskey]</td></tr>
<tr><td>Galeon/Mozilla/FireFox Linux</td><td>Alt e [accesskey]</td></tr>
</table>
{*<dl>
<dt>IE Windows, IBM Home Page Reader : </dt><dd>alt + [accesskey] + Invio </dd>
<dt>Mozilla, Netscape 6+, K-Meleon, FireFox Windows: </dt><dd>Alt+[accesskey]</dd>
<dt>Opera 7 Windows, Macintosh, Linux : </dt><dd>Esc + Shift e [accesskey]</dd>
<dt>MSIE Macintosh : </dt><dd>Ctrl e [accesskey], poi Invio</dd>
<dt>Safari 1.2 Macintosh : </dt><dd>Ctrl e [accesskey]</dd>
<dt>Mozilla, Netscape Macintosh : </dt><dd>Ctrl e [accesskey]</dd>
<dt>Galeon/Mozilla/FireFox Linux : </dt><dd>Alt e [accesskey]</dd>
<dt>Konqueror 3.3+ : </dt><dd>Ctrl, e successivamente [accesskey]</dd>
<dt>Handspring Blazer (Treo 600) : </dt><dd>[accesskey]</dd>
</dl>*}

{*<p>Il testo della pagina &egrave; ridimensionabile tramite browser fino ad un
150% circa del testo originale</p>*}
<p>Per gli utilizzatori di <span lang="en">browser</span> testuali o <span lang="en">screenreader</span> sono stati
aggiunti dei link che permettono una veloce navigazione tra le aree di
interesse della pagina, del tipo "Vai al contenuto".</p>
<p>Si &egrave; cercato inoltre di conformarsi il pi&ugrave; possibile tutti i punti elencati 
nelle le linee guide specificate dal <acronym title="Web Content Accessibility Guidelines">WCAG</acronym>
 del <acronym title="World Wide Web Consortium">W3C</acronym> e della legge
 legge italiana sull'accessibilit&agrave; del 4/2004.</p>

{include file="footer_index.tpl"}