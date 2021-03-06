<?php

namespace Doctrine\Tests\DBAL\Functional\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\Tests\DbalFunctionalTestCase;

abstract class AbstractDriverTest extends DbalFunctionalTestCase
{
    /**
     * The driver instance under test.
     *
     * @var Driver
     */
    protected $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = $this->createDriver();
    }

    public function testConnectsWithoutDatabaseNameParameter(): void
    {
        $params = $this->connection->getParams();
        unset($params['dbname']);

        $user          = $params['user'] ?? null;
        $password      = $params['password'] ?? null;
        $driverOptions = $params['driverOptions'] ?? [];

        $connection = $this->driver->connect($params, $user, $password, $driverOptions);

        self::assertInstanceOf(DriverConnection::class, $connection);
    }

    public function testReturnsDatabaseNameWithoutDatabaseNameParameter(): void
    {
        $params = $this->connection->getParams();
        unset($params['dbname']);

        $connection = new Connection(
            $params,
            $this->connection->getDriver(),
            $this->connection->getConfiguration(),
            $this->connection->getEventManager()
        );

        self::assertSame(
            static::getDatabaseNameForConnectionWithoutDatabaseNameParameter(),
            $this->driver->getDatabase($connection)
        );
    }

    abstract protected function createDriver(): Driver;

    protected static function getDatabaseNameForConnectionWithoutDatabaseNameParameter(): ?string
    {
        return null;
    }
}
