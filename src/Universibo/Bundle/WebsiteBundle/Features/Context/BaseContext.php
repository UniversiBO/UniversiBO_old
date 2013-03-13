<?php

namespace Universibo\Bundle\WebsiteBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\KernelInterface;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * Feature context.
 */
class BaseContext extends MinkContext
                  implements KernelAwareInterface
{
    protected $kernel;
    private $parameters;
    private $channel;

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
        $this->userHasAcceptedPrivacyPolicy($username);

        $session = $this->getSession();

        $session->visit('/login');
        $page = $session->getPage();

        $page->fillField('username', $username);
        $page->fillField('password', 'padrino');
        $page->pressButton('Login');

        $session->visit('/my/universibo');
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
     * @Then /^text "([^"]*)" should be present$/
     */
    public function textShouldBePresent($text)
    {
        $this->assertPageContainsText($text);
    }

    /**
     * @Given /^there is a channel with file service$/
     */
    public function thereIsAChannelWithFileService()
    {
        $container = $this->kernel->getContainer();

        $db = $container->get('doctrine.dbal.default_connection');

        $sql = $db
            ->createQueryBuilder()
            ->select('c.id_canale')
            ->from('canale', 'c')
            ->where('c.files_attivo = ?')
            ->setMaxResults(1)
            ->getSql()
        ;

        $id = $db->fetchColumn($sql, array('S'));

        if (false !== $id) {
            $channelRepo = $container->get('universibo_legacy.repository.canale2');
            $this->channel = $channelRepo->find($id);
        } else {
            $this->channel = $channel = new Canale(0, LegacyRoles::ALL, 0, Canale::CDEFAULT, '', 'Test channel', 0, false, true, false, 0, 0, false, false);
            $channelRepo = $container->get('universibo_legacy.repository.canale');
            $channelRepo->insert($channel);
        }
    }

    /**
     * @When /^I visit that channel$/
     */
    public function iVisitThatChannel()
    {
        $container = $this->kernel->getContainer();

        $channelRouter = $container->get('universibo_legacy.routing.channel');

        $this
            ->getSession()
            ->visit($channelRouter->generate($this->channel))
        ;
    }

    /**
     * @Given /^I select a PHP file for upload$/
     */
    public function iSelectAPhpFileForUpload()
    {
        $this
            ->getSession()
            ->getPage()
            ->findById('f12_file')
            ->attachFile(__FILE__)
        ;
    }

    /**
     * @Given /^user "([^"]*)" has accepted privacy policy$/
     */
    public function userHasAcceptedPrivacyPolicy($username)
    {
        $userRepo = $this->get('universibo_core.repository.user');
        $user = $userRepo->findOneByUsername($username);

        if (null === $user) {
            throw new InvalidArgumentException('User not found');
        }

        $privacyService = $this->get('universibo_legacy.service.privacy');

        if (!$privacyService->hasAcceptedPrivacy($user)) {
            $privacyService->markAccepted($user);
        }
    }

    private function get($id)
    {
        return $this->kernel->getContainer()->get($id);
    }
}
