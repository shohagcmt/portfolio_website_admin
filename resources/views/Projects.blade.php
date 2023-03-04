@extends('Layout.app')
@section('title','Projects')
@section('content')

<div id="mainProjectDiv" class="container d-none">
<div class="row">
<div class="col-md-12 p-5">
  <button id="addNewProjectBtnId" class="btn btn-sm my-3 btn-danger">Add New</button>
<table id="ProjectDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Name</th>
	  <th class="th-sm">Description</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="Project_table">
  
</tbody>
</table>
</div>
</div>
</div>

<div id="loadProjectDiv" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading_icon" src="{{asset('images\Spinner.gif')}}">
</div>
</div>
</div>

<div id="WrongProjectDiv" class="container d-none" >
<div class="row">
<div class="col-md-12 text-center p-5">
<h3>Something Went Wrong !</h3>
</div>
</div>
</div>


@endsection






@section('script')
 <script type="text/javascript">
 	ProjectsData();
//For Project Table
  function ProjectsData() {
    axios.get('/getProjectsData')
        .then(function(response) {


            if (response.status == 200) {

                $('#mainProjectDiv').removeClass('d-none');
                $('#loadProjectDiv').addClass('d-none');

                $('#ProjectDataTable').DataTable().destroy(); //DataTable jquear First Clear or Refersh and empty Before
                $('#Project_table').empty(); //First Table empty

 
                var jsonData = response.data;
                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td>" + jsonData[i].project_name + "</td>" +
                        "<td>" + jsonData[i].project_des  + "</td>" +
                        "<td> <a class='ProjectEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a class='ProjectDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#Project_table');
                });
                 
                //Project Table Delete Icon Click
                $('.ProjectDeleteBtn').click(function() {
                    var id = $(this).data('id');
                    $('#ProjectsDeleteId').html(id);
                    $('#deleteProjectsModal').modal('show');
                })


                //Service Delete Model Yes Bttn n
                $('#ProjectsDeleteConfirmBtr').click(function() {
                    var id = $('#ProjectsDeleteId').html();
                    ProjectsDelete(id);
                })


                //Project Table Edit Icon Click
                $('.ProjectEditBtn').click(function() {
                    var id = $(this).data('id');
                    $('#ProjectsEditId').html(id);
                    ProjectUpdateDetails(id);
                    $('#editProjectsModal').modal('show');
                })

                //Project Edit / Update Model Save Bttn n
                $('#ProjectsEditconfirmBtr').click(function() {
                    var id = $('#ProjectsEditId').html();
                    var name = $('#ProjectsNameID').val();
                    var des = $('#ProjectsDesID').val();
                    var link = $('#project_linkID').val();
                    var img = $('#ProjectsImgID').val();        
                    ProjectUpdate(id,name,des,link,img);
                })

               // Project Add New btn Click
                 $('#addNewProjectBtnId').click(function(){
                 $('#AddProjectsModal').modal('show');
                    });

                 // Project Add Model Save Bttn n
                $('#ProjectsAddconfirmBtr').click(function() {
                    var name = $('#ProjectsNameAddID').val();
                    var des = $('#ProjectsDesAddID').val();
                    var link = $('#project_linkAddID').val();
                    var img = $('#ProjectsImgAddID').val();
                    ProjectAdd(name,des,link,img);
                })
 

                //DataTable jquear show
                $('#ProjectDataTable').DataTable({"order":false});
                $('.dataTables_length').addClass('bs-select');

            } 
            else {
                $('#WrongProjectDiv').removeClass('d-none');
                $('#loadProjectDiv').addClass('d-none');
            }

        })
        .catch(function(error) {
            $('#WrongProjectDiv').removeClass('d-none');
            $('#loadProjectDiv').addClass('d-none');
        });

}


//Service Delete
function ProjectsDelete(deleteID) {
    $('#ProjectsDeleteConfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
    axios.post('/ProjectsDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#ProjectsDeleteConfirmBtr').html("Yes"); //Animation then Yes show
            if (response.data == 1) {
                $('#deleteProjectsModal').modal('hide');
                ProjectsData();
            } else {
                $('#deleteProjectsModal').modal('hide');
                ProjectsData();
            }
        })

        .catch(function(error) {
 
        });

}


//Each Service Update Details
function ProjectUpdateDetails(detailsID) {
    axios.post('/ProjectsDetails',{
            id: detailsID
        })
        .then(function(response) {
            if (response.status == 200) {
            $("#ProjectsEditFrom").removeClass('d-none');
            $("#ProjectsEditLoder").addClass('d-none');     
            var jsonData = response.data;
            $('#ProjectsNameID').val(jsonData[0].project_name);
            $('#ProjectsDesID').val(jsonData[0].project_des);
            $('#project_linkID').val(jsonData[0].project_link);
            $('#ProjectsImgID').val(jsonData[0].project_img);
            }
            else{
             $("#ProjectsEditLoder").addClass('d-none');
             $("#ProjectsEditWrong").removeClass('d-none');
            }
            
        })

        .catch(function(error) {
             $("#ProjectsEditLoder").addClass('d-none');
             $("#ProjectsEditWrong").removeClass('d-none');
 
        });

}


