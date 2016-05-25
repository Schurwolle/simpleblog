<script type="text/javascript">
	$.expr[":"].icontains = $.expr.createPseudo(function(arg) {
	    return function(e) {
	        return $(e).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
	    };
	});
	var queryWords = {!!json_encode($query_words)!!}
	var query = {!!json_encode(htmlentities($query))!!}
	queryWords.unshift(query);

	for(var i=0; i<queryWords.length;i++)
	{	
		$('.panel-body[name="panelbody"], h1>a, h1#articleTitle:icontains("' +queryWords[i]+ '")').each(function(){
			var regex = new RegExp("(&lt;span style='background-color:#FFFF00'&gt;)(" +queryWords[i]+ ")(&lt;/span&gt;)", "gi")
			$(this).html($(this).html().replace(regex, "<span class='marker'>$2</span>"));
		});
	}
</script>