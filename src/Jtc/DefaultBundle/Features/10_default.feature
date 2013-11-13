Feature: Default feature
@default

Scenario: 10.1 - Test page
	  Given I am on "/testme"
	  Then I should see "It works!"

Scenario: 10.2 - Test home page 200
	  Given I am on "/"
	  Then the response status code should be 200