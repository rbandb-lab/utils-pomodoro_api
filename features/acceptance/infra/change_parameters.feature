Feature:
# List and edit all parameters:
#  - cycles
#  - interruptions management
#  - team sync

  Background:
    Given I register with parameters:
      | first_name   |  email                   |   password  |
      | toto         |  john.doe@example.com    |   12345678  |

  @edit-params
  Scenario: I edit my cycle parameters
    Given the worker "john.doe@example.com" with password "12345678" is authenticated
    Given I edit my parameters:
      |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |   1200                |    950                    |   400                    |   900                   |
    And I access my profile parameters
    Then the response payload should contain parameters:
      |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      |   1200                |    950                    |   400                    |   900                   |
