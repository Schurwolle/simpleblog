
    <script type="text/javascript">

        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>'
        };
    </script>
    {{Html::script('/parsley.min.js')}}
    <script type="text/javascript">
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
	    });
	</script>