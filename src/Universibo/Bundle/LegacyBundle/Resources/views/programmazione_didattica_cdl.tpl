{include file="header_index.tpl"}
Gestione Corsi di Laurea
<form method="post">
<table>
<tr><td>Codice - Nome Corso di laurea</td><td>Codice Preside</td><td>Attiva</td></tr>
{foreach item=cdl_curr from=$programmazioneDidatticaCdl_elencoCdl}
<tr>

<td align="left">
{if $cdl_curr.attiva=='true'}<a href="/?do=ProgrammazioneDidatticaCdl">{$cdl_curr.cod_corso} - {$cdl_curr.desc_corso}</a>{else}{$cdl_curr.cod_corso} - {$cdl_curr.desc_corso}{/if}</td>

<td>{$cdl_curr.cod_doc_presidente}</td>

<td>{if $cdl_curr.attiva=='false'}<input type="checkbox" name="f25_cod_corso[{$cdl_curr.cod_corso}]" value="{$cdl_curr.cod_corso}" />
{elseif $cdl_curr.public=='false'}
<input type="submit" name="f25_submit_publish[{$cdl_curr.cod_corso}]" value="pubblica" />
{else}
<input type="submit" name="f25_submit_hide[{$cdl_curr.cod_corso}]" value="nascondi" />
{/if}</td>

</tr>
{/foreach}
</table>

<input type="submit" name="f25_submit" value="Attiva" />

</form>
{include file="footer_index.tpl"}