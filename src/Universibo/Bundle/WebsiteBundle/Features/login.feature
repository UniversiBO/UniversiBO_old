Feature: Login
  In order to use my custom settings
  As user
  I need to login
  
  Scenario: Login as administrator
    Given I'm not logged in
    When I click on "Login" link
    And I type "admin" on "username" field
    And I type "padrino" on "passsword" field
    And I click on "Login" button
    Then Text "Benvenuto admin" should be present
    And Text "Admin" should be present
