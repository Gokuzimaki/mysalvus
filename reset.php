<?php  
session_start();
include('./snippets/connection.php');

$activepage="active";
// obtain data
$fullscript="";

$mpagecrumbstyle="";
$mpagetitle="Password Reset";
$mpagecrumbtitle="Password Reset";
$mpagecrumbclass="";
$mpagecrumbdata='
    <li><a href="'.$host_addr.'">Home</a></li>
    <li class="active">Password Reset</li>
';
$title="Password Reset <br><small>Provide your account email details.</small>";
$messageout="<h3>Password reset link sent</h3>
<p>A link has been sent to the email address you used, follow the link to reset your 
account password, sorry for any inconvenience</p>";
// get the user email if available
$t=isset($_GET['t'])?mysql_real_escape_string($_GET['t']):"";
$formtruetype="resetform";
if($t!==""&&$t=="reset"&&isset($_GET['h'])&&isset($_GET['checksum'])){
    $checksum=mysql_real_escape_string($_GET['checksum']);
    $userhash=mysql_real_escape_string($_GET['h']);
    // check the database for a match on the user
    $query="SELECT * FROM users WHERE MD5(id)='$userhash' AND EXISTS(SELECT * FROM 
        resetpassword WHERE userid=`users`.`id` AND checksum='$checksum' AND 
        status='active')";
    $run=briefquery($query,__LINE__,"mysqli");
    $numrows=$run['numrows'];
    if($numrows>0){
        $row=$run['resultdata'][0];

        $title="Password Reset <br> <small>Provide your new password.</small>";
        $messageout='
            <form class="resetform" name="'.$formtruetype.'" 
            action="'.$host_addr.'snippets/edit.php" method="POST">
                <input type="hidden" name="entryvariant" value="resetpassword">
                <input type="hidden" name="entryid" value="'.$row['id'].'"/>
                <input type="hidden" name="checksum" value="'.$checksum.'"/>
                <input type="hidden" name="returl" 
                value="'.$host_addr.'completion.php"/>
                <div class="col-md-12">
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" 
                        data-cvalidate="true" 
                        data-element-data="confirmpassword-:-input" 
                        data-type="password" 
                        data-error-output="Passwords do not match, try again" 
                        placeholder="New Password"/>
                        <div class="input-group-addon pshow">
                            <i class="fa fa-eye-slash"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password"/>
                </div>                            
                <input type="hidden" name="formdata" value="'.$formtruetype.'"/>
                <input type="hidden" name="extraformdata" value="password-:-input<|>
                  confirmpassword-:-input"/>
                <input type="hidden" name="errormap" value="password-:-Provide your new password<|>
                    confirmpassword-:-Please confirm your password"/>
                <div class="col-md-12 clearboth">
                    <div class="box-footer text-center">
                        <input type="button" class="btn btn-success _salvsubmit" 
                        name="user" 
                        data-formdata="'.$formtruetype.'" 
                        onclick="submitCustom(\''.$formtruetype.'\',\'complete\')" 
                        value="Reset Password"/>
                    </div>
                </div>
            </form>
        ';
        
    }else{
        $title="Reset Link Invalid";
        $messageout="<p>Sorry there seems to be a problem here, 
        its either you have already run a reset through this link before or the link
        has expired or it is non-existent in our system.</p>";
    }
}else{
    // produce the user email collection form
    // $messageout='NO data detected.';
    $messageout='
        <form class="resetform" name="resetform" 
        action="'.$host_addr.'snippets/basicsignup.php" method="POST">
            <input type="hidden" name="entryvariant" value="createresetlink">
    
            <input type="hidden" name="returl" value="'.$host_addr.'completion.php"/>
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-user"></i>
                        </div>
                        <select name="usertype" class="form-control">
                            <option value="">--Account Type--</option>
                            <option value="user">Salvus User</option>
                            <option value="serviceprovider">Service Provider</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-at"></i>
                        </div>
                        <input type="resetemail" class="form-control"
                        name="resetemail" placeholder="Email Address"/>

                    </div>
                </div>
            </div>
              
            <input type="hidden" name="extraformdata" value="usertype-:-select<|>
                resetemail-:-input"/>
            <input type="hidden" name="errormap" value="usertype-:-Choose your user category.<|>
                resetemail-:-Provide your registered email address"/>
            <div class="col-md-12 clearboth">
                <div class="box-footer text-center">
                    <input type="button" class="btn btn-success _salvsubmit" 
                    name="user" 
                    data-formdata="'.$formtruetype.'" 
                    onclick="submitCustom(\''.$formtruetype.'\',\'complete\')" 
                    value="Reset Password"/>
                </div>
            </div>
         </form>
    ';
}

include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');
?>
    <body class="">
        <?php
            include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
        ?>
        <!-- Content start -->
        <div class="row content">
            <div class="container">
                <div class="col-md-12 _salvus-intro-container">
                    <h2 class="page-header text-center salvus-intro-header">
                        <?php echo $title;?>
                       
                    </h2>            
                    <?php echo $messageout;?>
                </div>
            </div>
        </div>
        <!-- Content End -->
        <?php
            include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
        ?>
        <?php
            include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
        ?>

    </body>
</html>
    

