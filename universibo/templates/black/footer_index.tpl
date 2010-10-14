</td></tr></table>

</td><td width="170" valign="top" class="Normal">&nbsp;<br />
<!-- Inizio MENU Right -->
{include file=box_loginlogout.tpl}

{include file=box_contacts.tpl}

{include file=box_links.tpl}

{include file=box_forum.tpl}

{include file=box_show_files_studenti.tpl}

{include file=box_calendar.tpl}

{include file=box_visite.tpl}

<p align="center" class="MenuC">Versione {$common_version|escape:"htmlall"}</p>
<!-- Fine MENU Right -->
</td></tr>
</table>
</td></tr>
<tr><td align="center">
<!--INIZIO DISCLAIMER -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td align="center" class="Piccolo">
&nbsp;<br />&nbsp;<br /><hr style="color: #000066;" />&nbsp;<br />
{foreach from=$common_disclaimer item=temp_disclaimer} 
	<p>{$temp_disclaimer|escape:"htmlall"|bbcode2html|nl2br}</p>
{/foreach}
</td></tr></table>
<!--FINE DISCLAIMER -->
</td></tr>
</table>
<p class="Piccolo" align="center"> Pagina creata in  
0.20476 secondi</p>
</body>
</html>

