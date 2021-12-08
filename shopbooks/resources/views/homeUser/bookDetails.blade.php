@extends("homeUser.layout.fullter")
@section("content")
 <div class="single-product">
      <div class="container mt-5">
        <div class="row">
          <div class="col-md-12">
             <div class="section-heading">
              <div class="line-dec"></div>
              <h1>{{$bookDetail->name_book}}</h1>
            </div>
          </div>
          <div class="col-md-6 mt-2">
            <div class="product-slider">
              <div id="slider" class="flexslider">
              </div>
              <div id="carousel" class="flexslider">
              <img style="width:400px;height:300px" src="{{$bookDetail->image_book}}" alt="">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="right-content">
              <h4>{{$bookDetail->name_book}}</h4>
              <h6>{{number_format($bookDetail->price)."VNĐ"}}</h6>
              <p>{{$descriptionBook->Content_book}}</p>
                <label for="quantity">Quantity:</label>
                <form action="{{route('homeUser.addToCart',$bookDetail->id)}}" method="post">
                @csrf
                <input type="number" name="quantity" 
                    value="1">
            <button  class="button">thêm vào <i class="fas fa-shopping-cart"></i></button>
              </form>
              <div class="col-12">
            @if (Session::has('success'))
            <p width="width:200px" class="alert-success">
                <i class="fa fa-check" aria-hidden="true"></i>{{ Session::get('success') }}
            </p>
            @endif
              <div class="down-content">
                <div class="categories">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="featured-items">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <div class="line-dec"></div>
                    <div class="col-12 product-listing-heading">
                        <h1 class="heading text-left">các sách tượng tự</h1>
                    </div>
                    <!-- END LISTING HEADING -->
   
                    <!-- START PRODUCT LISTING SECTION -->
                    <div class="col-12 product-listing-products">

                        <!-- START DISPLAY PRODUCT -->
                        <div class="product-list row">
                        @foreach($categoryBooks as $categorBook)
                            <div class="col-12 col-md-6 col-lg-4 manage-color-hover wow slideInUp" data-wow-delay=".2s">
                           
                                <div class="product-item owl-theme product-listing-carousel owl-carousel">
                                    <div class="item p-item-img">
                                       <a href="{{route('home.bookDetails',$categorBook->id)}}"> <img style="width:350px; height:350px;" src="{{$categorBook->image_book}}" alt="images"></a>
                                        <div class="text-center d-flex justify-content-center align-items-center">
                                        </div>
                                    </div>
                                    <div class="item p-item-img">
                                    <a href="{{route('home.bookDetails',$categorBook->id)}}">  <img style="width:350px; height:350px;" src="{{$categorBook->image_book}}" alt="images"></a>
                                        <div class="text-center d-flex justify-content-center align-items-center">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-item-detail">
                                    <h4 class="text-center p-item-name"><a href="{{route('home.bookDetails',$categorBook->id)}}"> {{$categorBook->name_book}} </a>
                                    </h4>
                                    <p class="text-center p-item-price">{{number_format($categorBook->price)."VNĐ"}}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- END DISPLAY PRODUCT -->


                    </div>
                    <!-- END PRODUCT LISTING SECTION -->
                </div>
            </div>
              <!-- <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-02.jpg" alt="Item 2">
                  <h4>Erat odio rhoncus</h4>
                  <h6>$25.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-03.jpg" alt="Item 3">
                  <h4>Integer vel turpis</h4>
                  <h6>$35.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-04.jpg" alt="Item 4">
                  <h4>Sed purus quam</h4>
                  <h6>$45.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-05.jpg" alt="Item 5">
                  <h4>Morbi aliquet</h4>
                  <h6>$55.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-06.jpg" alt="Item 6">
                  <h4>Urna ac diam</h4>
                  <h6>$65.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-04.jpg" alt="Item 7">
                  <h4>Proin eget imperdiet</h4>
                  <h6>$75.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-05.jpg" alt="Item 8">
                  <h4>Nullam risus nisl</h4>
                  <h6>$85.00</h6>
                </div>
              </a>
              <a href="single-product.html">
                <div class="featured-item">
                  <img src="assets/images/item-06.jpg" alt="Item 9">
                  <h4>Cras tempus</h4>
                  <h6>$95.00</h6>
                </div>
              </a> -->
            </div>
          </div>
        </div>
      </div>
    </div>
   @endsection


