<?php include('header.php'); ?>
<?php
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
							
							foreach($cursor as $doc){
								if(isset($doc['PMatch_id'])){
									foreach($doc['PMatch_id'] as $found_id){
										echo $doc['Item'] . "   " . $found_id . "<br /><br />";
									}
								}
							}
						}
					?>
					<div class="match-container">
						
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
