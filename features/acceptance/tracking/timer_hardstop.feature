Feature: When a timer is launched it has to ring, except if the task can not be continued
  As a worker, I want to hard-stop the timer when the following happens :
  - I stop the timer because I must gather an important information
  - I have an emergency that requires stop -> perso / pro

  Background:
    Given I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated
    Given I add a "foo" task with id "123task" in my "todoTaskList"
    And I start the timer for the "123task" task

    @stop-timer
    Scenario: I stop the timer in my first pomodoro
      Given I stop the timer for the "123task" task
      Then I access my activity inventory
      And the "todoTaskList" node should have 1 elements
      And the first task in the todoTaskList should have 0 timer and 0 pomodoro
