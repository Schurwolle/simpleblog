@if(isset($query_words))
	<script type="text/javascript">
		$.expr[":"].icontains = $.expr.createPseudo(function(arg) {
		    return function(e) {
		        return $(e).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		    };
		});
		
		$('#articleBody').find('span.marker').removeClass('marker');

		$('.panel-body[name="panelbody"]').each(function() {
			$(this).html($(this).html().trim());
		});
		var queryWords = {!!json_encode($query_words)!!}
		for(var i=0; i<queryWords.length;i++)
		{	
			$('.panel-body[name="panelbody"], h1[class!="sectionHeading"]:icontains("' +queryWords[i]+ '")').each(function(){
				var regex = new RegExp("(" +queryWords[i]+ ")", "gi")
				$(this).html($(this).html().replace(regex, "<span class='marker'>$1</span>"));
			});
		}
		
	</script>
@endif