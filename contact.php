<?php  
session_start();
include('./snippets/connection.php');
$activemainlink="current activemainlink";
$activepage7="active";

$contactdata=getAllBranches("viewer","all","");
$branchdata=$contactdata['branchdata'];
$mainbranchdata=array();
if($contactdata['numrows']>0){
    $mainbranchdata=$contactdata['mainbranchdata'][0];
    
}
// var_dump($mainbranchdata);
// obtain data
$scriptout="";
$jsarrsetup="";
$multiple="";
$mc=0;
$domap="";
$curesultset=array();
$latitude="";
$longitude="";
$jsarrsetup='var curdata=[];';

if($contactdata['numrows']>0){
    if($mainbranchdata['lat']!==""&&$mainbranchdata['lng']!==""){
        $domap="yes";
        if($latitude==""&&$longitude==""){
            $latitude=$mainbranchdata['lat'];
            $longitude=$mainbranchdata['lng'];
        }
        $jsarrsetup.='
            curdata['.$mc.']=[];
            curdata['.$mc.'][0]="'.$mainbranchdata['location'].'";
            curdata['.$mc.'][1]="'.$mainbranchdata['lat'].'";
            curdata['.$mc.'][2]="'.$mainbranchdata['lng'].'";
            curdata['.$mc.'][3]="'.$mainbranchdata['address'].'";
            curdata['.$mc.'][4]=\'<div id="content">\' + \'<div id="siteNotice">\' + \'<p></p><h4><b>'.$mainbranchdata['location'].'</b></h4>\' + \'</div>\' + \'<div id="bodyContent">\' + \'<p><i class="icon-map-marker"></i> '.str_replace("'", "\'", $mainbranchdata['address']).'\' + \'</p>\' + \'</div>\' + \'</div>\';
            curdata['.$mc.'][5]=host_addr+\'images/fvtimages/map-icon-2.png\';
        ';
        $mc++;
    }
}

