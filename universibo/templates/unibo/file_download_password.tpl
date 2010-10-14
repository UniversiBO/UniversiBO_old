{include file=header_index.tpl}
<div class="titoloPagina">
<h2>Password file</h2>
</div>
<p class="Normal">Il file richiesto è stato protetto dall'autore con una password,<br />
Per per proseguire con il download è necessario inserirla nel seguente form.</p>
{include file=avviso_notice.tpl}

<form method="post">
	<p><label for="f11_file_password">Password:</label>
		<input type="password" id="f11_file_password" name="f11_file_password" size="20" maxlength="130" value="" /></p>
	<p><input class="submit" type="submit" id="" name="f11_submit" size="20" value="Invia" /></p>
</form>

<p><a href="{$fileDownload_InfoURI|escape:"htmlall"}">Torna&nbsp;indietro</a></p>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a><p>

<hr />
{include file=Help/topic.tpl showTopic_topic=$showTopic_topic idsu=$showTopic_topic.reference}

{include file=footer_index.tpl}