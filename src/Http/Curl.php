<?php
namespace Msg91\Campaign\Http;

use Msg91\Campaign\Exception\CampaignException;
use Msg91\Campaign\Http\Response;

class Curl
{
    public const DEFAULT_TIMEOUT = 10;
    protected $curlOptions = [];

    public $lastRequest;
    public $lastResponse;

    /**
     * Constructor
     *
     * @public
     * @param {array} $options
     * @memberof Curl
     */
    public function __construct(array $options = [])
    {
        $this->curlOptions = $options;
    }

    /**
     * Calls the api
     *
     * @public
     * @param {string} $method
     * @param {string} $url
     * @param {string} $authKey
     * @param {array} $params
     * @param {array} $data
     * @returns object
     * @memberof Curl
     */
    public function request(string $method, string $url, string $authKey = null, array $params = [], array $data = []): Response
    {
        $options = $this->options($method, $url, $authKey, $params, $data);

        $this->lastRequest = $options;
        $this->lastResponse = null;

        try {
            if (!$curl = \curl_init()) {
                throw new CampaignException('Unable to initialize cURL');
            }

            if (!\curl_setopt_array($curl, $options)) {
                throw new CampaignException(\curl_error($curl));
            }

            if (!$response = \curl_exec($curl)) {
                throw new CampaignException(\curl_error($curl));
            }

            $parts = \explode("\r\n\r\n", $response, 3);

            list($head, $body) = (
                \preg_match('/\AHTTP\/1.\d 100 Continue\Z/', $parts[0])
                || \preg_match('/\AHTTP\/1.\d 200 Connection established\Z/', $parts[0])
                || \preg_match('/\AHTTP\/1.\d 200 Tunnel established\Z/', $parts[0])
            )
            ? array($parts[1], $parts[2])
            : array($parts[0], $parts[1]);

            $statusCode = \curl_getinfo($curl, CURLINFO_HTTP_CODE);

            $responseHeaders = [];
            $headerLines = \explode("\r\n", $head);
            \array_shift($headerLines);
            foreach ($headerLines as $line) {
                list($key, $value) = \explode(':', $line, 2);
                $responseHeaders[$key] = $value;
            }

            \curl_close($curl);

            if (isset($options[CURLOPT_INFILE]) && \is_resource($options[CURLOPT_INFILE])) {
                \fclose($options[CURLOPT_INFILE]);
            }

            $this->lastResponse = new Response($statusCode, $body, $responseHeaders);

            return $this->lastResponse;
        } catch (\Exception$e) {
            if (isset($curl) && \is_resource($curl)) {
                \curl_close($curl);
            }

            if (isset($options[CURLOPT_INFILE]) && \is_resource($options[CURLOPT_INFILE])) {
                \fclose($options[CURLOPT_INFILE]);
            }

            throw $e;
        }
    }

    /**
     * Returns the curl options
     *
     * @public
     * @param {string} $method
     * @param {string} $url
     * @param {string} $authKey
     * @param {array} $params
     * @param {array} $data
     * @returns curl options
     * @memberof Curl
     */
    public function options(string $method, string $url, string $authKey = null, array $params = [], array $data = []): array
    {
        $timeout = self::DEFAULT_TIMEOUT;

        if ($params && count($params) > 0) {
            $url .= "?" . $this->buildQuery($params);
        }

        $options = $this->curlOptions + [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_INFILESIZE => null,
            CURLOPT_HTTPHEADER => [],
            CURLOPT_TIMEOUT => $timeout,
        ];

        switch (\strtolower(\trim($method))) {
            case 'get':
                $options[CURLOPT_HTTPHEADER] = array('authkey:' . $authKey);
                $options[CURLOPT_HTTPGET] = true;
                break;
            case 'post':
                $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json', 'authkey:' . $authKey, 'Content-Length:' . strlen(json_encode($data)));
                $options[CURLOPT_CUSTOMREQUEST] = "POST";
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = \strtoupper($method);
        }

        return $options;
    }

    /**
     * Returns the curl options
     *
     * @public
     * @param {array} $params
     * @returns query params
     * @memberof Curl
     */
    public function buildQuery(?array $params): string
    {
        $parts = [];
        $params = $params ?: [];

        foreach ($params as $key => $value) {
            if (\is_array($value)) {
                foreach ($value as $item) {
                    $parts[] = \urlencode((string) $key) . '=' . \urlencode((string) $item);
                }
            } else {
                $parts[] = \urlencode((string) $key) . '=' . \urlencode((string) $value);
            }
        }

        return \implode('&', $parts);
    }
}
