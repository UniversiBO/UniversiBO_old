{include file=header_index.tpl}
Gestione Facolt&agrave;
<form method="post">
<table>
<tr><td>Codice - Nome Facolta</td><td>Codice Preside</td><td>Attiva</td></tr>
{foreach item=facolta_curr from=$programmazioneDidatticaFacolta_elencoFacolta}
<tr>

<td align="left">
{if $facolta_curr.attiva=='true'}<a href="index.php?do=ProgrammazioneDidatticaCdl&amp;cod_fac={$facolta_curr.cod_fac}">{$facolta_curr.cod_fac} - {$facolta_curr.desc_fac}</a>{else}{$facolta_curr.cod_fac} - {$facolta_curr.desc_fac}{/if}</td>

<td>{$facolta_curr.cod_doc_preside}</td>

<td>{if $facolta_curr.attiva=='false'}<input type="checkbox" name="f24_cod_fac[{$facolta_curr.cod_fac}]" value="{$facolta_curr.cod_fac}" />
{elseif $facolta_curr.public=='false'}
<input type="submit" name="f24_submit_publish[{$facolta_curr.cod_fac}]" value="pubblica" />
{else}
<input type="submit" name="f24_submit_hide[{$facolta_curr.cod_fac}]" value="nascondi" />
{/if}</td>

</tr>
{/foreach}
</table>

<input type="submit" name="f24_submit" value="Attiva" />

</form>
{include file=footer_index.tpl}