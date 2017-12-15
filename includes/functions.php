<?php

function redirect_to ($new_location){
	header("Location: ".$new_location);
	exit;
}

function mysql_prep($string){
	global $connection;
	$escaped_string = mysqli_real_escape_string($connection, $string);
 	return $escaped_string;
}

function confirm_query($result_set) {
	if (!$result_set){
		die("Query failed.");
	}
}

function find_all_subjects(){
	global $connection;

	$query = "SELECT * ";
	$query.= "FROM subjects ";
	$query.= "WHERE visible = 1 ";
	$query.= "ORDER BY position ASC";
	$subject_set = mysqli_query($connection, $query);
	// Test if there was a wuery error
	confirm_query($subject_set);
	return $subject_set;
}

function find_pages_for_subject($subject_id){
	global $connection;
	
	$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
	
	$query = "SELECT * ";
	$query.= "FROM pages ";
	$query.= "WHERE visible = 1 ";
	$query.= "AND subject_id = {$safe_subject_id} ";
	$query.= "ORDER BY position ASC";
	$page_set = mysqli_query($connection, $query);
	// Test if there was a wuery error
	confirm_query($page_set);
	return $page_set;
}

function find_subject_by_id($subject_id){

	global $connection;

	$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
//	$safe_subject_id = $subject_id;

	$query = "SELECT * ";
	$query.= "FROM subjects ";
	$query.= "WHERE id = {$safe_subject_id} ";
	$query.= "LIMIT 1"; 

	$subject_set = mysqli_query($connection, $query);
	// Test if there was a wuery error
	confirm_query($subject_set);
	if($subject = mysqli_fetch_assoc($subject_set)){
		return $subject;
	}else{
	return null;
	}
}


function find_page_by_id($page_id){

	global $connection;

	$safe_page_id = mysqli_real_escape_string($connection, $page_id);
//	$safe_subject_id = $subject_id;

	$query = "SELECT * ";
	$query.= "FROM pages ";
	$query.= "WHERE id = {$safe_page_id} ";
	$query.= "LIMIT 1"; 

	$page_set = mysqli_query($connection, $query);
	// Test if there was a wuery error
	confirm_query($page_set);
	if($page = mysqli_fetch_assoc($page_set)){
		return $page;
	}else{
	return null;
	}
}

function find_selected_page (){
	//refering to as global even if they haven't been defined yet.
	//this means they are still available to us when we leave the function
	//otherwise we may have used return
	global $current_subject;
	global $current_page;
	
	if (isset($_GET["subject"])) {
		$current_subject = find_subject_by_id($_GET["subject"]);	
		$current_page = null;
	} elseif (isset($_GET["page"])) {
		$current_subject = null;
		$current_page = find_page_by_id($_GET["page"]);
	} else {
		$current_subject = null;
		$current_page = null;
	}

}

?>


