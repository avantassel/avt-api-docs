<div class="contain-to-grid sticky">
<nav class="top-bar" data-topbar data-options="sticky_on: large">
  <ul class="title-area">
    <!-- Title Area -->
    <li class="name">
      <h1><a href="/v<?=$api_json->version?>/docs"><?=$api_json->name?>  <?=$api_json->version?></a></h1>
    </li>
    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span></span></a></li>
  </ul>
  <section class="top-bar-section">
    <!-- Left Nav Section -->
    <ul class="left">
      <li class="divider"></li>
      <li class="has-dropdown"><a href="/<?=$api_json->version?>/docs/">Versions</a>
        <ul class="dropdown">
          <? if(!empty($api_json->versions)){
              foreach($api_json->versions as $v){ 
                ?>
                <li><a href="/<?=$v?>/docs/"><?=$v?></a></li>
          <? } } ?>
        </ul>
      </li>
      <li class="divider"></li>
      
      <li class="has-dropdown"><a href="/<?=$api_json->version?>/docs/">Environments</a>
        <ul class="dropdown">
          <? if(!empty($api_json->env)){
              foreach($api_json->env as $e){ 
                ?>
                <li><a href="<?=str_replace('[version]', $api_json->version, $e->url)?>/docs/"><?=$e->name?></a></li>
          <? } } ?>
        </ul>
      </li>
      <li class="divider"></li>

      <li class="has-dropdown"><a href="/<?=$api_json->version?>/docs/">Endpoints</a>

        <ul class="dropdown">
          <? if(!empty($api_json->endpoints)){
              usort($api_json->endpoints,'sortEndpointsByName');
              foreach($api_json->endpoints as $e){ 
                $endpoint = str_replace('/', '-', $e->name);
                ?>
                <li><a class="explore-endpoint" href="#"><?=$endpoint?></a></li>
          <? } } ?>
        </ul>
      </li>
      
    	<li class="divider"></li>
    
    </ul>
    <ul class="right">
      <li class="has-form">
          <a class="button" href="mailto:<?=$api_json->contact->email?>?subject=<?=$api_json->name?>">Contact Us</a>
      </li>
    </ul>
    </section>
</nav>
</div>