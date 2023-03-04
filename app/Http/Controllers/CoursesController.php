<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoursModel;

class CoursesController extends Controller
{
     function CoursesIndex(){
    return view('Courses');
    }


    function getCoursesData(){
    	$result=json_encode(CoursModel::orderBy('id','desc')->get());
    	return $result;
    }


    function getCoursesDetails(Request $request){
    	$id=$request->input('id');
    	$result=CoursModel::where('id','=',$id)->get();
    	return $result;
    }

    function CoursesDelete(Request $request){
    	$id=$request->input('id');
    	$result=CoursModel::where('id','=',$id)->delete();
    	if($result==true){
    		return 1;
    	}
    	else{
    		return 0;
    	}
    }


      function CoursesUpdate(Request $request){
        $id=$request->input('id');
        $course_name =$request->input('course_name');
        $course_des =$request->input('course_des');
        $course_fee =$request->input('course_fee');
        $course_totalenroll =$request->input('course_totalenroll');
        $course_totalclass =$request->input('course_totalclass');
        $course_link  =$request->input('course_link');
        $course_img =$request->input('course_img');
        $result=CoursModel::where('id','=',$id)->update([
        	'course_name'=>$course_name,
        	'course_name'=>$course_name,
        	'course_fee'=>$course_fee,
        	'course_totalenroll'=>$course_totalenroll,
        	'course_totalclass'=>$course_totalclass,
        	'course_link'=>$course_link,
        	'course_img'=>$course_img
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }


    function CoursesAdd(Request $request){
        $course_name =$request->input('course_name');
        $course_des =$request->input('course_des');
        $course_fee =$request->input('course_fee');
        $course_totalenroll =$request->input('course_totalenroll');
        $course_totalclass =$request->input('course_totalclass');
        $course_link  =$request->input('course_link');
        $course_img =$request->input('course_img');
        $result=CoursModel::insert([
        	'course_name'=>$course_name,
        	'course_des'=>$course_des,
        	'course_fee'=>$course_fee,
        	'course_totalenroll'=>$course_totalenroll,
        	'course_totalclass'=>$course_totalclass,
        	'course_link'=>$course_link,
        	'course_img'=>$course_img,
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }
}
