<?php require_once("../includes/session.php"); ?>		
<?php require_once("../includes/db_connection.php"); ?>		
<?php require_once("../includes/functions.php"); ?>		
<?php include("../includes/layouts/header.php"); ?>		

<?php find_selected_page(); ?>


<div id="main">
	<div id="navigation">
		<a href="admin.php">&laquo; Main menu</a><br/>

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
		<br/>
		<a href="new_subject.php">+ Add a subject</a>	
	</div>
	<div id="page">
		<?php message(); ?>	
		<?php if ($current_subject) {
			echo"<h2>Manage Subject</h2>";
			echo "Menu name: ".htmlentities($current_subject["menu_name"])."<br/>";
			echo "Position: ".$current_subject["position"]."<br/>";
			echo "Visible: ";
			echo $current_subject["visible"] == 1 ? "yes" : "no";
			echo "<br/>";
			echo "<a href='edit_subject.php?subject={$current_subject['id']}'>Edit Subject</a>";
			?> <div style="margin-top: 2em; border-top: 1px solid #000000;">
				<h3>Pages in this subject:</h3>
				<ul>
			<?php
				$subject_pages=find_pages_for_subject($current_subject["id"]);
				while($page = mysqli_fetch_assoc($subject_pages)){
					echo "<li>";
					$safe_page_id=urlencode($page["id"]);
					echo "<a href=\"manage_content.php?page={$safe_page_id}\">";
					echo htmlentities($page["menu_name"]);
					echo "</a>";
					echo "</li>";
				}
				?>
				</ul>
				<br />
				+ <a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Add new page to this subject</a>
			</div>


<?php
			}		

elseif ($current_page) {


			echo"<h2>Manage Page</h2>";
			echo "Submenu name: ".htmlentities($current_page["menu_name"])."<br/>";
			echo "Position: ".$current_page["position"]."<br/>";
			echo "Visible: ";
			echo $current_page["visible"] == 1 ? "yes" : "no";
			echo "<br/>Content: "."<br/>"; ?>
				<div class="view-content">
				<?php echo htmlentities($current_page["content"])."<br/>"; ?>
				</div>
				<br />
				<br />
				<a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit page</a>

			<?php
	}
			else {echo "Please select a subject or a page.";}
		?>


	</div>
</div>	

<?php
		//4. Release returned data
		mysqli_free_result($subject_set);
		//5. Close database connection
		if(isset($connection)) {mysqli_close($connection);}
?>

<?php include("../includes/layouts/footer.php"); ?>		
