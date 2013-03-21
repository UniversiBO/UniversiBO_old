{include file="header_index.tpl"}
		
{include file="avviso_notice.tpl"}

<h2 id="inizio">{$collaboratore_langAltTitle|escape:"htmlall"|bbcode2html}&nbsp;{$collaboratore.username}</h2>

<hr />
<div class="chi_siamo">
	<h3>::&nbsp;{$collaboratore.username|escape:"html"}&nbsp;::</h3>
	<img src="{$common_basePath}/{$contacts_path}{$collaboratore.foto|escape:"htmlall"}" alt="foto di {$collaboratore.username|escape:"htmlall"}" width="60" height="80" />
	<hr class="hide" />
	<div>
		<p><span>Ruolo:</span>&nbsp;{$collaboratore.ruolo|escape:"htmlall"}</p>
		<p><span>Email:</span>&nbsp;<a href="mailto:{$collaboratore.email|escape:"htmlall"}">{$collaboratore.email|escape:"htmlall"}</a></p>
		<p><span>Recapito:</span>&nbsp;{$collaboratore.recapito|escape:"htmlall"}</p>
	</div>
	<p>{$collaboratore.intro|escape:"htmlall"}&nbsp;</p>
	<p>{$collaboratore.obiettivi|escape:"htmlall"|bbcode2html|nl2br}&nbsp;</p>
	{if $collaboratore.modifica !== ""}
	  <span class="actions">&nbsp;&nbsp;&nbsp;<img src="{$common_basePath}/bundles/universibodesign/images/file_edit_32.gif" width="15" height="15" alt="modifica" />
	    <a href="{$collaboratore.modifica_link|escape:"htmlall"}">{$collaboratore.modifica|escape:"htmlall"|nl2br}</a>
	  </span>
	{/if}  
	
</div>

<p><a href="{$common_contactsUri|escape:"htmlall"}">Torna&nbsp;al&nbsp;{$common_contacts}</a></p>

{include file="footer_index.tpl"}	
 