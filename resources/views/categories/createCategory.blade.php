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
              <h3 class="box-title">Category Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{URL::to('/categories/createCategory')}}" method="POST" class="form-horizontal">
                {{csrf_field()}}
              <div class="box-body"> 
                 
                   <div class="form-group">
                       <label for="Department" class="col-sm-2 control-label" >Department</label>
                   <div class="col-sm-8">
                  <select class="form-control" name="departments">                    
                    @foreach ($departments as $department)         
                       <option value="{{ $department->id }}"> {{ $department->name }} </option>                                  
                    @endforeach                                                               
                  </select>
                   </div>
                </div>
                  
                  <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">Category Name</label>

                  <div class="col-sm-8">
                    <input type="text" name="name" class="form-control" id="name" placeholder="name">
                  </div>
                </div>
                  
                  
                <div class="form-group">
                  <label for="description" class="col-sm-2 control-label">Description</label>

                  <div class="col-sm-8">
                    <input type="text" name ="description" class="form-control" id="description" placeholder="description">
                  </div>
                </div>                                                        
               
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Save</button>
              </div>
              <!-- /.box-footer -->
             
            </form>
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


@include('layout.footer');