<?php include('header.php'); ?>

			<div id="content">
				<div class="padding">
					<h1>Report <?php echo $output ?></h1>
					<form class="center-form" action="report" method="post">
						Item: <input type="text" name="item"><br />
						Latitude <input type="text" name="lat"><br />
						Longitude <input type="text" name="long"><br />
						Description: <input type="textarea" name="description"><br />
						Tags: <input type="text" name="tags">
						<?php if($output == "Found"): ?>
							<input type="hidden" name="found" value="true" />
						<?php endif ?>
						<input id="submit" type="submit" />
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
