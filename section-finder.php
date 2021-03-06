<?php
	// pagination was derived from a tutorial on "My Programming Tutorials"
	// https://www.myprogrammingtutorials.com/create-pagination-with-php-and-mysql.html


	// sets value of $event to the one passed from event-selector.php
	if (isset($_GET['EventID']) and $_GET['EventID']!="")
	{
		$event = $_GET['EventID'];
	}
	else
	{
		$event = 2;
	}
	
	// This statement evaluates what form fields were set
	if(isset($_REQUEST['price_select'])  or isset($_REQUEST['row_select']) or isset($_REQUEST['psect_select']) ) {
			$search_condition	=	"";
			
		
		$hrf = "";
		// an example event number for testing
		if (isset($_GET['EventID']) and $_GET['EventID']!="")
		{
			$event = $_GET['EventID'];
			$hrf .= "&EventID=".$_GET['EventID'];
		}
		else {
			$event = 2;
		}
		
		// check field for SeatPrice
		if(isset($_GET['price_select']) and $_GET['price_select']!="")
		{
			$p = "&price_select=".$_GET['price_select'];
			$hrf .= $p;
			if ($_GET['price_select']!="Please select price ($)") {
			$search_condition		.=	"AND SeatPrice='".$_GET['price_select']."' ";
			}
		}
		
		// check field for SectionID
		if(isset($_GET['psect_select']) and $_GET['psect_select']!="")
		{
			
			$s = "&psect_select=".$_GET['psect_select'];
			$hrf .= $s;
			if ($_GET['psect_select']!="Please select section") {
			$search_condition		.= "AND SectionID = '".$_GET['psect_select']."' ";
			}
		}
		
		// check field for RowID
		if(isset($_GET['row_select']) and $_GET['row_select']!="" and $_GET['row_select']!="Please select row")
		{
			$r = "&row_select=".$_GET['row_select'];
			$hrf .= $r;
			if($_GET['row_select']!="Please select row") {
			$search_condition		.= "AND RowID = '".$_GET['row_select']."'";
			}
		}
		
		// check if string is empty
		$search_condition = "WHERE ".ltrim($search_condition,"AND");
		if ($search_condition == "WHERE ")
		{
			$search_condition = "";
		}
	}
	// if no form fields are set, empty condition
	else {
		$search_condition		=	"";
	}
	
	
	
	// for current page in table
	if (isset($_GET['price_pageno'])) {
		$price_pageno = $_GET['price_pageno'];
	} else {
		$price_pageno = 1;
	}
	
	// for limiting number of entries in table
	$no_of_records_per_page = 10;
	
	// for monitoring page in table
	$offset = ($price_pageno-1) * $no_of_records_per_page;

	// for counting total number of entries in table based on condition
	$total_pages_sql = "SELECT COUNT(*)
						FROM (SELECT pt.SeatPrice, pt.SectionID, s.RowID, s.SeatID
								FROM PriceTiers pt 
								NATURAL JOIN Seats s
								LEFT OUTER JOIN Reservations r 
								ON s.RowID = r.RowID AND s.SeatID = r.SeatID AND s.SectionID = r.SectionID 
								WHERE r.ResNo NOT IN 
									(SELECT ResNo FROM Reservations WHERE (SectionID,SeatID,RowID) IN
										(SELECT SectionID, SeatID, RowID FROM Reservations WHERE ResNo IN 
											(SELECT ResNo FROM Reservations WHERE EventID = '$event')))
								OR r.EventID is NULL
								ORDER BY pt.SeatPrice DESC, pt.SectionID ASC, s.RowID ASC, s.SeatID ASC) EventPriceTable 
						$search_condition"; // search_condition = WHERE CLAUSE
	
	
	// runs sql to find total number of pages/entries in table
	$result = mysqli_query($conn,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);
	if($total_pages == 0)
	{
		$price_pageno = 0;
	}
?>

<style>
	.my_other {
		border-color: #2C5171;
		color: white;
		background-color: #2C5171;
		width: 100%;
	}

	.my_other:hover {
		background-color: #2C5171;
		color: white;
	}
