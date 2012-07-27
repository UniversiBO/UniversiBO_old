<?php
namespace Universibo\Bundle\CoreBundle\Twig;

use Universibo\Bundle\CoreBundle\Security\UniversiboContext;

use Universibo\Bundle\CoreBundle\Routing\ChannelRouter;

class SecurityExtension extends \Twig_Extension
{
    /**
     * @var UniversiboContext
     */
    private $context;

    /**
     * Class constructor
     *
     * @param ChannelRouter $router
     */
    public function __construct(UniversiboContext $context)
    {
        $this->context = $context;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'universibo_core.security';
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array (
                'ub_is_granted' =>  new \Twig_Function_Method($this, 'isGranted'),
        );
    }

    public function isGranted($attributes, $object = null)
    {
        return $this->context->isGranted($attributes, $object);
    }
}
