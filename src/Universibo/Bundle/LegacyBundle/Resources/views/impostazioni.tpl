{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<div class="titoloPagina">
<h2>Le mie impostazioni</h2>
<p>{$showSettings_langIntro|escape:"htmlall"|bbcode2html}</p>
</div>
<hr />
<h4>Preferenze</h4>
{include file="tabellina_due_colonne.tpl" arrayToShow=$showSettings_langPreferences} 
{if $showSettings_showAdminPanel == "true"} 
<hr />
<h4>Admin</h4>
{include file="tabellina_due_colonne.tpl" arrayToShow=$showSettings_langAdmin}
{/if}

{include file="footer_index.tpl"}
