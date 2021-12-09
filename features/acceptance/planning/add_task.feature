Feature:
# input of a task
  Background:
    Given I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated

  @add-task
  Scenario:
    Given I add a "foo" task in my "todoTaskList"
    Then the TasksContext response payload should contain a "id" elements
    Given I access my activity inventory
    Then the response payload should contain a "todoTaskList" elements
    And the "todoTaskList" node should have 1 elements
