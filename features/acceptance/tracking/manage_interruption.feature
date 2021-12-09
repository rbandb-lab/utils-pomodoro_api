Feature: As a worker i started a timer and I am interrupted. I want handle interruption (internal or external)
  * Internal interruption is handled by ticking an apostrophe on the task record -> I may decide to add a task :
  - unplanned (U) [add to todo-list with a (U)]
  - unplanned and urgent (UU) [add to todo-list / unplanned and urgent with a (UU)]
  - may add a deadline
  -> continue work. A pomodoro HAS TO ring when it's started.
  * External interruption : you need to protect current timer. Inform, negotiate, reschedule interruption.
  For example to recontact / visio call later at the long break, next 2hrs, tomorrow ... depending on the estimated urgence
  Add it as you would do in an internal interruption.

  Background: I handle an interruption
    Given I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |  toto        |  toto@example.com    |   12345678        |   1500                |    900                    |   300                    |   1500                  |
    Given the worker "toto@example.com" with password "12345678" is authenticated
    Given I add a "foo" task with id "123task" in my "todoTaskList"
    And I start the timer for the "123task" task

  @internal-interruption
  Scenario: I record an internal interruption and add a task as unplanned (U)
    Given I add an interruption of type "internal" to the task "123task" and a "bar" task in my unplannedTaskList
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "internal" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as non-urgent with a deadline equal to null

  @internal-interruption-urgent
  Scenario: I record an internal interruption add a task as unplanned and urgent (UU)
    Given I add an interruption of type "internal" to the task "123task" and a "bar" task "urgent" in my unplannedTaskList
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "internal" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as urgent with a deadline equal to null

  @internal-interruption-urgent-deadline
  Scenario: I record an internal interruption add a task as unplanned and urgent (UU)
    Given I add an interruption of type "internal" to the task "123task" and a "bar" task "urgent" in my unplannedTaskList with a deadline at "2021-12-31 23:59:00"
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "internal" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as urgent with a deadline equal to "1640995140"

  @external-interruption
  Scenario: I record an external interruption and add a new unplanned task (U) - i.g. "recontact toto"
    Given I add an interruption of type "external" to the task "123task" and a "bar" task in my unplannedTaskList
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "external" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as non-urgent with a deadline equal to null

  @external-interruption-urgent
  Scenario: I record an external interruption add a task as unplanned and urgent (UU)
    Given I add an interruption of type "external" to the task "123task" and a "bar" task "urgent" in my unplannedTaskList
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "external" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as urgent with a deadline equal to null

  @external-interruption-urgent-deadline
  Scenario: I record an external interruption add a task as unplanned and urgent (UU)
    Given I add an interruption of type "external" to the task "123task" and a "bar" task "urgent" in my unplannedTaskList with a deadline at "2021-12-31 23:59:00"
    When I access my activity inventory
    Then the "todoTaskList" node should have 1 elements
    And the first task in the todoTaskList should have 1 interruption of "external" type
    And the "unplannedTaskList" node should have 1 elements
    And the first task in the unplannedTaskList should have 1 task named "bar" marked as urgent with a deadline equal to "1640995140"

# ADD schedule-response (manage automatic action : programm a call ...). Depends on "Scheduler / Scheduled Task"














