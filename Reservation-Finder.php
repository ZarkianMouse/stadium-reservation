<?php
$param_id = $_SESSION["id"];
?>
<form method="get" action="welcome.php" id="seat_select">
	<select name="psect_select" onchange="submit()">
		<option>Please select section</option>
			<?php
				$seat_qry = mysqli_query($conn,"SELECT DISTINCT SectionID FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY SectionID ASC");
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
				$row_qry = mysqli_query($conn,"SELECT DISTINCT RowID FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY RowID ASC");
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
				$price_qry = mysqli_query($conn,"SELECT DISTINCT SeatPrice FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' ORDER BY SeatPrice DESC");
				while($prow = mysqli_fetch_assoc($price_qry)) {
					echo "<option";
					if(isset($_REQUEST['price_select']) and $_REQUEST['price_select']==$prow['SeatPrice']) echo ' selected="selected"';
					echo "> {$prow['SeatPrice']}</option>\n";
				}
			?>
	</select>
</form>

<?php

	if(isset($_REQUEST['price_select'])  or isset($_REQUEST['row_select'])) {
			$condition	=	"";
			$hrf = "";
			
		if(isset($_GET['price_select']) and $_GET['price_select']!="")
		{
			$p = "&price_select=".$_GET['price_select'];
			$hrf .= $p;
			if ($_GET['price_select']!="Please select price ($)") {
			$condition		.=	"AND SeatPrice='".$_GET['price_select']."' ";
			}
		}
		if(isset($_GET['psect_select']) and $_GET['psect_select']!="")
		{
			
			$s = "&psect_select=".$_GET['psect_select'];
			$hrf .= $s;
			if ($_GET['psect_select']!="Please select section") {
			$condition		.= "AND SectionID = '".$_GET['psect_select']."' ";
			}
		}
		if(isset($_GET['row_select']) and $_GET['row_select']!="" and $_GET['row_select']!="Please select row")
		{
			$r = "&row_select=".$_GET['row_select'];
			$hrf .= $r;
			if($_GET['row_select']!="Please select row") {
			$condition		.= "AND RowID = '".$_GET['row_select']."'";
			}
		}
		
		
		echo "<span> the condition is $condition </span>";
		
		if (isset($_GET['reserve_pageno'])) {
            $reserve_pageno = $_GET['reserve_pageno'];
        } else {
            $reserve_pageno = 1;
        }
		$no_of_records_per_page = 10;
        $offset = ($reserve_pageno-1) * $no_of_records_per_page;


        $total_pages_sql = "SELECT COUNT(*) FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' $condition 
			ORDER BY SeatPrice DESC, SectionID ASC, RowID ASC";
        $result = mysqli_query($conn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);
	}
	else {
		$condition		=	"";
		
		if (isset($_GET['reserve_pageno'])) {
            $reserve_pageno = $_GET['reserve_pageno'];
        } else {
            $reserve_pageno = 1;
        }
		$no_of_records_per_page = 10;
        $offset = ($reserve_pageno-1) * $no_of_records_per_page;


        $total_pages_sql = "SELECT COUNT(*) FROM PriceTiers NATURAL JOIN Reservations WHERE UserID = '$param_id' $condition
			ORDER BY SeatPrice DESC, SectionID ASC, RowID ASC";
        $result = mysqli_query($conn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);
	}
?>


<table id="price-table" class="my-table">
	<caption class="head">Section Prices</caption>
	<thead>
		<tr>
			<th class="th_row">Event</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Row</th>
			<th class="th_row">Section</th>
			<th class="th_row">Price</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$sql = "SELECT SectionID, RowID, SeatPrice, SeatID, EventID 
					FROM PriceTiers 
					NATURAL JOIN Reservations 
					WHERE UserID = '$param_id' $condition
				    ORDER BY SeatPrice DESC, SectionID ASC, RowID ASC, SeatID ASC LIMIT $offset, $no_of_records_per_page";
				
			if($res_data = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_array($res_data)){
						echo "<tr class=\"clickable text-center\" 
						onclick=\"window.location= 'delete-reservation.php?EventID=".$row['EventID']."&SeatID=".$row['SeatID']."&RowID=".$row['RowID']."&SectionID=".$row['SectionID']."' \">";
							echo "<td> " . $row['EventID'] . "</td>";
							echo "<td> " . $row['SeatID'] . "</td>";
							echo "<td> " . $row['RowID'] . "</td>";
							echo "<td>" . $row['SectionID'] . "</td>";
							echo "<td> \$" . $row['SeatPrice'] . "</td>";
						echo "</tr>";
					}
				}
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th class="th_row">Event</th>
			<th class="th_row">Seat</th>
			<th class="th_row">Row</th>
			<th class="th_row">Section</th>
			<th class="th_row">Price</th>
		</tr>
		<tr>
			<td class="head" colspan=5><?php echo "Page $reserve_pageno out of $total_pages "?></td>
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