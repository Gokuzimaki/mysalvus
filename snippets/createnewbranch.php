<?php 
  
  $outs=getAllBranches("admin","","");
?>
<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Branch Locator Create/Edit Section</h3>
          <div class="box-tools pull-right">
            <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
            <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
          </div>
        </div>
        <div class="box-body">
            <div class="box-group" id="generaldataaccordion">
                  <div class="panel box overflowhidden box-primary" data-name="branchdata">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#projectdataBlock">
                            <i class="fa fa-gear"></i> Create Branch
                          </a>
                        </h4>
                      </div>
                      <div id="projectdataBlock" class="panel-collapse collapse" data-name="projectdatacontent">
                        <div class="box-body">
                            <div class="row overflowhidden">
                            <form name="branchform" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
                                <input type="hidden" name="entryvariant" value="createbranch"/>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Location Title</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="locationtitle" Placeholder="e.g LEKKI OFFICE"/>
                                        </div><!-- /.input group -->
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Set this as Main Branch?</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <select name="mainbranch" class="form-control">
                                              <option value="">Choose Option</option>
                                              <option value="mainbranch">Main Branch</option>
                                            </select>
                                        </div><!-- /.input group -->
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Latitude <small>value with 6 decimal places required</small></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="latitude" Placeholder="e.g 50.897678"/>
                                        </div><!-- /.input group -->
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Longitude <small>value with 6 decimal places required</small></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="longitude" Placeholder="e.g 50.897678"/>
                                        </div><!-- /.input group -->
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <label>Address</label>
                                          <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-bars"></i>
                                              </div>
                                              <textarea class="form-control" id="postersmallfive" name="address" Placeholder=""></textarea>
                                          </div><!-- /.input group -->
                                        </div>
                                  </div>
                                  <div class="col-md-12" name="projectsentry">
                                    <input type="hidden" name="curcontactcount" value="1"/>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label>Contact Persons (1)</label>
                                          <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-bars"></i>
                                              </div>
                                              <input type="text" class="form-control" name="contactpersons1" Placeholder="Contact Persons e.g Segun Ibrahim"/>
                                          </div><!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label>Phone Numbers</label>
                                          <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-bars"></i>
                                              </div>
                                              <input type="text" class="form-control" name="phonenumbers1" Placeholder="Phone Numbers (1)"/>
                                          </div><!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label>Email </label>
                                          <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-at"></i>
                                              </div>
                                              <input type="text" class="form-control" name="email1" Placeholder="Email Address (1)"/>
                                          </div><!-- /.input group -->
                                        </div>
                                    </div>
                                    <div name="entrycontactpoint"></div>
                                    <a href="##" name="addextracontact">Click to add another contact person</a>
                                  </div>

                                  <div class="col-md-12">
                                    <div class="box-footer">
                                        <input type="button" class="btn btn-danger" name="newbranchsubmit" value="Create"/>
                                    </div>
                                  </div>
                            </form>
                            
                            </div>
                        </div>
                      </div>
                  </div>
                  <div class="panel box overflowhidden box-primary" data-name="brancheditdata">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#branchdataBlock">
                            <i class="fa fa-gear"></i> Edit Branches 
                          </a>
                        </h4>
                      </div>
                      <div id="branchdataBlock" class="panel-collapse collapse in" data-name="brancheditdata">
                        <div class="box-body">
                          <div class="row overflowhidden">
                            <?php echo $outs['adminoutput'];?>
                          </div>
                        </div>
                      </div>
                  </div>
                <script>
                  tinyMCE.init({
                        theme : "modern",
                        selector: "textarea#adminposter",
                        skin:"lightgray",
                        width:"94%",
                        height:"650px",
                        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
                        plugins : [
                         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                        ],
                        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                        image_advtab: true ,
                        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                        external_filemanager_path:""+host_addr+"scripts/filemanager/",
                        filemanager_title:"Goldlink Admin Blog Content Filemanager" ,
                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                  });
                  tinyMCE.init({
                          theme : "modern",
                          selector:"textarea#postersmallthree",
                          menubar:false,
                          statusbar: false,
                          plugins : [
                           "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                           "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                          ],
                          width:"100%",
                          height:"400px",
                          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                          toolbar2: "| responsivefilemanager | link unlink anchor | code",
                          image_advtab: true ,
                          content_css:""+host_addr+"stylesheets/mce.css",
                          external_filemanager_path:""+host_addr+"scripts/filemanager/",
                          filemanager_title:"Site Content Filemanager" ,
                          external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                  });
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
                          height:"300px",
                          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                          toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
                          image_advtab: true ,
                          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                        external_filemanager_path:""+host_addr+"scripts/filemanager/",
                          filemanager_title:"Goldlink Blog Content Filemanager" ,
                          external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                  });   
                </script>
            </div>
        </div>
    </div>