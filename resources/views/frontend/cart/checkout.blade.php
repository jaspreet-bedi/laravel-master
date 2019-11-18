@include('frontend/front_layout/header');
<br><br><br><br>
        <!-- Start Bradcaump area -->
        
        
         
        <!-- End Bradcaump area -->
		<!-- Start My Account Area -->
		<section class="my_account_area pt--80 pb--55 bg--white">
			<div class="container">
				<div class="row">

					<div class="col-lg-6 col-12">
						<div class="my__account__wrapper">
							<h3>Confirm yours Billing and Shipping Details</h3>
                                                        
                                               </div>
                                        </div>          
                                </div> 
                             <form id="account_details_form">
                            <div class="row">
                      
					<div class="col-lg-6 col-12">
                                                       
							 
                                                    
                                                              {{csrf_field()}}
                                                          
                                                              <input name="email"  id="email" type="hidden"  value="{{ $accountDetails['email'] }}">
								<div class="account__form">

                                                                    
                                                                    <div class="input__box">
										<label for="name">Name <span>*</span></label>
										<input name="name"  id="name" type="text" value="{{ $accountDetails['name'] }}">
                                                                                
							           </div>

                                                                    <div class="input__box">
										<label for="company">Company<span>*</span></label>
										   <input name="company" id="company"  type="text" value="{{ $accountDetails['company'] }}">
                                                                                
							            </div>

                                                                    <div class="input__box">
										<label for="country">Country <span>*</span></label>
										<select class="select__option" name="country" id="country" value="{{ $accountDetails['country'] }}">
                                                                                <option>{{ $accountDetails['country'] }}</option>
<!--                                                                                <option>India</option>-->
                                                                                <option>US</option>
                                                                            </select>
                                                                                
								    </div>
                                                                    
                                                                    <div class="input__box">
										<label for="state">State <span>*</span></label>
                                                                                  <input name="state" id="state"  type="text" value="{{ $accountDetails['state'] }}">
<!--										<select class="select__option" name="state" id="state" value="{{ $accountDetails['state'] }}">
                                                                                <option>{{ $accountDetails['state'] }}</option>
                                                                                <option>CA</option>
                                                                                <option>Punjab</option>
                                                                                <option>U.P</option>
                                                                                <option>Haryana</option>
                                                                                <option>Bihar</option>
                                                                                <option>Himachal</option>-->

                                                                            </select>
                                                                                
                    					            </div>
                                                                </div>    
                                                 </div>
<!--                                        </div>      -->
                                                                   
<!--                                    <div class="row">-->

					<div class="col-lg-6 col-12">
                                                                    <div class="account__form">
                                                                    <div class="input__box">
										<label for="city">City <span>*</span></label>
                                                                                   <input name="city" id="city"  type="text" value="{{ $accountDetails['city'] }}">
<!--										<select class="select__option" name="city" id="city" value="{{ $accountDetails['city'] }}">-->
<!--                                                                                <option>{{ $accountDetails['city'] }}</option>
                                                                                <option>San Francisco</option>
                                                                                <option>Los Angeles</option>
                                                                                <option>Riverside</option>
                                                                                <option>San Diego</option>
                                                                                <option>Chandigarh</option>
                                                                                <option>Ludhiana</option>
                                                                                <option>Patiala</option>
                                                                                <option>Bathinda</option>
                                                                                <option>Ferozpur</option>-->

                                                                            </select>
                                                                                
									</div>

                                                                     <div class="input__box">
										<label for="street">Street <span>*</span></label>
										 <input name="street"  id="street" type="text" value="{{ $accountDetails['street'] }}">
                                                                                
									</div>
                                                                    
                                                                    <div class="input__box">
										<label for="postcode">Postal code <span>*</span></label>
										       <input name="postcode"  id="postcode" type="text" value="{{ $accountDetails['postcode'] }}">
                                                                                
									</div>

                                                                  <div class="input__box">
										<label for="mobile">Mobile<span>*</span></label>
										 <input name="phone"  id="phone" type="text" value="{{ $accountDetails['phone'] }}">
                                                                                
									</div>
                                                                           
                                                                    </div>
                                                            </div>
<!--                                            </div>-->
                                                                    
									<span class="form__btn text-right">
							<button class="btn btn-primary" formaction="{{URL::to('/frontend/order/saveShipmentAddressInSession')}}" >Continue</button> 
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
<script src="{{url('assets/js/frontend/cart/saveShipmentAddress.js')}}"></script>	
<script src="{{url('assets/js/frontend/account/myAccount.js')}}"></script>	
		
@include('frontend/front_layout/footer');