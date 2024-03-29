@include('frontend/front_layout/header');

<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area bg-image--6">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bradcaump__inner text-center">
                    <h2 class="bradcaump-title">Shop Grid</h2>
                    <nav class="bradcaump-content">
                        <a class="breadcrumb_item" href="{{ url('/frontend/home/') }}">Home</a>
                        <span class="brd-separetor">/</span>
                        <span class="breadcrumb_item active">Shop Grid</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- Start Shop Page -->
<div class="page-shop-sidebar left--sidebar bg--white section-padding--lg">
    <div class="container">
        <div class="row">
<!--            <div class="col-lg-3 col-12 order-2 order-lg-1 md-mt-40 sm-mt-40">
                <div class="shop__sidebar">
                    <aside class="wedget__categories poroduct--cat">
                        <h3 class="wedget__title">Product Categories</h3>
                        <ul>
                            <li><a href="#">Biography <span>(3)</span></a></li>
                            <li><a href="#">Business <span>(4)</span></a></li>
                            <li><a href="#">Cookbooks <span>(6)</span></a></li>
                            <li><a href="#">Health & Fitness <span>(7)</span></a></li>
                            <li><a href="#">History <span>(8)</span></a></li>
                            <li><a href="#">Mystery <span>(9)</span></a></li>
                            <li><a href="#">Inspiration <span>(13)</span></a></li>
                            <li><a href="#">Romance <span>(20)</span></a></li>
                            <li><a href="#">Fiction/Fantasy <span>(22)</span></a></li>
                            <li><a href="#">Self-Improvement <span>(13)</span></a></li>
                            <li><a href="#">Humor Books <span>(17)</span></a></li>
                            <li><a href="#">Harry Potter <span>(20)</span></a></li>
                            <li><a href="#">Land of Stories <span>(34)</span></a></li>
                            <li><a href="#">Kids' Music <span>(60)</span></a></li>
                            <li><a href="#">Toys & Games <span>(3)</span></a></li>
                            <li><a href="#">hoodies <span>(3)</span></a></li>
                        </ul>
                    </aside>
                    <aside class="wedget__categories pro--range">
                        <h3 class="wedget__title">Filter by price</h3>
                        <div class="content-shopby">
                            <div class="price_filter s-filter clear">
                                <form action="#" method="GET">
                                    <div id="slider-range"></div>
                                    <div class="slider__range--output">
                                        <div class="price__output--wrap">
                                            <div class="price--output">
                                                <span>Price :</span><input type="text" id="amount" readonly="">
                                            </div>
                                            <div class="price--filter">
                                                <a href="#">Filter</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </aside>
                    <aside class="wedget__categories poroduct--tag">
                        <h3 class="wedget__title">Product Tags</h3>
                        <ul>
                            <li><a href="#">Biography</a></li>
                            <li><a href="#">Business</a></li>
                            <li><a href="#">Cookbooks</a></li>
                            <li><a href="#">Health & Fitness</a></li>
                            <li><a href="#">History</a></li>
                            <li><a href="#">Mystery</a></li>
                            <li><a href="#">Inspiration</a></li>
                            <li><a href="#">Religion</a></li>
                            <li><a href="#">Fiction</a></li>
                            <li><a href="#">Fantasy</a></li>
                            <li><a href="#">Music</a></li>
                            <li><a href="#">Toys</a></li>
                            <li><a href="#">Hoodies</a></li>
                        </ul>
                    </aside>
                    <aside class="wedget__categories sidebar--banner">
                        <img src="images/others/banner_left.jpg" alt="banner images">
                        <div class="text">
                            <h2>new products</h2>
                            <h6>save up to <br> <strong>40%</strong>off</h6>
                        </div>
                    </aside>
                </div>
            </div>-->
            <div class="col-lg-12 col-12 order-1 order-lg-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="shop__list__wrapper d-flex flex-wrap flex-md-nowrap justify-content-between">
                            <div class="shop__list nav justify-content-center" role="tablist">
                                <a class="nav-item nav-link active" data-toggle="tab" href="#nav-grid" role="tab"><i class="fa fa-th"></i></a>
                                <a class="nav-item nav-link" data-toggle="tab" href="#nav-list" role="tab"><i class="fa fa-list"></i></a>
                            </div>
