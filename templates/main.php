<?php include('header.php'); ?>

			<div id="content">
				<div class="padding">
					<?php echo $header; ?>
					<?php if(!isset($output)): ?>
						<p>Welcome, <?php echo $_SESSION['first-name'] ?>.  What have you lost or found today?</p>
					<?php endif ?>
					<p><?php echo $output ?></p>
				</div>
			</div>
		</div>
	</body>
</html>
