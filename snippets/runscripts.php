<?php
	include("connection.php");
	$joiner2="";
	$L=isset($_GET['l'])?$_GET['l']:"LIMIT 0,15";
	$query="SELECT * FROM onlinestoreentries $joiner2 ORDER BY id DESC $L";
	$run=mysql_query($query)or die(mysql_error()." ".__LINE__);
	$numrows=mysql_num_rows($run);
	require_once("../getid3/getid3.php");
	if($numrows>0){
		while($row=mysql_fetch_assoc($run)){
			$outs=getSingleStoreAudio($row['id']);
			$audiofilepath=".".$outs['audio'];
			$type=$outs['typeid'];
			// write album art to mp3 files
			if($type==1){
				$album="Frankly Speaking With Muyiwa Afolabi";
				$albumart='../images/frontierslogoalbumart.jpg';
			}else if ($type==2) {
				# code...
				$album="Christ Society International Outreach";
				$albumart='../images/csi.png';

			}else if($type==3){
				$album="The Ultimate State Of Mind";
				$albumart='../images/frontierslogoalbumart.jpg';
			}
			$TaggingFormat = 'UTF-8';
			// Initialize getID3 engine
			$getID3 = new getID3;
			$getID3->setOption(array('encoding'=>$TaggingFormat));
			$Filename = (isset($_REQUEST['Filename']) ? $_REQUEST['Filename'] : $audiofilepath);
			$TagFormatsToWrite = (isset($_POST['TagFormatsToWrite']) ? $_POST['TagFormatsToWrite'] : array());
			$TagFormatsToWrite['Tags']="id3v2.4";
			getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);
			$tagwriter = new getid3_writetags;
			$tagwriter->filename       = $Filename;
			$tagwriter->tagformats     = $TagFormatsToWrite;
			$tagwriter->overwrite_tags = true;
			$tagwriter->tag_encoding   = $TaggingFormat;
			$TagData=array();
			$commonkeysarray = array('Title', 'Artist', 'Album', 'Year', 'Comment');
			$TagData['title'][] = "$title";
			$TagData['artist'][] = "Muyiwa Afolabi";
			$TagData['album'][] = "$album";
			$TagData['year'][] = date("Y");
			$TagData['comment'][] = "";
			$TagData['genre'][] = "MOTIVATION";
			$TagData['genre'][] = "Inspirational";
			$TagData['track'][] = isset($_POST['Track'])&&$_POST['Track'].(!empty($_POST['TracksTotal']) ? '/'.$_POST['TracksTotal'] : '01');
	
			if (!empty($audiofilepath)) {
				if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)|| in_array('id3v2', $tagwriter->tagformats)||in_array('id3v2.0', $tagwriter->tagformats)) {
					if (file_exists($audiofilepath)) {
						ob_start();
						if ($fd = fopen(''.$albumart.'', 'rb')) {
							ob_end_clean();
							$APICdata = fread($fd, filesize(''.$albumart.''));
							fclose ($fd);
							list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize(''.$albumart.'');
							$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
							if (isset($imagetypes[$APIC_imageTypeID])) {
								$TagData['attached_picture'][0]['data']          = $APICdata;
								$TagData['attached_picture'][0]['picturetypeid'] = "2";
								$TagData['attached_picture'][0]['description']   = "Cover Art";
								$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];
							} else {
								echo '<b>invalid image format (only GIF, JPEG, PNG)</b><br>';
							}
						} else {
							$errormessage = ob_get_contents();
							ob_end_clean();
							echo '<b>cannot open '.$audiofilepath.'</b><br>';
						}
					} else {
						echo '<b>!is_uploaded_file('.$audiofilepath.')</b><br>';
					}
				} else {
					echo '<b>WARNING:</b> Can only embed images for ID3v2<br>';
				}
			}

			$tagwriter->tag_data = $TagData;
			if ($tagwriter->WriteTags()) {
				echo 'Successfully wrote tags<BR>';
				if (!empty($tagwriter->warnings)) {
					echo 'There were some warnings:<BLOCKQUOTE STYLE="background-color:#FFCC33; padding: 10px;">'.implode('<br><br>', $tagwriter->warnings).'</BLOCKQUOTE>';
				}
			} else {
				echo 'Failed to write tags!<BLOCKQUOTE STYLE="background-color:#FF9999; padding: 10px;">'.implode('<br><br>', $tagwriter->errors).'</BLOCKQUOTE>';
			}
			/*end*/
		}
	}
?>