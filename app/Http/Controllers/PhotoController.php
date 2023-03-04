<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Storage;

class PhotoController extends Controller
{
    public function PhotoIndex(){
        return view('gallery');
    }

    //database img uploard
    public function PhotoUpload(Request $request){
        $photoPath=$request->file('photo')->store('public');
        $photoName=(explode('/',$photoPath))[1];
        $host=$_SERVER['HTTP_HOST'];
        $location="http://".$host."/storage/".$photoName;
        $result=Photo::insert(['location'=>$location]);
        return $result;

    }

    //image show
    public function PhotoJSON(){
        return Photo::take(3)->get();
    }

    //pagination
    public function PhotoJSONByID(Request $request){
        $FirstID=$request->id;
        $LastID=$FirstID+3;
        return Photo::where('id','>=',$FirstID)->where('id','<',$LastID)->get();
    }

    //Photo Delete
    public function PhotoDelete(Request $request){
        $OldPhotoURL=$request->input('OldPhotoURL');
        $OldPhotoID=$request->input('id');
  
        $OldPhotoURLArray= explode("/", $OldPhotoURL);
        $OldPhotoName=end($OldPhotoURLArray);
        $DeletePhotoFile=Storage::delete('public/'.$OldPhotoName);
        
        $DeleteRow=Photo::where('id','=',$OldPhotoID)->delete();
        return  $DeleteRow;
    }
}
