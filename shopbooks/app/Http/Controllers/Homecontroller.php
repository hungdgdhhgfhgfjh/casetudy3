<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Homecontroller extends Controller
{
    public function index()
    {
        $communication_book         = DB::table('books')->where('category_id','=','1')->first();
        $exploration_book           = DB::table('books')->where('category_id','=','2')->first();
        $favorite_book              = DB::table('books')->where('category_id','=','3')->first();
        $think_big_book             = DB::table('books')->where('category_id','=','4')->first();
        $love_book                  = DB::table('books')->where('category_id','=','5')->first();
        $book_people_and_animals    = DB::table('books')->where('category_id','=','6')->first();
        return view('home.home',["communication_book"        =>$communication_book,
                            "exploration_book"          =>$exploration_book,
                            "favorite_book"             =>$favorite_book,
                            "think_big_book"            =>$think_big_book,
                            "love_book"                =>$love_book,
                            "book_people_and_animals"   =>$book_people_and_animals,
        
                                                                                     ]);
    }
    public function booksListing($id)
    {
        $booksListing         = DB::table('books')->where('category_id','=',"$id")->get();
        return view('home.booksListing',["booksListing"=>$booksListing]);
    }
    public function bookDetails($id)
    {
        $bookDetail         = DB::table('books')->where('id','=',"$id")->first();
        $descriptionBook    = DB::table('book_details')->where('book_id','=',"$id")->first();
        $categoryBooks     = DB::table('books')->where('category_id','=',"$bookDetail->category_id")->get();
         return view('home.bookDetails',["bookDetail"=>$bookDetail,
                                    "descriptionBook"=>$descriptionBook,
                                    "categoryBooks"=>$categoryBooks,
         ]);
    }
    public function register()
    {
        return view("home.register");
    }
    public function acctionRegister(Request $request)
    {
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
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8',
            'address'               => 'required',
            'number_phone'          => 'required|unique:users',
        ];
        $this->validate($request, $roles,$messages); 
        $user = new User();
        $user->name             = $request->name;
        $user->email            = $request->email;
        $user->password         = Hash::make($request->password);
        $user->number_phone     = $request->number_phone;
        $user->address          = $request->address;
        $user->level            = "kh??ch h??ng";
        $user->status           = 1;
        $user->save();
        Session::flash('success',"????ng k?? th??nh c??ng");
        return redirect()->route('home.register');
    }
    public function login()
    {
        return view("home.login");
    }public function handleLogin(Request $request)
    {
       $loginUsers = [
            'email' => $request->email,
            'password' => $request->password,
            'level' => "Kh??ch h??ng",
        ];
        $loginAdmin =
        [
            'email' => $request->email,
            'password' => $request->password,
            'level' => "s???p",
        ];
        if (Auth::attempt($loginUsers)) {
            return redirect()->route('homeUser.index');
        }
        if(Auth::attempt($loginAdmin)) {
            return redirect()->route('users.index');
        } 
        else {
        Session::flash('error_email','email c???a b???n kh??ng t???n t???i');
        Session::flash('error_password','m???t kh???u c???a b???n kh??ng t???n t???i');
            return redirect()->back();
        }
        
    }
    public function logout(Request $request) {
        Auth::logout();
        return redirect()->route('home.login');
      }
    
}
