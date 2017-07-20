<?php
  /* Defining the scope and work of this collage one point module
    Eval grouping for singlegeneralinfo
    initeval
    processeval
    adminoutputeval
    outputeval
    postoutputeval

    Admin tabledata rows for collage boxes
    <td>icon</div><>
  */
  /*
    advanced formmontioring and validation for generic forms
    *tinymce textareas must have a data-mce="true" attribute value for focus to be
    transfered to the mce field directly
    
    sentinel elements for groups can have the following additional attributes
    data-valset="1,2,3" this is comma seperated list of element values that 
    must be provided by default on a group set that requires at least one entry provided
    on the form
    data-valcount="1-n" where n is a number this is used to control the total amount
    of expected entries from a groupset in a form, it defaults to 1 when not defined
    and data-valset has valid values.
    
    data attributes on edit form elements
    
    1. data-form-edit="true" this attribute is for form file elements in groupset
      once set to "true" the validation for the element is ignored on the edit form

    2. data-edittype="true" this attribute is for single form elements in the edit
     form, it basically does the same thing as the aforementioned data-edittype but 
     addresses only single elements in the form

    3. data-mce="true" setting this attribute on a textarea field that uses tinymce 
    would ensure that the tinymce frame created for the textarea element is given focus

    <input type="hidden" name="extraformdata" value="collagecolourtype-:-select<|>
      contenttitle-:-input<|>
      contentpost-:-textarea-:-[
        validationtype-|-fieldname-|-type-|-value>|<
        group-|-fieldname-|-type-|-value-|-fieldname-|-type-|-value
      ]<|>(validataion type are as follows
        all,single, group
      )
      egroup|data-:-[testmultiplevaliddata>|< (this part is for grouped content 
      to be validate, it is basically the name of the input element that holds the value
      of the current number of entries available in the form) 
        linktitlea-|-input>|<
        linktitleb-|-input>|<
        linktitlec-|-select
      ]-:-[1,2,3] OR [1-2,2-3,3-1] E.T.C
      this system is Javascript based and is triggered by
      for submit button with value of "submitcontenttwo"
      a hidden input field with the name formdata also must be present
      and it must hold the name of the form currently being assesed
      "/>
      <input type="hidden" name="errormap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
      <input type="hidden" name="labelmap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
      <input type="hidden" name="placeholdermap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
      <input type="hidden" name="bsmap" value="
        collagecolourtype-:-Please Select a Color<|>
        contenttitle-:-Please provide a title<|>
        contentpost-:-Please give the section details<|>
        egroup|data-:-[Provide some text for the field a>|<
        Provide some text for the field b>|<
        Provide some text for the field c]
      "/>
    

  */

  !isset($pagetype)?$pagetype="hometopcollagebox":$pagetype=$pagetype;
  if(isset($entrytype)){
    $pagetype=$entrytype;
  }
  $datatype[0]=!isset($pagetype)?"hometopcollageboxintro":$pagetype."intro";
  $part="";
  if($datatype[0]=="hometopcollageboxintro"){
    $part=" Top";

  }else if($datatype[0]=="homebottomcollageboxintro"){
    $part=" Bottom";

  }
  // echo $datatype[0];
  $datatype[1]="admin";
  $truesubtype=$datatype[0];
  $datatype[4]="nouse";
  if(isset($contentgroupdatasingle)){
    unset($contentgroupdatasingle);
  }
  if(isset($contentgroupdatageneral)){
    unset($contentgroupdatageneral);
  }
  $contentgroupdatasingle=array();
  $contentgroupdatageneral=array();
  // setup variables for the collagebox intro system
  $singlevarout='
    // $showhidetitle="display:none;";
    // $showhidesubtitle="display:none;";
    $showhideimage="display:none;";
    // $showhideintro="display:none;";
    $showhidecontent="display:none;";
    $contenttextheaderout="";
    $maintype="'.$truesubtype.'";
    $curvariant="contententryupdate";
    $contenttexttitleout="Collage Box Title";

    $contenttextsubtitleout="Collage Box Sub-title";

    $contenttextcontentout="Collage Box Details (Optional since collage box is being used)";

    $contentplaceholdertitleout="The collage section Title";
    $contentplaceholdersubtitleout="Collage section subtitle";
    $frameout="WHERE maintype=\''.$truesubtype.'\'";
    $formtypeout="submit";
    $formmonitor=\'<input type="hidden" name="monitorcustom" value="1:"/>\';
    $itsubmitbtnname="submitcustom";
    $itformname="contentform";
    // $extraformdata=\'<input name="extraformfields" type="hidden" value="">\';
  ';
  $singlefuncout='';

  $generalintrophasedata='
    if($maintype=="'.$datatype[0].'"){

      // $showhidetitle="display:none;";
      // $showhidesubtitle="display:none;";
      // $showhide="display:none;";
      $showhideimage="display:none;";
      $showhideintro="display:none;";
      // $showhidecontent="display:none;";
      $contenttextheaderout="Home Page'.$part.' Collage Box Introduction</small>";
      $contentplaceholdertitleout="Collage Section title";
      $contentplaceholdersubtitleout="Collage Section sub-title";
      $contenttexttitleout="The Section title goes here";
      $subtypeout="";
    }
  ';
  // $contentgroupdatasingle['evaldata']['single']['initeval']=$singlevarout;
  /*echo "The count is ".count($contentgroupdatasingle['evaldata']['single'])."
    Second count is ".count($contentgroupdatasingle['evaldata']['general'])."
  ";*/
  // setup variables for the collagebox system
  $contentgroupdatageneral['evaldata']['general']=array();
  $contentgroupdatageneral['evaldata']['general']['initeval']=$singlevarout;
  $contentgroupdatageneral['evaldata']['single']['processeval']=$generalintrophasedata;
  // var_dump($contentgroupdatageneral);

  // var_dump($contentgroupdatageneral);
  $introdata=getAllGeneralInfo("admin","$truesubtype","","",$contentgroupdatageneral);
  $outfa=pullFontAwesomeClasses();
  sort($outfa['faiconnames']);
  sort($outfa['famatches']);

  $list="";
  if($outfa['numrows']>0){
    for ($x = 0;$x < $outfa['numrows'];$x++) 
    { 
        $list.='<li class=""><a href="##" data-type="fapicker" data-toggle="tooltip" data-original-title="'.$outfa['faiconnames'][$x].'" title="'.$outfa['faiconnames'][$x].'" value="'.$outfa['famatches'][$x].'"><i class="fa '.$outfa['famatches'][$x].'"></i></a></li>';
    } 
  }
  // create selection list
  $faselectsection='
      
  ';
  $extradata='
      
  ';
  // get the general data for the current entry
  if(isset($cursubtype)&&$cursubtype!==""){
    $processfile="process$cursubtype.php";
    $dogeneraldata="multiple";
    if(file_exists("$processfile")){
      include("$processfile");
    }
    // var_dump($contentgroupdata);
    $outsdata=getAllGeneralInfo("admin",$pagetype,'','',$contentgroupdata);
  }else{
    // echo "here";
    $outsdata=getAllGeneralInfo("admin",$pagetype,'','',$contentgroupdatageneral);
    
  }
  // create form stamp
  $curstamp = date("Y-m-d H:i:s"); // current time 
  $curstamp=md5(strtotime($curstamp));
  $multitest='
    <div class="col-md-12">
      <?php
        $max_lenght=6;
      for ($i=1; $i < $max_lenght; $i++) { 
        # code...
        
      ?>
        <div class="col-sm-4"> 
          <div class="form-group">
            <label>Link Title A:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-file-text"></i>
              </div>
              <input type="text" class="form-control" name="linktitlea<?php echo $i?>" Placeholder="display title for the link"/>
            </div><!-- /.input group -->
          </div><!-- /.form group -->
        </div>
        <div class="col-sm-4"> 
          <div class="form-group">
            <label>Link Title B</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-file-text"></i>
              </div>
              <input type="text" class="form-control" name="linktitleb<?php echo $i?>" Placeholder="display title for the link"/>
            </div><!-- /.input group -->
          </div><!-- /.form group -->
        </div>
        <div class="col-sm-4"> 
          <div class="form-group">
            <label>Link Title C:</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-file-text"></i>
              </div>
              <select class="form-control" name="linktitlec<?php echo $i?>">
                <option value="">Choose Something</option> 
                <option value="Opt 1">Opt 1</option> 
                <option value="Opt 2">Opt 2</option> 
                <option value="Opt 3">Opt 3 Something</option> 
              </select>
              <!-- <input type="text" class="form-control" name="linktitlec" Placeholder="display title for the link"/> -->
            </div><!-- /.input group -->
          </div><!-- /.form group -->
        </div>  
      <?php
       }
      ?>
      <input type="hidden" name="testmultiplevaliddata" value="<?php echo $max_lenght;?>">
    </div>
  ';
