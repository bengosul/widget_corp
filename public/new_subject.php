<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>		
<?php require_once("../includes/functions.php"); ?>		
<?php require_once("../includes/validation_functions.php"); ?>		
<?php include("../includes/layouts/header.php"); ?>		

<?php find_selected_page(); ?>


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


		<h2>Create Subject</h2>

		<form action="create_subject.php" method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position:
				<select name="position">
				<?php
			// 	$subject_count = 8;
				$subject_set = find_all_subjects()	;
				$subject_count= mysqli_num_rows($subject_set);
				for($count=1; $count<=$subject_count+1; $count++){
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
			<input type="submit" name="submit" value="Create Subject" />
		</form>
		<br />
		<a href ="manage_content.php">Cancel</a>
	</div>
</div>	

<?php
		//4. Release returned data
		mysqli_free_result($subject_set);
		//5. Close database connection
		if(isset($connection)) {mysqli_close($connection);}
?>

<?php include("../includes/layouts/footer.php"); ?>		
