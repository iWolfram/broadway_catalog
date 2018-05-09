<?php
include ("includes/header.php");
?>
<div class="col-md-12 well">
	<div class="col-md-8">
		<h1 class="page-header">Broadway Catalog Project</h1>
		<hr>
		<p style="text-align: justify;">
		<?php 
		echo nl2br("
		This catalog project is based on my interest in Broadway shows that I have seen and perhaps shows that I may want to see in the future. Most of these Broadway shows are categorized based on 4 main categories: Originals, Comedy, Drama, and Romance. Likewise, many of these shows have premiere dates ranging from as early as the 1960s till most modern.

		All information is entered into a database that is administered by a secure admin section; protected by a username and password. The admin will be able to add, update, and remove any information about the show. Some information that is required to be entered into this database is its title, a brief description, and an image of the show. Some other fields entered used for filtering is an array of categories mentioned above that can be chosen, the year of the shows first premiere date, and the number of total performances (this is updated manually). Each show can be more than one type of category, thus the use of checkboxes for categories it can choose from.

		One of my favorite features when adding or editing information to a Broadway show is the ability to add a YouTube URL with a valid video ID associated with its video. When entering a URL, it goes through a regular expression to check whether the URL is a valid YouTube video that has the appropriate video ID parameter (\"?v=\"). When it passes the regular expression, the video ID is saved to the database and is used to display a YouTube video on the display page of a show.

		There are many features I've tried to implement into this project that was taught in-class as well as features that I've researched on my own. The following is a list of features that I have implemented:

		Key features:
		- Youtube video embedded
		- Use of regular expression to capture video id
		- Regular display by catalog query
		- Range slider for \"BETWEEN\" query
		- Date picker
		- Built-in search bar on the side posting to list.php
		- Display number of results
		- Collapsable panels
		- Randomizing shows
		- Displaying latest upcoming show; if no show available, user is notified
		- Pagination on \"show all\" only
		- Pagination on display
		- Use of unique id to identify filename
		- Resizing of images (thumb and display)
		- Watermarking images
		- Use of grouped checkbox arrays in insert and edit

		Things to fix/improve on or future features:
		- Pagination based on filtering
		- Cleanup code
		- Retain panel collapse upon refresh
		- Fix an issue when user filters by either year or performance and then clicks on the thumbnail; the user can no longer go back to filter page.");
		 ?>
		</p>
	</div>


	<div class="col-md-4 sidebar">
		<form method="post" action="list.php" class="col-md-12" autocomplete="off">
			<div class="input-group form-group has-feedback">
				<input type="text" class="form-control" placeholder="Search for a broadway here" name="searchterm" />
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				</span>
			</div>
		</form>
		<!-- change this bit of code below -->
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">Upcoming Shows</div>
				<div class="panel-body" style="margin:10px 0;">
					<?php 
					$upcomingShows = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE edmontonStartDate > CURDATE() ORDER BY edmontonStartDate DESC LIMIT 1") or die(mysqli_error($con));
					if (mysqli_num_rows($upcomingShows) == 0):
						echo "<h4 class=\"text-center\">No upcoming shows</h4>";
					else :
						while ($row = mysqli_fetch_array($upcomingShows)):
							$title = $row['title'];
							$showid = $row['showid'];
							$filename = $row['filename'];?>
						<div class="col-md-12" style="margin-bottom: 20px;">
							<div class="col-md-8 col-md-offset-2 text-center">
								<h5 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><a href="display.php?showid=<?php echo $showid?>"><?php echo $title ?></a></h5>
								<a href="display.php?showid=<?php echo $showid?>"><img src="admin/thumbs/<?php echo $filename?>" class="img-thumbnail"/></a>
							</div>
						</div>
					<?php endwhile; endif; ?>
				</div>
			</div>
			<div class="panel panel-primary text-center">
				<div class="panel-heading">Categories</div>
				<div class="panel-body">
					<ul class="list-group">
						<!-- <li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=original">Originals</a></li> -->
						<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=comedy">Comedy</a></li>
						<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=drama">Drama</a></li>
						<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=romance">Romance</a></li>
					</ul>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading text-center">Random Shows</div>
				<div class="panel-body" style="margin:10px 0;">
					<?php 
					$randomShow = mysqli_query($con, "SELECT * FROM broadway_catalog ORDER BY RAND() LIMIT 2") or die(mysqli_error($con));
					while ($row = mysqli_fetch_array($randomShow)):
						$title = $row['title'];
						$showid = $row['showid'];
						$filename = $row['filename'];?>

						<div class="col-md-12" style="margin-bottom: 20px;">
							<div class="col-md-8 col-md-offset-2 text-center">
								<h5 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><a href="display.php?showid=<?php echo $showid?>"><?php echo $title ?></a></h5>
								<a href="display.php?showid=<?php echo $showid?>"><img src="admin/thumbs/<?php echo $filename?>" class="img-thumbnail"/></a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<!-- change this bit of code above -->
	</div>
</div>
<?php
include ("includes/footer.php");
?>