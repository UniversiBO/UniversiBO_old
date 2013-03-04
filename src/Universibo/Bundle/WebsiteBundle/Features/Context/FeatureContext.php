<?php

namespace Universibo\Bundle\WebsiteBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

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
        $this->iMNotLoggedIn();

        $session = $this->getSession();

        $session->visit('/login');
        $page = $session->getPage();

        $page->fillField('username', $username);
        $page->fillField('password', 'padrino');
        $page->pressButton('Login');

        if (preg_match('/regolamento/', $session->getCurrentUrl())) {
            $page->checkField('accept_check');
            $page->pressButton('Accetto');

            $this->assertPageNotMatchesText('/Non hai accettato il regolamento/');
        }

        $this->assertPageContainsText('Benvenuto '.$username);
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
