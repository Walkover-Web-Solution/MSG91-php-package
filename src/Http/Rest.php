<?php
namespace Msg91\Campaign\Http;

use Msg91\Campaign\Http\Curl;
use Msg91\Campaign\Config;

class Rest
{
    private $_curl;
    private $_config;
    private $_baseUrl;
    private $_alternateBaseUrl;

    /**
     * Constructor
     *
     * @public
     * @memberof Rest
     */
    public function __construct()
    {
        $this->_curl = new Curl();
        $this->_config = new Config();
        $this->_baseUrl = $this->_config->getBaseUrl();
        $this->_alternateBaseUrl = $this->_config->getAlternateBaseUrl();
    }

    /**
     * Sends request to curl method
     *
     * @public
     * @param {string} $method
     * @param {string} $url
     * @param {string} $authKey
     * @param {array} $params
     * @param {array} $data
     * @memberof Rest
     */
    public function call(string $method, string $url, string $authKey = null, array $params = [], array $data = [])
    {
        $response = $this->_curl->request($method, $this->_baseUrl . $url, $authKey, $params, $data);
        if ($response && substr($response->statusCode, 0, 1) == 5) {
            return $this->_curl->request($method, $this->_alternateBaseUrl . $url, $authKey, $params, $data);
        } else {
            return $response;
        }
    }
}
