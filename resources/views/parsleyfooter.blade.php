
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
	  	var token = ($('#title').data('token'));
	  	var title = ($('#title').val());
	  	var response = false;
	  		$.ajax({
	  			url:'/articles/unique',
	  			type:'POST',
	  			async: false,
	  			data:{'title': title, _token: token},
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
	    .addMessage('title', 'That title has already been taken.');
	</script>