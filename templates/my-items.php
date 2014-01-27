<?php include('header.php'); ?>

			<div id="content">
				<div class="padding">
					<?php if(isset($email)): ?>
					<div class="item-container">
					<h2><?php echo $name ?>'s Lost Items</h2>
					<?php foreach ($lost_cursor as $doc): ?>
						<div class="item">
							<div class="padding-item">
								<h1><?php echo $doc['Item'] ?> - <?php echo $doc["location"]["coordinates"][1] . ", " . $doc["location"]["coordinates"][0]; ?> <br /> <?php echo $doc['Date Created'] ?></h1>
								<hr />
								<p class="description">
									<?php echo $doc['Description']; ?>
								</p>
								<hr />
								<p>
									<?php foreach ($doc['Tags'] as $tag): ?>
										<span class="tag"><?php echo $tag ?>,</span>
									<?php endforeach ?>
								</p>
							</div>
						</div>
					<?php endforeach; ?>
					</div>
					<div class="item-container">
					<h2><?php echo $name ?>'s Found Items</h2>
					<?php foreach ($found_cursor as $doc): ?>
						<div class="item">
							<div class="padding-item">
								<h1><?php echo $doc['Item'] ?> - <?php echo $doc["location"]["coordinates"][1] . ", " . $doc["location"]["coordinates"][0]; ?> - <?php echo $doc['Date Created'] ?></h1>
								<hr />
								<p class="description">
									<?php echo $doc['Description']; ?>
								</p>
								<hr />
								<p>
									<?php foreach ($doc['Tags'] as $tag): ?>
										<span class="tag"><?php echo $tag ?>,</span>
									<?php endforeach ?>
								</p>
							</div>
						</div>
					</p>
					<?php endforeach ?>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
