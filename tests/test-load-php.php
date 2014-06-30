<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>test-load-page</title>
	<script type="text/javascript" src="../assets/lib/jquery-1.11.1.js"></script>
	<script type="text/javascript">
	$(function () {
		$('#search').on('click',function () {
			$('#load').load('search.php',{
						"a": "a",
						"b": "b"
					},function (data) {
						console.log(data);
					})
		});

		$('#search1').on('click',function () {
			$.get('search.php',{
						"a": "a",
						"b": "b"
					},function (data) {
						console.log(data);
					});
		});


	});

	</script>
</head>
<body>

<button id="search">search</button>
<div id="load">
</div>


<button id="search1">search1</button>
<div id="load1">
	
</div>
</body>
</html>