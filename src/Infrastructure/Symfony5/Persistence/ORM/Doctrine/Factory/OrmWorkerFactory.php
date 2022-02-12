<?php

declare(strict_types=1);

namespace Symfony5\Persistence\ORM\Doctrine\Factory;

use Pomodoro\Domain\Worker\Entity\ActivityInventoryInterface;
use Pomodoro\Domain\Worker\Entity\Worker;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

final class OrmWorkerFactory
{
    public static function fromOrm(OrmWorker $ormWorker): Worker
    {
        $cycleParameters = $ormWorker->getCycleParameters();

        $worker = new Worker(
            $ormWorker->getId(),
            $ormWorker->getUsername(),
            $ormWorker->getFirstName(),
            $ormWorker->getPassword(),
            $cycleParameters->getPomodoroDuration(),
            $cycleParameters->getShortBreakDuration(),
            $cycleParameters->getLongBreakDuration(),
            $cycleParameters->getStartFirstTaskIn(),
        );

        $ormActivityInventory = $ormWorker->getActivityInventory();
        $activityInventory = OrmInventoryFactory::fromOrm($ormActivityInventory);
        $worker->setActivityInventory($activityInventory);

        return $worker;
    }

    public static function toOrm(Worker $worker): OrmWorker
    {
        $ormWorker = new OrmWorker(
            $worker->getId(),
            $worker->getUsername(),
            $worker->getFirstName(),
        );

        $inventory = $worker->getActivityInventory();
        if (!$inventory instanceof ActivityInventoryInterface) {
            throw new \LogicException(__METHOD__ . '. ActivityInventory::Class expected ');
        }

        $ormInventory = OrmInventoryFactory::toOrm($inventory);

        $ormWorker->setPassword($worker->getPassword());
        $ormWorker->setEmailValidated($worker->isEmailValidated());
        $ormWorker->setPomodoroDuration($worker->getParameters()->getPomodoroDuration());
        $ormWorker->setLongBreakDuration($worker->getParameters()->getLongBreakDuration());
        $ormWorker->setStartFirstTaskIn($worker->getParameters()->getStartFirstTaskIn());
        $ormWorker->setShortBreakDuration($worker->getParameters()->getShortBreakDuration());
        $tokens = $worker->getTokens();

        foreach ($tokens as $token) {
            $ormToken = OrmTokenFactory::toOrm($token, $ormWorker);
            $ormWorker->addToken($ormToken);
        }

        $ormWorker->setActivityInventory($ormInventory);

        return $ormWorker;
    }

    public static function fromRequestArray(array $data): Worker
    {
        return new Worker(
            $data['worker_id'],
            $data['username'],
            $data['first_name'],
            $data['password'],
            $data['pomodoro_duration'],
            $data['short_break_duration'],
            $data['long_break_duration'],
            $data['start_first_task_in'],
        );
    }
}
