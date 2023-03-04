@extends('Layout.app')
@section('title_page', 'Review')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#galleryModal">Add New</button>
            </div>
        </div>
        <!--Gallery Show-->
        <div id="PhotoRow" class="row py-3">

        </div>
        <!--Load More-->
        <button id="LoadMoreBtn" class="btn btn-primary">Load More</button>
    </div>

@section('model')
    <!--Inseart Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Photo</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input id="imgInput" class="form-control" type="file">
                    <img id="imgpreview" class="imgpreview mt-3" src="{{ asset('images/default_image.png') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-mdb-dismiss="modal">Close</button>
                    <button type="button" id="ImgSaveConfirmBtn" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@section('script')
<script type="text/javascript">
    //img read
    $('#imgInput').change(function() {
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function(event) {
            var ImgSource = event.target.result;
            $('#imgpreview').attr('src', ImgSource)
        }
    })

    //img upload Database
    $('#ImgSaveConfirmBtn').on('click', function() {
        $('#ImgSaveConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
        var photoFile = $('#imgInput').prop('files')[0];
        var formData = new FormData();
        formData.append('photo', photoFile);

        axios.post("/photoupload", formData).then(function(response) {
            if (response.status == 200 && response.data == 1) {
                $('#ImgSaveConfirmBtn').html("Save");
                $('#galleryModal').modal('hide');
                //toastr.success('photo Upload Success');
            } else {
                $('#galleryModal').modal('hide');
                //toastr.error('Photo Update Fail');
            }
            window.location.href="/gallery";

        }).catch(function(error) {
            $('#ImgSaveConfirmBtn').html("Save");
            $('#galleryModal').modal('hide');
            //toastr.error('Photo Update Fail');
        })
    })

    //image Show or loard
    LoadPhoto() //function Call
    function LoadPhoto() {
        axios.get('/photojson').then(function(response) {
            $.each(response.data, function(i, item) {
                $("<div class='col-md-4 p-1'>").html(
                    "<img data-id="+item['id']+" class='imgOnRow' src=" + item['location'] + ">"+
                    "<button data-id="+item['id']+" data-photo="+item['location']+" class='btn btn-sm deletePhoto'>Delete</button>"
                ).appendTo('#PhotoRow');
            });
          
            //Same Request agent by Agent not Allow
            $('.deletePhoto').on('click',function(event){ 
                let id=$(this).data('id');
                let photo=$(this).data('photo');
                PhotoDelete(photo,id);
                event.preventDefault(); //event মাধ্যমে একটা জিনিস বার বার যাতে না হয় সেই কাজ টা করছে ।
                
            })

        }).catch(function(error) {

        })
    }

    // On click Load More id add
    $('#LoadMoreBtn').on('click',function(){
        let FirstImgID=$(this).closest('div').find('img').data('id');
        //let LoadMoreBtn=$(this); 62 Number tutorial
        //alert(FirstImgID);
        LoadByID(FirstImgID);
    })

    //pagination Load More Button click
      let ImgID=0;
    function LoadByID(FirstImgID){
        ImgID=ImgID+3;
        let PhotoID=FirstImgID+ImgID;
        let URL="/PhotoJSONByID/"+PhotoID;
        //alert(URL);
        $('#LoadMoreBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation
        axios.get(URL).then(function(response) {
            $('#LoadMoreBtn').html("Load More");
            $.each(response.data, function(i, item) {
                $("<div class='col-md-4 p-1'>").html(
                    "<img data-id="+item['id']+" class='imgOnRow' src=" + item['location'] + ">"+
                    "<button data-id="+item['id']+" data-photo="+item['location']+" class='btn deletePhoto btn-sm'>Delete</button>"
                ).appendTo('#PhotoRow');
            });

        }).catch(function(error) {

        })
    }

    //Photo Delete
    function PhotoDelete(OldPhotoURL,id) {
                let URL="/PhotoDelete";
                let MyFormData=new FormData();
                MyFormData.append('OldPhotoURL',OldPhotoURL);
                MyFormData.append('id',id);
                axios.post(URL,MyFormData).then(function (response) {
                    if(response.status==200 && response.data==1){
                        //toastr.success('Photo Delete Success');
                         window.location.href="/gallery";

                    }
                    else{
                       // toastr.error('Delete Fail Try Again');
                    }
                }).catch(function () {
                    //toastr.error('Delete Fail Try Again');
                })

        }
</script>
@endsection
