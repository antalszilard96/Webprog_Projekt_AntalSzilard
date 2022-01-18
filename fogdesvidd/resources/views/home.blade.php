@extends('layouts.app')

@section('content')

{{-- AddItemModal --}}
<div class="modal fade" id="AddItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form  id="addItemForm" method="POST" enctype="multipart/form-data">
        @csrf    
        <div class="modal-body">

                <ul class="alert alert-warning d-none" id="save_errlist"></ul>

                <div class="form-group mb-3">
                    <label for="name">Nev</label>
                    <input type="text" class="form-control" placeholder="A tulajdonos neve" name="name">
                </div>
                <div class="form-group mb-3">
                        <label for="phone">Telefon</label>
                        <input type="text" class="form-control" placeholder="Add meg a telfonszamod" name="phone">
                </div>
                <div class="form-group mb-3">
                    <label for="itemName">Targy neve</label>
                    <input type="text" class="form-control" placeholder="Add meg a targy nevet" name="itemName">
                </div>
                <div class="form-group mb-3">
                    <label for="itemDescription">Leiras</label>
                    <textarea class="form-control" placeholder="Targy leirasa" name="itemDescription"></textarea>
                </div>
                <div class="form-group mb-3">                       
                    <label for="img">Kép</label>
                    <input type="file" class="form-control" name="image" id="image"> 
                    <span class="text-danger" id="image-input-error"></span>              
                </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="sub"  class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
{{-- End- AddItemModal --}}

{{-- EditItemModal --}}
<div class="modal fade" id="EditItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Foglal item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit_item_id">
            <p>Biztosan foglalni akarod?</p>
            <ul id="update_errlist"></ul>
            <div class="form-group" style="display: none;>
                <label for="busy">Foglal</label>
                <input type="text" class="busy form-control" id="edit_busy">
            </div>         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary update_item">Foglal</button>
      </div>
    </div>
  </div>
</div>
{{-- End- EditItemModal --}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                    
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        @if(Session::has('error'))
                    <div class="alert alert-success" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif
                    Üdv a felhasználói felületen!
                    

                    <div id="success_message">

                    </div>
                    <a href="#" id="show-items" class="btn btn-primary ">Show items</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#AddItemModal" class="btn btn-primary float-end btn-sm">Add item</a>

                </div>
                <div id="show">
                                      
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function (){

        //fetchItem();
        function fetchItem()
        {
            $.ajax({
                type: "GET",
                url: "/fetch-item",
                dataType: "json",
                success: function(response){
                    //console.log(response.items);
                    $('#show').html("")
                    
                    $.each(response.items, function(key, item){
                            $('#show').append('<div class="modal-content" style="padding: 20px; margin-top: 20px;">\
                                <p>Name:  '+  item.name+'</p>\
                                <p>Phone:  '+  item.phone+'</p>\
                                <p>Item name:  '+  item.itemName+'</p>\
                                <p>Description:  '+  item.itemDescription+'</p>\
                                <p id="fog" value="'+  item.busy+' ">Busy:  '+  item.busy+'</p>\
                                <p><img src="uploads/images/'+item.image+'" width="300px"; height="300px" alt="Image"> </p>\
                                <p><button type="button" value="'+item.id+'" class="edit_item btn btn-primary btn-sm">Foglal</button></p>\
                                </div>');   
                    });
                   
                }
            });
           
        }
        ////mejelenites f


        $(document).on('click', '.update_item', function(e){
            e.preventDefault();
            var item_id = $('#edit_item_id').val();
            var data = $('#edit_busy').val();
                
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                type: "PUT",
                url: "/update-item/"+item_id,
                data: data,
                dataType: "json",
                success: function(response){
                    //console.log(response);
                    if(response.status == 400){

                        $('#update_errlist').html("");
                        $('#update_errlist').addClass('alert alert-danger');
                        $.each(response.errors, function(key, err_values){
                            $('#update_errlist').append('<li>'+err_values+'</li>');
                        });
                    }else if(response.status == 400){

                        $('#update_errlist').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        
                    }else{
                        $('#update_errlist').html("");
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);

                        $('#EditItemModal').modal('hide');
                        alert(response.message);
                        fetchItem();
                    }
                }
            });
           
        });
        ///foglal f



        $(document).on('click','.edit_item', function(e){
            e.preventDefault();
            var item_id = $(this).val();
            $('#EditItemModal').modal('show');
            $.ajax({
                type: "GET",
                url: "/edit-item/"+item_id,
                success:function(response){
                    //console.log(response);
                    if(response.status == 404)
                    {
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-danger');
                        $('#success_message').text(response.message);
                    }
                    else
                    {
                        $('#edit_busy').val(response.item.busy);
                        $('#edit_item_id').val(item_id);
                    }
                }
            })
        });
        //foglal modal



        $('#show-items').click(function(e) {
            fetchItem();
            
        });
        ///megjelenites gomb



        $('#sub').click(function(e) {
            e.preventDefault();
            let formData = new FormData($('#addItemForm')[0]);
        
            $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
            });
            $.ajax({
                type: "POST",
                url: "/home",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                   
                   if(response.status == 400)
                   {
                       $('#save_errlist').html("");
                       $('#save_errlist').removeClass('d-none');
                        $.each(response.errors, function(key, err_values){
                            $('#save_errlist').append('<li>'+err_values+'</li>');
                        });
                   }
                   else if(response.status == 200)
                   {
                        $('#save_errlist').html("");
                        $('#save_errlist').addClass('d-none');
                        $('#success_message').text(response.message);
                        //this.reset();
                        $('#addItemForm').find('input').val('');
                        $('#AddItemModal').modal('hide');
                                               
                        alert(response.message);                      
                   }        
                }
            });
            
        });
        ///additem f

    });
</script>

@endsection