{include file="header_index.tpl"}
<div class="titoloPagina">
<h2>Modifica il commento al file</h2>
</div>
{include file="avviso_notice.tpl"}
{include file="Files/show_file_studenti_commento.tpl"}
<form method="post" enctype="multipart/form-data">
	<p><span><label for="f27_commento"><p>Il tuo commento/descrizione<br /> sul file:<br />(max 3000 caratteri)</p></label>
		<textarea cols="50" rows="10" id="f27_commento" name="f27_commento">{$f27_commento|escape:"htmlall"}</textarea></span></p>
	<p><span><label for="f27_voto">Il tuo voto (da 0 a 5):</label>&nbsp;
			<select id="f27_voto" name="f27_voto">
			<option value =""></option>
			<option value ="0">0</option>
  			<option value ="1">1</option>
  			<option value ="2">2</option>
  			<option value ="3">3</option>
  			<option value ="4">4</option>
  			<option value ="5">5</option>
			</select>
	</span></p>
	<p><input class="submit" type="submit" id="" name="f27_submit" size="20" value="Invia" /></p>
</form>
<p><a href="{$common_canaleURI|escape:"htmlall"}">Torna&nbsp;{$common_langCanaleNome}</a></p>

{include file="footer_index.tpl"}
