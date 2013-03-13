Feature: List schools
  In order to know which schools are available
  As moderator
  I need to list schools

  Scenario: Standard list
    Given I'm logged in as "moderator"
    When I visit "/dashboard/didactics/schools"
    Then text "Agraria e Medicina veterinaria" should be present
    And text "Economia, Management e Statistica" should be present
    And text "Farmacia, Biotecnologie e Scienze motorie" should be present
    And text "Giurisprudenza" should be present
    And text "Ingegneria e Architettura" should be present
    And text "Lettere e Beni culturali" should be present
    And text "Lingue e Letterature, Traduzione e Interpretazione" should be present
    And text "Medicina e Chirurgia" should be present
    And text "Psicologia e Scienze della Formazione" should be present
    And text "Scienze" should be present
    And text "Scienze politiche" should be present
