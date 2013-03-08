<?php

namespace Universibo\Bundle\DashboardBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use PHPUnit_Framework_Assert;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext
                  implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

        /**
     * @Given /^I\'m logged in as "([^"]*)"$/
     */
    public function iMLoggedInAs($username)
    {
        $container = $this->kernel->getContainer();

        $user = $container
            ->get('fos_user.user_manager')
            ->findUserByUsername($username)
        ;

        PHPUnit_Framework_Assert::assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $container->get('security.context')->setToken($token);
    }

    /**
     * @When /^I visit "([^"]*)"$/
     */
    public function iVisit($url)
    {
        $this
            ->getSession()
            ->visit($url)
        ;
    }

    /**
     * @Then /^text "([^"]*)" should be present$/
     */
    public function textShouldBePresent($text)
    {
        $this
            ->assertPageContainsText($text)
        ;
    }
}
