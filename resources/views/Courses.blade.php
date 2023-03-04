@extends('Layout.app')
@section('title','Courses')
@section('content')

<div id="mainDivcourses" class="container d-none" >
<div class="row">
<div class="col-md-12 p-5">
<button id="addNewCoursesBtnId" class="btn btn-sm my-3 btn-danger">Add New</button>
<table id="CourseDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
	  <th class="th-sm">Name</th>
	  <th class="th-sm">Course Fee</th>
	  <th class="th-sm">Class</th>
	  <th class="th-sm">Enroll</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="courses_table">
    
	</tbody>
</table>
</div>
</div>
</div>

<div id="loadDivcourses" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading_icon" src="{{asset('images\Spinner.gif')}}">
</div>
</div>
</div>

<div id="WrongDivcourses" class="container d-none" >
<div class="row">
<div class="col-md-12 text-center p-5">
<h3>Something Went Wrong !</h3>
</div>
</div>
</div>


@endsection



@section('script')

<!-- Add Modal Courses -->
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Add New Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body  text-center">
       <div class="container">
       	<div class="row">
       		<div class="col-md-6">
             	<input id="CourseNameId" type="text" id="" class="form-control mb-3" placeholder="Course Name">
          	 	<input id="CourseDesId" type="text" id="" class="form-control mb-3" placeholder="Course Description">
    		 	<input id="CourseFeeId" type="text" id="" class="form-control mb-3" placeholder="Course Fee">
     			<input id="CourseEnrollId" type="text" id="" class="form-control mb-3" placeholder="Total Enroll">
       		</div>
       		<div class="col-md-6">
     			<input id="CourseClassId" type="text" id="" class="form-control mb-3" placeholder="Total Class">      
     			<input id="CourseLinkId" type="text" id="" class="form-control mb-3" placeholder="Course Link">
     			<input id="CourseImgId" type="text" id="" class="form-control mb-3" placeholder="Course Image">
       		</div>
       	</div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="CourseAddConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal Courses -->
<div class="modal fade" id="UpdateCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Upate Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body  text-center">

        <h5 id="CourseEditId" class="mt-4 d-none">  </h5>

       <div id="CourseEditFrom" class="container d-none">

        <div class="row">
          <div class="col-md-6">
              <input id="CourseNameUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Name">
              <input id="CourseDesUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Description">
          <input id="CourseFeeUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Fee">
          <input id="CourseEnrollUpdateId" type="text" id="" class="form-control mb-3" placeholder="Total Enroll">
          </div>
          <div class="col-md-6">
          <input id="CourseClassUpdateId" type="text" id="" class="form-control mb-3" placeholder="Total Class">      
          <input id="CourseLinkUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Link">
          <input id="CourseImgUpdateId" type="text" id="" class="form-control mb-3" placeholder="Course Image">
          </div>
        </div>
       </div>

       <img id="CourseEditLoder" class="loading_icon" src="{{asset('images\Spinner.gif')}}">
        <h3 id="CourseEditWrong" class="d-none">Something Went Wrong !</h3>
         

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="CourseUpdateConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


<!-- Delete Modal Courses -->
<div class="modal fade" id="deleteCoursesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do Want To Delete ?</h5>
        <h5 id="CoursesDeleteId" class="mt-4 d-none">  </h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">No</button>
        <button  id="CoursesDeleteConfirmBtr" type="button" class="btn btn-danger  btn-sm">Yes</button>
      </div>
    </div>
  </div>
</div>

 
 <script type="text/javascript">

 	getCoursesData();


  //For Courses Table