<!--                            <p>Showing 1–12 of {{count($products)}} results</p>
                            <div class="orderby__wrapper">
                                <span>Sort By</span>
                                <select class="shot__byselect">
                                    <option>Default sorting</option>
                                    <option>HeadPhone</option>
                                    <option>Furniture</option>
                                    <option>Jewellery</option>
                                    <option>Handmade</option>
                                    <option>Kids</option>
                                </select>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="tab__container">
                    <div class="shop-grid tab-pane fade show active" id="nav-grid" role="tabpanel">
                        <div class="row">
                            <!-- Start Single Product -->
                            @foreach ($products as $product)                                             
                            <div class="product product__style--3 col-lg-4 col-md-4 col-sm-6 col-12">

                                <div class="product__thumb"> 
                                    <?php
                                    $src = url("/assets/images/products/4/one.jpg");
                                    if (!empty($imageFileNames[$product->id])) {
                                        // $src = 'http://localhost:8082/laravel-master/public/assets/images/products/' . $product->id . '/' . $imageFileNames[$product->id]; 
                                        $src = url("/assets/images/products/").'/'. $product->id . '/' . $imageFileNames[$product->id];
                                    }
                                    ?>
                                    <!-- <a class="first__img" href="http://localhost:8082/laravel-master/public/frontend/product/showProduct/{{ $product->id }}"><img src="{{$src}}" alt="product image"></a> -->
                                    <a class="first__img" href="{{ url("/frontend/product/showProduct/". $product->id)}}"><img src="{{$src}}" alt="product image"></a>
                                <!--    <a class="second__img animation1" href="http://localhost:8082/laravel-master/public/frontend/product/showProduct/{{ $product->id }}"><img src="{{$src}}" alt="product image"></a> -->
                                    <div class="hot__box">
                                        <span class="hot-label">BEST SALLER</span>
                                    </div>
                                </div>


                                <div class="product__content content--center">
                                    <h4><a href="single-product.html">{{ $product->name }}</a></h4>
                                    <ul class="prize d-flex">
                                        <li>${{ $product->price }}</li>
                                        <li class="old_prize">${{ $product->actual_price }}</li>
                                        
                                    </ul>
                                    <div class="action">
                                        <div class="actions_inner">
                                            <ul class="add_to_links">
                                                <li> <button class="btn btn-primary" tpe="button" formaction="" onclick="addToCart({{ $product->id }})">Add To Cart</button></li>
<!--                                                <li><a class="cart" href="#"><i class="bi bi-shopping-bag4"></i></a></li>
                                                <li><a class="wishlist" href="#"><i class="bi bi-shopping-cart-full"></i></a></li>
                                                <li><a class="compare" href="#"><i class="bi bi-heart-beat"></i></a></li>
                                                <li><a data-toggle="modal" title="Quick View" class="quickview modal-view detail-link" href="#"><i class="bi bi-search"></i></a></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product__hover--content">
                                        <ul class="rating d-flex">
                                            <li class="on"><i class="fa fa-star-o"></i></li>
                                            <li class="on"><i class="fa fa-star-o"></i></li>
                                            <li class="on"><i class="fa fa-star-o"></i></li>
                                            <li><i class="fa fa-star-o"></i></li>
                                            <li><i class="fa fa-star-o"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <br>
                                       

                            </div>
                            @endforeach    
                            <!-- End Single Product -->
                        </div>
                    </div>
                    <div class="shop-grid tab-pane fade" id="nav-list" role="tabpanel">
                        <div class="list__view__wrapper">
                            @foreach ($products as $product) 
                            <div class="list__view">
                                
                                <div class="thumb">
                                    <?php
                                    $src = 'assets/images/products/no_image.jpg';
                                    
                                    if (!empty($imageFileNames[$product->id])) {
                                        $src = 'assets/images/products/' . $product->id . '/' . $imageFileNames[$product->id];
                                    }
                                    ?>
                                    <a class="first__img" href="{{ url("/frontend/product/showProduct/". $product->id) }}"><img src="{{ url($src) }}" alt="product images"></a>
                                    <a class="second__img animation1" href="{{url("/frontend/product/showProduct/". $product->id)}}"><img src="{{url($src)}}" alt="product images"></a>
                                </div>
                                <div class="content">
                                        <h2><a href="{{url("/frontend/product/showProduct/". $product->id)}}">{{$product->name}}</a></h2>
                                        <ul class="rating d-flex">
                                                <li class="on"><i class="fa fa-star-o"></i></li>
                                                <li class="on"><i class="fa fa-star-o"></i></li>
                                                <li class="on"><i class="fa fa-star-o"></i></li>
                                                <li class="on"><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                        </ul>
                                        <ul class="prize__box">
                                                <li>${{$product->price}}</li>
                                                <li class="old__prize">${{$product->actual_price}}</li>
                                        </ul>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue nec est tristique auctor. Donec non est at libero vulputate rutrum. Morbi ornare lectus quis justo gravida semper. Nulla tellus mi, vulputate adipiscing cursus eu, suscipit id nulla.</p>
                                        <ul class="cart__action d-flex">
                                                <input type='hidden' id="qty" class="input-text qty" name="qty" min="1" value="1" title="Qty" type="number">
                                                <li class="cart"><a href="#" onclick="addToCart({{ $product->id }})">Add to cart</a></li>
                                                <li class="wishlist"><a href="cart.html"></a></li>
                                                <li class="compare"><a href="cart.html"></a></li>
                                        </ul>

                                </div>
                                
                            </div>
                            <br/><br/><br/><br/>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Shop Page -->




