@extends('Layout.app')
@section('title','Contacts')
@section('content')
<div id="maincontactDiv" class="container d-none ">
<div class="row">
<div class="col-md-12 p-5">
<table id="contactDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
	  <th class="th-sm">Name</th>
	  <th class="th-sm">Mobile</th>
	  <th class="th-sm">Email</th>
	  <th class="th-sm">Message</th>
    <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="contact_table">
  
</tbody>
</table>
</div>
</div>
</div>

<div id="loadcontactDiv" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading_icon" src="{{asset('images\Spinner.gif')}}">
</div>
</div>
</div>

<div id="WrongcontactDiv" class="container d-none" >
<div class="row">
<div class="col-md-12 text-center p-5">
<h3>Something Went Wrong !</h3>
</div>
</div>
</div>



@endsection


@section('script')
 <script type="text/javascript">
  getcontactData();


//For contact Table
function getcontactData() {
    axios.get('/getContactData')
        .then(function(response) {


            if (response.status == 200) {

                $('#maincontactDiv').removeClass('d-none');
                $('#loadcontactDiv').addClass('d-none');

                $('#contactDataTable').DataTable().destroy(); //DataTable jquear First Clear or Refersh and empty Before
                $('#contact_table').empty(); //First Table empty


                var jsonData = response.data;
                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td>" + jsonData[i].contact_name + "</td>" +
                        "<td>" + jsonData[i].contact_mobile  + "</td>" +
                        "<td>" + jsonData[i].contact_email   + "</td>" +
                        "<td>" + jsonData[i].contact_msg + "</td>" +
                        "<td> <a class='contactDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#contact_table');
                });
                 
                  
                 //Services Table Delete Icon Click
                $('.contactDeleteBtn').click(function() {
                    var id = $(this).data('id');
                    $('#contactDeleteId').html(id);
                    $('#deletecontactModal').modal('show');
                })

                //Service Delete Model Yes Bttn n
                $('#contactDeleteConfirmBtr').click(function() {
                    var id = $('#contactDeleteId').html();
                    contactDelete(id);
                })
               
                //DataTable jquear show
                $('#contactDataTable').DataTable({"order":false});
                $('.dataTables_length').addClass('bs-select');


            } 
            else {
                $('#WrongcontactDiv').removeClass('d-none');
                $('#loadcontactDiv').addClass('d-none');
            }

        })
        .catch(function(error) {
            $('#WrongcontactDiv').removeClass('d-none');
            $('#loadcontactDiv').addClass('d-none');
        });

}


//contactDelete
function contactDelete(deleteID) {
    $('#contactDeleteConfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
    axios.post('/ContactDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#contactDeleteConfirmBtr').html("Yes"); //Animation then Yes show
            if (response.data == 1) {
                $('#deletecontactModal').modal('hide');
                getcontactData();
            } else {
                $('#deletecontactModal').modal('hide');
                getcontactData();
            }
        })

        .catch(function(error) {
 
        });

}
  
  </script>
  <div class="modal fade" id="deletecontactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do Want To Delete ?</h5>
        <h5 id="contactDeleteId" class="mt-4 d-none">  </h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">No</button>
        <button  id="contactDeleteConfirmBtr" type="button" class="btn btn-danger  btn-sm">Yes</button>
      </div>
    </div>
  </div>
</div>
@endsection