$branchout="";
if($branchdata['count']>0){
    for($i=0;$i<$branchdata['count'];$i++){
        $tp=$i+1;
        $curbranchcontactdata="";
        $curesultset=$branchdata[$i];
        $curbranchcontactdata.='<div class="text-box singlecontactbox">
                                <p class="singlecontactname">'.$curesultset['contactname'].'</p>
                                <p>Phone:'.$curesultset['phonenumbers'].'</p>
                                <p>Email: '.$curesultset['email'].'</p>
                            </div>';
        if($curesultset['subcontactscount']>0){
            for ($t=0; $t < $curesultset['subcontactscount']; $t++) { 
                # code...
                $curcontact=$curesultset['subcontacts'][$t];
                $curbranchcontactdata.='<div class="text-box singlecontactbox">
                                <p class="singlecontactname">'.$curcontact['contactname'].'</p>
                                <p>Phone: '.$curcontact['phonenumbers'].'</p>
                                <p>Email: '.$curcontact['email'].'</p>
                            </div>';
            }
        }
        // var_dump($branchdata[$i]);
        $branchout.='
            <div class="col-md-3">
                <div class="accordion-group">
                    <div class="accordion-heading active">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne'.$i.'">
                            <strong class="date">'.$tp.'</strong>
                            <div class="text-col">
                                <strong class="title">'.$curesultset['location'].'</strong>
                                <strong class="location">
                                    <i class="fa fa-map-marker"></i>
                                    '.$curesultset['address'].'
                                </strong>
                                
                            </div>
                            <span class="close-panel">
                                <i class="fa fa-plus"></i>
                            </span>
                        </a>
                    </div>
                    <div id="collapseOne'.$i.'" class="accordion-body collapse">
                        <div class="accordion-text-box">
                            <div class="accordion-inner">
                                '.$curbranchcontactdata.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
        if($curesultset['lat']!==""&&$curesultset['lng']!==""){
            $domap="yes";
            $multiple='yes';

            if($latitude==""&&$longitude==""){
                $latitude=$mainbranchdata['lat'];
                $longitude=$mainbranchdata['lng'];
            }
            $jsarrsetup.='
                curdata['.$mc.']=[];
                curdata['.$mc.'][0]="'.$curesultset['location'].'";
                curdata['.$mc.'][1]="'.$curesultset['lat'].'";
                curdata['.$mc.'][2]="'.$curesultset['lng'].'";
                curdata['.$mc.'][3]="'.$curesultset['address'].'";
                curdata['.$mc.'][4]=\'<div id="content">\' + \'<div id="siteNotice">\' + \'<p></p><h4><b>'.$curesultset['location'].'</b></h4>\' + \'</div>\' + \'<div id="bodyContent">\' + \'<p><i class="icon-map-marker"></i> '.str_replace("'", "\'", $curesultset['address']).'\' + \'</p>\' + \'</div>\' + \'</div>\';
                curdata['.$mc.'][5]=host_addr+\'images/fvtimages/map-icon-2.png\';
            ';
            $mc++;
        }
    }
    // $branchout=$curbranchcontactdata;
}
if($domap=="yes"){
    if($mc>2){
        $zoom=14;
    }else{
        $zoom=18;
    }
    $scriptout='
        <script>
            $(document).ready(function(){
                '.$jsarrsetup.'
                var data=[];
                data["elid"]="real_map_contact";
                data["zoom"]="'.$zoom.'";
                data["zoomcontrol"]="true";
                data["styles"]=[{stylers: [{hue: \'#FF8000\'}, {saturation: -10}, ] }];
                // console.log("typeof google - ", typeof(google))
                if(typeof(google)!=="undefined"){
                    if ($(\'#real_map_contact\').length>0) {
                        infos=[];
                        initializeGmap('.$latitude.','.$longitude.',data,"'.$multiple.'",curdata);
                    }
                }
            })
        </script>
    ';
}
include('snippets/headcontentfvt.php');
?>
    <body class="loaded">
        <noscript>
            <div class="javascript-required">
                <i class="fa fa-times-circle"></i> You seem to have Javascript disabled. This website needs javascript in order to function properly!
            </div>
        </noscript>
        <div id="wrapper">
            <?php include('snippets/toplinksfvt.php');?>
            <div id="main" class="spacing">

                <section class="contact-page">
                    <?php
                            if($cweldata['numrows']>0){
                        ?>
                        <div class="generic-heading">
                            <h2><?php echo $cweldata['title'];?></h2>
                            <strong class="title-line"><?php echo $cweldata['subtitle'];?></strong>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $cweldata['content'];?>
                            </div>
                        </div>
                        <?php
                            }else if($cweldata['defaultmarker']=="yes"){
                        ?>
                        <div class="generic-heading-3">
                            <h1>Contact Us</h1>
                            <strong class="title-line"></strong>
                        </div>
                        <?php 
                            }
                        ?>
                        <?php if ($domap=="yes") {
                            # code...
                        ?>
                    <div class="contact-map">
                        <div id="real_map_contact" class="map_canvas active"></div>
                    </div>
                    <?php
                        }else{
                    ?>
                    <div class="contact-map">
                        <div id="map_contact_2" class="map_canvas active"></div>
                    </div
                    <?php
                        }
                    ?>
                    <div class="contact-detail">
                        <div class="container">
                            <div class="row-fluid">
                                <div class="span7">
                                    <form action="<?php echo $host_addr;?>snippets/basicsignup.php" method="post" class="contact-form">
                                        <h2>Contact Form</h2>
                                        <input name="entryvariant" type="hidden" value="contacthelpdesk">
                                        <div class="form-box">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Fullname</label>
                                                    <input name="name" required pattern="[a-zA-Z ]+" type="text" placeholder="Fullname" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input required class="form-control" pattern="^[a-zA-Z0-9-_\.]+@[a-zA-Z0-9-_\.]+\.[a-zA-Z0-9.]{2,5}$" placeholder="Email Address" name="email" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Subject</label>
                                                    <input type="text" name="subject" required pattern="[a-zA-Z ]+" class="form-control" placeholder="Subject"/>
                                                </div>        
                                            </div>
                                            <div class="col-sm-12 textarea-box">
                                                <div class="form-group">
                                                    <label>Message</label>
                                                    <textarea name="message" placeholder="Message" required cols="10" rows="10"></textarea>
                                                </div>
                                            </div>
                                            <input name="" type="submit" value="Send Message">
                                        </div>
                                    </form>
                                </div>
                                <div class="span5">
                                    <address class="contact-address">
                                        <strong>FVT Africa</strong>
                                        <div class="address-box">
                                            <i class="fa fa-home"></i>
                                            <div class="text-box">
                                                <?php
                                                    if(isset($mainbranchdata['id'])){
                                                        
                                                ?>
                                                <p class="singlecontactname"><?php echo $mainbranchdata['location'];?></p>
                                                <p><?php echo $mainbranchdata['address'];?></p>
                                                <?php
                                                    }else{
                                                ?>
                                                <p class="singlecontactname">Lagos Office</p>
                                                <p>36, Raymond Njoku Street Ikoyi, Lagos Nigeria</p>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="address-box">
                                            <i class="fa fa-user"></i>
                                            <div class="text-box">
                                                <?php
                                                    if(isset($mainbranchdata['id'])){
                                                        // echo $mainbranchdata['subcontactscount'];
                                                ?>
                                                    <div class="singlecontactperson">
                                                        <p class="singlecontactname"><b><?php echo $mainbranchdata['contactname'];?></b></p>
                                                        <p>Phone:<br> <b><?php echo $mainbranchdata['phonenumbers'];?><b></p>
                                                        <p>Email:<br> <b><?php echo $mainbranchdata['email'];?></b></p>
                                                    </div>
                                                <?php        
                                                        for ($i=0; $i < $mainbranchdata['subcontactscount']; $i++) { 
                                                            # code...
                                                            $curcontact=$mainbranchdata['subcontactscount'][$i];
                                                        
                                                ?>
                                                    <div class="singlecontactperson">
                                                        <p class="singlecontactname"><?php echo $curcontact['contactname'];?></p>
                                                        <p>Phone:<br> <?php echo $curcontact['phonenumbers'];?></p>
                                                        <p>Email:<br> <?php echo $curcontact['email'];?></p>
                                                    </div>
                                                <?php
                                                        }
                                                    }else{
                                                ?>
                                                    <div class="singlecontactperson">
                                                        <p class="singlecontactname">Kenny</p>
                                                        <p>Phone: 08081108726</p>
                                                        <p>Email: kemmny3000000@gmail.com</p>
                                                    </div>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="social-box">
                                            <strong>Connect with Us</strong>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $defaultfacebook;?>" target="_blank">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo $defaulttwitter;?>" target="_blank">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo $defaultlinkedin;?>" target="_blank">
                                                        <i class="fa fa-linkedin"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        if ($branchdata['count']>0) {
                            # code...
                    ?>
                    <div class="our-event-accordion">
                        <div class="generic-heading-3">
                            <h1>Other Branches</h1>
                            <strong class="title-line"></strong>
                        </div>
                        <div class="accordion" id="accordion2">
                            
                            <?php echo $branchout;?>
                        </div>
                    </div>
                    <?php 
                        }
                    ?>
                    <?php echo $scriptout;?>
                </section>
            </div>
            <?php include('snippets/footerfvt.php');?>
        </div>
    </body>
    <?php include('snippets/themescriptsdumpfvt.php');?>
</html>