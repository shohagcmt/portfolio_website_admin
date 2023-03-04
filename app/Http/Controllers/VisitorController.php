<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorModel;
class VisitorController extends Controller
{
    function VisitorIndex(){
      $VisitorData=json_decode(visitorModel::orderBy('id','desc')->take(100)->get()); 
    return view('Visitor',['VisitorData'=>$VisitorData]);
    }
}
