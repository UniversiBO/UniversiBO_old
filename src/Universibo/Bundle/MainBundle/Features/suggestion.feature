Feature: Suggestion
  In order to provide my feedback to UniversiBO's team
  As registered user
  I need to submit a suggestion form

  Scenario: Form submission
    Given user "student" has role "ROLE_BETA"
    And I'm logged in as "student"
    When I click on "Invia suggerimento" link
    And I type "Something" on "Titolo" field
    And I type "Something else" on "Descrizione" field
    And I click on "Invia" button
    Then text "Grazie!" should be present
    And  text "Il tuo suggerimento è stato ricevuto. Ti risponderemo sulla tua mail appena possibile." should be present
