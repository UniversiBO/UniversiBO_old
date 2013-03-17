Feature: Login
  In order to use my custom settings
  As registered user
  I need to login
  
  Scenario: Logging in as an Administrator
    Given I'm not logged in
    And user "admin" has accepted privacy policy
    When I click on "Entra" link
    And I type "admin" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ admin ]" should be present

  Scenario: Logging in as a Moderator
    Given I'm not logged in
    And user "moderator" has accepted privacy policy
    When I click on "Entra" link
    And I type "moderator" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ moderator ]" should be present

  Scenario: Logging in as a Student
    Given I'm not logged in
    And user "student" has accepted privacy policy
    When I click on "Entra" link
    And I type "student" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ student ]" should be present

  Scenario: Logging in as a Professor
    Given I'm not logged in
    And user "professor" has accepted privacy policy
    When I click on "Entra" link
    And I type "professor" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ professor ]" should be present

  Scenario: Logging in as a Tutor
    Given I'm not logged in
    And user "tutor" has accepted privacy policy
    When I click on "Entra" link
    And I type "tutor" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ tutor ]" should be present

  Scenario: Logging in as a non-teaching staff member
    Given I'm not logged in
    And user "staff" has accepted privacy policy
    When I click on "Entra" link
    And I type "staff" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then text "[ staff ]" should be present