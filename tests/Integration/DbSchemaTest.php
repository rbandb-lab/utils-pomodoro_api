<?php

declare(strict_types=1);

namespace PomodoroTests\Integration;

use Doctrine\DBAL\Connection;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony5\Persistence\ORM\Doctrine\Entity\OrmWorker;

class DbSchemaTest extends KernelTestCase
{
    /**
     * @var Connection
     */
    protected Connection $connection;

    protected $databaseTool;


    /**
     * @BeforeScenario
     */
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->connection = $kernel->getContainer()->get('doctrine')->getConnection();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $sql = <<<EOF
CREATE TABLE orm_worker (id VARCHAR(36) NOT NULL, username VARCHAR(120) NOT NULL, first_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, pomodoro_duration INTEGER NOT NULL, short_break_duration INTEGER NOT NULL, long_break_duration INTEGER NOT NULL, start_firs_task_in INTEGER NOT NULL, email_validated BOOLEAN NOT NULL, PRIMARY KEY(id));
CREATE TABLE token (id VARCHAR(36) NOT NULL, worker_id VARCHAR(36) DEFAULT NULL, token_string VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id));
CREATE INDEX IDX_5F37A13B6B20BA36 ON token (worker_id);
CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL);
CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name);
CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at);
CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at);
EOF;
        $this->connection->beginTransaction();
        $this->connection->executeStatement($sql);
        $this->connection->commit();
    }

    /**
     * @AfterScenario
     */
    public function tearDown(): void
    {
        self::$kernel->shutdown();
    }

    public function testItLoadsWorkerFixtures()
    {
        $this->databaseTool->loadFixtures([
            'Symfony5\Persistence\ORM\Doctrine\DataFixtures\WorkerFixtures',
        ]);

        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('Symfony5\Entity:OrmWorker');
        $workers = $repo->findAll();
        $worker = array_shift($workers);
        self::assertInstanceOf(OrmWorker::class, $worker);
        $this->connection->close();
    }
}
