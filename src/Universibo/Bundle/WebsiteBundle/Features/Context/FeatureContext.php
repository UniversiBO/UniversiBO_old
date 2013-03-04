<?php

namespace Universibo\Bundle\WebsiteBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

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
     * @Given /^I\'m not logged in$/
     */
    public function iMNotLoggedIn()
    {
        $this
            ->getSession()
            ->visit('/logout')
        ;
    }

    /**
     * @When /^I click on "([^"]*)" link$/
     */
    public function iClickOnLink($link)
    {
        $this
            ->getSession()
            ->getPage()
            ->clickLink($link)
        ;
    }

    /**
     * @Given /^I type "([^"]*)" on "([^"]*)" field$/
     */
    public function iTypeOnField($value, $fieldName)
    {
        $this
            ->getSession()
            ->getPage()
            ->fillField($fieldName, $value)
        ;
    }

    /**
     * @Given /^I click on "([^"]*)" button$/
     */
    public function iClickOnButton($button)
    {
        $this
            ->getSession()
            ->getPage()
            ->pressButton($button)
        ;
    }

    /**
     * @Then /^Text "([^"]*)" should be present$/
     */
    public function textShouldBePresent($text)
    {
        $this->assertPageContainsText($text);
    }
}
