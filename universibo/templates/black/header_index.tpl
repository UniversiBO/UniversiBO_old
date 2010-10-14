{config_load file="main.conf"}
{* #docType# *}
{*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<title>{$common_title|escape:"htmlall"}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta lang="it" name="keywords" content="{$common_metaKeywords|escape:"htmlall"}" />
<meta lang="it" name="description" content="{$common_metaDescription|escape:"htmlall"}" />
<script type="text/javascript" src="tpl/black/js_common.js"></script>
{#styleSheet#}
{#script#}
{#favIcon#}
</head>
 
<body text="#FFFFFF" bgcolor="#000000" > <!--leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0"-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td>
<!-- Inizio Testa -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
{if $common_alert != ""}
<tr><td class="Normal" align="center"><font color="#FF0000">{$common_alert|escape:"htmlall"}</font></td></tr>
{/if}
<tr><td bgcolor="#000099"><img src="tpl/black/rule_grande.gif" width="625" height="4" alt="" /></td></tr>

<tr><td bgcolor="#000099"><img src="tpl/black/universibo_45_{$common_logoType}.gif" width="516" height="54" alt="{$common_logo}" /></td></tr>
<tr><td bgcolor="#000099"><img src="tpl/black/rule_grande.gif" width="625" height="4" alt="" /></td></tr>

<tr><td bgcolor="#000050">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
 <tr>
 <td valign="middle" align="left" class="Navbar" width="400">&nbsp;&nbsp;<a href="{$common_homepageUri}">&nbsp;<img src="tpl/black/torri3.gif" width="27" height="20" border="0" alt="" />&nbsp;{$common_homepage}&nbsp;</a>&nbsp;&nbsp;&nbsp;<a href="{$common_forumUri}" target="_blank">&nbsp;<img src="tpl/black/forum_omini.gif" width="16" height="20" border="0" alt="" />&nbsp;{$common_forum}&nbsp;</a>&nbsp;&nbsp;&nbsp;{if $common_userLoggedIn == 'true'}<a href="{$common_settingsUri}">&nbsp;<img src="tpl/black/puzzle2.gif" width="20" height="20" border="0" alt="" />&nbsp;{$common_settings|ereg_replace:" ":"&nbsp;"}&nbsp;</a>{/if}&nbsp;&nbsp;&nbsp;&nbsp;</td>
 <td>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" summary="">
  <tr><td class="Titolo" align="right">{$common_longDate} - {$common_time}&nbsp;&nbsp;</td></tr>
  <tr><td bgcolor="#000099"><img src="tpl/black/rule_darkL.gif" width="100" height="2" alt="" /></td></tr>
  <tr><td class="PiccoloC" align="right">
  <a style="cursor:hand" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('{$common_rootUrl}');" ><img src="tpl/black/add_home.gif" width="13" height="12" alt="" border="0" />&nbsp;<font style='COLOR: #55A0D0; text-decoration:underline' >{$common_setHomepage|escape:"htmlall"|replace:" ":"&nbsp;"}</font></a>&nbsp;&nbsp;
  <a href="javascript:window.external.AddFavorite('{$common_hostName}','{$common_universibo}')"><img src="tpl/black/add_preferiti.gif" width="14" height="12" alt="" border="0" />&nbsp;<font style='COLOR: #55A0D0; text-decoration:underline' >{$common_addBookmarks|escape:"htmlall"|replace:" ":"&nbsp;"}</font></a>&nbsp;&nbsp;
  </td></tr>
  </table>
 </td></tr>
 </table>
</td></tr>

<tr><td bgcolor="#000099"><img src="tpl/black/rule_grande.gif" width="625" height="4" alt="" /></td></tr>
</table>
<!-- Fine Testa --> </td></tr>
<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td width="175" valign="top" class="Normal">&nbsp;<br />
<!-- Inizio MENU Left -->
{include file=box_facolta.tpl}

{include file=box_my_universibo.tpl}

<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr valign="bottom"> 
<td height="12" width="12"><img src="tpl/black/menuTL.gif" width="12" height="12" alt="" /></td>
<td height="12" align="left"><img src="tpl/black/menuT.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="12" alt="" /></td>
</tr>
<tr> 
<td width="12" valign="top"><img src="tpl/black/menuL.gif" width="12" height="67" alt="" /></td>
<td valign="top">
 
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr> 
<td colspan="2"><img src="tpl/black/servizi_18.gif" width="83" height="22" alt="{$common_services}" /></td>
</tr>

{foreach from=$common_servicesLinks item=temp_link}
<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$temp_link.uri|escape:"htmlall"}" >{$temp_link.label|escape:"htmlall"}</a></td>
</tr>
{/foreach}
</table>

</td>
<td width="12" valign="bottom"><img src="tpl/black/menuR.gif" width="12" height="67" alt="" /></td>
</tr>
<tr valign="top"> 
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
<td height="12" align="right"><img src="tpl/black/menuB.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/menuBR.gif" width="12" height="12" alt="" /></td>
</tr>
</table>
&nbsp;<br /><table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr valign="bottom"> 
<td height="12" width="12"><img src="tpl/black/menuTL.gif" width="12" height="12" alt="" /></td>
<td height="12" align="left"><img src="tpl/black/menuT.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="12" alt="" /></td>
</tr>
<tr> 
<td width="12" valign="top"><img src="tpl/black/menuL.gif" width="12" height="67" alt="" /></td>
<td valign="top">

<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr> 
<td colspan="2"><img src="tpl/black/informazioni_18.gif" width="136" height="22" alt="{$common_info}" /></td>
</tr>

{*<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_helpUri}" >{$common_help}</a></td>
</tr>
*}
<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_helpByTopicUri}" >{$common_help}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_rulesUri}" >{$common_rules}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_contactsUri}" >{$common_contacts}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_contributeUri}" >{$common_contribute}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_manifestoUri}" >{$common_manifesto}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/pallino1.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_creditsUri}" >{$common_credits}</a></td>
</tr>