<!-- QUICKVIEW PRODUCT -->
<div id="quickview-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="productmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal__container" role="document">
            <div class="modal-content">
                <div class="modal-header modal__header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-product">
                        <!-- Start product images -->
                        <div class="product-images">
                            <div class="main-image images">
                                <img alt="big images" src="images/product/big-img/1.jpg">
                            </div>
                        </div>
                        <!-- end product images -->
                        <div class="product-info">
                            <h1>Simple Fabric Bags</h1>
                            <div class="rating__and__review">
                                <ul class="rating">
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                </ul>
                                <div class="review">
                                    <a href="#">4 customer reviews</a>
                                </div>
                            </div>
                            <div class="price-box-3">
                                <div class="s-price-box">
                                    <span class="new-price">$17.20</span>
                                    <span class="old-price">$45.00</span>
                                </div>
                            </div>
                            <div class="quick-desc">
                                Designed for simplicity and made from high quality materials. Its sleek geometry and material combinations creates a modern look.
                            </div>
                            <div class="select__color">
                                <h2>Select color</h2>
                                <ul class="color__list">
                                    <li class="red"><a title="Red" href="#">Red</a></li>
                                    <li class="gold"><a title="Gold" href="#">Gold</a></li>
                                    <li class="orange"><a title="Orange" href="#">Orange</a></li>
                                    <li class="orange"><a title="Orange" href="#">Orange</a></li>
                                </ul>
                            </div>
                            <div class="select__size">
                                <h2>Select size</h2>
                                <ul class="color__list">
                                    <li class="l__size"><a title="L" href="#">L</a></li>
                                    <li class="m__size"><a title="M" href="#">M</a></li>
                                    <li class="s__size"><a title="S" href="#">S</a></li>
                                    <li class="xl__size"><a title="XL" href="#">XL</a></li>
                                    <li class="xxl__size"><a title="XXL" href="#">XXL</a></li>
                                </ul>
                            </div>
                            <div class="social-sharing">
                                <div class="widget widget_socialsharing_widget">
                                    <h3 class="widget-title-modal">Share this product</h3>
                                    <ul class="social__net social__net--2 d-flex justify-content-start">
                                        <li class="facebook"><a href="#" class="rss social-icon"><i class="zmdi zmdi-rss"></i></a></li>
                                        <li class="linkedin"><a href="#" class="linkedin social-icon"><i class="zmdi zmdi-linkedin"></i></a></li>
                                        <li class="pinterest"><a href="#" class="pinterest social-icon"><i class="zmdi zmdi-pinterest"></i></a></li>
                                        <li class="tumblr"><a href="#" class="tumblr social-icon"><i class="zmdi zmdi-tumblr"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="addtocart-btn">
                                <a href="#">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END QUICKVIEW PRODUCT -->
</div>
<!-- //Main wrapper -->
<script src="{{url('assets/js/frontend/product/showProduct.js')}}"></script>

@include('frontend/front_layout/footer');
<!-- JS Files -->
<script src="js/vendor/jquery-3.2.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>

</body>
</html>