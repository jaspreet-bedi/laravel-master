@include('frontend/front_layout/header');

        <!-- Start Bradcaump area -->
        <div class="ht__bradcaump__area bg-image--6">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bradcaump__inner text-center">
                        	<h2 class="bradcaump-title">My Account</h2>
                            <nav class="bradcaump-content">
                              <a class="breadcrumb_item" href="index.html">Home</a>
                              <span class="brd-separetor">/</span>
                              <span class="breadcrumb_item active">My Account</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- End Bradcaump area -->
		<!-- Start My Account Area -->
		<section class="my_account_area pt--80 pb--55 bg--white">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-12">
						<div class="my__account__wrapper">
							<h3 class="account__title">Login</h3>
							<form>
								<div class="account__form">
									<div class="input__box">
										<label>Username or email address <span>*</span></label>
										<input type="text" name="email" id="ëmail">
									</div>
									<div class="input__box">
										<label>Password<span>*</span></label>
										<input type="password" name="password" id="password"> 
									</div>
                                                                    
									<div class="form__btn">
										<!-- <button>Login</button> -->
                                                                                 <button type="submit" formaction="{{URL::to('/frontend/account/authenticateUser')}}">Login</button>
										<label class="label-for-checkbox">
											<input id="rememberme" class="input-checkbox" name="rememberme" value="forever" type="checkbox">
											<span>Remember me</span>
										</label>
                                                                                 <br><br>
                                                                         @if (session('loginStatus'))
                                                                            <div class="alert alert-success">
                                                                              {{ session('loginStatus') }}           
                                                                             </div>
                                                                         @endif
                                                                         
									</div>
									
								</div>
							</form>
                                                       
						</div>
					</div>
					<div class="col-lg-6 col-12">
						<div class="my__account__wrapper">
							<h3 class="account__title">Register</h3>
                                                       
							<form action="{{URL::to('/frontend/account/registerUser')}}" method="POST" id="registration_form">
                                                              {{csrf_field()}}
								<div class="account__form">
                                                                     @if (session('registrationStatus'))
                                                                            <div class="alert alert-success">
                                                                              {{ session('registrationStatus') }}           
                                                                             </div>
                                                                         @endif
									<div class="input__box">
										<label for="email">Email address <span>*</span></label>
										<input type="email" name="email" id="email">
                                                                                
									</div>
									<div class="input__box">
										<label for="password">Password<span>*</span></label>
										<input type="password" name="password" id="password">
                                                                                
                                                                        </div>
<!--                                                                    <div class="input__box">
										<label for="confirmpassword">Confirm Password<span>*</span></label>
										<input type="password" name="confirmPassword" id="confirmpassword">
                                                                                
									</div>-->
                                                                    
                                                                    
									<div class="form__btn">
										<button>Register</button>
<!--                                                                                <button type="submit" formaction="{{URL::to('/frontend/account/authenticateUser')}}">Login</button>-->
									</div>
	
                                                                    
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End My Account Area -->
		
<script src="{{url('assets/js/frontend/account/signupLogin.js')}}"></script>		
@include('frontend/front_layout/footer');