<tr>
 <td valign="top" width="14"><img src="tpl/black/freccia_4.gif" width="12" height="11" alt="" /></td>
 <td class="Menu"><a href="{$common_docSfUri|escape:"htmlall"}" target="_blank"  title="Apre in un altra finestra" >{$common_docSf}</a></td>
</tr>

</table>

</td>
<td width="12" valign="bottom"><img src="tpl/black/menuR.gif" width="12" height="67" alt="" /></td>
</tr>
<tr valign="top"> 
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
<td height="12" align="right"><img src="tpl/black/menuB.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/menuBR.gif" width="12" height="12" alt="" /></td>
</tr>
</table>
&nbsp;<br /><table width="100%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr valign="bottom"> 
<td height="12" width="12"><img src="tpl/black/menuTL.gif" width="12" height="12" alt="" /></td>
<td height="12" align="left"><img src="tpl/black/menuT.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="12" alt="" /></td>
</tr>
<tr> 
<td width="12" valign="top"><img src="tpl/black/menuL.gif" width="12" height="67" alt="" /></td>
<td valign="top">
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" summary="">
<tr> 
<td><img src="tpl/black/links_18.gif" width="62" height="22" alt="Links" /></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.unibo.it" target="_blank" title="Apre in un altra finestra" >Universit&agrave; di BO</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.ing.unibo.it" target="_blank" title="Apre in un altra finestra" >Facolt&agrave; di Ingegneria</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="https://uniwex.unibo.it" target="_blank" title="Apre in un altra finestra" >Uniwex</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://guida.ing.unibo.it" target="_blank" title="Apre in un altra finestra" >Guida dello Studente</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.ing.unibo.it/Ingegneria/dipartimenti.htm" target="_blank" title="Apre in un altra finestra" >Elenco Dipartimenti</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www2.unibo.it/avl/org/constud/tutteass/tutteass.htm" target="_blank" title="Apre in un altra finestra" >Assoc. Studentesche</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.nettuno.it/bo/ordineingegneri/" target="_blank" title="Apre in un altra finestra" >Ordine Ingegneri</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.atc.bo.it/" target="_blank" title="Apre in un altra finestra" >ATC Bologna</a></td>
</tr>
<tr> 
<td class="Menu"><img src="tpl/black/freccia_4.gif" width="12" height="11" border="0" alt="" /><a href="http://www.trenitalia.com/" target="_blank" title="Apre in un altra finestra" >Trenitalia</a></td>
</tr>
</table></td>
<td width="12" valign="bottom"><img src="tpl/black/menuR.gif" width="12" height="67" alt="" /></td>
</tr>
<tr valign="top"> 
<td height="12" width="12"><img src="tpl/black/invisible.gif" width="1" height="1" alt="" /></td>
<td height="12" align="right"><img src="tpl/black/menuB.gif" width="67" height="12" alt="" /></td>
<td height="12" width="12"><img src="tpl/black/menuBR.gif" width="12" height="12" alt="" /></td>
</tr>
</table>
&nbsp;<br /></td>
<td valign="top" align="center" class="Normal">&nbsp;<br /> 
<table width="95%" border="0" cellspacing="0" cellpadding="0" summary="">
<tr><td class="Normal" align="left">
