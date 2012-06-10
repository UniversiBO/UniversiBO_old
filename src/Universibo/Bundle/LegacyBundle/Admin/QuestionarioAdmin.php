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
            ->add('attivitaOffline')
            ->add('attivitaProgettazione');
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
        $filter->add('id');
        $filter->add('nome');
        $filter->add('cognome');

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
