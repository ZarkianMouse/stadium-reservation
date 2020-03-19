
<table class="price-table">
	<caption class="head">Price Tiers</caption>
	<tr>
		<th class="th_row">Price</th>
		<th class="th_row">Section</th>
	</tr>
<?php
	$sql = "SELECT SeatPrice, SectionID FROM PriceTiers ORDER BY SeatPrice DESC, SectionID ASC";
        
if($result = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
				echo "<td>" . $row['SeatPrice'] . "</td>";
                echo "<td>" . $row['SectionID'] . "</td>";
                
            echo "</tr>";
        }
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    }
}

?>

</table>

