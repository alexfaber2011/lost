<?php
	// sub insert_found()
	// {
	  // my ($user, $item, $desc, $tags, $loc, $ts) = @_;
	  // my @tag_arr= @{$tags};
	  // my $id = $losts->insert
	  // (
	     // {
	       // User => $user,
	       // Item => $item,
	       // Description => $desc,
	       // Tags => ['Blue', 'Has Water', 'MHacks'],
	       // Found_Location => $loc,
	       // Date_Created => $ts,
	       // Matched => 0
	      // }
	  // );
	// }

    class mongo{
    	private $db;
		
		function __construct($db){
			$this->db = $db;
		}
		
		
    }
?>