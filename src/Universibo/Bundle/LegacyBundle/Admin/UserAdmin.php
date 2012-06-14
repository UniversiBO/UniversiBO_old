<?php
namespace Universibo\Bundle\LegacyBundle\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class UserAdmin extends Admin
{

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('idUser', 'int', array('route' => array('name' => 'show')))
            ->addIdentifier('username', 'string', array('route' => array('name' => 'show')))
            ->add('ultimoLogin', 'datetime')
            ->add('email', 'string', array('label' => 'e-mail'))
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
            ->add('idUser')
            ->add('username')
            ->add('ADUsername', 'string', array('label' => 'UPN'))
            ->add('ultimoLogin', 'datetime')
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
