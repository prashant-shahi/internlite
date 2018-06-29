<?php
require_once 'server.php';

if(!isset($_SESSION['employer']) || empty($_SESSION['employer'])) {
	header('location: index.php');
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

<div class="row justify-content-center py-2">
	<div class="col-lg-8 col-md-12 py-2">
		<div class="col-12">
			<h2 class="text-center text-primary"><?php echo $_SESSION['name']; ?></h2>
			<hr/>
			<h4 class="text-center">View Internship Applications</h4>
		</div>
		<div class="col-12 justify-content-center">
			<?php
// Print all internships for employer
			$query = "SELECT id, description, ts from internships where employerid='{$_SESSION['employer']}'";
			$res1 = mysqli_query($db, $query);
			if($res1) {
				while($row1=mysqli_fetch_assoc($res1)) {
					?>
					<div class="text-primary">
						<h5><?php echo $row1['description']; ?></h5>
						<div class="col-11 justify-content-center">
							<?php
							// Print all internships for everyone
							$query = "SELECT name, email,ts from internshipapps as intrnapp, students as stud where intrnapp.studentid=stud.id and internshipid={$row1['id']}";
							$res2 = mysqli_query($db, $query);
							if($res2) {
								$count = mysqli_num_rows($res2);
								?>
								<div class="row">
									<p class="col text-dark"><?php echo $row1['ts']; ?></p>
									<p class="col">Total Applications: <?php echo $count; ?>.</p>
								</div>
								<?php
								if($count<=0) {
									?>
									<div class="text-danger">
										No Applications received yet
									</div>
									<?php
								}
								else {
									?>

									<div class="text-primary">
										Table is as follow:
									</div>
									<table class="col-12 text-dark mb-4">
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Time/Date when Applied</th>
										</tr>
										<?php
										while($row2=mysqli_fetch_assoc($res2)) {
											?>
											<tr>
												<td>
													<?php echo $row2['name']; ?>
												</td>
												<td>
													<?php echo $row2['email']; ?>
												</td>
												<td>
													<?php echo $row2['ts']; ?>
												</td>
											</tr>
											<?php
										}
										?>
									</table>
									<?php
								}
								?>
							</div>
						</div>
						<?php
					}
				}
			}
			?>
		</div>
	</div>
	<hr/>
</div>

<?php require_once 'footer.php'; ?>