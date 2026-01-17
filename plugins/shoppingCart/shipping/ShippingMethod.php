<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: store.gmoplus.com
 *  FILE: SHIPPINGMETHOD.PHP
 *  
 *  The software is a commercial product delivered under single, non-exclusive,
 *  non-transferable license for one domain or IP address. Therefore distribution,
 *  sale or transfer of the file in whole or in part without permission of Flynax
 *  respective owners is considered to be illegal and breach of Flynax License End
 *  User Agreement.
 *  
 *  You are not allowed to remove this information from the file without permission
 *  of Flynax respective owners.
 *  
 *  Flynax Classifieds Software 2025 | All copyrights reserved.
 *  
 *  https://www.flynax.com
 ******************************************************************************/

namespace ShoppingCart\Shipping;

abstract class ShippingMethod
{
    /**
     * Test mode
     *
     * @var bool
     */
    protected $testMode;

    /**
     * Request data
     *
     * @var array
     */
    protected $request;

    /**
     * API host of shipping service
     *
     * @var string
     */
    protected $apiHost;

    /**
     * Method info
     *
     * @var array
     */
    protected $methodInfo;

    public function getQuote()
    {}

    public function prepareFields()
    {}

    /**
     * Set request option
     *
     * @param string $key
     * @param string $value
     */
    public function setOption($key = '', $value = '')
    {
        if ($key) {
            $this->request[$key] = $value;
        }
    }

    /**
     * Get request option
     *
     * @param string $key
     * @return string
     */
    public function getOption($key = '')
    {
        if (isset($this->request[$key])) {
            return $this->request[$key];
        }

        return null;
    }

    /**
     * Set API host
     *
     * @param mixed $mode
     */
    public function setAPIHost($host = '')
    {
        $this->apiHost = $host;
    }

    /**
     * Get API host
     *
     * @return string
     */
    public function getAPIHost()
    {
        return $this->apiHost;
    }

    /**
     * Get Method
     *
     * @return array
     */
    public function getMethod()
    {
        return $this->methodInfo;
    }

    /**
     * Send request to DHL server
     *
     * @param mixed $xml
     */
    public function post($request)
    {
        if (!$request) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getAPIHost());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return false;
        }

        return $response;
    }

    /**
     * Parse SOAP xml
     *
     * @param string $xml
     * @param string $pattern
     * @return array
     */
    public function parseSOAP($xml, $pattern = '')
    {
        if (!$xml) {
            return [];
        }
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $xml = new \SimpleXMLElement($xml);
        $body = $xml->xpath('//' . $pattern)[0];
        $response = json_decode(json_encode((array) $body), true);

        return $response;
    }
}
