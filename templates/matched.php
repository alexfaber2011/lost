<?php include('header.php'); ?>
<?php
	session_start();
	$email = $_SESSION['email'];
	$name = $_SESSION['first-name'];
	$go = false;
	if(isset($email)){
		$go = true;
	}
?>
			<div id="content">
				<div class="padding">
					<h1><?php echo $name ?>'s Matched Items</h1>
					<?php
						if($go){
							$query = array("email" => $email); 
							$cursor = $db->Lost->find($query);
					?>
						<?php if(isset($cursor)) : ?>
								<?php foreach ($cursor as $doc): ?>
										<?php if($doc['Matched'] == 1): ?>
									<!-- <div class="match-container"> -->
												<div class="your-item-container match">
													<h1><?php echo $doc['Item']; ?></h1>
													<table>
														<tr>
															<td>Reported Lost:</td>
															<td><?php echo $doc['Date Created']; ?></td>
														</tr>
														<tr>
															<td>Location:</td>
															<td><?php echo $doc['Location']; ?></td>
														</tr>
														<tr>
															<td class="description" colspan="2"><?php echo $doc['Description']; ?></td>
														</tr>
														<tr>
															<td class="tags" colspan="2">
																<?php 
																	foreach($doc["Tags"] as $tag){
																		echo $tag . ", ";
																	}
																?>
															</td>
														</tr>
													</table>
													<script src="https://www.dwolla.com/scripts/button.min.js" class="dwolla_button" type="text/javascript"
														  data-key="V/oPszrn0r5PCl5sIbcDN+LBXIs3jI+RHT6sU7Az7fnlX6MsNM"
														  data-redirect="http://secret-cove-9044.herokuapp.com/thank-you"
														  data-label="Thank your Finder!"
														  data-name="Lost and Found"
														  data-description="undefined"
														  data-amount="1"
														  data-type="simple"
													></script>
												</div>
												<?php endif ?>
										<!-- </div> -->
								<?php endforeach ?>
								<?php endif ?>
								<?php
							}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