//Each Project Update 
function ProjectUpdate(ProjectID,ProjectName,ProjectDes,Projectlink,Projectimg) {
     if(ProjectName.length==0){
      alert('Project Name is empty !');
    }
    else if(ProjectDes.length==0){
     alert('Project Description is empty !');
    }
    else if(Projectlink.length==0){
      alert('Project link is empty !');
    }
    else if(Projectimg.length==0){
      alert('Project Image is empty !');
    }
    else{
        $('#ProjectsEditconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/ProjectsUpdate',{
              id:ProjectID,
            name: ProjectName,
            des: ProjectDes,
            link: Projectlink,
            img: Projectimg
        })
        .then(function(response) {
            $('#ProjectsEditconfirmBtr').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#editProjectsModal').modal('hide');
                ProjectsData();
            } else {
                $('#editProjectsModal').modal('hide');
                ProjectsData();
            }
            
        })

        .catch(function(error) {
            
 
        });

    }

    
}

 // Project Add Model Save Bttn n
  $('#ProjectsAddconfirmBtr').click(function() {
     var name = $('#ProjectsNameAddID').val();
     var des = $('#ProjectsDesAddID').val();
     var link = $('#project_linkAddID').val();
     var img = $('#ProjectsImgAddID').val();
     ProjectAdd(name,des,link,img);
                })


//Project Add mathiod
function ProjectAdd(ProjectName,ProjectDes,ProjectLink,ProjectImg) {
     if(ProjectName.length==0){
      alert('Project Name is empty !');
    }
    else if(ProjectDes.length==0){
     alert('Project Description is empty !');
    }
    else if(ProjectLink.length==0){
      alert('Project Iink is empty !');
    }
    else if(ProjectImg.length==0){
      alert('Project Image is empty !');
    }
    else{
        $('#ProjectsAddconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/ProjectsAdd',{
            name: ProjectName,
            des: ProjectDes,
            link: ProjectLink,
            img: ProjectImg
        })
        .then(function(response) {
            $('#ProjectsAddconfirmBtr').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#AddProjectsModal').modal('hide');
                ProjectsData();
            } else {
                $('#AddProjectsModal').modal('hide');
                ProjectsData();
            }
            
        })

        .catch(function(error) {
           $('#AddProjectsModal').modal('hide'); 
 
        });

    }

    
}
 	</script>


<!-- Projects Delete-->
 	<div class="modal fade" id="deleteProjectsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do Want To Delete ?</h5>
        <h5 id="ProjectsDeleteId" class="mt-4 d-none">  </h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">No</button>
        <button  id="ProjectsDeleteConfirmBtr" type="button" class="btn btn-danger  btn-sm">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- Projects Edit-->
<div class="modal fade" id="editProjectsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
       <h5 id="ProjectsEditId" class="mt-4 d-none">  </h5>

         
         <div id="ProjectsEditFrom" class="d-none w-100">
         <input id="ProjectsNameID" type="text" id="" class="form-control mb-4" placeholder="Projects Name" />
         <input id="ProjectsDesID" type="text" id="" class="form-control mb-4" placeholder="Projects Description" />
         <input id="project_linkID" type="text" id="" class="form-control mb-4" placeholder="Projects  Link" />
         <input id="ProjectsImgID" type="text" id="" class="form-control mb-4" placeholder="Projects Image " />
       </div>

        <img id="ProjectsEditLoder" class="loading_icon" src="{{asset('images\Spinner.gif')}}">
        <h3 id="ProjectsEditWrong" class="d-none">Something Went Wrong !</h3>
         </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="ProjectsEditconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>



<!-- Projects Add-->
<div class="modal fade" id="AddProjectsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-4 text-center">
       
         <div id="ProjectsAddFrom" class="w-100">
         	<h6 class="mb-4">Add New Project</h6>
         <input id="ProjectsNameAddID" type="text" id="" class="form-control mb-4" placeholder="Projects Name" />
         <input id="ProjectsDesAddID" type="text" id="" class="form-control mb-4" placeholder="Projects Description" />
         <input id="project_linkAddID" type="text" id="" class="form-control mb-4" placeholder="Projects  Link" />
         <input id="ProjectsImgAddID" type="text" id="" class="form-control mb-4" placeholder="Projects Image " />
       </div>

         </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="ProjectsAddconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>
 @endsection