<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require __DIR__.'/../../../vendor/autoload.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    protected $response  = null;

    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     *  @Given /^I call "([^"]*)" with params "([^"]*)" and method "([^"]*)"$/
     */
    public function iCallWithParamsAndMethod($endpoint,$params,$method)
    {
        $client = new GuzzleHttp\Client();

        if($method=='GET'){
            $request = $client->get($endpoint.'?'.$params);
        }
        else if($method=='POST'){
            parse_str($params,$params_array);
            $request = $client->post($endpoint,array('body'=>$params_array));
        }

        $this->response = $request;
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (!$this->response->getBody()) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->response->getBody());

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->response);
        }
    }

    /**
     * @Given /^the response contains a success$/
     */
    public function theResponseContainsASuccess()
    {
        if ($this->response->getStatusCode() != 200) {
            throw new Exception("Response was ".$this->response->getStatusCode()." and not 200");
        }
    }

}
