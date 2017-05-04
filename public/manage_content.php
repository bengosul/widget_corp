<?php require_once("../includes/db_connection.php"); ?>		
<?php require_once("../includes/functions.php"); ?>		
<?php include("../includes/layouts/header.php"); ?>		

<div id="main">
	<div id="navigation">
		<ul class="subjects">
			<?php
				// 2. Perform database query
				$query = "SELECT * ";
				$query.= "FROM subjects ";
				$query.= "WHERE visible = 1 ";
				$query.= "ORDER BY position ASC";
				$subject_set = mysqli_query($connection, $query);
				// Test if there was a wuery error
				//	echo $query;
				confirm_query($subject_set);
			?>
		<?php
			// 3. Use returned data (if any)
			while($subject = mysqli_fetch_assoc($subject_set)){
				//output data form each row
		?>
			<li>
				<?php echo $subject["menu_name"]." (" . $subject["id"] . ")"; ?>
				<?php
					// 2. Perform database query
					$query = "SELECT * ";
					$query.= "FROM pages ";
					$query.= "WHERE visible = 1 ";
					$query.= "AND subject_id = {$subject["id"]} ";
					$query.= "ORDER BY position ASC";
					$page_set = mysqli_query($connection, $query);
					// Test if there was a wuery error
					//	echo $query;
					confirm_query($page_set);
				?>
				<ul class="pages">
					<?php
					// 3. Use returned data (if any)
					while($page = mysqli_fetch_assoc($page_set)){
					//output data form each row
					?>
					<li>
					<?php echo $page["menu_name"]; ?>
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
		<?php
			//4. Release returned data
			mysqli_free_result($subject_set);
			//5. Close database connection
			if(isset($connection)) {mysqli_close($connection);}
		?>

		</ul>	
		
	</div>
	<div id="page">
		<h2>Manage Content</h2>

	</div>
</div>	

<?php include("../includes/layouts/footer.php"); ?>		
