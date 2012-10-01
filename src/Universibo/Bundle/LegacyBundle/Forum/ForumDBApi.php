<?php
namespace Universibo\Bundle\LegacyBundle\Forum;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini
 */
interface ForumDBApi
{
    public function insertUser(User $user, $password);
    public function updateUserStyle(User $user);
    public function updatePassword(User $user, $password);
    public function updateUserEmail(User $user);
    public function addUserGroup($userId, $group);
    public function removeUserGroup($userId, $group);
    public function addGroup($title, $desc, $id_owner);
    public function addGroupForumPrivilegies($forum_id, $group_id);
    public function getMaxForumId();
    public function addForumInsegnamentoNewYear($forum_id, $anno_accademico);
    public function getPostUri($id_post);
    public function getLastPostsForum(User $user, $id_forum, $num = 10);
}
