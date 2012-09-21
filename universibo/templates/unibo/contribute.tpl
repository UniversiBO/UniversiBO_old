{include file="header_index.tpl"}

<h2>Collabora</h2>
{foreach from=$contribute_langIntro item=temp_intro}
   <p>{$temp_intro|escape:"htmlall"|bbcode2html}
    <br /><br /></p>
{/foreach}
<h4>{$contribute_langTitle|escape:"htmlall"|bbcode2html}</h4>
{foreach from=$contribute_langHowToContribute item=temp_HowToContribute}
    <p>{$temp_HowToContribute|escape:"htmlall"|bbcode2html}</p>
{/foreach}
<hr />

{include file="questionario.tpl"}
{include file="footer_index.tpl"}