<?php
	function ifType1($section,$page,$sel_subject) {
	
	$page_by_section = get_pages_by_section($section['id'],$sel_subject['id']);
				while ($page = mysql_fetch_array($page_by_section)) {
					$string = $page['img_path'];
					$lstring = substr_replace($string,"L.JPG",-4,4);
					 
					if ($page['format'] == 1) {
						echo "<div class='imageContainerWide'>";
						} else {
							echo "<div class='imageContainer'>";
					}
					echo "<div class='imageFrame'>";					
								
					if ($sel_subject['id'] == 2) {
						echo "<a class='rollover' style='background: url('images/painting/TML/" . $lstring ."')'> 
									<img class='displace' src='images/painting/" . $page['img_path']. "' />
								</a>";
						} elseif ($sel_subject['id'] == 5) {
							echo "images/paper/" . $page['img_path'] . "' />";
							}
						  
					 echo "</div>
					<div class='infoContainer'>
						<p class='bold'> " . $page['title'] . "</p>
						<p><span class='smaller'> " . $page['description'];
						if ($sel_subject['id'] != 5){
							echo "<br  />" . $page['dimensions'];
						}
					echo "<br  />" . $page['year'] . "<br  />
					</span></p>	<p></p>            
					</div> <!-- end infoContainer -->
					<div class='captionContainer'>
						<p><span class='italic'></span><br /><span class='smaller'><br  /></span></p>
					</div> <!-- end captionContainer -->
					</div> <!-- end imageContainer -->";
					
				} // END  while $page = msqlfa $page_by_section)
			
	
	}
	
	function ifType2($section,$page,$sel_subject) {
	
		$page_by_section = get_pages_by_section($section['id'], $sel_subject['id']);
			while ($page = mysql_fetch_array($page_by_section)) {
				echo "<div class='imageContainer'>
				<div class='imageFrame'>
				
					<img src='";
					if ($sel_subject['id'] == 2) {
						echo "images/painting/";
						} elseif ($sel_subject['id'] == 3) {
							echo "images/paper/";
							}
						echo  $page['img_path'] . "' />
				</div>
					
				  <table align='left' height='250' cellpadding='0' cellspacing='0'>
						<tr>
							<td height='10'>" ;
							
							 if ($page['title'] == "Hoop Snake Bebop") {
								 echo "<p class='bold'>The Idea of Order at Key West</p>
										<p class='indented'>by Wallace Stevens</p>   " ;
							 }
						  echo "</td>
						</tr>
						<tr>
							<td height='170'>
							<p class='noMargin'> "
							 . $page['content'] .
							" </p>
						  </td>
						</tr>
						<tr>                
						<td class='bottomAlign'>
								<p class='noMargin'>
									<span class= 'bold'><span class='italic'> " . $page['title'] . "</span></span><br />
									<span class='smaller'>"	. $page['description'] . " <br  />12' x 16' <br /> 2011 </span>
								</p>                       
						  </td>
						</tr>
					</table>
				
				</div> <!-- end imageContainer -->";
			} // EHD WHILE $page = mysql_fetch_array($page_by_section)
	
	}
?>