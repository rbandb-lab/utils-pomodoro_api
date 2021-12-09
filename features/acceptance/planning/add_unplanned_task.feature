Feature:
# input of a task
  Background:
    Given I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated

  @add-unplanned
  Scenario:
    Given I add a "foo" task in my "unplannedTaskList" with no deadline
    Then the TasksContext response payload should contain a "id" elements
    Given I access my activity inventory
    Then the response payload should contain a "unplannedTaskList" elements
    And the "unplannedTaskList" node should have 1 elements

  @add-unplanned-deadline
  Scenario:
    Given I add a "bar" task in my unplannedTaskList with a deadline to "2021-12-31 23:59:00"
    Then the TasksContext response payload should contain a "id" elements
    Given I access my activity inventory
    Then the response payload should contain a "unplannedTaskList" elements
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have a deadline element with value "1640995140"
