Feature: As a an anonymous I access the application and it displays the activity inventory

  Background:
    When I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated

  @inventory-empty
  Scenario:
    Given I access my activity inventory
    Then the response payload should contain a "todoTaskList" elements
    Then the response payload should contain a "calendarTaskList" elements
    Then the response payload should contain a "unplannedTaskList" elements
    And the "todoTaskList" node should have 0 elements
    And the "calendarTaskList" node should have 0 elements
    And the "unplannedTaskList" node should have 0 elements
