<?php
require_once 'server.php';
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
				<a class="active nav-link" href="index.php"><i class="fas fa-home fa-lg"></i> Home</a>
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
		<?php
		if(isset($_SESSION['employer'])) {
			?>
			<div class="row justify-content-around">
				<div class="col-12 mb-3">
					<h2 class="text-center text-primary"><?php echo $_SESSION['name']; ?></h2>
					<hr/>
					<h4 class="text-center">Employer Utilities</h4>
				</div>
				<input type="button" value="Post an Internship" class="btn btn-danger col-5 mybtnredirect" data-target="postinternship.php" />
				<input type="button" value="View Applications" class="btn btn-danger col-5 mybtnredirect" data-target="viewinternship.php" />
			</div>
			<?php
		}
		?>

		<hr/>
		<div class="row py-2">
			<div class="col">
				<h4>List of all internships</h4>
			</div>
			<form class="col searchres">
				<input type="text" name="search" id="search" placeholder="Search String" <?php
				if(isset($_GET['search']) && !empty($_GET['search'])) {
					echo 'value="'.$_GET["search"].'"';
				}
				?>/>
				<input type="submit" value="Search" class="btn btn-danger" />
			</form>
		</div>
		<hr/>
		<div class="row" id="results">
			<?php
// Print all internships for everyone
			$query = "SELECT intern.id as internid, emp.name, description ,emp.id as empid,ts from internships as intern, employers as emp where intern.employerid=emp.id";
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$query .= " and (emp.name like '%{$_GET['search']}%' OR description like '%{$_GET['search']}%')";
			}
			$res = mysqli_query($db, $query);
			if($res) {
				while($row=mysqli_fetch_assoc($res)) {
					?>
					<div class="col-md-8">
						<h5 class="text-info"><?php echo $row["description"]; ?></h5>
					</div>
					<div class="col-md-4">
						<?php
						if(isset($_SESSION['student'])) {
							$query = "SELECT COUNT(*) as count from internshipapps as internapp, students as std where internapp.studentid=std.id and std.id='{$_SESSION['student']}' and internshipid={$row["internid"]}";
							$countresult=mysqli_query($db,$query);
							$first=mysqli_fetch_assoc($countresult);
							if($first['count']==0) {
								?>
								<button class="btn btn-primary mybtninternapply" data-internid="<?php echo $row["internid"]; ?>"><i class="far fa-check-square"></i> Apply</button>
								<?php
							}
							else {
								?>
								<button class="btn btn-primary mybtninvalidapply" data-user="student"><i class="fas fa-ban"></i> Apply</button>
								<?php
							}
						}
						else if(isset($_SESSION['employer'])) {
							?>
							<button class="btn btn-primary mybtninvalidapply" data-user="employer"><i class="fas fa-ban"></i> Apply</button>
							<?php
						}
						else {
							?>
							<button class="btn btn-primary mybtnredirect" data-target="login.php"><i class="far fa-check-square"></i> Apply</button>
							<?php
						}
						?>
					</div>
					<div class="col-md-6 text-dark">
						<p>Company: <strong><?php echo $row["name"]; ?></strong></p>
					</div>
					<div class="col-md-6 text-dark pb-3">
						<p><?php echo $row["ts"]; ?></p>
					</div>
					<hr/>
					<?php
				}
			}
			else {
				echo '<h3 class="text-danger">Error Occurred while loading internships</h3>';
			}
			?>
		</div>
	</div>
</div>

<?php require_once 'footer.php'; ?>