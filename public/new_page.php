<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>		
<?php require_once("../includes/functions.php"); ?>		
<?php require_once("../includes/validation_functions.php"); ?>		
<?php include("../includes/layouts/header.php"); ?>		

<?php find_selected_page(); ?>

<?php 
	//can't add a new page unless we have a subject as parent
if (!current_subject){
	// subject id was missing or invalid
	redirect_to("manage_content.php");
}

if (isset($_POST['submit'])){
	//process the form
	//validations
	$requierd_fields = array("menu_name","position","visible","content");
	validate_presences($required_fields);

	$fields_with_max_lengths = array("menu_name"=>30);
	validate_max_lengths($fields_with_maX_lengths);

	if(empty($errors)){
		// perfrom create
		// make sure you add the subject id
		$subject_id = $current_subject["id"];
		$menu_name = mysql_prep($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		$content = mysql_prep($_POST["content"]);

		$query = "INSERT INTO pages (";
		$query .=" subject_id, menu_name, position, visible, content";
		$query .=") VALUES(";
		$query .=" {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
		$query .= ")";

		$result =  mysqli_query($connection, $query);

		

		if ($result){
			$_SESSION["message"] = "Page created. ";
			redirect_to("manage_content.php?subject=". urlencode($current_subject["id"]));
		} else {
			$_SESSION["message"]= "Page creation failed.";
			$_SESSION["message"] .= $query ;
			
		}
	}else {
		//this is probably a GET requiest
		redirect_to("new_page.php?subject=". urlencode($current_subject["id"]) );
	}

}




?>

<div id="main">
	<div id="navigation">
		<ul class="subjects">
			<?php $subject_set=find_all_subjects();	?>
		<?php
			// 3. Use returned data (if any)
			while($subject = mysqli_fetch_assoc($subject_set)){
				//output data form each row
		?>
			<?php 
				echo "<li";
				if ($subject["id"]==$current_subject["id"]) { 
					echo " class=\"selected\"";
				}
				echo ">" ;
			?>
				<a href="manage_content.php?subject=<?php echo urlencode($subject["id"]); ?> "><?php echo $subject["menu_name"]." (" . $subject["id"] . ")"; ?></a>
				<?php $page_set=find_pages_for_subject($subject["id"]); ?>

				<ul class="pages">
					<?php
					// 3. Use returned data (if any)
					while($page = mysqli_fetch_assoc($page_set)){
					//output data form each row
					?>
			<?php 
				echo "<li";
				if ($page["id"]==$current_page["id"]) { 
					echo " class=\"selected\"";
				}
				echo ">" ;
			?>
					<a href="manage_content.php?page=<?php echo urlencode($page["id"]); ?>" ><?php echo $page["menu_name"]; ?></a>
					</li>
					<?php		
							}
					?>
					<?php
						//4. Release returned data
						mysqli_free_result($page_set);
					?>
				</ul>
			</li>
		<?php		
				}
		?>

		</ul>	
		
	</div>
	<div id="page">

		<?php message();?>
		<?php $errors=errors();
			echo form_errors($errors);?>


		<h2>Create Page</h2>

			<form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position:
				<select name="position">
				<?php
				// 	$subject_count = 8;
				
				$page_set=find_pages_for_subject($current_subject["id"]);
				$subject_count= mysqli_num_rows($page_set);
				for($count=1; $count<=$page_count+1; $count++){
					echo "<option value=\"{$count}\">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" /> No
				&nbsp;
				<input type="radio" name="visible" value="1" /> Yes
			</p>
			<p>Content: <br/>
				<textarea name="content" rows="20" cols="80"></textarea>
			</p>

			<input type="submit" name="submit" value="Create Page" />
		</form>
		<br />
		<a href ="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>
	</div>
</div>	

<?php
		//4. Release returned data
		mysqli_free_result($subject_set);
		//5. Close database connection
		if(isset($connection)) {mysqli_close($connection);}
?>

<?php include("../includes/layouts/footer.php"); ?>		
