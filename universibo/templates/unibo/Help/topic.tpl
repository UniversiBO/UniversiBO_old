{* showTopic_topic array di un 'reference', un 'titolo' del topic seguito da array di argomenti (id, titolo, contenuto) passato da ShowHelpId, *}
{* idsu			stringa contenente l'id dell'ancora a inizio pagina [campo obbligatorio] *}
    <h3 id="{$showTopic_topic.reference|escape:"htmlall"}" class="Titolo">&nbsp;<br />{$showTopic_topic.titolo|escape:"htmlall"}</h3>		
	<p>{include file=Help/help_id.tpl showHelpId_langArgomento=$showTopic_topic.argomenti indice=false idsu=$idsu}</p>
