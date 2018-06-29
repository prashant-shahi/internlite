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
				<a class="nav-link" href="index.php"><i class="fas fa-home fa-lg"></i> Home</a>
			</li>
			<?php
			if(!isset($_SESSION['success']) || empty($_SESSION['success']))
			{
				?>
				<li class="nav-item">
					<a class="nav-link" href="employer-login.php"><i class="far fa-building fa-lg"></i> Employer Portal</a>
				</li>
				<li class="active nav-item">
					<a class="nav-link" href="#"><i class="fas fa-users fa-lg"></i> Student Portal</a>
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

<div class="row justify-content-md-center pt-2">
	<div class="col-sm-8">
		<div>
			<h2 class="text-white bg-info text-center">Student Login</h2>
		</div>
		<form action="" method="POST">
			<div class="form-group">
				<label for="email">Email address:</label>
				<input type="email" class="form-control" id="email" name="email">
			</div>
			<div class="form-group">
				<label for="pwd">Password:</label>
				<input type="password" class="form-control" id="pwd" name="pwd">
			</div>
			<input type="submit" name="login" value="LOGIN" class="btn btn-info" />
			<a class="pl-3" href="register.php">New to platform? Register!</a>
		</form>
	</div>
</div>

<?php require_once 'footer.php'; ?>