<html>
<head>
	<title>INFO</title>
</head>
<body>
<script>
	function callingfunction()
	{
		var n = document.getElementById("mytable").rows.length;
		for(i=1;i<n;i++)
		{
			var x = document.getElementById("mytable").rows[i].cells;
			alert(x[0].innerHTML);
			alert(x[1].innerHTML);
		}
	}
</script>
STATUS : <progress id="myProgress" value="11" max="100">
</progress>
	<table id="mytable">
	 <tr>
	  <th>Name</th>
	  <th>Favorite</th>
	 </tr>
	 <tr>
	  <td>Bob</td>
	  <td>Yellow</td>
	 </tr>
	 <tr>
	  <td>Michelle</td>
	  <td>Purple</td>
	 </tr>
	</table>
	<input type="button" name="but" id="but" onclick="callingfunction();">
</body>
</html>
<?php

?>