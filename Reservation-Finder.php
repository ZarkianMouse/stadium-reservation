<?php
	// pagination was derived from a tutorial on "My Programming Tutorials"
	// https://www.myprogrammingtutorials.com/create-pagination-with-php-and-mysql.html

	$param_id = $_SESSION["id"];

	if(isset($_REQUEST['r_price_select'])  or isset($_REQUEST['r_row_select']) or isset($_REQUEST['rsect_select']) ) {
			$res_condition	=	"";
			$hrf = "";
			
		if(isset($_GET['r_price_select']) and $_GET['r_price_select']!="")
		{
			$p = "&r_price_select=".$_GET['r_price_select'];
			$hrf .= $p;
			if ($_GET['r_price_select']!="Please select price ($)") {
			$res_condition		.=	"AND SeatPrice='".$_GET['r_price_select']."' ";
			}
		}
		if(isset($_GET['rsect_select']) and $_GET['rsect_select']!="")
		{
			
			$s = "&rsect_select=".$_GET['rsect_select'];
			$hrf .= $s;
			if ($_GET['rsect_select']!="Please select section") {
			$res_condition		.= "AND SectionID = '".$_GET['rsect_select']."' ";
			}
		}
		if(isset($_GET['r_row_select']) and $_GET['r_row_select']!="" and $_GET['r_row_select']!="Please select row")
		{
			$r = "&r_row_select=".$_GET['r_row_select'];
			$hrf .= $r;
			if($_GET['r_row_select']!="Please select row") {
			$res_condition		.= "AND RowID = '".$_GET['r_row_select']."'";
			}
		}
		
	}
	else {
		$res_condition		=	"";
	}
	
	if (isset($_GET['reserve_pageno'])) {
		$reserve_pageno = $_GET['reserve_pageno'];
	} else {
		$reserve_pageno = 1;
	}
	$no_of_records_per_page = 10;
	$offset = ($reserve_pageno-1) * $no_of_records_per_page;


	$total_pages_sql = "SELECT COUNT(*) FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' $res_condition
		ORDER BY SeatPrice DESC, SectionID ASC, RowID ASC";
	$result = mysqli_query($conn,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);
	if($total_pages == 0)
	{
		$reserve_pageno = 0;
	}
?>


<table id="price-table" class="my-table">
	<caption class="head" style="background-color: #2C5171">My Reservations</caption>
	<thead>
		<tr>
			<td class="head" colspan=6>
				<form method="get" action="welcome.php" id="seat_select" style="color: black">
					<select name="rsect_select" onchange="submit()">
						<option>Please select section</option>
							<?php
								$seat_qry = mysqli_query($conn,"SELECT DISTINCT SectionID FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY SectionID ASC");
								while($prow = mysqli_fetch_assoc($seat_qry)) {
									echo "<option";
									if(isset($_REQUEST['rsect_select']) and $_REQUEST['rsect_select']==$prow['SectionID']) echo ' selected="selected"';
									echo "> {$prow['SectionID']}</option>\n";
								}
							?>
					</select>
					<select id="r_row_select" name="r_row_select" onchange="submit()">
						<option>Please select row</option>
							<?php
								$row_qry = mysqli_query($conn,"SELECT DISTINCT RowID FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY RowID ASC");
								while($rrow = mysqli_fetch_assoc($row_qry)) {
									echo "<option";
									if(isset($_REQUEST['r_row_select']) and $_REQUEST['r_row_select']==$rrow['RowID']) echo ' selected="selected"';
									echo "> {$rrow['RowID']}</option>\n";
								}
							?>
					</select>
					<select id="r_price_select" name="r_price_select" onchange="submit()">
						<option>Please select price ($)</option>
							<?php
								$price_qry = mysqli_query($conn,"SELECT DISTINCT SeatPrice FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY SeatPrice DESC");
								while($prow = mysqli_fetch_assoc($price_qry)) {
									echo "<option";
									if(isset($_REQUEST['r_price_select']) and $_REQUEST['r_price_select']==$prow['SeatPrice']) echo ' selected="selected"';
									echo "> {$prow['SeatPrice']}</option>\n";
								}
							?>
					</select>
				</form>
			</td>
		</tr>
		<tr>
			<th class="th_row">Event Date</th>
			<th class="th_row">Name</th>
			<th class="th_row">Section</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Price</th>
			<th class="th_row"></th>
			
			
		</tr>
	</thead>
	<tbody>
		<?php
			$sql = "SELECT EventID, SectionID, RowID, SeatPrice, SeatID, EventName, EventDate
					FROM PriceTiers 
					NATURAL JOIN Reservations
					NATURAL JOIN Events
					WHERE UserID = '$param_id' $res_condition
				    ORDER BY EventDate ASC, SeatPrice DESC, SectionID ASC, RowID ASC, SeatID ASC LIMIT $offset, $no_of_records_per_page";
				
			if($res_data = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_array($res_data)){
						echo "<tr class=\"clickable text-center\" >";
							echo "<td> " . date("m/d/y",strtotime($row['EventDate'] )). "</td>";
							echo "<td> " . $row['EventName'] . "</td>";
							echo "<td>" . $row['SectionID'] . "</td>";
							echo "<td> " . $row['RowID'] .$row['SeatID'] . "</td>";
							echo "<td> \$" . $row['SeatPrice'] . "</td>";
							echo "<td><a class=\"btn btn-default\" href=\"delete-reservation.php?EventID=".$row['EventID']."&SeatID=".$row['SeatID']."&RowID=".$row['RowID']."&SectionID=".$row['SectionID']."\">Delete</a></td>";
							
							
						echo "</tr>";
					}
				}
				else {
					echo "<tr><td colspan=6 >No rows available</td></tr>";
				}
			}
			else {
				echo "<tr><td colspan=6 >Query Failed: No rows available</td></tr>";
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th class="th_row">Event Date</th>
			<th class="th_row">Name</th>
			<th class="th_row">Section</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Price</th>
			<th class="th_row"></th>
			
		</tr>
		<tr>
			<td class="head" colspan=6><?php echo "Page $reserve_pageno out of $total_pages "?></td>
		</tr>
		<tr>
			<td class="head" colspan=6 style="background-color: #2C5171; color: #2C5171">.</td>
		</tr>
	</tfoot>

</table>
 <ul class="pagination">
	<li><a href="?reserve_pageno=1<?php if($hrf != "" ){echo $hrf;} ?>">First</a></li>
	<li class="<?php if($reserve_pageno <= 1){ echo 'disabled'; } ?>">
		<a href="<?php if($reserve_pageno <= 1){ echo '#'; } elseif($hrf != "" ){echo "?reserve_pageno=".($reserve_pageno - 1).$hrf;} else { echo "?reserve_pageno=".($reserve_pageno - 1); } ?>">Prev</a>
	</li>
	<li class="<?php if($reserve_pageno >= $total_pages){ echo 'disabled'; } ?>">
		<a href="<?php if($reserve_pageno >= $total_pages){ echo '#'; }elseif($hrf != "" ){echo "?reserve_pageno=".($reserve_pageno + 1).$hrf;} else { echo "?reserve_pageno=".($reserve_pageno + 1); } ?>">Next</a>
	</li>
	<li><a href="?reserve_pageno=<?php if($hrf != "" ){echo $total_pages.$hrf; }else {echo $total_pages;} ?>">Last</a></li>
</ul>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"> 
</script> 
<script src= 
 "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"> 
</script>