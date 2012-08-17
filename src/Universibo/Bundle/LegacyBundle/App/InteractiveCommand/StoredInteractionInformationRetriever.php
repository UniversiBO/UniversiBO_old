<?php
namespace Universibo\Bundle\LegacyBundle\App\InteractiveCommand;

/**
 * Classe per recuperare le informazioni memorizzate nelle interazioni con gli utenti
 */
class StoredInteractionInformationRetriever
{
    /**
     * @author Pinto
     * @static
     * @param  boolean $groupedByCallbackName se true, ritorna un array a due livelli array (callbackName => array(param_name => param_value)); se false array(param_name => param_value)
     * @return array   contiene i valori memorizzati
     */
    public function getInfoFromIdUtente ($idUtente, $nomeInteractiveCommand, $groupedByCallbackName = false)
    {
        $logRepo = $this->getContainer()->get('universibo_legacy.repository.interactivecommand.step_log');
        $latest = $logRepo->findLatestPositive($idUtente, $nomeInteractiveCommand);

        if ($latest === null) {
            return array();
        }

        $listRepo = $this->getContainer()->get('universibo_legacy.repository.interactivecommand.step_list');
        $data = $listRepo->findByIdStep($latest->getId());

        $list = array();

        if ($groupedByCallbackName) {
            foreach ($data as $item) {
                $list[$item->getCallbackName()][$item->getParamName()] = $item->getParamValue();
            }
        } else {
            foreach ($data as $item) {
                $list[$item->getParamName()] = $item->getParamValue();
            }
        }

        return $list;
    }

    /**
     * @author Pinto
     * @static
     * @param  boolean $groupedByCallbackName se true, ritorna un array a due livelli array (callbackName => array(param_name => param_value)); se false array(param_name => param_value)
     * @return array   contiene i valori memorizzati
     */
    public function getInfoFromUsernameUtente ($username, $nomeInteractiveCommand, $groupedByCallbackName = false)
    {
        $user = User::selectUserUsername($username);

        return StoredInteractionInformationRetriever::getInfoFromIdUtente($user->getIdUser(), $nomeInteractiveCommand, $groupedByCallbackName);
    }

}
