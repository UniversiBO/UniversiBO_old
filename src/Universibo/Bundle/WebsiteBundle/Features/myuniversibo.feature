Feature: ShowMyUniversiBO
  In order to use my custom settings
  As registered user
  I need to see my universibo page

  Scenario: Plain test
  Given I'm logged in as "student"
  When I visit "/my/universibo"
  Then Text "My News" should be present
  And Text "My Files" should be present