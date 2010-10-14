{if $common_pageType == "index"}
{include file=header_index.tpl}
{elseif $common_pageType == "popup"}
{include file=header_popup.tpl}
{/if}

<table width="90%" border="0" cellspacing="0" cellpadding="0" summary="" align="center">
<tr><td class="Normal" align="center">
	&nbsp;<br />
	<img src="tpl/black/collabora_30.gif" width="167" height="39" alt="{$contribute_langTitleAlt|escape:"htmlall"}" /><br />&nbsp;
</td></tr>
<tr>
    <td class="Normal" align="left">
    {foreach from=$contribute_langIntro item=temp_intro}
    {$temp_intro|escape:"htmlall"|bbcode2html}
    <br /><br />
    {/foreach}
    
    <p align="center" class="Titolo">
    {$contribute_langTitle|escape:"htmlall"|bbcode2html}
    <br /><br />
    </p>
    
    {foreach from=$contribute_langHowToContribute item=temp_HowToContribute}
    {$temp_HowToContribute|escape:"htmlall"|bbcode2html}
    <br />
    {/foreach}
    
    </td>
</tr>
</table>

<br />
{include file=questionario.tpl}


{if $common_pageType == "index"}
{include file=footer_index.tpl}
{elseif $common_pageType == "popup"}
{include file=footer_popup.tpl}
{/if}