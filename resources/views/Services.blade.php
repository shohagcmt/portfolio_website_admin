@extends('Layout.app')
@section('title','Services')
@section('content')
<div id="mainDiv" class="container d-none">
<div class="row">
<div class="col-md-12 p-5">
  <button id="addNewBtnId" class="btn btn-sm my-3 btn-danger">Add New</button>
<table id="ServiceDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Image</th>
	  <th class="th-sm">Name</th>
	  <th class="th-sm">Description</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="service_table">
  
</tbody>
</table>
</div>
</div>
</div>

<div id="loadDiv" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading_icon" src="{{asset('images\Spinner.gif')}}">
</div>
</div>
</div>

<div id="WrongDiv" class="container d-none" >
<div class="row">
<div class="col-md-12 text-center p-5">
<h3>Something Went Wrong !</h3>
</div>
</div>
</div>



@endsection

@section('script')
 <script type="text/javascript">
 	getServicesData();



  //For Services Table
function getServicesData() {
    axios.get('/getserviceData')
        .then(function(response) {


            if (response.status == 200) {

                $('#mainDiv').removeClass('d-none');
                $('#loadDiv').addClass('d-none');

                $('#ServiceDataTable').DataTable().destroy(); //DataTable jquear First Clear or Refersh and empty Before
                $('#service_table').empty(); //First Table empty


                var jsonData = response.data;
                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td> <img class='table-img' src=" + jsonData[i].service_img + "> </td>" +
                        "<td>" + jsonData[i].service_name + "</td>" +
                        "<td>" + jsonData[i].service_des + "</td>" +
                        "<td> <a class='serviceEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a class='serviceDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#service_table');
                });
                 
                 
                 //Services Table Delete Icon Click
                $('.serviceDeleteBtn').click(function() {
                    var id = $(this).data('id');
                    $('#serviceDeleteId').html(id);
                    $('#deleteModal').modal('show');
                })
                 
                 //Service Delete Model Yes Bttn n
                $('#serviceDeleteConfirmBtr').click(function() {
                    var id = $('#serviceDeleteId').html();
                    ServicesDelete(id);
                })


                  //Services Table Edit Icon Click
                $('.serviceEditBtn').click(function() {
                    var id = $(this).data('id');
                    $('#serviceEditId').html(id);
                    ServicesUpdateDetails(id);
                    $('#editModal').modal('show');
                })
                 
               //Service Edit Model Save Bttn n
                $('#serviceEditconfirmBtr').click(function() {
                    var id = $('#serviceEditId').html();
                    var name = $('#serviceNameID').val();
                    var des = $('#serviceDesID').val();
                    var img = $('#serviceImgID').val();      
                    ServicesUpdate(id,name,des,img);
                })


                //Service Add Model Save Bttn n
                $('#serviceAddconfirmBtr').click(function() {
                    var name = $('#serviceNameAddID').val();
                    var des = $('#serviceDesAddID').val();
                    //var img = $('#serviceImgAddID').val();
                    var img = $('#serviceImgAddID').prop('files')[0];

    				  var formData = new FormData();
    				  formData.append('name', name);
    				  formData.append('des', des);
    				  formData.append('photo', img);
                    ServicesAdd(formData);
                })
                
                //DataTable jquear show
                $('#ServiceDataTable').DataTable({"order":false});
                $('.dataTables_length').addClass('bs-select');


            } 
            else {
                $('#WrongDiv').removeClass('d-none');
                $('#loadDiv').addClass('d-none');
            }

        })
        .catch(function(error) {
            $('#WrongDiv').removeClass('d-none');
            $('#loadDiv').addClass('d-none');
        });

}


//Service Delete
function ServicesDelete(deleteID) {
    $('#serviceDeleteConfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
    axios.post('/serviceDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#serviceDeleteConfirmBtr').html("Yes"); //Animation then Yes show
            if (response.data == 1) {
                $('#deleteModal').modal('hide');
                getServicesData();
            } else {
                $('#deleteModal').modal('hide');
                getServicesData();
            }
        })

        .catch(function(error) {
 
        });

}


