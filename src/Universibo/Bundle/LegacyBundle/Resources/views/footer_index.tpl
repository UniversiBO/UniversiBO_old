	</div>
</td> {* FINE MENU CENTRALE*}
<td id="rightmenu" width="170"> {* COLONNA MENU DI DESTRA*}
    {if $common_userLoggedIn == 'true'}
	<div class="box"> {* primo blocchetto *} 
		<h3>Info</h3>
		<div class="contenuto">
			<p>{$common_langWelcomeMsg|escape:"htmlall"|bbcode2html|nl2br} {$common_userUsername|escape:"htmlall"}<br />
			{$common_langUserLivello|escape:"htmlall"|bbcode2html|nl2br} {foreach from=$common_userLivello item=temp_nomeLivello}{$temp_nomeLivello|escape:"htmlall"} {/foreach}<br />
		</div>	
	</div>
	{/if}
	{if $common_contactsCanaleAvailable|default:"false" =="true"}
	<div class="box"> {* secondo blocchetto *}
	<h3>Contatti</h3>
		<div class="contenuto">
			{foreach from=$common_contactsCanale key=temp_key item=temp_currGroup}
				<p>{$temp_key|escape:"htmlall"}</p>
				{foreach from=$temp_currGroup item=temp_currLink}
					<p><img src="{$common_basePath}/bundles/universibolegacy/images/pallino1.gif" width="11" height="10" alt="" />&nbsp;
					<a href="{$temp_currLink.utente_link|escape:"htmlall"}">{$temp_currLink.label|escape:"htmlall"}</a>
					{if $temp_currLink.ruolo=="R"}&nbsp;<img src="{$common_basePath}/bundles/universibolegacy/images/icona_r.gif" width="9" height="9" alt="Referente" title="Referente" />{/if}
					{if $temp_currLink.ruolo=="M"}&nbsp;<img src="{$common_basePath}/bundles/universibolegacy/images/icona_r.gif" width="9" height="9" alt="Moderatore" title="Moderatore" />{/if}</p>
				{/foreach}
			{/foreach}
			{if $common_contactsEditAvailable == "true"}
				<div class="actions"><a href="{$common_contactsEdit.uri|escape:"htmlall"}">{$common_contactsEdit.label|escape:"htmlall"}</a></div>
			{/if}
		</div>
	</div>
	{/if}
	{if $common_newPostsAvailable|default:"false" =="true"}
	<div class="box"> {* blocchetto forum*}
	<h3>Ultimi messaggi dal forum</h3>
		<div class="contenuto">
			{section loop=$common_newPostsList name=temp_currPost}
				<p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="11" height="10" alt="" />&nbsp;<a title="Questo link apre una nuova pagina" target="_blank" href="{$common_newPostsList[temp_currPost].URI|escape:"htmlall"}">{$common_newPostsList[temp_currPost].desc|escape:"htmlall"}</a></p>
			{sectionelse}<p>Non ci sono post nel forum</p>
			{/section}
                        <p><a href="{$common_forumLink|escape:"htmlall"}">Visita il forum</a></p>
		</div>
                
	</div>
	{/if}
{*	<div class="box"> 
		<h3>Links</h3>
		<div class="contenuto">
<p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.unibo.it" target="_blank">Universit&agrave; di BO</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.ing.unibo.it" target="_blank">Facolt&agrave; di Ingegneria</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="https://uniwex.unibo.it" target="_blank">Uniwex</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://guida.ing.unibo.it" target="_blank">Guida dello Studente</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.ing.unibo.it/Ingegneria/dipartimenti.htm" target="_blank">Elenco Dipartimenti</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www2.unibo.it/avl/org/constud/tutteass/tutteass.htm" target="_blank">Assoc. Studentesche</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.nettuno.it/bo/ordineingegneri/" target="_blank">Ordine Ingegneri</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.atc.bo.it/" target="_blank">ATC Bologna</a></p><p><img src="{$common_basePath}/bundles/universibolegacy/images/freccia.gif" width="12" height="11" alt="->" />&nbsp;<a title="Questo link apre una nuova pagina" href="http://www.trenitalia.com/" target="_blank">Trenitalia</a></p>
		</div>
	</div>
*}
	{* blocchetto links *}
{if $showLinks_linksListAvailable|default:"false" =="true"}
	{include file="Links/show_links.tpl"}
{/if}	
{if $showFileStudentiTitoli_langFileAvailableFlag|default:"false" =="true"}
	{include file="Files/box_show_files_studenti.tpl"}
{/if}	

	{$common_calendarBox}
	
	{if $common_isSetVisite == 'true'}
	<div class="box"> {* sesto blocchetto *}
		<h3>Statistiche</h3>
		<div class="contenuto">
			<p>{$common_visite}&nbsp;</p>
			<span>visite in questa pagina</span>
		</div> 
	</div>
	{/if}
	<p id="versione">Versione {$common_version|escape:"htmlall"}</p>
</td></tr> {* FINE MENU DI DESTRA*}
<tr>
	<td colspan="2">
		<div id="footer"> {* FONDO PAGINA *}
		{foreach from=$common_disclaimer item=temp_disclaimer} 
			<p>{$temp_disclaimer|escape:"htmlall"|bbcode2html|nl2br}</p>
		{/foreach}
		</div>
	</td>
</tr>
</table> 
</body>
</html>



