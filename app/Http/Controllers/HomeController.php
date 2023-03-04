<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactModel;
use App\Models\CoursModel;
use App\Models\ProjectModel;
use App\Models\ReviewModel;
use App\Models\ServiceModel;
use App\Models\VisitorModel;

class HomeController extends Controller
{
    function HomeIndex(){
          $TotalContact=ContactModel::count();
          $TotalCours=CoursModel::count();
          $TotalProject=ProjectModel::count();
          $TotalReview=ReviewModel::count();
          $TotalService=ServiceModel::count();
          $TotalVisitor=VisitorModel::count();

    return view('Home',[
         'TotalContact'=>$TotalContact,
         'TotalCours'=>$TotalCours,
         'TotalProject'=>$TotalProject,
         'TotalReview'=>$TotalReview,
         'TotalService'=>$TotalService,
         'TotalVisitor'=>$TotalVisitor
       ]);
    }
}
