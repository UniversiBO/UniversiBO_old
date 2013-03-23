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
use Universibo\Bundle\MainBundle\Entity\SchoolChannel;

/**
 * Controller for channel
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ChannelController extends Controller
{
    /**
     * Dashboard index action
     *
     * @return Response
     */
    public function indexAction()
    {
        $schoolRepo  = $this->get('universibo_didactics.repository.school');
        $channelRepo = $this->get('universibo_main.repository.channel.school');

        $schools = ['channel'=> [], 'noChannel' => []];

        foreach ($schoolRepo->findAll() as $school) {
            if ($channelRepo->findOneBySchool($school)) {
                $schools['channel'][] = $school;
            } else {
                $schools['noChannel'][] = $school;
            }
        }

        $response = $this->render('UniversiboDashboardBundle:Channel:index.html.twig', array (
            'schools' => $schools
        ));

        return $response;
    }

    public function schoolCreateAction($schoolId)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $schoolRepo = $this->get('universibo_didactics.repository.school');
        $school     = $schoolRepo->find($schoolId);

        $em->beginTransaction();

        if (null === $school) {
            $em->rollBack();
            throw $this->createNotFoundException('School not found');
        }

        $channelRepo = $this->get('universibo_main.repository.channel.school');
        if ($channelRepo->findOneBySchool($school)) {
            $em->rollBack();
        } else {
            $channel = new SchoolChannel();
            $channel->setSchool($school);
            $channel->setType('school');
            $channel->setName($school->getName());
            $channel->setSlug('');
            $channel->setHits(0);
            $channel->setLegacyGroups(127);

            $em->persist($channel);
            $em->flush();
            $em->commit();
        }

        return $this->redirect($this->generateUrl('universibo_dashboard_admin_channels'));
    }
}
