Feature:
# The first start set the task to "running"

  Background:
    Given I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated

  @start-timer
  Scenario:
    Given I add a "foo" task with id "123task" in my "todoTaskList"
    Given I start the timer for the "123task" task
    And the response payload should contain a "startedAt" elements I should remember as "startedTs"
    And I access my activity inventory
    Then the response payload should contain a "todoTaskList" elements
    And the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have one task with a startTask property equal to previous startTs
    And the first task in the todoTaskList should have 1 timer and 0 pomodoro
