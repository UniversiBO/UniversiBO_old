<?php
/**
 * Symfony2 controller file
 *
 * @copyright (c) 2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for dashboard school page
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SchoolController extends Controller
{
    /**
     * Dashboard index action
     *
     * @Template
     */
    public function indexAction()
    {
        $schoolRepo = $this->get('universibo_didactics.repository.school');

        return array (
            'schools' => $schoolRepo->findAll()
        );
    }
}
