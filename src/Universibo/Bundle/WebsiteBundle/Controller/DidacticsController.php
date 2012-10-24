<?php
namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DidacticsController extends Controller
{
    /**
     * @Template()
     * @return array
     */
    public function academicalYearAction($min, $max, $current, $route, array $params)
    {

        $years = array();
        if ($current > $min) {
            $years['prev'] = $this->yearToArray($current-1, $route, $params);
        } else {
            $years['prev'] = null;
        }

        $years['current'] = $this->yearToArray($current, $route, $params);

        if ($current < $max) {
            $years['next'] = $this->yearToArray($current+1, $route, $params);
        } else {
            $years['next'] = null;
        }

        return array ('years' => $years, 'route' => $route);
    }

    private function yearToArray($year, $route, array $params)
    {
        $router = $this->get('router');

        return array (
                'label' => $year . '/' . ($year+1),
                'value' => $year,
                'uri' => $router->generate($route, array_merge($params, array(
                        'anno_accademico' => $year)
                ))
        );
    }
}
