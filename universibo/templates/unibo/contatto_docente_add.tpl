{include file=header_index.tpl}

<h2>{$ContattoDocenteAdd_titolo}</h2>

{include file=avviso_notice.tpl}
<p>{$ContattoDocenteAdd_esito|escape:"htmlall"}</p>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>
{include file=footer_index.tpl}