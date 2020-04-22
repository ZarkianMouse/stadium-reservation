<?php
	$hrf="";
	// for current page in table
	if (isset($_GET['event_pageno'])) {
		$event_pageno = $_GET['event_pageno'];
	} else {
		$event_pageno = 1;
	}
	
	// for limiting number of entries in table
	$no_of_records_per_page = 10;
	
	// for monitoring page in table
	$event_offset = ($event_pageno-1) * $no_of_records_per_page;

	// for counting total number of entries in table based on condition
	$total_event_pages_sql = "SELECT COUNT(*)
						FROM Events
						ORDER BY EventDate ASC, EventName ASC";
	$event_result = mysqli_query($conn,$total_event_pages_sql);
	$total_event_rows = mysqli_fetch_array($event_result)[0];
	$total_event_pages = ceil($total_event_rows / $no_of_records_per_page);
	if($total_event_pages == 0)
	{
		$event_pageno = 0;
	}
?>

<table id="event-table" class="my-table">
	<caption class="head">Events</caption>
	<thead>
		<tr>
			<th class="th_row">Event Date</th>
			<th class="th_row">Name</th>
			<th class="th_row">Description</th>
			<th class="th_row"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$event_sql ="SELECT *
					FROM Events
					ORDER BY EventDate ASC, EventName ASC
					LIMIT $event_offset, $no_of_records_per_page";
				
			if($event_res_data = mysqli_query($conn, $event_sql)){
				if(mysqli_num_rows($event_res_data) > 0){
					while($event_row = mysqli_fetch_array($event_res_data)){
						echo "<tr class=\"text-center\">";
							echo "<td> " . date("m/d/y",strtotime($event_row['EventDate'] )). "</td>";
							echo "<td> " . $event_row['EventName'] . "</td>";
							echo "<td>" . $event_row['EventDescrip'] . "</td>";
							echo "<td><a class=\"btn btn-default\" href=\"welcome.php?EventID=".$event_row['EventID']."\">Select</a></td>";
						echo "</tr>";
					}
				}
				else {
					echo "<tr><td colspan=4 >No rows available</td></tr>";
				}
			}
			else {
				echo "<tr><td colspan=4>Query Failed: No rows available</td></tr>";
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th class="th_row">Event Date</th>
			<th class="th_row">Name</th>
			<th class="th_row">Description</th>
			<th class="th_row"></th>
		</tr>
		<tr>
			<td class="head" colspan=4><?php echo "Page $event_pageno out of $total_event_pages "?></td>
		</tr>
	</tfoot>

</table>
 <ul class="pagination">
	<li><a href="?event_pageno=1<?php if($hrf != "" ){echo $hrf;} ?>">First</a></li>
	<li class="<?php if($event_pageno <= 1){ echo 'disabled'; } ?>">
		<a href="<?php if($event_pageno <= 1){ echo '#'; } elseif($hrf != "" ){echo "?event_pageno=".($event_pageno - 1).$hrf;} else { echo "?event_pageno=".($event_pageno - 1); } ?>">Prev</a>
	</li>
	<li class="<?php if($event_pageno >= $total_event_pages){ echo 'disabled'; } ?>">
		<a href="<?php if($event_pageno >= $total_event_pages){ echo '#'; }elseif($hrf != "" ){echo "?event_pageno=".($event_pageno + 1).$hrf;} else { echo "?event_pageno=".($event_pageno + 1); } ?>">Next</a>
	</li>
	<li><a href="?event_pageno=<?php if($hrf != "" ){echo $total_event_pages.$hrf; }else {echo $total_event_pages;} ?>">Last</a></li>
</ul>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"> 
</script> 
<script src= 
 "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"> 
</script>