<?php

declare(strict_types=1);

namespace PomodoroTests\UnitTest\Phpunit\Worker\UseCase\Parameters;

use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParameters;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersPresenter;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersRequest;
use Pomodoro\Domain\Worker\UseCase\Parameters\ShowParametersResponse;
use PomodoroTests\UnitTest\Phpunit\Worker\UseCase\WorkerTest;

class ShowParametersTest extends WorkerTest implements ShowParametersPresenter
{
    private ShowParametersRequest $request;
    private ?ShowParametersResponse $response = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ShowParametersRequest();
    }

    public function present(ShowParametersResponse $response)
    {
        $this->response = $response;
    }

    public function testShowParametersRequest()
    {
        $this->request->withWorkerId('123');
        self::assertInstanceOf(ShowParametersRequest::class, $this->request);
        self::assertClassHasAttribute('workerId', ShowParametersRequest::class);
    }

    public function testShowParameters()
    {
        $useCase = new ShowParameters($this->workerRepository);
        $this->request->withWorkerId('123');
        $useCase->execute($this->request, $this);
        self::assertInstanceOf(ShowParametersResponse::class, $this->response);
        self::assertClassHasAttribute('parameters', ShowParametersResponse::class);
        self::assertNotEmpty($this->response->parameters);
        self::assertArrayHasKey('pomodoroDuration', $this->response->parameters);
        self::assertArrayHasKey('shortBreakDuration', $this->response->parameters);
        self::assertArrayHasKey('longBreakDuration', $this->response->parameters);
        self::assertArrayHasKey('startFirstTaskIn', $this->response->parameters);
    }
}
