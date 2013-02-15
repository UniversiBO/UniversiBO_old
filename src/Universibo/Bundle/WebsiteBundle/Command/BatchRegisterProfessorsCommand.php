<?php
/**
 * @copyright (c) 2002-2013, Associazione UniversiBO
 * @license GPLv2, {@link http://opensource.org/licenses/gpl-2.0.php}
 */
namespace Universibo\Bundle\WebsiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\LegacyBundle\App\Constants;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Docente;

/**
 * New professors registration
 *
 * @version 2.6.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class BatchRegisterProfessorsCommand extends ContainerAwareCommand
{
    private $verbose;

    /**
     * Configures command's name and options
     */
    protected function configure()
    {
        $this
            ->setName('universibo:professors:register')
            ->setDescription('Batch register professors')
        ;
    }

    /**
     * Initalize method
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input,
            OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    /**
     * Command body
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $db = $container->get('doctrine.dbal.default_connection');
        $forumDb = $container->get('doctrine.dbal.default_connection');
        $userManager = $container->get('fos_user.user_manager');
        $forumUserDAO = $container->get('universibo_forum.dao.user');
        $docenteRepo = $container->get('universibo_legacy.repository.docente');

        $db->beginTransaction();
        $forumDb->beginTransaction();

        $res = $db->executeQuery('SELECT cod_doc, nome_doc, email FROM docente2 WHERE cod_doc NOT IN (SELECT cod_doc FROM docente WHERE 1=1)');

        while (false !== ($row = $res->fetch())) {
            $email = $row['email'];
            $exploded = explode('@', $email);
            $username = $exploded[0];

            if ($userManager->findUserByUsername($username) === null) {
                $user = $userManager->createUser();
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setNotifications(Constants::NOTIFICA_NONE);
                $user->setLegacyGroups(LegacyRoles::DOCENTE);
                $user->setPassword('x');
                $user->setEnabled(true);

                $userManager->updateUser($user);
                $forumUserDAO->findOrCreate($user);

                $docente = new Docente();
                $docente->setIdUtente($user->getId());
                $docente->setCodDoc($row['cod_doc']);
                $docente->setNomeDoc($row['nome_doc']);

                $docenteRepo->insert($docente);

                $output->writeln('User: '.$username.' registered.');
            } else {
                $output->writeln('User: '.$username.' exists, won\'t register it.');
            }
        }

        $db->commit();
        $forumDb->commit();
    }
}
