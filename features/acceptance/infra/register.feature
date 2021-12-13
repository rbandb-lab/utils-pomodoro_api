Feature: I register as a worker by sending my information and I receive a validation link by email

  @register
  Scenario: I register with my email and password and my email does not exists
    Given no users exists
    When I register with parameters:
      | first_name   |  email               |   password  |
      | toto         |  toto@example.com    |   12345678  |
    Then an email is sent to "toto@example.com" with a validation link

  @register-fail-email
  Scenario: I register with my email and password and my email already exists
    Given a user exists:
      | first_name   |  email               |   password  |
      | toto         |  toto@example.com    |   12345678  |
    When I register with parameters:
      | first_name   |  email               |   password  |
      | toto         |  toto@example.com    |   12345678  |
    Then the response payload contains errors
    And the error message should contain "email:already registered"

  @register-fail-password
  Scenario: I register with my email and password and my password is too short
    Given no users exists
    When I register with parameters:
      | first_name   |  email               |   password  |
      | toto         |  toto@example.com    |   123       |
    Then the response payload contains errors
    And the error message should contain 'password:Value "123" is too short, it should have at least 8 characters, but only has 3 characters.'

  @registration-email
  Scenario: I register and receive a validation email
    Given no users exists
    When I register with parameters:
      | first_name   |  email                   |   password  |
      | toto         |  john.doe@example.com    |   12345678  |
    Then the response payload should contain no errors
    And an email should be sent to "john.doe@example.com"
    And the email title should be "Your registration to pomodoro-app"
    And the email content should have a link which contains "/validate"
    And the link should contain a valid token
    Given the worker "john.doe@example.com" with password "12345678" is authenticated
    And when I access my profile
    And the Worker property emailValidated should be equal to false

  @register-override-params
  Scenario: I register with my email and password and my cycle parameters are set
    Given no users exists
    When I register with parameters:
      | first_name   |  email               |   password        |   pomodoro_duration   |    long_break_duration    |   short_break_duration   |   start_first_task_in   |
      | toto         |  toto@example.com    |   12345678        |   1500                |    1000                   |   500                    |   500                   |
    Given the worker "toto@example.com" with password "12345678" is authenticated
    Then the response payload should contain no errors
    And I access my profile parameters
    Then the response payload should contain parameters
    And the "parameters" node should contains the associative array:
      | key                 | value   |
      | pomodoroDuration    | 1500    |
      | shortBreakDuration  | 500     |
      | longBreakDuration   | 1000    |
      | startFirstTaskIn    | 500     |

  @register-default-params
  Scenario: I register with my email and password and my cycle parameters are set
    Given no users exists
    When I register with parameters:
      | first_name   |  email               |   password  |
      | toto         |  toto@example.com    |   12345678  |
    Then the response payload should contain no errors
    Given the worker "toto@example.com" with password "12345678" is authenticated
    And I access my profile parameters
    Then the response payload should contain parameters
    And the "parameters" node should contains the associative array:
      | key                       | value |
      | pomodoroDuration          | 1500  |
      | shortBreakDuration        | 300   |
      | longBreakDuration         | 900   |
      | startFirstTaskIn          | 1500  |

  @register-validate
  Scenario: I use the validation link in the email and my Worker account is validated
    Given no users exists
    When I register with parameters:
      | first_name   |  email                   |   password  |
      | toto         |  john.doe@example.com    |   12345678  |
    Then the response payload should contain no errors
    And an email should be sent to "john.doe@example.com"
    And the email title should be "Your registration to pomodoro-app"
    And the email content should have a link which contains "/validate"
    And the link should contain a valid token
    Given the worker "john.doe@example.com" with password "12345678" is authenticated
    And when I access my profile
    And the Worker property emailValidated should be equal to false
    Given I validate my email with the validation link
    Then when I access my profile
    And the Worker property emailValidated should be equal to true