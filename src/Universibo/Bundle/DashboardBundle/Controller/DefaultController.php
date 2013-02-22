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
        return array();
    }
}
