Feature: Publish a file
  In order to make a file available to users
  As user with rights on a channel
  I want to add a file to it

  Scenario: PHP file upload (forbidden action)
    Given I'm logged in as "admin"
    And there is a channel with file service
    When I visit that channel
    And I click on "Invia un nuovo file" link
    And I select a PHP file for upload
    And I type "Title" on "f12_titolo" field
    And I click on "f12_submit" button
    Then text "severamente vietato inserire file con estensione" should be present