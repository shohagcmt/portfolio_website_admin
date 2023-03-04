<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectModel;

class ProjectsController extends Controller
{
     function ProjectsIndex(){
    return view('Projects');
    }

    function getProjectsData(){
    	$result=json_encode(ProjectModel::orderBy('id','desc')->get());
    	return $result;
    }

    function getProjectsDetails(Request $request){
    	$id=$request->input('id');
    	$result=ProjectModel::where('id','=',$id)->get();
    	return $result;
    }

    function ProjectsDelete(Request $request){
    	$id=$request->input('id');
    	$result=ProjectModel::where('id','=',$id)->delete();
    	if($result==true){
    		return 1;
    	}
    	else{
    		return 0;
    	}
    }


    function ProjectsUpdate(Request $request){
        $id=$request->input('id');
        $name=$request->input('name');
        $des=$request->input('des');
        $link=$request->input('link');
        $img=$request->input('img');
        $result=ProjectModel::where('id','=',$id)->update([
        	'project_name'=>$name,
        	'project_des'=>$des,
        	'project_link'=>$link,
        	'project_img'=>$img
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }


    function ProjectsAdd(Request $request){
        $name=$request->input('name');
        $des=$request->input('des');
        $link=$request->input('link');
        $img=$request->input('img');
        $result=ProjectModel::insert([
        	'project_name'=>$name,
        	'project_des'=>$des,
        	'project_link'=>$link,
        	'project_img'=>$img
        ]);
        if($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }

}
