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
            $response = $client->get($endpoint.'?'.$params);
        }
        else if($method=='POST'){
            parse_str($params,$params_array);
            $response = $client->post($endpoint,array('body'=>$params_array));
        }

        $this->response = $response->getBody();
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->response)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->response);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->response);
        }
    }

    /**
     * @Given /^the response contains a success meta code$/
     */
    public function theResponseContainsASuccessMetaCode()
    {
        $data = json_decode($this->response);

        if (!isset($data->meta->code) || (isset($data->meta->code) && $data->meta->code != 200)) {
            throw new Exception("Response did not contain meta code of 200");
        }
    }

    /**
     * @Given /^the response contains a response$/
     */
    public function theResponseContainsAResponse()
    {
        $data = json_decode($this->response);
        
        if (!isset($data->response)) {
            throw new Exception("Response did not contain a response");
        }
    }
}