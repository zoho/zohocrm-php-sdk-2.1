<?php
namespace com\zoho\crm\api;

use com\zoho\crm\api\util\Constants;

use com\zoho\crm\api\util\Utility;

class ProxyBuilder
{
    private $host;

    private $port;

    private $user;

    private $password = "";

    public function host($host)
    {
        Utility::assertNotNull($host, Constants::REQUEST_PROXY_ERROR, Constants::HOST_ERROR_MESSAGE);

        $this->host = $host;

        return $this;
    }

    public function port($port)
    {
        Utility::assertNotNull($port, Constants::REQUEST_PROXY_ERROR, Constants::PORT_ERROR_MESSAGE);

        $this->port = $port;

        return $this;
    }

    public function user($user)
    {
        $this->user = $user;

        return $this;
    }

    public function password($password)
    {
        $this->password = $password;

        return $this;
    }

    public function build()
    {
        Utility::assertNotNull($this->host, Constants::REQUEST_PROXY_ERROR, Constants::HOST_ERROR_MESSAGE);

        Utility::assertNotNull($this->port, Constants::REQUEST_PROXY_ERROR, Constants::PORT_ERROR_MESSAGE);

        $class = new \ReflectionClass(RequestProxy::class);

        $constructor = $class->getConstructor();

        $constructor->setAccessible(true);

        $object = $class->newInstanceWithoutConstructor();

        $constructor->invoke($object, $this->host, $this->port, $this->user, $this->password);

        return $object;
    }
}
?>