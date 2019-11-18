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
              <h3 class="box-title">Horizontal Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{URL::to('/users/saveUser')}}" method="POST" class="form-horizontal">
                {{csrf_field()}}
              <div class="box-body"> 
                  
                  <div class="form-group">
                  <label for="Username" class="col-sm-2 control-label">Username</label>

                  <div class="col-sm-8">
                    <input type="text" name="username" class="form-control" id="Username" placeholder="Username">
                  </div>
                </div>
                  
                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-8">
                    <input type="email" name ="email" class="form-control" id="inputEmail3" placeholder="Email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-8">
                    <input type="password" name ="password" class="form-control" id="inputPassword3" placeholder="Password">
                  </div>
                </div>
                  
                  <div class="form-group">
                  <label for="ConfirmPassword" class="col-sm-2 control-label">Confirm Password</label>

                  <div class="col-sm-8">
                    <input type="password" name ="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="ConfirmPassword">
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