</style>

<table id="price-table" class="my-table">
	<caption class="head" style="background-color: #2C5171">
			<?php
				$even_sql ="SELECT EventName,EventDate FROM Events WHERE EventID = '$event'";
				if($even = mysqli_query($conn, $even_sql)){
						if(mysqli_num_rows($even) > 0){
							$row = mysqli_fetch_array($even);
							echo "<span style=\"font-size: 25px;\">".date("m/d/y",strtotime($row['EventDate'] ))."</span>";
							
							echo "<br/><b>".$row['EventName']."</b>";
							
						}
				}
			?>
		
		
	</caption>
	<thead>
		
		<tr>
			<td class="head" colspan=4>
				<form method="get" action="welcome.php" id="seat_select" style="color: black">
					<input name="EventID" value="<?php echo $event; ?>" hidden>
					<select name="psect_select" onchange="submit()">
						<option>Please select section</option>
							<?php
								$seat_sql = "SELECT DISTINCT pt.SectionID FROM PriceTiers pt 
											  JOIN Seats s ON pt.SectionID = s.SectionID 
											  LEFT OUTER JOIN Reservations r 
											  ON s.RowID = r.RowID AND s.SeatID = r.SeatID AND s.SectionID = r.SectionID 
											  WHERE r.ResNo NOT IN 
													(SELECT ResNo FROM Reservations WHERE (SectionID,SeatID,RowID) IN
														(SELECT SectionID, SeatID, RowID FROM Reservations WHERE ResNo IN 
															(SELECT ResNo FROM Reservations WHERE EventID = '$event')))
												OR r.EventID is NULL 
											  ORDER BY pt.SectionID ASC";
								$seat_qry = mysqli_query($conn,$seat_sql);
								while($prow = mysqli_fetch_assoc($seat_qry)) {
									echo "<option";
									if(isset($_REQUEST['psect_select']) and $_REQUEST['psect_select']==$prow['SectionID']) echo ' selected="selected"';
									echo "> {$prow['SectionID']}</option>\n";
								}
							?>
					</select>
					<select id="row_select" name="row_select" onchange="submit()">
						<option>Please select row</option>
							<?php
								$row_sql = "SELECT DISTINCT s.RowID FROM PriceTiers pt 
											  JOIN Seats s ON pt.SectionID = s.SectionID 
											  LEFT OUTER JOIN Reservations r 
											  ON s.RowID = r.RowID AND s.SeatID = r.SeatID AND s.SectionID = r.SectionID 
											  WHERE r.ResNo NOT IN 
													(SELECT ResNo FROM Reservations WHERE (SectionID,SeatID,RowID) IN
														(SELECT SectionID, SeatID, RowID FROM Reservations WHERE ResNo IN 
															(SELECT ResNo FROM Reservations WHERE EventID = '$event')))
												OR r.EventID is NULL  
											  ORDER BY s.RowID ASC";
								$row_qry = mysqli_query($conn,$row_sql);
								while($rrow = mysqli_fetch_assoc($row_qry)) {
									echo "<option";
									if(isset($_REQUEST['row_select']) and $_REQUEST['row_select']==$rrow['RowID']) echo ' selected="selected"';
									echo "> {$rrow['RowID']}</option>\n";
								}
							?>
					</select>
					<select id="price_select" name="price_select" onchange="submit()">
						<option>Please select price ($)</option>
							<?php
								$price_sql = "SELECT DISTINCT SeatPrice FROM PriceTiers pt 
											  NATURAL JOIN Seats s
											  LEFT OUTER JOIN Reservations r 
											  ON s.RowID = r.RowID AND s.SeatID = r.SeatID AND s.SectionID = r.SectionID 
											  WHERE r.ResNo NOT IN 
													(SELECT ResNo FROM Reservations WHERE (SectionID,SeatID,RowID) IN
														(SELECT SectionID, SeatID, RowID FROM Reservations WHERE ResNo IN 
															(SELECT ResNo FROM Reservations WHERE EventID = '$event')))
												OR r.EventID is NULL 
											 ORDER BY SeatPrice DESC";
								$price_qry = mysqli_query($conn,$price_sql);
								while($prow = mysqli_fetch_assoc($price_qry)) {
									echo "<option";
									if(isset($_REQUEST['price_select']) and $_REQUEST['price_select']==$prow['SeatPrice']) echo ' selected="selected"';
									echo "> {$prow['SeatPrice']}</option>\n";
								}
							?>
					</select>
				</form>
			</td>
		</tr>
		<tr>
			<th class="th_row">Section</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Price</th>
			<th class="th_row"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$sql ="SELECT SeatPrice, SectionID, RowID, SeatID
					FROM (SELECT pt.SeatPrice, pt.SectionID, s.RowID, s.SeatID
								FROM PriceTiers pt 
								NATURAL JOIN Seats s
								LEFT OUTER JOIN Reservations r 
								ON s.RowID = r.RowID AND s.SeatID = r.SeatID AND s.SectionID = r.SectionID 
								WHERE r.ResNo NOT IN 
									(SELECT ResNo FROM Reservations WHERE (SectionID,SeatID,RowID) IN
										(SELECT SectionID, SeatID, RowID FROM Reservations WHERE ResNo IN 
											(SELECT ResNo FROM Reservations WHERE EventID = '$event')))
								OR r.EventID is NULL
								ORDER BY pt.SeatPrice DESC, pt.SectionID ASC, s.RowID ASC, s.SeatID ASC) EventPriceTable 
					$search_condition
					LIMIT $offset, $no_of_records_per_page";
				
			if($res_data = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($res_data) > 0){
					while($row = mysqli_fetch_array($res_data)){
						echo "<tr class=\"clickable text-center\">";
							echo "<td>" . $row['SectionID'] . "</td>";
							echo "<td> " . $row['RowID'] . $row['SeatID'] . "</td>";
							echo "<td>$" . $row['SeatPrice'] . "</td>";
							echo "<td><a class=\"btn btn-default\" href=\"Seat-Reservation.php?EventID=$event&SeatID=".$row['SeatID']."&RowID=".$row['RowID']."&SectionID=".$row['SectionID']."\">Select</a></td>";
						echo "</tr>";
					}
				}
				else {
					echo "<tr><td colspan=4>No rows available</td></tr>";
				}
			}
			else {
				echo "<tr><td colspan=4>Query Failed: No rows available</td></tr>";
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th class="th_row">Section</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Price</th>
			<th class="th_row"></th>
		</tr>
		
		<tr>
			<td class="head" colspan=4><?php echo "Page $price_pageno out of $total_pages "?></td>
		</tr>
		<tr>
			<td class="head" colspan=4><a href="welcome.php" class="btn btn-default my_other" style="">Change Event</a></td>
		</tr>
	</tfoot>

</table>
 <ul class="pagination">
	<li><a href="?price_pageno=1<?php if($hrf != "" ){echo $hrf;} ?>">First</a></li>
	<li class="<?php if($price_pageno <= 1){ echo 'disabled'; } ?>">
		<a href="<?php if($price_pageno <= 1){ echo '#'; } elseif($hrf != "" ){echo "?price_pageno=".($price_pageno - 1).$hrf;} else { echo "?price_pageno=".($price_pageno - 1); } ?>">Prev</a>
	</li>
	<li class="<?php if($price_pageno >= $total_pages){ echo 'disabled'; } ?>">
		<a href="<?php if($price_pageno >= $total_pages){ echo '#'; }elseif($hrf != "" ){echo "?price_pageno=".($price_pageno + 1).$hrf;} else { echo "?price_pageno=".($price_pageno + 1); } ?>">Next</a>
	</li>
	<li><a href="?price_pageno=<?php if($hrf != "" ){echo $total_pages.$hrf; }else {echo $total_pages;} ?>">Last</a></li>
</ul>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"> 
</script> 
<script src= 
 "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"> 
</script>

