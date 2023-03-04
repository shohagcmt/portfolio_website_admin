@extends('Layout.app')
@section('title','Review')
@section('content')

<div id="mainReviewDiv" class="container d-none">
<div class="row">
<div class="col-md-12 p-5">
  <button id="addNewReviewBtnId" class="btn btn-sm my-3 btn-danger">Add New</button>
<table id="ReviewDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Name</th>
	  <th class="th-sm">Description</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="Review_table">
  
</tbody>
</table>
</div>
</div>
</div>

<div id="loadReviewDiv" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading_icon" src="{{asset('images\Spinner.gif')}}">
</div>
</div>
</div>

<div id="WrongReviewDiv" class="container d-none" >
<div class="row">
<div class="col-md-12 text-center p-5">
<h3>Something Went Wrong !</h3>
</div>
</div>
</div>
@endsection



@section('script')
 <script type="text/javascript">
  getReviewData();

function getReviewData(){
  axios.get('/getReviewData')
    .then(function(response){
        if(response.status==200){
            $('#mainReviewDiv').removeClass('d-none');
            $('#loadReviewDiv').addClass('d-none');

            $('#ReviewDataTable').DataTable().destroy(); //DataTable jquear First Clear or Refersh and empty Before
            $('#Review_table').empty(); //First Table empty

          var jsonData = response.data;
                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td>" + jsonData[i].name + "</td>" +
                        "<td>" + jsonData[i].des  + "</td>" +
                        "<td> <a class='ReviewEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a> </td>" +
                        "<td> <a class='ReviewDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a> </td>"
                    ).appendTo('#Review_table');
                });
                
                //Review Table Delete Icon Click
                $('.ReviewDeleteBtn').click(function() {
                    var id = $(this).data('id');
                    $('#ReviewDeleteId').html(id);
                    $('#deleteReviewModal').modal('show');
                })


               //Review Delete Model Yes Bttn n
                $('#ReviewDeleteConfirmBtr').click(function() {
                    var id = $('#ReviewDeleteId').html();
                    ReviewDelete(id);
                })

                //Review Table Edit Icon Click
                $('.ReviewEditBtn').click(function() {
                    var id = $(this).data('id');
                    $('#ReviewEditId').html(id);
                    ReviewUpdateDetails(id);
                    $('#editReviewModal').modal('show');
                })

               //Review Edit / Update Model Save Bttn n
                $('#ReviewEditconfirmBtr').click(function() {
                    var id = $('#ReviewEditId').html();
                    var name = $('#ReviewNameID').val();
                    var des = $('#ReviewDesID').val();
                    var img = $('#ReviewImgID').val();        
                    ReviewUpdate(id,name,des,img);
                }) 

                 // Review Add New btn Click
                 $('#addNewReviewBtnId').click(function(){
                 $('#AddReviewModal').modal('show');
                    });

                 // Review Add Model Save Bttn n
                    $('#ReviewAddconfirmBtr').click(function() {
                     var name = $('#ReviewNameAddID').val();
                     var des = $('#ReviewDesAddID').val();
                     var img = $('#ReviewImgAddID').val();
                     ReviewAdd(name,des,img);
                    })



                //DataTable jquear show
                $('#ReviewDataTable').DataTable({"order":false});
                $('.dataTables_length').addClass('bs-select');
        }
       

        else{
          $('#WrongReviewDiv').removeClass('d-none');
          $('#loadReviewDiv').addClass('d-none');
        }

     

    }).catch(function(){
        $('#WrongReviewDiv').removeClass('d-none');
        $('#loadReviewDiv').addClass('d-none');
    })


}


//Review  Delete
function ReviewDelete(deleteID) {
    $('#ReviewDeleteConfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
    axios.post('/ReviewDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#ReviewDeleteConfirmBtr').html("Yes"); //Animation then Yes show
            if(response.status==200){
                 if (response.data == 1) {
                $('#deleteReviewModal').modal('hide');
                //toastr.success('Delete Success');
                getReviewData();
            } 
            else {
                $('#deleteReviewModal').modal('hide');
                //toastr.error('Delete Fail');
                getReviewData();
            }

            }
            else{
                $('#deleteReviewModal').modal('hide');
                //toastr.error('Samthing went Worng !');
                getReviewData();
            }
           
        })

        .catch(function(error) {
             $('#deleteReviewModal').modal('hide');
                //toastr.error('Samthing went Worng !');
 
        });

}

//Each Review Update Details
function ReviewUpdateDetails(detailsID) {
    axios.post('/getReviewDetails',{
            id: detailsID
        })
        .then(function(response) {
            if (response.status == 200) {
            $("#ReviewEditFrom").removeClass('d-none');
            $("#ReviewEditLoder").addClass('d-none');     
            var jsonData = response.data;
            $('#ReviewNameID').val(jsonData[0].name);
            $('#ReviewDesID').val(jsonData[0].des);
            $('#ReviewImgID').val(jsonData[0].img );
            }
            else{
             $("#ReviewEditLoder").addClass('d-none');
             $("#ReviewEditWrong").removeClass('d-none');
            }
            
        })

        .catch(function(error) {
             $("#ReviewEditLoder").addClass('d-none');
             $("#ReviewEditWrong").removeClass('d-none');
 
        });

}

