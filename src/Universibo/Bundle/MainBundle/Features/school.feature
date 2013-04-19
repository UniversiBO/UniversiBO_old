Feature: Show School
  In order to find my Degree Course
  As any kind of user
  I need to list school's degree courses

  Scenario: List Ingegneria e rchitettura
  Given I'm logged in as "student"
  When I visit "/scuole/ingegneria-e-architettura/"
  Then text "Scuola di Ingegneria e Architettura" should be present
  And text "Corsi di laurea" should be present
  And text "CORSI DI LAUREA MAGISTRALE" should be present
  And text "INGEGNERIA INFORMATICA" should be present