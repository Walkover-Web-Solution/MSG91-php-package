<?php
namespace Msg91\Campaign;

class Config
{
    private $_baseUrl = "https://control.msg91.com/api/v5/campaign/api/";
    private $_alternateBaseUrl = "https://control.msg91.com/api/v5/campaign/api/";

    /**
     * Constructor
     *
     * @public
     * @memberof Config
     */
    public function __construct()
    {

    }

    /**
     * Returns base url of api
     *
     * @public
     * @memberof Config
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * Returns alternate base url of api
     *
     * @public
     * @memberof Config
     */
    public function getAlternateBaseUrl()
    {
        return $this->_alternateBaseUrl;
    }
}