?>
<div class="box">
      <div class="box-body">
        <div class="box-group" id="generaldataaccordion">
          <div class="panel box overflowhidden box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlockIntro">
                    <i class="fa fa-gear"></i> Create/Edit Collage Box Section Introduction
                  </a>
                </h4>
              </div>
              <div id="EditBlockIntro" class="panel-collapse collapse">
                <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <?php
                          echo $introdata['introoutputtwo'];
                        ?>
                      </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="panel box overflowhidden box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
                        <i class="fa fa-sliders"></i> New Collage Box
                      </a>
                    </h4>
                </div>
                <div id="NewPageManagerBlock" class="panel-collapse collapse ">
                    <div class="row">
                        <form name="collageboxes" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
                          <input type="hidden" name="entryvariant" value="contententry"/>
                          <input type="hidden" name="maintype" value="<?php echo $pagetype?>"/>
                          <input type="hidden" name="subtype" value="collagebox"/>

                          <div class="col-md-12" name="surveysliderpoint">
                                <!-- <h4>Create New Collage Box</h4> -->
                                <input type="hidden" name="curbannerslidecount" value="1"/>
                                <div class="col-md-12">
                                    <div class="col-sm-6"> 
                                      <div class="form-group">
                                        <label>Choose Color Group</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                          </div>
                                          <select name="collagecolourtype" class="form-control">
                                            <option value="">Choose Collage Color Group</option>
                                            <option value="red">Red</option>
                                            <option value="orange">Orange</option>
                                            <option value="yellow">Yellow</option>
                                            <option value="green">Green</option>
                                            <option value="blue">Blue</option>
                                            <option value="purple">Indigo</option>
                                            <option value="maroon">Violet</option>
                                          </select>
                                        </div><!-- /.input group -->
                                      </div><!-- /.form group -->
                                    </div>
                                    <div class="col-sm-6"> 
                                      <div class="form-group">
                                        <label>Collage Box Title
                                        </label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                          </div>
                                          <input type="text" class="form-control" name="contenttitle" Placeholder="The collage box title"/>
                                        </div><!-- /.input group -->
                                      </div><!-- /.form group -->
                                    </div>
                                    <div class="col-md-12">
                                        <label>Collage Box Content(brief content)</label>
                                        <textarea class="form-control" rows="3" name="contentpost" data-mce="true" id="postersmallfive" placeholder="Collage box details"></textarea>
                                    </div>
                                    <div class="col-sm-6"> 
                                      <div class="form-group">
                                        <label>link Address(if this collage box leads to another page):</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                          </div>
                                          <input type="text" class="form-control" name="linkaddress" Placeholder="Full Link address "/>
                                        </div><!-- /.input group -->
                                      </div><!-- /.form group -->

                                    </div>
                                    <div class="col-sm-6"> 
                                      <div class="form-group">
                                        <label>Link Title(The text the link displays):</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-file-text"></i>
                                          </div>
                                          <input type="text" class="form-control" name="linktitle" Placeholder="display title for the link"/>
                                        </div><!-- /.input group -->
                                      </div><!-- /.form group -->
                                    </div>  
                                    <div name="entrybannerslidepoint"></div>
                                </div>
                                
                                <div class="col-md-12 faselectsectionhold">
                                  <div class="col-md-12 fadisplaypoint">
                                    <div class="col-sm-12"><h3 class="labelheading">Choose Collage Icon</h3></div>
                                    <div class="col-md-1 currentfa">
                                      <i class=""></i>
                                    </div>
                                    <div class="col-md-4 textfieldfa">
                                        <div class="form-group">
                                          <input type="text" class="form-control" data-name="icontitle" name="icontitle" readonly Placeholder="Selected icon code displays here"/>
                                        </div>
                                    </div>  
                                  </div>
                                  <div class="col-md-12 fadisplaylisthold">
                                    <ul class="fadisplaylist">
                                      <?php echo $list; ?>
                                    </ul>
                                  </div>
                                </div>
                                <!-- form control section -->
                                <input type="hidden" name="formdata" value="collageboxes"/>
                                <input type="hidden" name="extraformdata" value="
                                  collagecolourtype-:-select<|>
                                  contenttitle-:-input<|>
                                  contentpost-:-textarea<|>
                                  linktitle-:-input<|>
                                  icontitle-:-input<|>
                                  linkaddress-:-input
                                  "/>
                                  <!--  -->
                                <input type="hidden" name="errormap" value="
                                    collagecolourtype-:-Please Select a Color<|>
                                    contenttitle-:-Please provide a title<|>
                                    contentpost-:-Please give the Collage box content details. In a brief format.<|>
                                    linktitle-:-Provide the title for the link in this collage box, preferably a one or two words<|>
                                    icontitle-:-NA<|>
                                    linkaddress-:-Give the url/web address for the link here.
                                    "/>

                                <!-- <a href="##" class="addpoint" name="addextrabannerslide">Click to add another slide</a> -->
                          </div>
                          <div class="col-md-12">
                              <div class="box-footer">
                                  <input type="button" class="btn btn-danger" name="submitcustomtwo" data-formdata="collageboxes" value="Create"/>
                              </div>
                          </div>
                        </form>
                    </div>
                </div>
          </div>
          <div class="panel box overflowhidden box-primary">
                <div class="box-header with-border">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
                      <i class="fa fa-gear"></i> Edit Collage Boxes
                    </a>
                  </h4>
                </div>
                <div id="EditBlock" class="panel-collapse collapse in">
                  <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <?php
                            echo $outsdata['adminoutput'];
                          ?>
                        </div>
                    </div>
                  </div>
                </div>
          </div>
      </div>
</div>
<script>
  tinyMCE.init({
          theme : "modern",
          selector:"textarea#postersmallfive",
          menubar:false,
          statusbar: false,
          plugins : [
           "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
           "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
          ],
          width:"100%",
          height:"250px",
          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
          toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
          image_advtab: true ,
          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          external_filemanager_path:""+host_addr+"scripts/filemanager/",
          filemanager_title:"Content Filemanager" ,
          external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
  });
</script>