//Each Service Update Details
function ServicesUpdateDetails(detailsID) {
    axios.post('/serviceDetails',{
            id: detailsID
        })
        .then(function(response) {
            if (response.status == 200) {
            $("#serviceEditFrom").removeClass('d-none');
            $("#serviceEditLoder").addClass('d-none');     
            var jsonData = response.data;
            $('#serviceNameID').val(jsonData[0].service_name);
            $('#serviceDesID').val(jsonData[0].service_des);
            $('#serviceImgID').val(jsonData[0].service_img);
            }
            else{
             $("#serviceEditLoder").addClass('d-none');
             $("#serviceEditWrong").removeClass('d-none');
            }
            
        })

        .catch(function(error) {
             $("#serviceEditLoder").addClass('d-none');
             $("#serviceEditWrong").removeClass('d-none');
 
        });

}




//Each Service Update 
function ServicesUpdate(serviceID,serviceName,serviceDes,serviceImg) {
     if(serviceName.length==0){
      alert('Service Name is empty !');
    }
    else if(serviceDes.length==0){
     alert('Service Description is empty !');
    }
    else if(serviceImg.length==0){
      alert('Service Image is empty !');
    }
    else{
        $('#serviceEditconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/serviceUpdate',{
            id: serviceID,
            name: serviceName,
            des: serviceDes,
            img: serviceImg
        })
        .then(function(response) {
            $('#serviceEditconfirmBtr').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#editModal').modal('hide');
                getServicesData();
            } else {
                $('#editModal').modal('hide');
                getServicesData();
            }
            
        })

        .catch(function(error) {
            
 
        });

    }

    
}


// Service Add New btn Click
$('#addNewBtnId').click(function(){
    $('#addModal').modal('show');
});

//Service Add mathiod
function ServicesAdd(formData) {
     if(formData.length==0){
      alert('Service Name is empty !');
    }
    else{
        $('#serviceAddconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/ServiceAdd',formData)
        .then(function(response) {
            $('#serviceAddconfirmBtr').html("Save"); //Animation then Save show
            if (response.data == 1) {
                $('#addModal').modal('hide');
                getServicesData();
            } else {
                $('#addModal').modal('hide');
                getServicesData();
            }
            
        })

        .catch(function(error) {
            
 
        });

    }

    
}

 </script>






 <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do Want To Delete ?</h5>
        <h5 id="serviceDeleteId" class="mt-4 d-none">  </h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">No</button>
        <button  id="serviceDeleteConfirmBtr" type="button" class="btn btn-danger  btn-sm">Yes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Service</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
       <h5 id="serviceEditId" class="mt-4 d-none">  </h5>

         
         <div id="serviceEditFrom" class="d-none w-100">
         <input id="serviceNameID" type="text" id="" class="form-control mb-4" placeholder="Service Name" />
         <input id="serviceDesID" type="text" id="" class="form-control mb-4" placeholder="Service Description" />
         <input id="serviceImgID" type="text" id="" class="form-control mb-4" placeholder="Service Image Link" />
       </div>

        <img id="serviceEditLoder" class="loading_icon" src="{{asset('images\Spinner.gif')}}">
        <h3 id="serviceEditWrong" class="d-none">Something Went Wrong !</h3>
         </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="serviceEditconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">

       <div id="serviceAddFrom" class="" w-100">
          <h6 class="mb-4">Add New Service</h6>
         <input id="serviceNameAddID" type="text" id="" class="form-control mb-4" placeholder="Service Name" />
         <input id="serviceDesAddID" type="text" id="" class="form-control mb-4" placeholder="Service Description" />
         <input id="serviceImgAddID" type="file" id="" class="form-control mb-4" placeholder="Service Image Link" />
       </div>

      </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="serviceAddconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>
@endsection

