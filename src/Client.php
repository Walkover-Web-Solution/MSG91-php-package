<?php
namespace Msg91\Campaign;

use Msg91\Campaign\Http\Rest;
use Msg91\Campaign\Util;

class Client
{
    private $_authKey;
    private $_rest;
    private $_util;

    /**
     * Constructor
     *
     * @public
     * @param {string} $authKey
     * @memberof Client
     */
    public function __construct($authKey = null)
    {
        $this->_authKey = $authKey;
        $this->_rest = new Rest();
        $this->_util = new Util();
    }

    /**
     * Get List of campaigns
     *
     * @public
     * @param {array} $params
     * @memberof Client
     */
    public function getCampaigns(array $params = [])
    {
        return $this->_rest->call("GET", "campaigns/", $this->_authKey, $params);
    }

    /**
     * Get mapping and fields of campaign
     *
     * @public
     * @param {string} $campaignSlug
     * @memberof Client
     */
    public function getFields($campaignSlug)
    {
        return $this->_rest->call("GET", "campaigns/" . $campaignSlug . "/fields", $this->_authKey, []);
    }

    /**
     * Get request body of campaign
     *
     * @public
     * @param {string} $campaignSlug
     * @memberof Client
     */
    public function getRequestBody($campaignSlug)
    {
        $fields = $this->getFields($campaignSlug);
        return $this->_util->getRequestBody($fields);
    }

    /**
     * Send campaign
     *
     * @public
     * @param {string} $campaignSlug
     * @param {array} $requestBody
     * @memberof Client
     */
    public function runCampaign($campaignSlug, $requestBody)
    {
        return $this->_rest->call("POST", "campaigns/" . $campaignSlug . "/run", $this->_authKey, [], $requestBody);
    }
}
