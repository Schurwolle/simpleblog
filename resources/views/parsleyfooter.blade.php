
    <script type="text/javascript">

        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>'
        };
    </script>
    {{Html::script('/parsley.min.js')}}
    <script type="text/javascript">
    var old_matches = "";
	var response = true;

	window.Parsley
	  .addValidator('unique', function(value, requirement) {
	  	var response = false;
	  		$.ajax({
	  			url:'/unique',
	  			type:'POST',
	  			async: false,
	  			data:{
	  					'value': value, 
	  					'oldValue': requirement.substring(requirement.indexOf('#')+1), 
	  					'column':requirement.split('#')[0].split('$')[1],
	  					'table':requirement.split('$')[0],
	  				},
	  			success: function(unique){
	  				if(unique === 'true')
	  				{
	  					response = true;
	  				} else {
	  					response = false;
	  				}
	  			}
	  		});
	  		return response;
	    })
	  .addValidator('ckeimgs', function(value, requirement){
	  	var regex = new RegExp('<a href="[^<>"]*"[^<>]*><img [^<>]*src="[^<>"]*"[^<>]*/></a>', 'g');
	  	var matches = value.match(regex);
  		if(matches.toString() != old_matches.toString())
  		{
  			old_matches = matches;
		  	if(matches != null)
		  	{
		  		$.ajax({
		  			url: '/validateCKEImages',
		  			type: 'POST',
		  			async: false,
		  			data:{
		  				'body': value,
		  				_token: requirement,
		  			},
		  			success: function(validation){
		  				if(validation == 'false')
		  				{
		  					response = false;
		  				} else {
		  					response = true;
		  				}
		  			}
			  	});
			}
  		}
		return response;
	  });
	</script>