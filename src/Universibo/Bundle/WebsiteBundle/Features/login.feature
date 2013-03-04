Feature: Login
  In order to use my custom settings
  As registered user
  I need to login
  
  Scenario: Logging in as an Administrator
    Given I'm not logged in
    When I click on "Login" link
    And I type "admin" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto admin" should be present
    And Text "Admin" should be present

  Scenario: Logging in as a Moderator
    Given I'm not logged in
    When I click on "Login" link
    And I type "moderator" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto moderator" should be present
    And Text "Collaboratore" should be present

  Scenario: Logging in as a Student
    Given I'm not logged in
    When I click on "Login" link
    And I type "student" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto student" should be present
    And Text "Studente" should be present

  Scenario: Logging in as a Professor
    Given I'm not logged in
    When I click on "Login" link
    And I type "professor" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto professor" should be present
    And Text "Docente" should be present

  Scenario: Logging in as a Tutor
    Given I'm not logged in
    When I click on "Login" link
    And I type "tutor" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto tutor" should be present
    And Text "Tutor" should be present

  Scenario: Logging in as a non-teaching staff member
    Given I'm not logged in
    When I click on "Login" link
    And I type "staff" on "username" field
    And I type "padrino" on "password" field
    And I click on "Login" button
    Then Text "Benvenuto staff" should be present
    And Text "Personale non docente" should be present