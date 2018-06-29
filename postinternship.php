<?php
require_once 'server.php';

if(!isset($_SESSION['employer']) || empty($_SESSION['employer'])) {
	header('location: index.php');
}

if(isset($_POST['postinternship'])) {
	$description = $_POST['internshipbody'];
	$employerid = $_SESSION['employer'];

	$query = "SELECT COUNT(*) as count FROM internships WHERE description='$description' and employerid='$employerid'";
	$res = mysqli_query($db, $query);
	$first = mysqli_fetch_array($res);
	if($first['count'] == 0) {
		$insertquery = "INSERT INTO internships(employerid,description) values('$employerid','$description')";
		$res = mysqli_query($db,$insertquery);
		if($res) {
			array_push($success,"Successfully added an Internship.");
		}
		else {
			array_push($errors,"Error occurred while adding internship.");
		}
	}
	else {
		array_push($errors,"Same internship already exists.");
	}
}

require_once 'header.php';
?>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
	<a href="index.php"><img src="images/favicon.png" alt="Logo" style="width:50px;"></a>
	&nbsp;&nbsp;
	<a class="navbar-brand" href="index.php">InternLite</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="index.php"><i class="fas fa-home fa-lg"></i> Home</a>
			</li>
			<?php
			if(!isset($_SESSION['success']) || empty($_SESSION['success']))
			{
				?>
				<li class="nav-item">
					<a class="nav-link" href="employer-login.php"><i class="far fa-building fa-lg"></i> Employer Portal</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php"><i class="fas fa-users fa-lg"></i> Student Portal</a>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
		if(isset($_SESSION['success']) && !empty($_SESSION['success']))
		{
			?>
			<form class="form-inline my-2 my-lg-0">
				<button class="btn btn-secondary my-2 my-sm-0" type="submit" name="logout"><i class="fas fa-sign-out-alt fa-lg"></i> Logout</button>
			</form>
			<?php
		}
		?>
	</div>  
</nav>

<?php require_once 'errors-success.php'; ?>

<div class="row justify-content-md-center py-2">
	<div class="col-sm-8 py-2">
		<div class="col-12">
			<h2 class="text-center text-primary"><?php echo $_SESSION['name']; ?></h2>
			<hr/>
			<h4 class="text-center">Post an Internship</h4>
		</div>
		<div class="col-12">
			<form method="POST">
				<div class="form-group">
					<label>Company Name:</label>
					<input type="text" class="form-control" id="name" required="required" name="name" value="<?php echo $_SESSION['name']; ?>" readonly="readonly">
				</div>
				<div class="form-group">
					<label>Short Internship Description:</label>
					<textarea class="form-control" type="text" name="internshipbody" placeholder="Example:  Web Developer - With knowledge of both Frontend and Backend and have done some projects"></textarea>
				</div>
				<input type="submit" value="Post Internship" name="postinternship" class="btn btn-danger" />
			</form>
		</div>
	</div>
	<hr/>
</div>

<?php require_once 'footer.php'; ?>