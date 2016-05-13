@if(isset($query_words))
	<script type="text/javascript">
		$.expr[":"].icontains = $.expr.createPseudo(function(arg) {
		    return function(e) {
		        return $(e).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		    };
		});
		
		$('.articleBody').find('span.marker').removeClass('marker');

		$('.panel-body[name="panelbody"]').each(function() {
			$(this).html($(this).html().trim());
		});
		
		
		
	</script>
@endif