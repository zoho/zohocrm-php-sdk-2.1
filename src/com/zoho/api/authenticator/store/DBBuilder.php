<?php

namespace com\zoho\api\authenticator\store;

use com\zoho\crm\api\util\Constants;

class DBBuilder
{
    private $userName = Constants::MYSQL_USER_NAME;

    private $portNumber = Constants::MYSQL_PORT_NUMBER;

    private $password = "";

    private $host = Constants::MYSQL_HOST;

    private $databaseName = Constants::MYSQL_DATABASE_NAME;

    private $tableName = Constants::MYSQL_TABLE_NAME;

    public function userName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    public function portNumber($portNumber)
    {
        $this->portNumber = $portNumber;

        return $this;
    }

    public function password($password)
    {
        $this->password = $password;

        return $this;
    }

    public function host($host)
    {
        $this->host = $host;

        return $this;
    }

    public function databaseName($databaseName)
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    public function tableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function build()
    {
        $class = new \ReflectionClass(DBStore::class);

        $constructor = $class->getConstructor();

        $constructor->setAccessible(true);

        $object = $class->newInstanceWithoutConstructor();

        $constructor->invoke($object, $this->host, $this->databaseName, $this->tableName, $this->userName, $this->password, $this->portNumber);

        return $object;
    }
}
?>