<?php include('header.php'); ?>

			<div id="content">
				<div class="padding">
					<h1>Insert - <?php echo $output ?></h1>
					<?php 
						if($output == "Found"){
							$append = "_found";
						}
					?>
					<form action="report" method="post">
						Name: <input type="text" name="name"><br>
						Item: <input type="text" name="item"><br>
						Location: <input type="text" name="location<?php echo $append ?>"><br>
						Description: <input type="textarea" name="description"><br>
						Tags: <input type="text" name="tags">
						<input type="submit" />
					</form>
					<?php
					// $connection = new MongoClient("mongodb://alexfaber:lost@linus.mongohq.com:10089/LF");
					// $db = $connection->LF;
// 					
					// $user = "Alex Faber";
					// $item = "Flashlight";
					// $description = "Brown and yellow flashlight";
					// $tags = array("brown", "yellow", "plastic");
					// $location = "Madison";
// 
// 
						// $date = date('M d Y');
						// $document = array("Date Created" => $date, "Item" => $item , "Found_Location" =>  $location, "User" => $user, "Matched" => 0, "Tags" => $tags, "Description" => $description, "Match_id" => "");
						// $db->Lost->insert($document);
					?>
				</div>
			</div>
		</div>
	</body>
</html>
