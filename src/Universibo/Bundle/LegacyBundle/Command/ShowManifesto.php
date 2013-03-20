<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowManifesto is an extension of UniversiboCommand class.
 *
 * It shows manifesto page
 *
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowManifesto extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template->assign('manifesto_TitleAlt', 'Manifesto');
        $template
                ->assign('manifesto_langQuoteAlt',
                        'Galileo Galilei: Ma sopra tutte le invezioni stupende qual eminenza di mente fu quella di colui che s\'immaginò di trovar modo di comunicare i suoi più reconditi pensieri a qualsivoglia altra persona, benché distante per lunghissimo intervallo di luogo e di tempo?');
        $template
                ->assign('manifesto_langWhatIsIt',
                        'Forse l\'avrete banalmente già notato, ma lo scopo di questo sito è quello di aiutare una specie animale che da tempo immemorabile s\'inerpica tutte le mattine per una salitella ai piedi dei colli Bolognesi... si tratta dello studente d\'ingegneria.

Non si sa cosa li spinga tutti i giorni a compiere queste fatiche... fatto sta che mediamente dopo quasi una decina d\'anni abbandona questi luoghi... non si sa dove vadano e il più triste dei particolari è che nemmeno loro sanno cosa cercare una volta usciti! ...e finiscono solo col chiedersi perché vi siano entrati.

Un giorno ero seduto su una panchina a godermi l\'ultimo sole autunnale, mentre tentavo di dare un\'interpretazione alle nuvole nel cielo... una farfalla... girava per i giardini dell\'Eden proprio ai piedi della collina in cui si recavano gli ingegneri, mi si avvicinò, e mi raccontò una strana storia...

 Mi disse che un giorno lei, era bruco, era nata in un angolino, dentro una strana gabbia con dei misteriosi vetri trasparenti al posto delle sbarre.
Tutti i giorni questo posto veniva frequentato regolarmente da questi strani ingegneri, loro la chiamavano aula...
C\'era una persona in particolare più anziana delle altre, sembrava essere il loro capo e voleva che gli altri gli portassero rispetto, anche se non sempre ci riusciva. Alcuni pretendevano di essere autorevoli, ma tranne qualche eccezione, non accorgendosi della contraddizione finivano con essere solo autoritari.
Tutti loro si facevano chiamare professori, ma alcuni non erano capaci di insegnare le loro conoscenze ...mentre altri erano davvero dei maestri del sapere!!
Questa persona tracciava degli strani segni su una superficie nera, e parlava ...e tutti gli altri lo stavano ad ascoltare ricopiando quegli strani disegni, alcuni con aria assonnata, altri pieni di dubbi.

Per fortuna un giorno lei si addormentò e quando si svegliò gli spuntarono due stupende ali variopinte che le permisero di volare via dalla sua gabbia.
Era libera! Finalmente libera!
Ed era felice! Finalmente felice, di potersene stare in quel bel giardino all\'aria aperta.
Finalmente poteva girare in tutte le direzioni senza farsi influenzare da chi le diceva di scegliere la via di destra, sinistra o dritto al centro... se solo voleva poteva tornarsene indietro infischiandosene di tutto!!
Ma il fatto che tutti quegli ingegneri se ne stessero là dentro la turbava un po\'.
A quel punto mi regalò la sua idea: "Perché non ricopi anche tu quegli strani disegni? Fai in modo che tutti possano copiarseli con poca fatica, così quei poveri ingegneri se ne potranno andare prima dalla loro gabbia e potranno essere come me che volo felice per questo giardino".

Allora nel colpo di un clock misi in opera la sua idea, con l\'aiuto di alcuni amici molto bravi e generosi, ricopiai quegli strani disegni in un luogo straordinario, raggiungibile da tutto il mondo e dove ognuno può farsene una copia con un click!

Ebbi la possibilità di parlare con alcuni di questi ingegneri, anche loro erano tutti contenti e felici, finalmente avevano a disposizione un po\' di aiuto per sollevarli dal loro carico... non capii perché, ma qualcuno mi disse che ora poteva volare.

In una strana giornata, piovigginava leggermente con il sole che ancora faceva capolino tra le nubi, tornai nel giardino per ringraziare la mia amica farfalla... girai a destra e a manca, provai a chiamarla, ma mi accorsi che non sapevo nemmeno il suo nome.
...non riuscii a trovarla, e stanco mi rimisi a sedere sulla panchina.
Però dopo un po\' mi si presentò una simpatica rondinella, mi disse: "Erano giorni che avevo una fame terribile... sai, vengo da un lungo viaggio. Per fortuna che è passata da questa parti una farfalla che era tanto felice e faceva tanto chiasso che non ho potuto non notarla... davvero un buon pasto!".
');
        $template->assign('manifesto_Author', 'brain');

        return 'default';
    }
}
