<span id="gotof3"></span>
<h3>Questionario</h3>
{include file="avviso_notice.tpl"}
<form action="#gotof3" id="f3" method="post">
	<fieldset>
	<legend>{$question_PersonalInfo|escape:"htmlall"}</legend>
	<p><label for="f3_nome">{$question_PersonalInfoData[0]|escape:"htmlall"}</label>
		<input type="text" id="f3_nome" maxlength="50" size="40" name="f3_nome" value="{$f3_nome|escape:"htmlall"}" /></p>
	<p><label for="f3_cognome">{$question_PersonalInfoData[1]|escape:"htmlall"}</label>
		<input type="text" id="f3_cognome" maxlength="50" size="40" name="f3_cognome" value="{$f3_cognome|escape:"htmlall"}" /></p>
	<p><label for="f3_mail">{$question_PersonalInfoData[2]|escape:"htmlall"}</label>
		<input type="text" id="f3_mail" maxlength="50" size="40" name="f3_mail" value="{$f3_mail|escape:"htmlall"}" /></p>
	<p><label for="f3_tel">{$question_PersonalInfoData[3]|escape:"htmlall"}</label>
		<input type="text" id="f3_tel" maxlength="50" size="40" name="f3_tel" value="{$f3_tel|escape:"htmlall"}" /></p>
	<p><label for="f3_cdl">{$question_PersonalInfoData[4]|escape:"htmlall"}</label>
		<input type="text" id="f3_cdl" maxlength="50" size="40" name="f3_cdl" value="{$f3_cdl|escape:"htmlall"}" /></p> 
	</fieldset>
	<fieldset>
	<legend>{$question_q1|escape:"htmlall"}</legend>
		<p><input id="f3_tempo_0" type="radio" value="120" name="f3_tempo" {if $f3_tempo==120}checked="checked"{/if} /> <label for="f3_tempo_0">{$question_q1Answers[0]|escape:"htmlall"}</label></p>
	 	<p><input id="f3_tempo_1" type="radio" value="30" name="f3_tempo" {if $f3_tempo==30}checked="checked"{/if} /> <label for="f3_tempo_1">{$question_q1Answers[1]|escape:"htmlall"}</label></p>
	 	<p><input id="f3_tempo_2" type="radio" value="1" name="f3_tempo" {if $f3_tempo==1}checked="checked"{/if} /> <label for="f3_tempo_2">{$question_q1Answers[2]|escape:"htmlall"}</label></p>
	</fieldset>
	<fieldset>
	<legend>{$question_q2|escape:"htmlall"}</legend>
		<p><input type="radio" id="f3_internet_0" name="f3_internet" value="1" {if $f3_internet==1}checked="checked"{/if} /> <label for="f3_internet_0">{$question_q2Answers[0]|escape:"htmlall"}</label></p>
		<p><input type="radio" id="f3_internet_1" name="f3_internet" value="60" {if $f3_internet==60}checked="checked"{/if} /> <label for="f3_internet_1">{$question_q2Answers[1]|escape:"htmlall"}</label></p>
		<p><input type="radio" id="f3_internet_2" name="f3_internet" value="200" {if $f3_internet==200}checked="checked"{/if} /> <label for="f3_internet_2">{$question_q2Answers[2]|escape:"htmlall"}</label></p>
		<p><input type="radio" id="f3_internet_3" name="f3_internet" value="1000" {if $f3_internet==1000}checked="checked"{/if} /> <label for="f3_internet_3">{$question_q2Answers[3]|escape:"htmlall"}</label></p>
	</fieldset>
	<fieldset>
		<legend>{$question_q3|escape:"htmlall"}</legend>
			<p><input id="f3_offline" type="checkbox" name="f3_offline" {if $f3_offline==true}checked="checked"{/if} /> <label for="f3_offline">{$question_q3AnswersMulti[0]|escape:"htmlall"}</label></p>
	 		<p><input id="f3_moderatore" type="checkbox" name="f3_moderatore" {if $f3_moderatore==true}checked="checked"{/if} /> <label for="f3_moderatore">{$question_q3AnswersMulti[1]|escape:"htmlall"}</label></p>
	 		<p><input id="f3_contenuti" type="checkbox" name="f3_contenuti" {if $f3_contenuti==true}checked="checked"{/if} /> <label for="f3_contenuti">{$question_q3AnswersMulti[2]|escape:"htmlall"}</label></p>
	 		<p><input id="f3_test" type="checkbox" name="f3_test" {if $f3_test==true}checked="checked"{/if} /> <label for="f3_test">{$question_q3AnswersMulti[3]|escape:"htmlall"}</label></p>
	 		<p><input id="f3_grafica" type="checkbox" name="f3_grafica" {if $f3_grafica==true}checked="checked"{/if} /> <label for="f3_grafica">{$question_q3AnswersMulti[4]|escape:"htmlall"}</label></p>
	 		<p><input id="f3_prog" type="checkbox" name="f3_prog" {if $f3_prog==true}checked="checked"{/if} /> <label for="f3_prog">{$question_q3AnswersMulti[5]|escape:"htmlall"}</label></p>
	</fieldset>
	 <label for="f3_altro">{$question_PersonalNotes|escape:"htmlall"}</label>
 		<p><textarea id="f3_altro" cols="50" rows="5" name="f3_altro">{$f3_altro|escape:"htmlall"}</textarea></p>
		<p><input id="f3_privacy" type="checkbox" name="f3_privacy" /> <label for="f3_privacy">{$question_Privacy|escape:"htmlall"}</label></p>
 		<p>&nbsp;<input class="submit" type="submit" value="{$question_Send|escape:"htmlall"}" name="f3_submit" /></p>
</form>