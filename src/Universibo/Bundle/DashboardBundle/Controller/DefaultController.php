<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for dashboard home page
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DefaultController extends Controller
{
    /**
     * @Template
     */
    public function indexAction()
    {
        $statService = $this->get('universibo_dashboard.statistics');
        $userRepo = $this->get('universibo_core.repository.user');
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');

        return array(
            'activeUsers'    => $userRepo->countActive(),
            'logged24h'      => $statService->getLoggedUsers24h(),
            'loggedWeek'     => $statService->getLoggedUsersWeek(),
            'loggedAcademic' => $statService->getLoggedAcademic(),
            'filesCount'     => $fileRepo->count()
        );
    }
}
