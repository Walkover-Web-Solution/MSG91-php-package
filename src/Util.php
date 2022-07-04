<?php
namespace Msg91\Campaign;

class Util
{
    /**
     * Constructor
     *
     * @public
     * @memberof Util
     */
    public function __construct()
    {

    }

    /**
     * Returns base url of api
     *
     * @public
     * @param {array} $fields
     * @memberof Util
     */
    public function getRequestBody($fields)
    {
        if ($fields->body['status'] == "success") {
            $request = array(
                "data" => array(
                    "sendTo" => array()
                ),
            );
            $mappings = array();

            if ($fields->body['data']['mapping'] && count($fields->body['data']['mapping']) > 0) {
                foreach ($fields->body['data']['mapping'] as $mapping) {
                    $mappings[$mapping['name']] = $mapping['label'];
                }
            }

            if (isset($mappings['to']) || isset($mappings['mobiles'])) {
                if (isset($mappings['to']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['to'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com",
                        "mobiles" => "911234567890"
                    );
                } else if (isset($mappings['to']) && !isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['to'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com"
                    );
                } else if (!isset($mappings['to']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['to'][0] = array(
                        "name" => "name",
                        "mobiles" => "911234567890"
                    );
                }
            }

            if (isset($mappings['cc'])) {
                if (isset($mappings['cc']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['cc'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com",
                        "mobiles" => "911234567890"
                    );
                } else if (isset($mappings['cc']) && !isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['cc'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com"
                    );
                } else if (!isset($mappings['cc']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['cc'][0] = array(
                        "name" => "name",
                        "mobiles" => "911234567890"
                    );
                }
            }

            if (isset($mappings['bcc'])) {
                if (isset($mappings['bcc']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['bcc'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com",
                        "mobiles" => "911234567890"
                    );
                } else if (isset($mappings['bcc']) && !isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['bcc'][0] = array(
                        "name" => "name",
                        "email" => "name@email.com"
                    );
                } else if (!isset($mappings['bcc']) && isset($mappings['mobiles'])) {
                    $request['data']['sendTo'][0]['bcc'][0] = array(
                        "name" => "name",
                        "mobiles" => "911234567890"
                    );
                }
            }

            if (isset($fields->body['data']['variables']) && count($fields->body['data']['variables']) > 0) {
                foreach ($fields->body['data']['variables'] as $variable) {
                    $request['data']['sendTo'][0]['variables'][$variable] = array(
                        "type" => "text",
                        "value" => "{your_value}"
                    );
                }
            }

            $request['data']['attachments'][0] = array(
                "fileType" => "url or base64",
                "fileName" => "{your_fileName}",
                "file" => "{your_file}"
            );

            $request['data']['reply_to'][0] = array(
                "name" => "{your_name}",
                "email" => "{your_email}"
            );

            return $request;
        } else {
            return $fields;
        }
    }
}
