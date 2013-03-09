<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 */
class ForumController extends Controller
{
    public function boxAction(array $channel)
    {
        $channel = $this->get('universibo_legacy.repository.canale2')->find($channel['id_canale']);

        $postDao = $this->get('universibo_forum.dao.post');
        $forumRouter = $this->get('universibo_forum.router');

        $forumId = $channel->getForumForumId();
        $forumUri = $forumRouter->getForumUri($forumId);

        $posts = array();
        foreach ($postDao->getLatestPosts($forumId, 10) as $post) {
            $posts[] = array(
                'title' => $post['topic_title'],
                'uri'   => $forumRouter->getPostUri($post['min'])
            );
        }

        $response = $this->render('UniversiboWebsiteBundle:Forum:box.html.twig', array(
            'posts'     => $posts,
            'forumUri'  => $forumUri,
            'channelId' => $channel->getIdCanale()
        ));

        $response->setSharedMaxAge(30);
        $response->setPublic();

        return $response;
    }
}
