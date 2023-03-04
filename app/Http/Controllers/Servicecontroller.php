<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceModel;

class Servicecontroller extends Controller
{
    function ServiceIndex(){
    	
    	return view('Services');
    }

    function getServiceData(){
    	$result=json_encode(ServiceModel::orderBy('id','desc')->get());
    	return $result;
    }

     function getServiceDetails(Request $request){
    	$id=$request->input('id');
    	$result=ServiceModel::where('id','=',$id)->get();
    	return $result;
    }

    function ServiceDelete(Request $request){
    	$id=$request->input('id');
    	$result=ServiceModel::where('id','=',$id)->delete();
    	if($result==true){
    		return 1;
    	}
    	else{
    		return 0;
    	}
    }

    function ServiceUpdate(Request $request){
        $id=$request->input('id');
        $name=$request->input('name');
        $des=$request->input('des');
        $img=$request->input('img');
        $result=ServiceModel::where('id','=',$id)->update(['service_name'=>$name,'service_des'=>$des,'service_img'=>$img]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }


    function ServiceAdd(Request $request){
        $name=$request->input('name');
        $des=$request->input('des');

        $photoPath=$request->file('photo')->store('public');
        $photoName=(explode('/',$photoPath))[1];
         $host=$_SERVER['HTTP_HOST'];
         $location="http://".$host."/storage/".$photoName;

        $result=ServiceModel::insert(['service_name'=>$name,'service_des'=>$des,'service_img'=>$location]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }
}

