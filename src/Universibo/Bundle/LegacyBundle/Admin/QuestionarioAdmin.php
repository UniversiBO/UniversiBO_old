<?php
namespace Universibo\Bundle\LegacyBundle\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class QuestionarioAdmin extends Admin
{

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id', 'int', array('route' => array('name' => 'show')))
            ->add('data', 'datetime')
            ->add('nome')
            ->add('cognome')
            ->add('mail', 'string', array('label' => 'e-mail'))
            ->add('telefono')
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
    }

     /**
      * @param ShowMapper $filter
      */
    protected function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('id')
            ->add('data', 'datetime')
            ->add('nome')
            ->add('cognome')
            ->add('mail', 'string', array('label' => 'e-mail'))
            ->add('telefono')
            ->add('attivitaOffline', 'string', array('label' => 'Offline'))
            ->add('attivitaModeratore', 'string', array('label' => 'Moderatore'))
            ->add('attivitaContenuti', 'string', array('label' => 'Contenuti'))
            ->add('attivitaTest', 'string', array('label' => 'Test'))
            ->add('attivitaGrafica', 'string', array('label' => 'Grafica'))
            ->add('attivitaProgettazione', 'string', array('label' => 'Progettazione'))
            ->add('tempoDisponibile')
            ->add('tempoInternet')
            ->add('altro')
            ->add('idUtente')
            ->add('cdl', 'string', array('label' => 'Corso di laurea'))
        ;

    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('create');
        $collection->remove('delete');
    }
}
