{include file="header_index.tpl"}

{include file="avviso_notice.tpl"}

<h2 id="inizio">{$contacts_langAltTitle|escape:"htmlall"|bbcode2html}</h2>
<p>{$contacts_langIntro|escape:"htmlall"|bbcode2html|linkify|nl2br}</p>
<hr />
<div class="elencoFile">
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
{foreach from=$contacts_langPersonal item=temp_curr_people}
    <tr class="{cycle values="even,odd"}">
      <td>
        {if $temp_curr_people.URI == 'false'}{$temp_curr_people.username|escape:"htmlall"}
        {else}<a href="{$temp_curr_people.URI|escape:"htmlall"}">{$temp_curr_people.username|escape:"htmlall"}</a>
        {/if}
      </td>
      <td>
        {if $temp_curr_people.inserisci != 'false'}<img src="{$common_basePath}/bundles/universibodesign/images/file_new.gif" width="10" height="9" /><a href="{$temp_curr_people.inserisci|escape:"htmlall"}">&nbsp;Inserisci&nbsp;profilo&nbsp;collaboratore</a>{/if}
      </td>
    </tr>
{/foreach}
</table>
</div>
{include file="footer_index.tpl"}	
