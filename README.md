<pre>require_once __DIR__ . '/vendor/autoload.php';

$client = new \Msg91\Campaign\Client('MSG91-AUTH-KEY');

$campaignList = $client->getCampaigns();

$campaignSlug = "demo";

$campaignFields = $client->getFields($campaignSlug);

$requestBody = $client->getRequestBody($campaignSlug);

$response = $client->runCampaign($campaignSlug, $requestBody);
</pre>
