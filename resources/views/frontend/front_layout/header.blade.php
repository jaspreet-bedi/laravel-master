<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Home | Bookshop Responsive Bootstrap4 Template</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicons -->
        <link rel="shortcut icon" href="{{ url('/assets/front_theme/images/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ url('/assets/front_theme/images/icon.png') }}">

        <!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet"> 

        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ url('/assets/front_theme/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/front_theme/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/front_theme/style.css') }}">

        <!-- Cusom css -->
        <link rel="stylesheet" href="{{ url('/assets/front_theme/css/custom.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css">
        <!-- Modernizer js -->
        <script src="{{ url('/assets/front_theme/js/vendor/modernizr-3.5.0.min.js') }}"></script>
        
        <script src="{{ url('/assets/front_theme/js/vendor/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ url('/assets/front_theme/js/jquery.validate.js') }}"></script>
        <script src="{{ url('/assets/front_theme/js/popper.min.js') }}"></script>
        <script src="{{ url('/assets/front_theme/js/bootstrap.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.js"></script>
        
        <script>
            var site_url = '{{url('/')}}';
        </script>
    </head>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5dc2e91e154bf74666b7d488/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
    <body>
        <!--[if lte IE 9]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Main wrapper -->
        <div class="wrapper" id="wrapper">
            <input type="hidden" value="{{csrf_token()}}" id="csrf-token"/>
            <!-- Header -->
            <header id="wn__header" class="header__area header__absolute sticky__header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6 col-lg-2">
                            <div class="logo">
                                <a href="index.html">
                                    <img src="{{ url('/assets/front_theme/images/logo/logo.png') }}" alt="logo images">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-8 d-none d-lg-block">
                            <nav class="mainmenu__nav">
                                <ul class="meninmenu d-flex justify-content-start">
                                    <li class="drop with--one--item"><a href="{{ url('/frontend/home') }}">Home</a></li>
                                    

                                    @foreach ($departments  as $department)         
                                                                     
                                  
                                    <li class="drop"><a href="#">{{ $department->name }}</a>
                                        <div class="megamenu mega03">
                                         <?php  $categories=$allDeptsCategories[$department->id];  ?>
                                            
                                                @foreach ($categories as $category)
                                            <ul class="item item03">
                                            
                                                <li class="title">
                                                    <?php  $catid=$category->id; ?>
                                                   {{ $category->name }}                                                                                                    
                                                </li>
                                                
                                                @foreach ($allDepsCatsSubcats[$department->id][$category->id] as $subcategory)
                                                <li>
                                                   
                                                    <a href="{{url('/frontend/shopgrid/'.$subcategory->id)}}" >{{ $subcategory->name  }}  </a>    
                                       <!--             <a href="http://localhost:8082/laravel-master/public/frontend/shopgrid/{{ $subcategory->id }}/"  >{{ $subcategory->name  }}  </a>    -->
                                            
                                                </li>  
                                                
                                                @endforeach
                                                
                                            </ul>
                                                @endforeach
                                           
                                        </div>
                                    </li>
                                      @endforeach  

                                    <li class="drop"><a href="blog.html">Blog</a>
                                        <div class="megamenu dropdown">
                                            <ul class="item item01">
                                                <li><a href="blog.html">Blog Page</a></li>
                                                <li><a href="blog-details.html">Blog Details</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-6 col-lg-2">
                            <ul class="header__sidebar__right d-flex justify-content-end align-items-center">
                                <li class="shop_search"><a class="search__active" href="#"></a></li>
                                <li class="wishlist"><a href="#"></a></li>
                                <!-- Start Shopping Cart -->
                                 <?php   
                                                 if(!empty($totalItems))
                                                 $quantity=$totalItems[0]->quantity;
                                                 else
                                                 $quantity="0";    
                                                ?>
                                <li class="shopcart"><a class="cartbox_active" href="#" id="cart_qty"><span class="product_qun" >{{ $quantity }}</span></a>
                                    
                                   
                                    <div class="block-minicart minicart__active" id="cart_item">
                                        @if(session("customer"))  
                                        <div class="minicart-content-wrapper">
                                            
                                            
                                            <div class="micart__close">
                                                <span>close</span>
                                            </div>
                                                                           
                                                                           
                                                                        
                                            <div class="items-total d-flex justify-content-between">
                                                
                                                <span>{{ $quantity  }} items</span>
                                                <span>Cart Subtotal</span>
                                            </div>
                                            <div class="total_amount text-right">
                                                 <?php   
                                                 if(!empty($totalPrice))
                                                 $price=$totalPrice[0]->price;
                                                 else
                                                 $price="0.00";    
                                                ?>
                                                <span>$ {{  $price }}</span>
                                            </div>
                                            <div class="mini_action checkout">
                                                <a class="checkout__btn" href="cart.html">Go to Checkout</a>
                                            </div>
                                            <div class="single__items" >
                                                
                                       @if(!empty($cartRows))    
                                            @foreach ($cartRows as $cartRow)
                                                <?php
                                                $image = !empty($productImagesArr[$cartRow->product_id]) ? 'assets/images/products/'.$cartRow->product_id.'/'.$productImagesArr[$cartRow->product_id] : 'assets/images/products/no_image.jpg';
                                                ?>
                                                <div class="miniproduct">
                                                    <div class="item01 d-flex">
                                                        <div class="thumb">
                                                            <a href="product-details.html"><img src="{{ url($image) }}" alt="product images"></a>
                                                        </div>
                                                        <div class="content">
                                                            <h6><a href="product-details.html">{{ $cartRow->product_name }}</a></h6>
                                                            <span class="prize">${{ number_format($cartRow->product_price * $cartRow->quantity, 2) }}</span>
                                                            <div class="product_prize d-flex justify-content-between">
                                                                <span class="qun">Qty: {{ $cartRow->quantity }}</span>
                                                                <ul class="d-flex justify-content-end">
                                                                    <li><a href="#"><i class="zmdi zmdi-settings"></i></a></li>
                                                                    <li><a href="#" onclick="deleteItem({{$cartRow->customer_id}},{{$cartRow->product_id}})"><i class="zmdi zmdi-delete"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
                                           
				        @endforeach
                                            @endif     
                                            </div>
                                            <div class="mini_action cart">
                                                <a class="cart__btn" href="{{ url('/frontend/cart/showCart') }}">View and edit cart</a>
                                            </div>
                                            
                                        </div>
                                        @else
                                         <div class="minicart-content-wrapper">
                                            
                                            
                                            <div class="micart__close">
                                                <span>close</span>
                                            </div>
                                                                           
                                                                           
                                                                        
                                            <div class="items-total d-flex justify-content-between">
                                                <span><a href="{{ url('/frontend/account') }}">Sign In to see cart</a></span>
                                            </div>
                                      </div>
                                    @endif
                                    </div>   
                                
                                    
                                </li>
                                <!-- End Shopping Cart -->
                                <li class="setting__bar__icon"><a class="setting__active" href="#"></a>
                                    <div class="searchbar__content setting__block">
                                        <div class="content-inner">
                                          
                                            
                                            <div class="switcher-currency">
                                                <strong class="label switcher-label">
                                                    <span>My Account</span>
                                                </strong>
                                                <div class="switcher-options">
                                                    <div class="switcher-currency-trigger">
                                                        <div class="setting__menu">
                                                            <span><a href="{{ url('frontend/account/showAccountDetails') }}">My Account</a></span>
                                                            <span><a href="{{ url('frontend/order/index') }}">Orders</a></span>
                                                            <?php
                                                            if(session()->has('customer')){
                                                               ?>
                                                                <span><a href="{{ url('frontend/account/signout') }}">Sign Out</a></span>
                                                               <?php
                                                            }else{
                                                                ?>
                                                                <span><a href="{{ url('frontend/account') }}">Sign In</a></span>
                                                                <?php
                                                            }
                                                            ?>
                                                            
                                                            <span><a href="{{ url('frontend/account') }}">Create An Account</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Start Mobile Menu -->
                    <div class="row d-none">
                        <div class="col-lg-12 d-none">
                            <nav class="mobilemenu__nav">
                                <ul class="meninmenu">
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="#">Pages</a>
                                        <ul>
                                            <li><a href="about.html">About Page</a></li>
                                            <li><a href="portfolio.html">Portfolio</a>
                                                <ul>
                                                    <li><a href="portfolio.html">Portfolio</a></li>
                                                    <li><a href="portfolio-details.html">Portfolio Details</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="my-account.html">My Account</a></li>
                                            <li><a href="cart.html">Cart Page</a></li>
                                            <li><a href="checkout.html">Checkout Page</a></li>
                                            <li><a href="wishlist.html">Wishlist Page</a></li>
                                            <li><a href="error404.html">404 Page</a></li>
                                            <li><a href="faq.html">Faq Page</a></li>
                                            <li><a href="team.html">Team Page</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="shop-grid.html">Shop</a>
                                        <ul>
                                            <li><a href="shop-grid.html">Shop Grid</a></li>
                                            <li><a href="single-product.html">Single Product</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="blog.html">Blog</a>
                                        <ul>
                                            <li><a href="blog.html">Blog Page</a></li>
                                            <li><a href="blog-details.html">Blog Details</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- End Mobile Menu -->
                    <div class="mobile-menu d-block d-lg-none">
                    </div>
                    <!-- Mobile Menu -->	
                </div>		
            </header>
            <!-- //Header -->
            <!-- Start Search Popup -->
            <div class="brown--color box-search-content search_active block-bg close__top">
                <form id="search_mini_form" class="minisearch" action="#">
                    <div class="field__search">
                        <input type="text" placeholder="Search entire store here...">
                        <div class="action">
                            <a href="#"><i class="zmdi zmdi-search"></i></a>
                        </div>
                    </div>
                </form>
                <div class="close__wrap">
                    <span>close</span>
                </div>
            </div>
<script src="{{url('assets/js/frontend/front_layout/header.js')}}"></script>