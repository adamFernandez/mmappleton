<?php
	function ifType1($section,$page,$sel_subject) {
	
	$page_by_section = get_pages_by_section($section['id'],$sel_subject['id']);	
	$page_count = mysql_num_rows($page_by_section);
	while ($page = mysql_fetch_array($page_by_section)) {
					if ($page['format'] == 1) {
						echo "<div class='imageContainerWide'>";
						} else {
							echo "<div class='imageContainer'>";
					}
					echo "<div class='imageFrame'>
								
							<img id='image" . $page['id']  . "' src='images/" . $page['img_path'] . "' rel='#mies" . $page['id'] . "' />
								";
								
							
							echo "<script type='text/javascript'>jQuery(document).ready(function($){			
										$('#image" . $page['id'] . "' ).addimagezoom({
											zoomrange: [3, 5],";
											if ($page['format'] == 0){
												echo "magnifiersize: [450,254],";
											} else {
												echo "magnifiersize: [454,180],";
											}
										echo "magnifierpos: 'right',
											cursorshade: true,
											largeimage: 'images/" . $page['l_img_path'] . "' //<-- No comma after last option!
										})		
									})";
							
					echo "			</script>";
							
					echo "</div>
						
					<div class='infoContainer'>
						<p class='bold'> " . $page['title'] . "</p>
						<p><span class='smaller'> " . $page['description'];
						
							echo "<br  />" . $page['dimensions'];
						
					echo "<br  />" . $page['year'] . "<br  />
					</span></p>	<p></p>            
					</div> <!-- end infoContainer -->
					<div class='captionContainer'>
						<p><span class='italic'></span><br /><span class='smaller'><br  /></span></p>
					</div> <!-- end captionContainer -->
					</div> <!-- end imageContainer -->";
					
				} // END  for $page = msqlfa $page_by_section)
				
	
	}
	
	function ifType2($section,$page,$sel_subject) {
	
		$page_by_section = get_pages_by_section($section['id'], $sel_subject['id']);
			while ($page = mysql_fetch_array($page_by_section)) {
				echo "<div class='imageContainer'>
				<div class='imageFrame'>
						
					<img id='image" . ($page['id'])  . "' src='images/" . $page['img_path']. "'/>
						
							<script type='text/javascript'>

									jQuery(document).ready(function($){			
										$('#image" . $page['id'] . "' ).addimagezoom({
											zoomrange: [3, 5],
											magnifiersize: [450,254],
											magnifierpos: 'right',
											cursorshade: true,
											largeimage: 'images/" . $page['l_img_path'] . "' //<-- No comma after last option!
										})		
									})	
								</script>
				</div>
					
				  <table align='left' height='240' cellpadding='0' cellspacing='0' >
						<tr>
							<td height='10'>" ;
							
							 if ($page['title'] == "Hoop Snake Bebop") {
								 echo "<p class='bold'>The Idea of Order at Key West</p>
										<p class='indented'>by Wallace Stevens</p>   " ;
							 }
						  echo "</td>
						</tr>
						<tr>
							<td height='160'>
							<p class='noMargin'> "
							 . $page['content'] .
							" </p>
						  </td>
						</tr>
						<tr>                
						<td class='bottomAlign'>
									<p class= 'bold'> " . $page['title'] . "</p>
									<p><span class='smaller'>"	. $page['description'] . "</span><br />
									<span class='smaller'>"	. $page['dimensions'] . "</span><br />
									<span class='smaller'>"	. $page['year'] . "</span></p>
						  </td>
						</tr>
					</table>
				
				</div> <!-- end imageContainer -->";
			} // EHD WHILE $page = mysql_fetch_array($page_by_section)
	
	}
?>