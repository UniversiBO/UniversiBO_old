<?php
/**
 * Symfony2 controller file
 *
 * @copyright (c) 2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function indexAction()
    {
        $schoolRepo = $this->get('universibo_didactics.repository.school');

        $response = $this->render('UniversiboDashboardBundle:School:index.html.twig', array (
            'schools' => $schoolRepo->findAll()
        ));

        $response->setPublic();
        $response->setSharedMaxAge(30);

        return $response;
    }
}
