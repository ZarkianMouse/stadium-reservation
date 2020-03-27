# Plan for Stadium Reservations

Our demo needs to have a select query, an update query, and a delete query

1. From previous conversations, we decided the select query
would involve a combined table with PriceTiers and Seats.
I'm taking this query on for now. My plan for this is
to have the user be able to select multiple values to search for,
which could include SeatPrice, SectionID, and RowID.
2. We also decided that the update query would involve the Seats
table.
	1. A plan for this could be to make the Seats table
	clickable (each row is a link to a different page).
	2. On clicking the row, the reserve-seats.php page would be 
	php-included onto the page (see how prices and seats are
	included on welcome.php).
	3. The reserve-seats.php page would then update the UserID
	in Seats to the UserID on the welcome.php page. The
	$_SESSION\["username"\] variable holds the Username for the
	current session. Do a select query for UserID in Users where
	Username is $_SESSION\["username"\].
	4. The php page also would be passed the SeatID of the row that
	was clicked. Do an update query to change the UserID to the
	Session UserID in Seats where SeatID is the one that which the
	row passed.
	
	**If you want to do this one, you can use the tab for seats.php**
	**to get started. You will have to change how the table rows**
	**are implemented in order to use them as buttons. Look online**
	**for making the \<tr\> tag clickable.**
3. Finally, we agreed on making a function where the user can delete
their account. I will work on this function as well.