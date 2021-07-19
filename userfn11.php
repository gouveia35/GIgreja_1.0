<?php

// Global user functions
// Page Loading event
function Page_Loading() {
	$_SESSION["agendados"]  = ew_ExecuteScalar("SELECT count(*) as qtda FROM agenda where Data = Curdate() and Resolvido <> 4");
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
?>