function getCoursesData() {
    axios.get('/getCoursesData')
        .then(function(response) {


            if (response.status == 200) {

                $('#mainDivcourses').removeClass('d-none');
                $('#loadDivcourses').addClass('d-none');
                
                $('#CourseDataTable').DataTable().destroy(); //DataTable jquear First Clear or Refersh and empty Before
                $('#courses_table').empty();

                var jsonData = response.data;
                $.each(jsonData, function(i,item) {
                    $('<tr>').html(
                        "<td>"+jsonData[i].course_name+"</td>"+
                        "<td>"+jsonData[i].course_fee+"</td>"+
                        "<td>"+jsonData[i].course_totalclass+"</td>"+
                        "<td>"+jsonData[i].course_totalenroll+"</td>"+
                        "<td> <a class='courseEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a class='courseDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#courses_table');
                });

//Courses Model add new 
$('#CourseAddConfirmBtn').click(function(){
  var CourseName1 = $('#CourseNameId').val();
  var CourseDes1 =$('#CourseDesId').val();
  var CourseFee1 =$('#CourseFeeId').val();
  var CourseEnroll1 =$('#CourseEnrollId').val();
  var CourseClass1 =$('#CourseClassId').val();
  var CourseLink1 =$('#CourseLinkId').val();
  var CourseImg1 =$('#CourseImgId').val();
  CoursesAdd(CourseName1,CourseDes1,CourseFee1,CourseEnroll1,CourseClass1,CourseLink1,CourseImg1);
})
//Courses Model Delete Icon
$('.courseDeleteBtn').click(function(){
  var id = $(this).data('id');
  $('#CoursesDeleteId').html(id); //id show
  $('#deleteCoursesModal').modal('show');
})

//Courses Delete Model Save Bttn n Confirm
 $('#CoursesDeleteConfirmBtr').click(function(){
   var id=$('#CoursesDeleteId').html();
   CourseDelete(id);
 })

 //Courses Model Edit/Update Icon
$('.courseEditBtn').click(function(){
    var id = $(this).data('id');
    CourseUpdateDetails(id);
    $('#CourseEditId').html(id); //id Show
    $('#UpdateCourseModal').modal('show');
})

//Courses Edit/Update Model Save Bttn n Confirm
$('#CourseUpdateConfirmBtn').click(function(){
  var CourseID1 = $('#CourseEditId').html();
  var CourseName1 =$('#CourseNameUpdateId').val();
  var CourseDes1 =$('#CourseDesUpdateId').val();
  var Course_Fee1 =$('#CourseFeeUpdateId').val();
  var Course_TotalEnroll1 =$('#CourseEnrollUpdateId').val();
  var Course_TotalClass1 =$('#CourseClassUpdateId').val();
  var Course_Link1 =$('#CourseLinkUpdateId').val();
  var Course_Img1 =$('#CourseImgUpdateId').val();
  CourseUpdate(CourseID1,CourseName1,CourseDes1,Course_Fee1,Course_TotalEnroll1,Course_TotalClass1,Course_Link1,Course_Img1);
     })

//DataTable jquear show
$('#CourseDataTable').DataTable({"order":false});
$('.dataTables_length').addClass('bs-select');

  }

             else {
                $('#WrongDivcourses').removeClass('d-none');
                $('#loadDivcourses').addClass('d-none');
            }

        })
        .catch(function(error) {
            $('#WrongDivcourses').removeClass('d-none');
            $('#loadDivcourses').addClass('d-none');
        });

}

//Courses Model Addnew Icon
$('#addNewCoursesBtnId').click(function(){
$('#addCourseModal').modal('show');
});

//Service Add mathiod
function CoursesAdd(CourseName,CourseDes,CourseFee,CourseEnroll,CourseClass,CourseLink,CourseImg) {
     if(CourseName.length==0){
      alert('Course Name is empty !');
    }
    else if(CourseDes.length==0){
     alert('Course Description is empty !');
    }
    else if(CourseFee.length==0){
      alert('Course fee is empty !');
    }
    else if(CourseEnroll.length==0){
      alert('Course Enroll is empty !');
    }
    else if(CourseClass.length==0){
      alert('Course class is empty !');
    }
    else if(CourseLink.length==0){
      alert('Course link is empty !');
    }
    else if(CourseImg.length==0){
      alert('Course Image is empty !');
    }
    else{
        $('#CourseAddConfirmBtn').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/CoursesAdd',{
            course_name: CourseName,
            course_des : CourseDes,
            course_fee: CourseFee,
            course_totalenroll : CourseEnroll,
            course_totalclass : CourseClass,
            course_link : CourseLink,
            course_img : CourseImg
        })
        .then(function(response) {
            $('#CourseAddConfirmBtn').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#addCourseModal').modal('hide');
                getCoursesData();
            } else {
                $('#addCourseModal').modal('hide');
                getCoursesData();
            }
            
        })

        .catch(function(error) {
            
 
        });

    }

    
}


