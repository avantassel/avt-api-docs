$(document).ready(function(){
	
	$('.explore-endpoint').on('click',function(){
		$('div.endpoint').hide();
		$('div.endpoint.'+$(this).html()).toggle();
		$('.api-response').empty();
		$('.api-curl').empty();
		$('#go-'+$(this).html()).click();
		return false;
	});
	
	$('div.endpoints').on('click','a.example',function(){
		var version = $(this).data('version');
		var endpoint = $(this).data('endpoint');
		var params = $(this).data('params');
		$('#params-'+endpoint).val(params);

		$('#go-'+endpoint).click();
	});
	
	$('.params').on('keypress',function(e){
		if (!e) e = window.event;
		var keyCode = e.keyCode || e.which;
	    if (keyCode == '13'){
	    	$('#go-'+$(this).data('endpoint')).click();
	    }	    
	});
	
	function updateParameter(uri, key, value) {
	   return uri + "&" + key + "=" + value;	  
	}

	$('div.endpoints').on('click','a.call-api',function(){
		var params = 'app=Client&'; 
		
		var method = ($(this).data('method').length)?$(this).data('method'):'GET';
		var version = $(this).data('version');
		var endpoint = $(this).data('endpoint');
		
		params += $('#params-'+endpoint).val();

		//update endpoint with version
		if(version != '')
			endpoint = version+'/'+endpoint;
		
		if(typeof $(this).data('offset') !== 'undefined')
			params = updateParameter(params,'offset',$(this).data('offset'));
		if(typeof $(this).data('limit') !== 'undefined')
			params = updateParameter(params,'limit',$(this).data('limit'));
			
		if(method=='GET'){
			$('.api-curl').html('curl -s "<a target="_blank" href="http://'+document.location.host+'/'+endpoint.replace('-','/')+'/?'+params+'">http://'+document.location.host+'/'+endpoint.replace('-','/')+'/?'+params+'</a>"');
		} else {
			$('.api-curl').html('curl --data "'+params+'" http://'+document.location.host+'/'+endpoint.replace('-','/')+'/');
		}
		$('div.curl').show();

		$('div.response').show().find('h4').html('Loading...');
		$('.api-response').html('');
		var start_time = new Date().getTime();
		
		//remove paging
		$('ul.pagination').remove();

		$.ajax({
			type: method
			,url: 'http://'+document.location.host+'/'+endpoint.replace('-','/')+'/'
			,data: params
			,dataType: "json"
			}).always(function(data){
			
			var request_time = new Date().getTime() - start_time;

			$('.api-response').html(JSONFormatter.objectToHTML(data));
			
			$('div.response').show().find('h4').html('Response in '+(request_time/1000)+'s');			
			
			//setup paging
			if(data.response && data.response.paging){				
				var paging=data.response.paging;
				if(paging.limit != -1 && paging.limit < paging.total){
					var page_html = '<ul class="pagination">'
						,totalPages=Math.round(paging.total/paging.limit)
						,pageLimit=totalPages>5?5:totalPages;

					for(var p=0;p<pageLimit;p++){
						if(paging.offset==(p*paging.limit))
							page_html += '<li class="current"><a href="#" class="has-tip call-api" data-method="'+method+'" data-tooltip data-endpoint="'+endpoint+'" data-offset="'+p*paging.limit+'" data-limit="'+paging.limit+'" title="Page '+p+'">'+p+'</a></li>';
						else
							page_html += '<li><a href="#" class="has-tip call-api" data-tooltip  data-method="'+method+'" data-endpoint="'+endpoint+'" data-offset="'+p*paging.limit+'" data-limit="'+paging.limit+'" title="Page '+p+'">'+p+'</a></li>';
					}
					if(totalPages>5){
						page_html += '<li>...</li>';
						for(var p=(totalPages-pageLimit);p<totalPages;p++){
							if(paging.offset==(p*paging.limit))
								page_html += '<li class="current"><a href="#" class="has-tip call-api"  data-method="'+method+'" data-tooltip data-endpoint="'+endpoint+'" data-offset="'+p*paging.limit+'" data-limit="'+paging.limit+'" title="Page '+p+'">'+p+'</a></li>';
							else
								page_html += '<li><a href="#" class="has-tip call-api" data-tooltip  data-method="'+method+'" data-endpoint="'+endpoint+'" data-offset="'+p*paging.limit+'" data-limit="'+paging.limit+'" title="Page '+p+'">'+p+'</a></li>';
						}
					}
					page_html += '</ul>';
					$('div.response').append(page_html);
					$('div.response').prepend(page_html);
				}
			}
		});
		return false;
	});
	
});