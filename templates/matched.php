<?php include('header.php'); ?>
<?php
	session_start();
	$email = $_SESSION['email'];
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
									<div class="match-container">
												<div class="your-item-container">
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
												</div>
										</div>
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