//Course Delete
function CourseDelete(deleteID) {
    $('#CoursesDeleteConfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
    axios.post('/CoursesDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#CoursesDeleteConfirmBtr').html("Yes"); //Animation then Yes show
            if(response.status==200){
               if (response.data == 1) {
                $('#deleteCoursesModal').modal('hide');
                //toastr.success('Delete Success');
                getCoursesData();
            } else {
                $('#deleteCoursesModal').modal('hide');
                //toastr.error('Delete Fail');
                getCoursesData();
            } 
            }
            else{
               $('#deleteCoursesModal').modal('hide'); 
               //toastr.error('Samething Want Wrong !');
            }
            
        })

        .catch(function(error) {
             $('#deleteCoursesModal').modal('hide');
             //toastr.error('Samething Want Wrong !');
        });

}

// Each Course Update Details
function CourseUpdateDetails(detailsID){
    axios.post('/CoursesDetails',{
            id: detailsID
        })
        .then(function(response) {
            if (response.status == 200) {
            $("#CourseEditFrom").removeClass('d-none');
            $("#CourseEditLoder").addClass('d-none');     
            var jsonData = response.data;
            $('#CourseNameUpdateId').val(jsonData[0].course_name);
            $('#CourseDesUpdateId').val(jsonData[0].course_des );
            $('#CourseFeeUpdateId').val(jsonData[0].course_fee );
            $('#CourseEnrollUpdateId').val(jsonData[0].course_totalenroll);
            $('#CourseClassUpdateId').val(jsonData[0].course_totalclass);
            $('#CourseLinkUpdateId').val(jsonData[0].course_link );
            $('#CourseImgUpdateId').val(jsonData[0].course_img);
            }
            else{
             $("#CourseEditLoder").addClass('d-none');
             $("#CourseEditWrong").removeClass('d-none');
            }
            
        })

        .catch(function(error) {
             $("#CourseEditLoder").addClass('d-none');
             $("#CourseEditWrong").removeClass('d-none');
 
        });
}

//Each Course Update 
function CourseUpdate(CourseID,CourseName,CourseDes,Course_Fee,Course_TotalEnroll,Course_TotalClass,Course_Link,Course_Img ) {
     if(CourseName.length==0){
      alert('Course Name is empty !');
    }
    else if(CourseDes.length==0){
     alert('Course Description is empty !');
    }
    else if(Course_Fee.length==0){
      alert('Course Course_Fee is empty !');
    }else if(Course_TotalEnroll.length==0){
      alert('Course Course_TotalEnroll is empty !');
    }
    else if(Course_TotalClass.length==0){
      alert('Course Course_TotalClass is empty !');
    }
    else if(Course_Link.length==0){
      alert('Course Course_Link is empty !');
    }
    else if(Course_Img.length==0){
      alert('Course Image is empty !');
    }
    else{
        $('#CourseUpdateConfirmBtn').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/CoursesUpdate',{
            id: CourseID,
            course_name: CourseName,
           course_des: CourseDes,
            course_fee: Course_Fee,
            course_totalenroll: Course_TotalEnroll,
           course_totalclass: Course_TotalClass,
            course_link: Course_Link,
            course_img: Course_Img
        })
        .then(function(response) {
            $('#CourseUpdateConfirmBtn').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#UpdateCourseModal').modal('hide');
                getCoursesData();
            } else {
                $('#UpdateCourseModal').modal('hide');
                getCoursesData();
            }
            
        })

        .catch(function(error) {
            
         $('#UpdateCourseModal').modal('hide');
        });

    }

    
}

 </script>
 @endsection