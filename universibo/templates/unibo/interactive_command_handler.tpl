{include file=header_index.tpl}

<div class="titoloPagina">
<h2>{$InteractiveCommandHandler_title_lang|escape:"htmlall"}</h2>
</div>

{include file=avviso_notice.tpl}

<form method="post">
{include file=$InteractiveCommandHandler_stepPath}
	<div class="navbar"><input class="post_link" name="action" type="submit" value="{$InteractiveCommandHandler_next_lang|escape:"htmlall"}" /><a href="{$InteractiveCommandHandler_canc_uri|escape:"htmlall"}">{$InteractiveCommandHandler_canc_lang|escape:"htmlall"}</a>{if $InteractiveCommandHandler_back_lang|default:"" != ""}<a href="{$InteractiveCommandHandler_back_uri|escape:"htmlall"}">{$InteractiveCommandHandler_back_lang|escape:"htmlall"}</a>{/if}</div>
</form>

{include file=footer_index.tpl}