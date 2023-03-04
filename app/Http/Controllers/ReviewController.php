<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewModel;
class ReviewController extends Controller
{
    function ReviewIndex(){
    return view('Review');
    }

    function getReviewData(){
    	$result=json_encode(ReviewModel::orderBy('id','desc')->get());
    	return $result;
    }

   function ReviewDelete(Request $request){
    	$id=$request->input('id');
    	$result=ReviewModel::where('id','=',$id)->delete();
    	if($result==true){
    		return 1;
    	}
    	else{
    		return 0;
    	}
    }
     
       function getReviewDetails(Request $request){
    	$id=$request->input('id');
    	$result=ReviewModel::where('id','=',$id)->get();
    	return $result;
    }

    function ReviewUpdate(Request $request){
        $id=$request->input('id');
        $name=$request->input('name');
        $des=$request->input('des');
        $img=$request->input('img');
        $result=ReviewModel::where('id','=',$id)->update([
        	'name'=>$name,
        	'des'=>$des,
        	'img'=>$img
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }


    function ReviewAdd(Request $request){
        $name=$request->input('name');
        $des=$request->input('des');
        $img=$request->input('img');
        $result=ReviewModel::insert([
        	'name'=>$name,
        	'des'=>$des,
        	'img'=>$img
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }
}
