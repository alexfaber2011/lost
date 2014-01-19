<?php include('header.php'); ?>

			<div id="content">
				<div class="padding">
					<h1>Insert <?php echo $output ?></h1>
					<form action="report" method="post">
						Item: <input type="text" name="item"><br>
						Location: <input type="text" name="location"><br>
						Description: <input type="textarea" name="description"><br>
						Tags: <input type="text" name="tags">
						<?php if($output == "Found"): ?>
							<input type="hidden" name="found" value="true" />
						<?php endif ?>
						<input type="submit" />
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
