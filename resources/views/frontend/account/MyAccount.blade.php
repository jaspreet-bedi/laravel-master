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
        
         @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}           
         </div>
     @endif
        <!-- End Bradcaump area -->
		<!-- Start My Account Area -->
		<section class="my_account_area pt--80 pb--55 bg--white">
			<div class="container">
				<div class="row">

					<div class="col-lg-6 col-12">
						<div class="my__account__wrapper">
							<h3 class="account__title">My Account Details</h3>
                                                        
                                               </div>
                                        </div>          
                                </div> 
                        <form action="{{URL::to('/frontend/account/updateAccountDetails')}}" method="POST" id="account_details_form">    
                            <div class="row">
                      
					<div class="col-lg-6 col-12">
                                                       
							  
                                                              {{csrf_field()}}
								<div class="account__form">

                                                                    
                                                                    <div class="input__box">
										<label for="name">Name <span>*</span></label>
										<input type="text" name="name" id="name" value="{{ $accountDetails['name'] }}">
                                                                                
							           </div>

                                                                    <div class="input__box">
										<label for="company">Company<span>*</span></label>
										<input type="text" name="company" id="company" value="{{ $accountDetails['company'] }}">
                                                                                
							            </div>

                                                                    <div class="input__box">
										<label for="country">Country <span>*</span></label>
											<input type="text" name="country" id="country" value="{{ $accountDetails['country'] }}">
                                                                                
								    </div>
                                                                    
                                                                    <div class="input__box">
										<label for="state">State <span>*</span></label>
										<input type="text" name="state" id="state" value="{{ $accountDetails['state'] }}">
                                                                                
                    					            </div>
                                                                </div>    
                                                 </div>
<!--                                        </div>      -->
                                                                   
<!--                                    <div class="row">-->

					<div class="col-lg-6 col-12">
                                                                    <div class="account__form">
                                                                    <div class="input__box">
										<label for="city">City <span>*</span></label>
									<input type="text" name="city" id="city" value="{{ $accountDetails['city'] }}">
                                                                                
									</div>

                                                                     <div class="input__box">
										<label for="street">Street <span>*</span></label>
												<input type="text" name="street" id="street" value="{{ $accountDetails['street'] }}">
                                                                                
									</div>
                                                                    
                                                                    <div class="input__box">
										<label for="postcode">Postal code <span>*</span></label>
											<input type="text" name="postcode" id="postcode" value="{{ $accountDetails['postcode'] }}">
                                                                                
									</div>

                                                                  <div class="input__box">
										<label for="mobile">Mobile<span>*</span></label>
										<input type="text" name="phone" id="phone" value="{{ $accountDetails['phone'] }}">
                                                                                
									</div>
                                                                    </div>
                                                            </div>
<!--                                            </div>-->
                                                                    
									<span class="form__btn text-right">
										<button class="btn btn-primary" >Update</button>
<!--                                                                                <button type="submit" formaction="{{URL::to('/frontend/account/authenticateUser')}}">Login</button>-->
                                                                        </span>
<!--                                                                           <div class="form__btn">
                                                                                <a class="btn btn-primary" href="{{URL::to('/frontend/home')}}">Skip</a>  
                                                                            </div>
	-->
                                                                    
								
						</div>
                            </form>

					</div>
				</div>
			</div>
		</section>
		<!-- End My Account Area -->
		
<script src="{{url('assets/js/frontend/account/myAccount.js')}}"></script>
@include('frontend/front_layout/footer');