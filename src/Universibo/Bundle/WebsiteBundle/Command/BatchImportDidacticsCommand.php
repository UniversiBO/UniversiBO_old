<?php
namespace Universibo\Bundle\WebsiteBundle\Command;

use PDO;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class BatchImportDidacticsCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('universibo:didactics:import')
            ->setDescription('Batch import didactics')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input,
            OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');

        $db->beginTransaction();

        $query = 'SELECT anno_accademico, cod_corso, cod_ind, cod_ori, cod_materia,
                anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_modulo,
                cod_doc, flag_titolare_modulo, tipo_ciclo, cod_ate, anno_corso_universibo
                FROM
                input_esami_attivi
                WHERE 1=1';

        $res = $db->executeQuery($query);

        $num_rows = $res->rowCount();
        $output->writeln('Input rowCount: '.$num_rows);
        $output->writeln('');

        while ( false !== ($row = $res->fetch(PDO::FETCH_NUM)) ) {
            $output->writeln('---------------');

            $query3 = 'SELECT * FROM prg_insegnamento  WHERE
            anno_accademico = '.$db->quote($row[0]).' AND anno_corso = '.$db->quote($row[5]).'
            AND anno_corso_ins='.$db->quote($row[7]).' AND cod_corso='.$db->quote($row[1]).' AND cod_doc='.$db->quote($row[10]).'
            AND cod_ind='.$db->quote($row[2]).' AND cod_materia='.$db->quote($row[4]).'
            AND cod_materia_ins='.$db->quote($row[6]).' AND cod_modulo='.$db->quote($row[9]).'
            AND cod_ori='.$db->quote($row[3]).' AND cod_ril='.$db->quote($row[8]).';';

            $res3= $db->executeQuery($query3);

            $num_rows3 = $res3->rowCount();
            $output->writeln('prg_insegnamento rowCount: '.$num_rows3);

            if ($num_rows3 == 0) {
                //$id_canale = $db->nextId('canale_id_canale');

                $query4 = 'INSERT INTO canale(tipo_canale,nome_canale,immagine,visite,ultima_modifica,permessi_groups,files_attivo,news_attivo
                ,forum_attivo,id_forum,group_id,links_attivo,files_studenti_attivo) VALUES (5,\'\',\'\',0,'.time().',127,\'S\',\'S\',\'N\',0,0,\'S\',\'S\');';
                $db->executeQuery($query4);

                $id_canale = $db->lastInsertId('canale_id_canale_seq');
                $output->writeln('New channel with id: '.$id_canale);

                $query5 = 'INSERT INTO prg_insegnamento (anno_accademico,cod_corso,cod_ind,cod_ori,cod_materia,anno_corso,cod_materia_ins,
                anno_corso_ins,cod_ril,cod_modulo,cod_doc,flag_titolare_modulo,id_canale,cod_orario,tipo_ciclo,cod_ate,anno_corso_universibo)
                VALUES('.$db->quote($row[0]).', '.$db->quote($row[1]).', '.$db->quote($row[2]).', '.$db->quote($row[3]).', '.$db->quote($row[4]).',
                '.$db->quote($row[5]).', '.$db->quote($row[6]).', '.$db->quote($row[7]).', '.$db->quote($row[8]).', '.$db->quote($row[9]).',
                '.$db->quote($row[10]).', '.$db->quote($row[11]).', '.$db->quote($id_canale).', NULL , '.$db->quote($row[12]).',
                '.$db->quote($row[13]).', '.$db->quote($row[14]).');';

                $db->executeQuery($query5);

                $query7 = 'INSERT INTO info_didattica (id_canale) VALUES ( '.$id_canale.' );';
                $db->executeQuery($query7);
            }
        }

        $db->commit();
    }
}
