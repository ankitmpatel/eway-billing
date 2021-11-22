<?php

namespace App\Http\Controllers;

use App\Libraries\MasterIndia\EwayBills;
use Illuminate\Http\Request;
use App\Models\User;
use Debugbar;
use DataTables;

class HomeController extends Controller {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index() {
    return redirect()->route('ewaybill.all');
    $m = new EwayBills();
    // dd($m->getEwayBillLists($documentNumber='123-81234191', $gstin='05AAABB0639G1Z8', $documentDate='10/5/2018', $generateStatus=1, $page=1, $limit=20));
    // dd($m->getEwayBill('371001555203', '05AAABB0639G1Z8'));
    $e = $m->getEwayBill('371001555203', '05AAABB0639G1Z8');
    dd($e);
    // dd($e);
    return view('eway/index', $e['message']);
    return view('home');
  }

  public function getUsers(Request $request) {
    Debugbar::info($request->all());
    $users = User::select('name', 'email', 'id');
    return $dtb =  DataTables::eloquent($users)
                ->toJson();
  }

  public function addUser(Request $request) {
    return view('users.add');
  }
}
