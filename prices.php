
<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>"id="price_select">
	<select name="price_select" onchange="submit()">
		<option>Please select price</option>
			<?php
				$price_qry = mysqli_query($conn,'SELECT DISTINCT SeatPrice FROM PriceTiers ORDER BY SeatPrice DESC');
				while($prow = mysqli_fetch_assoc($price_qry)) {
					echo "<option";
					if(isset($_REQUEST['price_select']) and $_REQUEST['price_select']==$prow['SeatPrice']) echo ' selected="selected"';
					echo ">{$prow['SeatPrice']}</option>\n";
				}
			?>
	</select>
</form>

<?php
	if(isset($_REQUEST['price_select'])) {
		$condition		=	"";
		if(isset($_GET['price_select']) and $_GET['price_select']!="")
		{
			$condition		.=	"WHERE SeatPrice='".$_GET['price_select']."'";
		}
		
		if (isset($_GET['price_pageno'])) {
            $price_pageno = $_GET['price_pageno'];
        } else {
            $price_pageno = 1;
        }
		$no_of_records_per_page = 10;
        $offset = ($price_pageno-1) * $no_of_records_per_page;


        $total_pages_sql = "SELECT COUNT(*) FROM PriceTiers $condition ORDER BY SeatPrice DESC, SectionID ASC";
        $result = mysqli_query($conn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);
		
	}
	else {
		$condition		=	"";
		
		if (isset($_GET['price_pageno'])) {
            $price_pageno = $_GET['price_pageno'];
        } else {
            $price_pageno = 1;
        }
		$no_of_records_per_page = 10;
        $offset = ($price_pageno-1) * $no_of_records_per_page;


        $total_pages_sql = "SELECT COUNT(*) FROM PriceTiers $condition ORDER BY SeatPrice DESC, SectionID ASC";
        $result = mysqli_query($conn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);
	}
	
?>

<table id="price-table" class="my-table">
	<caption class="head">Section Prices</caption>
	<thead>
		<tr>
			<th class="th_row">Section</th>
			<th class="th_row">Price</th>
		</tr>
	</thead>
<?php
	$sql = "SELECT SeatPrice ,SectionID FROM PriceTiers $condition ORDER BY SeatPrice DESC, SectionID ASC LIMIT $offset, $no_of_records_per_page";
        
if($res_data = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($res_data)){
            echo "<tr>";
				echo "<td>" . $row['SectionID'] . "</td>";
				echo "<td>" . $row['SeatPrice'] . "</td>";
            echo "</tr>";
        }
    }
}

?>
	<tfoot>
		<tr>
			<th class="th_row">Section</th>
			<th class="th_row">Price</th>
		</tr>
		<tr>
			<td class="head" colspan=2><?php echo "$price_pageno out of $total_pages pages"?></td>
		</tr>
	</tfoot>

</table>
 <ul class="pagination">
	<li><a href="?price_pageno=1">First</a></li>
	<li class="<?php if($price_pageno <= 1){ echo 'disabled'; } ?>">
		<a href="<?php if($price_pageno <= 1){ echo '#'; } else { echo "?price_pageno=".($price_pageno - 1); } ?>">Prev</a>
	</li>
	<li class="<?php if($price_pageno >= $total_pages){ echo 'disabled'; } ?>">
		<a href="<?php if($price_pageno >= $total_pages){ echo '#'; } else { echo "?price_pageno=".($price_pageno + 1); } ?>">Next</a>
	</li>
	<li><a href="?price_pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>