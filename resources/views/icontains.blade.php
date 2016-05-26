<script type="text/javascript">
	$.expr[":"].icontains = $.expr.createPseudo(function(arg) {
	    return function(e) {
	        return $(e).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
	    };
	});
	var queryWords = {!!json_encode($query_words)!!}
	var query = {!!json_encode(preg_quote(htmlentities($query)))!!}
	queryWords.unshift(query);

	for(var i=0; i<queryWords.length;i++)
	{	
		$(".panel-body[name='panelbody']:icontains(<span style='background-color:#FFFF00'>), h1>a:icontains(<span style='background-color:#FFFF00'>), h1#articleTitle:icontains(<span style='background-color:#FFFF00'>)").each(function(){

			var regex = new RegExp("(&lt;span style='background-color:#FFFF00'&gt;)(" +queryWords[i]+ ")(&lt;/span&gt;)", "gi")
			$(this).html($(this).html().replace(regex, "<span class='marker'>$2</span>"));
		});
	}
</script>