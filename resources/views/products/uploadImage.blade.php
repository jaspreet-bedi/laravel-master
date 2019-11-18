@include('layout.header');
@include('layout.sidebar');

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        General Form Elements
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">General Elements</li>
      </ol>
    </section>
    
    <section class="content">
      <div class="row">
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Subcategory Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <input type="hidden" id="product_id" value="{{$product_id}}"/>
<!--            <form action="{{URL::to('/products/storeImage')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">-->
                <input type="hidden" value="{{csrf_token()}}" id="csrf-token"/>
              <div class="box-body"> 
                 
                
                   <div class="form-group">
                  <label for="prodImage" class="col-sm-2 control-label">Upload Image</label>

                  <div class="col-sm-8">
                        <input type="file" name="upload_image" id="upload_image" style="display: none;"/>
                        <span id="add_image_button" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            <span>Add Image</span>
                        </span>
<!--                    <input type="file" name ="prodImages[]" id="prodImages" placeholder="prodImages" multiple>-->
                  </div>
                </div> 
                  
               
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Save</button>
              </div>
              <!-- /.box-footer -->
             
<!--            </form>-->
          </div>
          <!-- /.box -->
          <!-- general form elements disabled -->
        
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
  </div>
<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog " style="width:1250px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload & Crop Image</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="image_demo" style="display: inline-block;
                             vertical-align: top;
                             float: none;
                             margin: 0px!important;" class="center">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button class="btn btn-success crop_image">Crop & Upload Image</button>
                <button onclick="location.reload();" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="{{url('assets/js/products/upload_image.js')}}"></script>
@include('layout.footer');
