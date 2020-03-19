<table class="seat-table">
	<caption class="head">Seats</caption>
	<tr>
		<th class="th_row">Section</th>
		<th class="th_row">Row</th>
		<th class="th_row">Seat</th>
	</tr>
<?php
	$sql = "SELECT SectionID, RowID, SeatID FROM Seats WHERE UserID is NULL ORDER BY SectionID ASC, RowID ASC ";
        
if($result = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
				
                echo "<td>" . $row['SectionID'] . "</td>";
				echo "<td>" . $row['RowID'] . "</td>";
				echo "<td>" . $row['SeatID'] . "</td>";
                
            echo "</tr>";
        }
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    }
}

?>

</table>