//Each Project Update 
function ReviewUpdate(ReviewID,ReviewName,ReviewDes,Reviewimg) {
     if(ReviewName.length==0){
      alert('Review Name is empty !');
    }
    else if(ReviewDes.length==0){
     alert('Review Description is empty !');
    }
    else if(Reviewimg.length==0){
      alert('Review Image is empty !');
    }
    else{
        $('#ReviewEditconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/ReviewUpdate',{
              id:ReviewID,
            name: ReviewName,
            des: ReviewDes,
            img: Reviewimg
        })
        .then(function(response) {
            $('#ReviewEditconfirmBtr').html("Save"); //Animation then Save show
            if(response.status=200){
             if (response.data == 1) {
                $('#editReviewModal').modal('hide');
                //toastr.success('Delete Success');
               getReviewData();
            } 
            else {
                $('#editReviewModal').modal('hide');
                //toastr.error('Delete Fail');
                getReviewData();
            }

            }
            else{
               $('#editReviewModal').modal('hide');
                //toastr.error('Samthing went Worng !'); 
                }
            
            })

        .catch(function(error) {
           $('#editReviewModal').modal('hide');
                //toastr.error('Samthing went Worng !'); 
 
        });

    }

}


//Review Add mathiod
function ReviewAdd(ReviewName,ReviewDes,ReviewImg) {
     if(ReviewName.length==0){
      alert('Review Name is empty !');
    }
    else if(ReviewDes.length==0){
     alert('Review Description is empty !');
    }
    else if(ReviewImg.length==0){
      alert('Review Image is empty !');
    }
    else{
        $('#ReviewAddconfirmBtr').html("<div class='spinner-border-sm' role='status'></div>")//Animation
            axios.post('/ReviewAdd',{
            name: ReviewName,
            des: ReviewDes,
            img: ReviewImg
        })
        .then(function(response) {
            $('#ReviewAddconfirmBtr').html("Save"); //Animation then Save show
            if(response.status=200){
                 if (response.data == 1) {
                $('#AddReviewModal').modal('hide');
                 //toastr.success('Delete Success');
                getReviewData();
            } 

            else {
                $('#AddReviewModal').modal('hide');
                //toastr.error('Delete Fail');
                getReviewData();
                }

            }
                else{
                    $('#AddReviewModal').modal('hide');
                    //toastr.error('Delete Fail');
                    getReviewData();
                   }
           
            
        })

        .catch(function(error) {
           $('#AddReviewModal').modal('hide'); 
           //toastr.error('Delete Fail');
        });

    }

    
}

  
  </script>



  <!-- Review Delete-->
  <div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body p-3 text-center">
        <h5 class="mt-4">Do Want To Delete ?</h5>
        <h5 id="ReviewDeleteId" class="mt-4 d-none">  </h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">No</button>
        <button  id="ReviewDeleteConfirmBtr" type="button" class="btn btn-danger  btn-sm">Yes</button>
      </div>
    </div>
  </div>
</div>


<!-- Review Edit-->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4 text-center">
       <h5 id="ReviewEditId" class="mt-4 d-none">  </h5>

         
         <div id="ReviewEditFrom" class="d-none w-100">
         <input id="ReviewNameID" type="text" id="" class="form-control mb-4" placeholder="Review Name" />
         <input id="ReviewDesID" type="text" id="" class="form-control mb-4" placeholder="Review Description" />
         <input id="ReviewImgID" type="text" id="" class="form-control mb-4" placeholder="Review Image " />
       </div>

        <img id="ReviewEditLoder" class="loading_icon" src="{{asset('images\Spinner.gif')}}">
        <h3 id="ReviewEditWrong" class="d-none">Something Went Wrong !</h3>
         </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="ReviewEditconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>


<!-- Projects Add-->
<div class="modal fade" id="AddReviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body p-4 text-center">
       
         <div id="ReviewAddFrom" class="w-100">
          <h6 class="mb-4">Add New Review</h6>
         <input id="ReviewNameAddID" type="text" id="" class="form-control mb-4" placeholder="Review Name" />
         <input id="ReviewDesAddID" type="text" id="" class="form-control mb-4" placeholder="Review Description" />
         <input id="ReviewImgAddID" type="text" id="" class="form-control mb-4" placeholder="Review Image " />
       </div>

         </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-primary btn-sm" data-mdb-dismiss="modal">Cancle</button>
        <button  id="ReviewAddconfirmBtr" type="button" class="btn btn-danger  btn-sm">Save</button>
      </div>
    </div>
  </div>
</div>
@endsection  