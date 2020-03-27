<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>"id="seat_select">
	<caption></caption>
	<select name="seat_select" onchange="submit()">
		<option>Please select section</option>
			<?php
				$seat_qry = mysqli_query($conn,'SELECT DISTINCT SectionID FROM Seats WHERE UserID is NULL ORDER BY SectionID ASC');
				while($prow = mysqli_fetch_assoc($seat_qry)) {
					echo "<option";
					if(isset($_REQUEST['seat_select']) and $_REQUEST['seat_select']==$prow['SectionID']) echo ' selected="selected"';
					echo "> {$prow['SectionID']}</option>\n";
				}
			?>
	</select>
</form>

<?php
	if(isset($_REQUEST['seat_select'])) {
		$condition		=	"";
		if(isset($_GET['seat_select']) and $_GET['seat_select']!="")
		{
			$condition		.=	"AND SectionID = '".$_GET['seat_select']."'";
		}
		
		if (isset($_GET['seats_pageno'])) {
			$seats_pageno = $_GET['seats_pageno'];
		} else {
			$seats_pageno = 1;
		}
		$no_of_records_per_page = 10;
		$offset = ($seats_pageno-1) * $no_of_records_per_page;

		$total_pages_sql = "SELECT COUNT(*) FROM Seats WHERE UserID is NULL $condition ORDER BY SectionID ASC";
		$result = mysqli_query($conn,$total_pages_sql);
		$total_rows = mysqli_fetch_array($result)[0];
		$total_pages = ceil($total_rows / $no_of_records_per_page);
	}
	else {
		$condition		=	"";
		$row_cond = "";
		if (isset($_GET['seats_pageno'])) {
			$seats_pageno = $_GET['seats_pageno'];
		} else {
			$seats_pageno = 1;
		}
		$no_of_records_per_page = 10;
		$offset = ($seats_pageno-1) * $no_of_records_per_page;


		$total_pages_sql = "SELECT COUNT(*) FROM Seats WHERE UserID  is NULL $condition ORDER BY SectionID ASC";
		$result = mysqli_query($conn,$total_pages_sql);
		$total_rows = mysqli_fetch_array($result)[0];
		$total_pages = ceil($total_rows / $no_of_records_per_page);
	}
?>


<table id="seats-table" class="my-table"  cellspacing="0">
	<caption class="head">Seats</caption>
	<thead>
		<tr>
			<th class="th_row">Seat</th>
			<th class="th_row">Row</th>
			<th class="th_row">Section</th>
		</tr>
	</thead>
	<tbody>
<?php
	$sql = "SELECT SectionID, RowID, SeatID FROM Seats WHERE UserID  is NULL $condition
		ORDER BY SectionID ASC, RowID ASC, SeatID ASC LIMIT $offset, $no_of_records_per_page";
        
if($res_data = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($res_data) > 0){
		
        while($row = mysqli_fetch_array($res_data)){
            echo "<tr>";
				echo "<td>" . $row['SeatID'] . "</td>";
				echo "<td>" . $row['RowID'] . "</td>";
                echo "<td>" . $row['SectionID'] . "</td>";
                
            echo "</tr>";
        }
    }
}

?>
	</tbody>
	<tfoot>
		<tr>
			<th class="th_row">Seat</th>
			<th class="th_row">Row</th>
			<th class="th_row">Section</th>
		</tr>
		<tr>
			<td class="head" colspan=3><?php echo "$seats_pageno out of $total_pages pages"?></td>
		</tr>
	</tfoot>
</table>

 <ul class="pagination">
	<li><a href="?seats_pageno=1">First</a></li>
	<li class="<?php if($seats_pageno <= 1){ echo 'disabled'; } ?>">
		<a href="<?php if($seats_pageno <= 1){ echo '#'; } else { echo "?seats_pageno=".($seats_pageno - 1); } ?>">Prev</a>
	</li>
	<li class="<?php if($seats_pageno >= $total_pages){ echo 'disabled'; } ?>">
		<a href="<?php if($seats_pageno >= $total_pages){ echo '#'; } else { echo "?seats_pageno=".($seats_pageno + 1); } ?>">Next</a>
	</li>
	<li><a href="?seats_pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>