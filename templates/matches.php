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
					<h1><?php echo $name ?>'s Matches</h1>
					<?php
						if($go){
							$query = array("email" => $email);
							$cursor = $db->Lost->find($query);
							if(isset($cursor)){
								foreach($cursor as $doc){
									if(isset($doc['PMatch_id'])){?>
										<?php foreach($doc['PMatch_id'] as $found_id): ?>
											<?php
												$query = array("_id" => new MongoId($found_id));
												$their_document = $db->Found->findOne($query);
											?>
											<div class="match-container">
													<div class="arrow"> <!--  --></div>
													<div class="your-item-container">
														<h1><?php echo $doc['Item']?></h1>
														<table>
															<tr>
																<td>Reported Lost:</td>
																<td><?php echo $doc['Date Created'] ?></td>
															</tr>
															<tr>
																<td>Location:</td>
																<td><?php echo $doc["location"]["coordinates"][1] . ", " . $doc["location"]["coordinates"][0]; ?></td>
															</tr>
															<tr>
																<td class="description" colspan="2"><?php echo $doc['Description'] ?></td>
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
													<div class="their-item-container">
														<form id="reject" action="" method="post">
															<input type="hidden" name="Reject" value="<?php echo $found_id ?>" />
															<input type="hidden" name="Lost" value="<?php echo $doc["_id"] ?>" />
															<input type="submit" id="reject" value="NO" />
														</form>
														<form id="accept" action="" method="post">
															<input type="hidden" name="Found" value="<?php echo $found_id ?>" />
															<input type="hidden" name="Lost" value="<?php echo $doc["_id"] ?>" />
															<input type="submit" id="accept" value="YES" />
														</form>
														<h1><?php echo $their_document['Item'] ?></h1>
														<table>
															<tr>
																<td>Reported Lost:</td>
																<td><?php echo $their_document['Date Created'] ?></td>
															</tr>
															<tr>
																<td>Location:</td>
						
																<td><?php echo $their_document["location"]["coordinates"][1] . ", " . $their_document["location"]["coordinates"][0]; ?></td>
															</tr>
															<tr>
																<td class="description" colspan="2"><?php echo $their_document['Description'] ?></td>
															</tr>
															<tr>
																<td class="tags" colspan="2">
																	<?php 
																		foreach($their_document["Tags"] as $tag){
																			echo $tag . ", ";
																		}
																	?>
																</td>
															</tr>
															<tr>
																<td>Email: </td>
																<td><?php echo $their_document["email"] ?></td>
															</tr>
														</table>
													</div>
												</div>
										<?php endforeach ?>
									<?php
									}
								}
							}
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
