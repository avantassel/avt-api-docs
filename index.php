<?php
	include('vendor/autoload.php');

	$api_endpoints = @file_get_contents(__DIR__."/endpoints.json");
	$api_json = @json_decode($api_endpoints);

	if(empty($api_json)){
		die('endpoints.json is missing.');
	}

	function getParams($param,$endpoint_params,$format=false){
		$params = $param->field.'='.$param->value;
		if(isset($param->require)){
			$required = explode(",", $param->require);
			foreach ($required as $r) {
				foreach($endpoint_params as $ep){
					if($ep->field == $r && $ep->value)
						$params .= '&'.$ep->field.'='.$ep->value;
				}
			}
		}
		if($format)
			return str_replace('&', '<br/>', $params);
		return $params;
	}
	function sortEndpointsByName ($a, $b){
	 	return -1 * (strcmp ($b->name,$a->name));
	}
	function sortParamsByField ($a, $b){
	 	return -1 * (strcmp ($b->field,$a->field));
	}

?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?=$api_json->name?> <?=$api_json->version?> Docs</title>

  <link rel="stylesheet" href="css/foundation.min.css" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/api.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.min.js"></script>

</head>
<body>
	<? include 'nav.php'; ?>

	<div class="row endpoints">
		<div class="large-8 columns">
				<h4>Choose an endpoint</h4>
				<div class="panel">
					<div class="allendpoints">
						<ul class="inline-list">
							<?
							if(!empty($api_json->endpoints)){
							usort($api_json->endpoints,'sortEndpointsByName');
							foreach($api_json->endpoints as $e){
								$endpoint = str_replace('/', '-', $e->name);
								?>
								<li><a class="explore-endpoint" href="#"><?=$endpoint?></a></li>
							<? } } ?>
						</ul>
					</div>
				</div>
			</p>

			<? foreach($api_json->endpoints as $e){
				$endpoint = str_replace('/', '-', $e->name);
				?>
				<div class="row endpoint <?=str_replace('/', '-', $e->name)?>">
				<div class="large-12 columns">
					<div class="panel">
						<h4><?=$e->name?></h4>
						<p>
							<? if(isset($e->description))
								echo $e->description;
							?>
							<div class="row collapse">
							<h4>Endpoint</h4>
	            				<div class="large-12 columns endpoint-row">
									<span class="endpoint-url">http://<?=$_SERVER['HTTP_HOST']?>/<?=$api_json->version?>/<?=$e->name?>/?</span>
				  				</div>
							</div>
							<div class="row">
								<div class="large-12 columns">
									<div class="row collapse">
					  				<div class="small-10 columns">
						  				<input type="text" id="params-<?=$endpoint?>" class="params" data-endpoint="<?=$endpoint?>" data-version="<?=$api_json->version?>" data-method="<?=$e->method?>" placeholder="Enter parameters..."/>
						  			</div>
						  			<div class="small-2 columns">
										<a href="#response" id="go-<?=$endpoint?>" class="button prefix call-api" data-endpoint="<?=$endpoint?>" data-version="<?=$api_json->version?>" data-method="<?=$e->method?>">Go</a>
						  			</div>
						  			</div>
						  		</div>
							</div>
						</p>
					</div>
				</div>
			</div>
			<? } ?>

			<div class="row curl">
				<div class="large-12 columns">
					<div class="panel callout"><h4>Curl</h4>
						<div class="api-curl"></div>
					</div>
				</div>
			</div>

			<div class="row response">
				<div class="large-12 columns">
					<div class="panel"><h4>Response</h4>
						<div class="api-response"></div>
					</div>
				</div>
			</div>

		</div>

		<div class="large-4 columns">
			<h4>Endpoint Params</h4>

			<div class="app-info">

			  <? foreach($api_json->endpoints as $e){
				$endpoint = str_replace('/', '-', $e->name);
				?>
				<div class="endpoint <?=$endpoint?>">
			  	<ul class="pricing-table">
			  		<li class="title"><?=$e->name?></li>
			    	<li class="price"><?=$e->method?></li>
			    	<li class="description">Permission Required: <span class="label radius <?=(bool)$e->perms_required==true?"alert":"success"?>"><?=(bool)$e->perms_required==true?"true":"false"?></span></li>

			    	<? if(!empty($e->parameters)){
					//sort params by field name
			    	usort($e->parameters,'sortParamsByField');
			    	foreach($e->parameters as $p){ ?>
						<li class="bullet-item">
							<div class="params">
							<div>
					      	<?
					      	if(isset($p->test)) {
					      		echo '<sup>t</sup>';
					      	}?>
					      	<strong>
					      	<?
					      	if(isset($p->value)) {
					      		if(isset($p->require))
					      			echo '<a href="#response" data-tooltip class="example" title="<b>Fields used:</b><br/>'.getParams($p,$e->parameters,true).'" data-params="'.getParams($p,$e->parameters).'" data-endpoint="'.$endpoint.'" data-version="'.$api_json->version.'">';
					      		else
					      			echo '<a href="#response" data-tooltip class="example" title="<b>Fields used:</b><br/>'.getParams($p,$e->parameters,true).'" data-params="'.getParams($p,$e->parameters).'" data-endpoint="'.$endpoint.'" data-version="'.$api_json->version.'">';
					      	}
					      	echo $p->field;
					      	if(isset($p->value))
					      		echo '</a>';
					      	?>
					      </strong></div>
					      <div><?=$p->desc?></div>
					  	  </div>
				    	</li>
					<? } } ?>
				</ul>
				<sup>t</sup> indicates query used for test
				</div>
			<? }?>
			</div>
		</div>
	</div>

	<footer class="row sticky">
		<div class="large-12 columns">
		<hr/>
		<div class="row">
		<div class="large-6 columns">
			<p>&copy; Copyright no one at all. Go to town.</p>
		</div>
		<div class="large-6 columns">
		<ul class="inline-list right">
			<li><a href="http://github.com/avantassel/avt-api-docs">AVT API Docs</a></li>
		</ul>
		</div>
		</div>
		</div>
	</footer>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.tooltip.js"></script>
  <script src="js/jsonformatter.min.js"></script>
  <script src="js/url.min.js"></script>
  <script src="js/api.js"></script>

  <script>
    $(document).foundation({
  tooltips: {
    selector : '.example',
    additional_inheritable_classes : [],
    tooltip_class : '.tooltip',
    touch_close_text: 'tap to close',
    disable_for_touch: false,
    tip_template : function (selector, content) {
      return '<span data-selector="' + selector + '" class="'
        + Foundation.libs.tooltips.settings.tooltipClass.substring(1)
        + '">' + content + '<span class="nub"></span></span>';
    }
  }
});
  </script>
</body>
</html>
