<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Support_user;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeUserController extends Controller
{
   public function index(Request $request)
   { 
       $user = Auth::user();
        // Retrieve the currently authenticated user's ID...
        $id = Auth::id();
        if(!$user){
         return redirect()->route('home.login');
      }
      $communication_book         = DB::table('books')->where('category_id','=','1')->first();
      $exploration_book           = DB::table('books')->where('category_id','=','2')->first();
      $favorite_book              = DB::table('books')->where('category_id','=','3')->first();
      $think_big_book             = DB::table('books')->where('category_id','=','4')->first();
      $love_book                  = DB::table('books')->where('category_id','=','5')->first();
      $book_people_and_animals    = DB::table('books')->where('category_id','=','6')->first();
      return view('homeUser.homeUser',[ "communication_book"        =>  $communication_book,
                                        "exploration_book"          =>  $exploration_book,
                                        "favorite_book"             =>  $favorite_book,
                                        "think_big_book"            =>  $think_big_book,
                                        "love_book"                 =>  $love_book,
                                        "book_people_and_animals"   =>  $book_people_and_animals,
                                        "user"                      =>  $user
                                                                                      ]);
    
   
   }
   public function logout(Request $request) {
      Auth::logout();
      return redirect()->route('home.login');
    }
   public function booksListing($id)
   {
      $user = Auth::user();
       $booksListing         = DB::table('books')->orderBy('id','DESC')->where('category_id','=',"$id")->get();
       if(!$user){
         return redirect()->route('home.login');
      }
       return view('homeUser.booksListing',["booksListing"=>$booksListing,
                                            "user"        =>  $user]);
   }
   public function bookDetails($id)
    {
      $user = Auth::user();
        $bookDetail         = DB::table('books')->where('id','=',"$id")->first();
        $descriptionBook    = DB::table('book_details')->where('book_id','=',"$id")->first();
        $categoryBooks     = DB::table('books')->where('category_id','=',"$bookDetail->category_id")->get();
        if(!$user){
         return redirect()->route('home.login');
      }
         return view('homeUser.bookDetails',["bookDetail"   => $bookDetail,
                                    "descriptionBook"       => $descriptionBook,
                                    "categoryBooks"         => $categoryBooks,
                                    "user"                  =>  $user
         ]);
    }
   public function addToCart(Request $request,$id)
   {
      $user         = Auth::user();
      $book         = DB::table('books')->where('id','=',"$id")->first();
      if(!$user){
         return redirect()->route('home.login');
      }
      $cart                = new Cart();
      $cart->user_id       = $user->id;
      $cart->book_id       = $book->id;
      $cart->price         = $book->price;
      $cart->quantity      = $request->quantity;
      $cart->created_at    = date('Y-m-d h:i:s');
      $cart->updated_at    = date('Y-m-d h:i:s');
      Session::flash('success','c???m ??n b???n ???? ???ng h??? ch??ng t??i ch??c b???n s??? c???m th???y h???nh ph??c v???i s???n ph???m c???a ch??ng t??i ');
      $cart->save();
      return redirect()->back();
   }
   public function Cart()
   {
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
      $cartUser     = DB::table('carts')
      ->join('users', 'users.id', '=', 'carts.user_id')
      ->join('books', 'books.id', '=', 'carts.book_id')
      ->select('carts.id','books.name_book','books.image_book','carts.price',DB::raw('(carts.price * carts.quantity) as totalPrice'),'carts.quantity')
      ->where('carts.user_id','=',$user->id)
      ->get();
     $carts =   DB::table('carts')
     ->select('user_id', DB::raw('sum(carts.price * carts.quantity) as totalPrice'))
     ->where('carts.user_id','=',$user->id)
     ->groupBy('carts.user_id')
     ->first();
      return view('homeUser.cart',['cartUser' => $cartUser,
                                   "user"     =>  $user,
                                   "carts"    => $carts
      ]);
      
   }
   public function fixCartUser(Request $request,$id)
   {
   
      $cart = Cart::find($id);
      $user         = Auth::user();
      if(!$user){
         return redirect()->route('home.login');
      }
      $cart->quantity      = $request->quantity;
      $cart->save();
      Session::flash('success','thay ?????i th??nh c??ng');
      return redirect()->back();
   }
   public function delelteCartUser($id)
   {
      $cart = Cart::find($id);
      $user         = Auth::user();
      if(!$user){
         return redirect()->route('home.login');
      }
      $cart->delete();
      Session::flash('success','x??a th??nh c??ng');
      return redirect()->back();
   }
   public function supportsCustomer()
   {
      $user         = Auth::user();
      $mesageUser   = DB::table('support_users')->select('user_id','customer',"id","admin")->orderBy('id','DESC')->where('user_id','=',$user->id)->first();
      if(!$user){
         return redirect()->route('home.login');
      }
      return view('homeUser.contact',["user"       => $user,
                                      "mesageUser" => $mesageUser
      ]);
   }
   public function mesageUser(Request $request)
   {
      $user         = Auth::user();
      if(!$user){
         return redirect()->route('home.login');
      }
      $support_users             = new Support_user();
      $support_users->user_id    = $user->id;
      $support_users->customer   = $request->userMessage;
      $support_users->admin      = "ch??a tr??? l???i";
      $support_users->created_at = date('Y-m-d H:i:s');
      $support_users->updated_at = date('Y-m-d H:i:s');
      $support_users->save();
      Session::flash('success','c???m ??n qu?? kh??ch ???? ????ng g??p ?? ki???n ch??ng t??i s??? c?? c??u tr??? l???i s???m nh???t cho b???n');
      return redirect()->back();
   }
   public function checkout()
   {
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
      $cartUser     = DB::table('carts')
      ->join('users', 'users.id', '=', 'carts.user_id')
      ->join('books', 'books.id', '=', 'carts.book_id')
      ->select('carts.id','books.name_book','books.image_book','carts.price',DB::raw('(carts.price * carts.quantity) as totalPrice'),'carts.quantity')
      ->where('carts.user_id','=',$user->id)
      ->get();
     $carts =   DB::table('carts')
     ->select('user_id', DB::raw('sum(carts.price * carts.quantity) as totalPrice'))
     ->where('carts.user_id','=',$user->id)
     ->groupBy('carts.user_id')
     ->first();
      return view('homeUser.checkout',['cartUser' => $cartUser,
                                       "user"     =>  $user,
                                       "carts"    => $carts
      ]);
   }
   public function informattionUser()
   {
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
      $cartUser     = DB::table('carts')
      ->join('users', 'users.id', '=', 'carts.user_id')
      ->join('books', 'books.id', '=', 'carts.book_id')
      ->select('carts.id','books.name_book','books.image_book','carts.price',DB::raw('(carts.price * carts.quantity) as totalPrice'),'carts.quantity')
      ->where('carts.user_id','=',$user->id)
      ->get();
     $carts =   DB::table('carts')
     ->select('user_id', DB::raw('sum(carts.price * carts.quantity) as totalPrice'))
     ->where('carts.user_id','=',$user->id)
     ->groupBy('carts.user_id')
     ->first();
      return view('homeUser.informattionUser',['cartUser' => $cartUser,
                                       "user"     =>  $user,
                                       "carts"    => $carts
      ]);
   }
   public function pay(Request $request)
   {
      $messages = [
         'required_date.required'         =>   'b???n ch??a nh???p ng??y y??u c???u ',
     ];
     $roles = [
         'required_date'                  => 'required',
     ];
     $this->validate($request, $roles,$messages); 
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
      $cartUser     = DB::table('carts')
      ->join('users', 'users.id', '=', 'carts.user_id')
      ->join('books', 'books.id', '=', 'carts.book_id')
      ->select('books.id as book_id','carts.id','books.name_book','books.image_book','carts.price',DB::raw('(carts.price * carts.quantity) as totalPrice'),'carts.quantity')
      ->where('carts.user_id','=',$user->id)
      ->get();
     $totalPriceCarts =   DB::table('carts')
     ->select('user_id', DB::raw('sum(carts.price * carts.quantity) as totalPrice'))
     ->where('carts.user_id','=',$user->id)
     ->groupBy('carts.user_id')
     ->first();
     $orders                = new Order();
     $orders->created_at    = date('Y-m-d');
     $orders->updated_at    = date('Y-m-d');
     $orders->required_date = $request->required_date;
     $orders->user_id       = $user->id;
     $orders->totalPrice    = $totalPriceCarts->totalPrice;
     $orders->status        = "??ang x??? l??";
     $orders->save();
     $order_id = DB::getPdo()->lastInsertId();
     foreach( $cartUser as $key => $cart )
     {
      $order_details = new Order_detail();
      $order_details->book_id    = $cart->book_id;
      $order_details->price_each = $cart->price;
      $order_details->orders_id  = $order_id;
      $order_details->quantity   = $cart->quantity;
      $order_details->save();
      }
      Session::flash('success','c???m ??n b???n ???? mua s???n ph???m c???a ch??ng t??i');
     $cartUserDelete = Cart::where('user_id', '=', $user->id);
     $cartUserDelete->delete();
     return redirect()->route('homeUser.orderUser');
   }
   public function fixInformationUser()
   {
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
      return view('homeUser.fixInformationUser',["user"=>$user]);
   }
   public function handleInformationUser(Request $request)
   {
      $user         = Auth::user();  
      $id = $user->id;
      if(!$user){
         return redirect()->route('home.login');
      }
      $messages = [
         'name.required'         =>   'b???n ch??a nh???p t??n',
         'email.required'        => 'b???n ch??a nh???p email',
         'password.required'     => 'b???n ch??a nh???p m???t kh???u',
         'number_phone.required' => 'b???n ch??a nh???p s??? ??i???n tho???i',
         'address.required'      => 'b???n ch??a nh???p ?????a ch???',
         'email.email'           => '???? kh??ng ph???i email',
         'email.unique'          => 'Email ???? t???n t???i',
         'password.min'          => 'm???t kh???u c???a b???n ph???i tr??n 8 k?? t???',
         'number_phone.unique'   => 's??? ??i???n tho???i ???? t???n t???i',  
     ];
     $roles = [
         'name'                  => 'required',
         'email'                 => 'required|email|unique:users,email,'.$id,
         'password'              => 'required|min:8',
         'address'               => 'required',
         'number_phone'          => 'required|unique:users,number_phone,'.$id,
     ];
     $this->validate($request, $roles,$messages); 
     $user = User::find($id);
     $user->name             = $request->name;
     $user->email            = $request->email;
     $user->password         = $request->password;
     $user->number_phone     = $request->number_phone;
     $user->address          = $request->address;
     $user->save();
     
     Session::flash('success','thay ?????i th??nh c??ng');
     return redirect()->route('homeUser.informattionUser');
   }
   public function orderUser()
    {
      $user         = Auth::user();  
      $id = $user->id;
      if(!$user){
         return redirect()->route('home.login');
      }
        $orderUser = DB::table('orders')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->select('orders.*', 'users.name','users.address')
        ->orderBy('orders.id','DESC')
        ->where('users.id','=',$id)
        ->get();
        return view('homeUser.orderUser',["orderUser" => $orderUser,
                                          "user"      => $user
        ]);
    }
    public function order_detailsUser($id)
    {
      $user         = Auth::user();  
      if(!$user){
         return redirect()->route('home.login');
      }
        $order_detailsUser = DB::table('order_details')
        ->join('books','books.id', '=','order_details.book_id')
        ->join('orders','orders.id', '=','order_details.orders_id')
        ->select('books.image_book', "order_details.quantity",'books.name_book','orders.id as orders_id','order_details.price_each','orders.totalPrice')
        ->where('orders.id','=',$id)
        ->get();
      
        return view('homeUser.order_detailsUser',["order_detailsUser" => $order_detailsUser,
                                                  "user"              => $user
        ]);
    }
}