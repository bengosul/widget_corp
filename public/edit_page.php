<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>		
<?php require_once("../includes/functions.php"); ?>		
<?php find_selected_page(); 

if (!$current_page){
	redirect_to("manage_content.php");
}

if (isset($_POST['submit'])){

var_dump($_POST);

	$id = $current_page["id"];
	$menu_name = mysql_prep($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	$content = $_POST["content"];

	$query = "UPDATE pages SET ";
	$query.= "menu_name = '{$menu_name}', ";
	$query.= "position = '{$position}', ";
	$query.= "content = '{$content}' ";
	$query.= "WHERE id = '{$id}' ";
	$query.= "LIMIT 1";
	$result = mysqli_query($connection, $query);

	if ($result && mysqli_affected_rows($connection)==1){
	$_SESSION["message"] = "Page updated .";
	redirect_to("manage_content.php");
	}
	else {
		$_SESSION["message"] = "Page update	failed.". mysqli_error($connection)
			;
	}


}


?>
<?php include("../includes/layouts/header.php"); ?>		


<div id="main">
	<div id="navigation">
		<ul class="subjects">
			<?php $subject_set=find_all_subjects();	?>
		<?php
			// 3. Use returned data (if any)
			while($subject = mysqli_fetch_assoc($subject_set)){
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


				<h2>Edit Page: <?php echo $current_page['menu_name']; ?>
					</h2>

						<form action="edit_page.php?page=<?php echo $current_page["id"]; ?> " method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="<?php echo $current_page['menu_name']; ?>" />
			</p>
			<p>Position:
				<select name="position">

				<?php
			// 	$subject_count = 8;


	$query = "SELECT subject_id ";
	$query.= "FROM pages ";
	$query.= "WHERE id=".$current_page['id'];
	$query.= " LIMIT 1";
	$subject_id = mysqli_fetch_assoc(mysqli_query($connection, $query))['subject_id'];

				$page_set = find_pages_for_subject($subject_id)	;
				$subject_count= mysqli_num_rows($page_set);

				for($count=1; $count<=$subject_count; $count++){
						echo "<option value=\"{$count}\"";
						if ($current_page["position"] == $count){
							echo " selected";}
						echo ">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visible:
			<input type="radio" name="visible" value="0" <?php if ($current_page["visible"] == 0) {echo "checked";} ?> /> No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_page["visible"] == 1) {echo "checked";} ?> /> Yes
			</p>
			<p>Content: <br/>
			<textarea name="content" rows="20" cols="80"><?php echo $current_page["content"]; ?></textarea>
			</p>
			<input type="submit" name="submit" value="Edit Page" />
		</form>
		<br />
		<a href ="manage_content.php">Cancel</a>
		&nbsp;
		&nbsp;
		<a href ="delete_page.php?page=<?php echo $current_page["id"];?>" onclick="return confirm('Are you sure?');">Delete Page</a>

	</div>
</div>	

<?php
		//4. Release returned data
		mysqli_free_result($subject_set);
		//5. Close database connection
		if(isset($connection)) {mysqli_close($connection);}
?>

<?php include("../includes/layouts/footer.php"); ?>		
