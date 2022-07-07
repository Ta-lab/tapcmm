<?php
			if(isset($_POST['submit']))
			{
				$con=mysqli_connect('localhost','root','Tamil','myppc');
				if(!$con)
					die(mysqli_error());
				$tdate = $_POST['tdate'];
				$shift = $_POST['shift'];
				$rcno = $_POST['rcno'];
				$pnum = $_POST['pnum'];
				$operation = $_POST['operation'];
				$pro = $_POST['pro'];
				$oid = $_POST['oid'];
				$groupid = $_POST['groupid'];
				$mid = $_POST['mid'];
				$lname=$_POST['lname'];
				$td=$_POST['td'];
				$reason=$_POST['reason'];
				$dept=$_POST['dept'];
				$count=count($lname);
				for($i=0;$i<$count;$i++)
					mysqli_query($con,"INSERT INTO d15 (date,shift,rcno,pnum,operation,oid,gid,mid,qtyproduced,type,time1,reason,dept) VALUES ('$tdate','$shift','$rcno','$pnum','$operation','$oid','$groupid','$mid','$pro','$lname[$i]','$td[$i]','$reason[$i]','$dept[$i]')");
			header("location: inputlink.php");
			}
			?>