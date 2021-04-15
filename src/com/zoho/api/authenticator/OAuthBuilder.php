<?php

namespace com\zoho\api\authenticator;

use com\zoho\crm\api\util\Utility;

use com\zoho\crm\api\util\Constants;

use com\zoho\crm\api\exception\SDKException;

class OAuthBuilder
{
    private $clientID;

    private $clientSecret;

    private $redirectURL;

    private $grantToken;

    private $refreshToken;

    private $id;

    public function id($id)
    {
        $this->id = $id;

        return $this;
    }

    public function clientId($clientID)
    {
        Utility::assertNotNull($clientID, Constants::TOKEN_ERROR, Constants::CLIENT_ID_NULL_ERROR_MESSAGE);

        if (strtolower(gettype($clientID)) !== strtolower(Constants::STRING_NAMESPACE))
        {
            $error = array();

            $error[Constants::FIELD] = Constants::CLIENT_ID;

            $error[Constants::EXPECTED_TYPE] = Constants::STRING_NAMESPACE;

            $error[Constants::CLASS_KEY] = get_class();

            throw new SDKException(Constants::TOKEN_ERROR, null, $error, null);
        }

        $this->clientID = $clientID;

        return $this;
    }

    public function clientSecret($clientSecret)
    {
        Utility::assertNotNull($clientSecret, Constants::TOKEN_ERROR, Constants::CLIENT_SECRET_NULL_ERROR_MESSAGE);

        if (strtolower(gettype($clientSecret)) !== strtolower(Constants::STRING_NAMESPACE))
        {
            $error = array();

            $error[Constants::FIELD] = Constants::CLIENT_SECRET;

            $error[Constants::EXPECTED_TYPE] = Constants::STRING_NAMESPACE;

            $error[Constants::CLASS_KEY] = get_class();

            throw new SDKException(Constants::TOKEN_ERROR, null, $error, null);
        }

        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function redirectURL($redirectURL)
    {
        if ($redirectURL != null && strtolower(gettype($redirectURL)) !== strtolower(Constants::STRING_NAMESPACE))
        {
            $error = array();

            $error[Constants::FIELD] = Constants::REDIRECT_URL;

            $error[Constants::EXPECTED_TYPE] = Constants::STRING_NAMESPACE;

            $error[Constants::CLASS_KEY] = get_class();

            throw new SDKException(Constants::TOKEN_ERROR, null, $error, null);
        }

        $this->redirectURL = $redirectURL;

        return $this;
    }

    public function refreshToken($refreshToken)
    {
        if (strtolower(gettype($refreshToken)) !== strtolower(Constants::STRING_NAMESPACE))
        {
            $error = array();

            $error[Constants::FIELD] = Constants::REFRESH_TOKEN;

            $error[Constants::EXPECTED_TYPE] = Constants::STRING_NAMESPACE;

            $error[Constants::CLASS_KEY] = get_class();

            throw new SDKException(Constants::TOKEN_ERROR, null, $error, null);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function grantToken($grantToken)
    {
        if (strtolower(gettype($grantToken)) !== strtolower(Constants::STRING_NAMESPACE))
        {
            $error = array();

            $error[Constants::FIELD] = Constants::GRANT_TOKEN;

            $error[Constants::EXPECTED_TYPE] = Constants::STRING_NAMESPACE;

            $error[Constants::CLASS_KEY] = get_class();

            throw new SDKException(Constants::TOKEN_ERROR, null, $error, null);
        }

        $this->grantToken = $grantToken;

        return $this;
    }

    public function build()
    {
        $class = new \ReflectionClass(OAuthToken::class);

        $constructor = $class->getConstructor();

        $constructor->setAccessible(true);

        $object = $class->newInstanceWithoutConstructor();

        $constructor->invoke($object, $this->clientID, $this->clientSecret, $this->grantToken, $this->refreshToken, $this->redirectURL, $this->id);

        return $object;
    }
}