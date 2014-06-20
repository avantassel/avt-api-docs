#!/bin/php
<?php

$api_endpoints = @file_get_contents(__DIR__."/../endpoints.json");
$api_json = @json_decode($api_endpoints);
$features_dir = __DIR__."/features";

function getParams($param,$endpoint_params){
	$params = '&'.$param->field.'='.$param->value;
	if(isset($param->require)){
		$required = explode(",", $param->require);
		foreach ($required as $r) {
			foreach($endpoint_params as $ep){
				if($ep->field == $r && $ep->value)
					$params .= '&'.$ep->field.'='.$ep->value;
			}
		}
	}
	return $params;
}

//Set Environment params
$env_url = "";
$env_params = "";

if(!empty($api_json->env)){
	foreach($api_json->env as $e){
		if($e->abbr=='test'){
			$env_url = str_replace('[version]', $api_json->version, $e->url);
			if(isset($e->parameters))
				$env_params = "&".$e->parameters;
		}
	}
}

if(!empty($api_json->endpoints)){
	foreach($api_json->endpoints as $e){
		$endpoint = str_replace('/', '-', $e->name);
		$params = "env=dev";
		$has_test = false;

		if(!empty($e->parameters)){

			foreach ($e->parameters as $p) {
				if(!empty($p->test)){
					$params .= getParams($p,$e->parameters);
					$has_test = true;
					break;
				}
			}
			//if endpoint does not have a test then continue
			if(!$has_test)
				continue;

			//need at least one argument to pass
			if(!empty($params)){
				if(isset($e->priority))
					$file = fopen("$features_dir/".str_replace('-',$e->priority.'-',$endpoint).".feature","w");
				else
					$file = fopen("$features_dir/$endpoint.feature","w");

				if($file){
					fwrite($file,"Feature: $endpoint API endpoint\n");
					fwrite($file,"Scenario:\n");
					  fwrite($file,"Given I call \"$env_url/".$e->name."/\" with params \"".$params.$env_params."\" and method \"".$e->method."\"\n");
					  fwrite($file,"Then I get a response\n");
					  fwrite($file,"And the response is JSON\n");
					  fwrite($file,"And the response contains a success meta code\n");
					  fwrite($file,"And the response contains a response\n");
					fclose($file);
					echo "Created test for $features_dir/$endpoint.feature\n";
				}
			}
		}
	}
}
?>
