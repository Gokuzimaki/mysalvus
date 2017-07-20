<?php
    $outs=getAllHomeBanners("admin","",'');
  ?>
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Create/Edit Home Page Banners</h3>
        <div class="box-tools pull-right">
          <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
          <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
        </div>
      </div>
      <div class="box-body">
        <div class="box-group" id="generaldataaccordion">
              <div class="panel box overflowhidden box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#homesliders">
                            <i class="fa fa-file-image-o"></i> Home Default Sliders
                          </a>
                        </h4>
                    </div>
                    <div id="homesliders" class="panel-collapse collapse in">
                  <form name="homesliderbanners" method="POST" action="../snippets/edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="entryvariant" value="createhomesliderbanners">
                    <input type="hidden" name="curslidescount" value="6">
                          <input type="hidden" name="maintype" value="homemainbanners"/>
                          <?php echo $outs['homebannersedit'];?>
                        <div class="col-md-12">
                                    <div class="box-footer">
                                        <input type="button" class="btn btn-danger" name="homebannersmainsubmit" value="Create"/>
                                    </div>
                              </div>
                  </form>
                </div>
              </div>
              <div class="panel box overflowhidden box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
                            <i class="fa fa-sliders"></i> New Home Page Banners
                          </a>
                        </h4>
                    </div>
                    <div id="NewPageManagerBlock" class="panel-collapse collapse">
                        <div class="row">
                            <form name="homebanners" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
                              <input type="hidden" name="entryvariant" value="createhomebannerentry"/>
                              <input type="hidden" name="maintype" value="homemainbanners"/>
                              <input type="hidden" name="subtype" value="bannerentry"/>
                              <div class="col-md-12" name="">
                                    <h4>Create New Home Page Banner</h4>
                                    <input type="hidden" name="curbannerslidecount" value="1"/>
                                    <div class="col-md-12">
                                          <div class="col-xs-6">
                                    <div class="form-group">
                                      <label>New banner image (1)</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-image"></i>
                                        </div>
                                            <input type="hidden" name="entryid1" value="0"/>
                                        <input type="hidden" name="imgid1" value="0"/>
                                          <input type="hidden" name="subtype1" value="bannerentry"/>
                                        <input type="file" class="form-control" name="contentpic1" Placeholder=""/>
                                     </div><!-- /.input group -->
                                  </div>
                                  <div class="form-group">
                                      <label>Caption Text Main(1)</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-file-text-o"></i>
                                        </div>
                                        <input type="text" class="form-control" name="headercaption1" Placeholder=""/>
                                     </div><!-- /.input group -->
                                  </div>
                                  <div class="form-group">
                                      <label>Caption Text Small(1)</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                          <i class="fa fa-file-text-o"></i>
                                        </div>
                                        <input type="text" class="form-control" name="minicaption1" Placeholder=""/>
                                     </div><!-- /.input group -->
                                  </div>
                                          </div>
                                          
                                        <div name="entrybannerslidepoint"></div>
                                      </div>
                                  <a href="##" class="addpoint" name="addextrabannerslide">Click to add another slide</a>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="box-footer">
                                          <input type="button" class="btn btn-danger" name="homebannersubmit" value="Create"/>
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
                          <i class="fa fa-gear"></i> Edit Previously Uploaded Extra Banners
                        </a>
                      </h4>
                    </div>
                    <div id="EditBlock" class="panel-collapse collapse">
                      <div class="box-body">
                          <?php
                                echo $outs['adminoutput'];
                              ?>
                      </div>
                    </div>
              </div>
        </div>
      </div>
  </div>