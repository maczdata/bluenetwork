<x-layouts.authenticated>
    @section('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>


    <style>
        .modal-backdrop {
    z-index: -1;
    }
    </style>

    <script>
        $(document).ready(function() {
            $('#service').DataTable( {
    
            } );
        } );
        $(document).ready(function() {
            $('#field').DataTable( {
    
            } );
        } );
    </script>
    @endsection
    @section('seo')
        <title>Offers &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Offer list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Offers') }} -({{$offer->name}})
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-body">
                <h4>Offer Services</h4>

                @if(count($offerServices) > 0)
                <br>
                   
                       <table id="service" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Desciption</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offerServices as $service)
                                    <tr>
                                            <td>{{$service->name}}</td>
                                            <td>{{$service->description}}</td>
                                            
                                            <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#service_{{$service->id}}">
                                                            Edit
                                                            </button>
                                                <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick=" action('{{$service->id}}', '/offers/delete/service')">delete</a>
                                            </td>
                                        </tr>
                                        <!-- The Modal -->
                                        <div class="modal" id="service_{{$service->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Service </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <form action="/offers/update/service/{{$service->id}}" method="post">
                                                        @csrf
                                                        <div class="mb-3 mt-3">
                                                            <label for="name" class="form-label">Name:</label>
                                                            <input type="text" class="form-control" id="email" placeholder="Enter Offer Name" name="name" value="{{old('name') ?? $service->name }}" required>
                                                        </div>

                                                       
                                                        <div class="mb-3 mt-3">
                                                            <label for="price" class="form-label">Description:</label>
                                                            <textarea class="form-control" rows="5" id="comment" name="description" required>{{old('description') ?? $service->description }}</textarea>
                                                        </div>

                                                        <br>
                                                        <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>

                                                    </form>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                         
                            </tbody>
                        </table>
                   
                @endif
                <button type="button" class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#create_service">
                    Create Service
                </button>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-body">
                <h4>Offer Fields</h4>

                @if(count($offer->fields) > 0)
                    <br>
                        <table id="field" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Desciption</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offer->fields as $field)
                                    <tr>
                                            <td>{{$field->field_name}}</td>
                                            <td>{{$field->description}}</td>
                                            <td>{{$field->type}}</td>
                                            <td>@if($field->required_field == 1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#field_{{$field->id}}">
                                                            Edit
                                                            </button>
                                                <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick=" action('{{$field->id}}', '/offers/delete/field')">delete</a>
                                            </td>
                                        </tr>
                                        <!-- The Modal -->
                                        <div class="modal" id="field_{{$field->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit field </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <form action="/offers/update/field/{{$field->id}}" method="post">
                                                        @csrf
                                                        <div class="mb-3 mt-3">
                                                            <label for="name" class="form-label">Name:</label>
                                                            <input type="text" class="form-control" id="email" placeholder="Enter field Name" name="field_name" value="{{old('field_name') ?? $field->field_name }}" required>
                                                        </div>

                                                       
                                                        <div class="mb-3 mt-3">
                                                            <label for="price" class="form-label">Description:</label>
                                                            <textarea class="form-control" rows="5" id="comment" name="description" >{{old('description') ?? $field->description }}</textarea>
                                                        </div>

                                                        <div class="mb-3 mt-3">
                                                            <label for="type" class="form-label">Type:</label>
                                                            <select name="type" class="form-control" id="typeUpdate" onchange="fileInputsUpdate()" required>
                                                                <option value="">Please select a Type</option>
                                                                    <option value="Text" {{ $field->type == "Text" ? "selected" : "" }}>Text</option>
                                                                    <option value="Textarea" {{ $field->type == "Textarea" ? "selected" : "" }}>Textarea</option>
                                                                    <option value="Number" {{ $field->type == "Number" ? "selected" : "" }}>Number</option>
                                                                    <option value="Select" {{ $field->type == "Select" ? "selected" : "" }}>Select</option>
                                                                    <option value="Telephone" {{ $field->type == "Telephone" ? "selected" : "" }}>Telephone</option>
                                                                    <option value="CheckBox" {{ $field->type == "CheckBox" ? "selected" : "" }}>CheckBox</option>
                                                                    <option value="Radio" {{ $field->type == "Radio" ? "selected" : "" }}>Radio</option>
                                                                    <option value="File" {{ $field->type == "File" ? "selected" : "" }}>File</option>
                                                                    <option value="Boolean" {{ $field->type == "Boolean" ? "selected" : "" }}>Boolean</option>
                                                                    <option value="Image" {{ $field->type == "Image" ? "selected" : "" }}>Image</option>
                                                                    
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 mt-3">
                                                            <label for="type" class="form-label">Field is Required</label>
                                                            <select name="required_field" id="" class="form-control" required>
                                                                <option value="">Please select a required option</option>
                                                                    <option value="1" {{ $field->required_field == 1 ? "selected" : "" }}>Yes</option>
                                                                    <option value="0"  {{ $field->required_field == 0 ? "selected" : "" }}>No</option>
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label for="type" class="form-label">Enabled</label>
                                                             <select name="enabled" id="" class="form-control" required>
                                                                <option value="">Please select a required option</option>
                                                                <option value="1"  {{ $field->enabled == 1 ? "selected" : "" }}>Yes</option>
                                                                <option value="0" {{ $field->enabled == 0 ? "selected" : "" }}>No</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                        <label for="type" class="form-label">Field has value</label>
                                                        <select name="has_value" id="" class="form-control" required>
                                                            <option value="">Please select a required option</option>
                                                                <option value="1" {{ $field->has_value == 1 ? "selected" : "" }}>Yes</option>
                                                                <option value="0" {{ $field->has_value == 0 ? "selected" : "" }}>No</option>
                                                        </select>
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label for="name" class="form-label">Answers:</label>
                                                            <input type="text" class="form-control" id="answers" placeholder="Please input your answers in coma seperated format" name="answers" value="@if($field->answers != null) @foreach(json_decode($field->answers) as $key=>$answer) {{$answer}}, @endforeach @endif" >
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label for="name" class="form-label">Default Value:</label>
                                                            <input type="text" class="form-control" id="name" placeholder="Default Value" name="default_value" value="{{ $field->default_value}}" >
                                                        </div>
                                                        <div class="mb-3 mt-3">
                                                            <label for="name" class="form-label">Validation Rules:</label>
                                                            <input type="text" class="form-control" id="name" placeholder="Enter a Validation Rules" name="validation_rules" value="{{ $field->validation_rules}}" >
                                                        </div>
                                                        <div id="fileDivUpdate">
                                                            <div class="mb-3 mt-3">
                                                                <label for="name" class="form-label">Max file size:</label>
                                                                <input type="text" class="form-control" id="name" placeholder="Enter a max file size" name="max_file_size" value="{{ $field->max_file_size}}">
                                                            </div>

                                                            <div class="mb-3 mt-3" >
                                                                <label for="name" class="form-label">File types:</label>
                                                                <input type="text" class="form-control" id="file_types" placeholder="Enter different file types in comma seperated format" name="file_types" value="@if($field->file_types != null) @foreach(json_decode($field->file_types) as $key=>$file) {{$file}}, @endforeach @endif" >
                                                            </div>
                                                        </div>

                                                        <br>
                                                        <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>

                                                    </form>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                         
                            </tbody>
                        </table>  
                @endif
                <button type="button" class="btn btn-primary btn-sm mt-4" data-bs-toggle="modal" data-bs-target="#create_field">
                                Create Fields
                </button>
            </div>
        </div>
       
    </div>
        <!-- The Modal -->
        <div class="modal" id="create_service">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create Service</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/offers/create/service" method="post">
                        @csrf
                        <input type="hidden" name="offer_id" value="{{$offer->id}}">
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="email" placeholder="Enter Offer Name" name="name" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="price" class="form-label">Description:</label>
                            <textarea class="form-control" rows="5" id="comment" name="description" required></textarea>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>

                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>

        <div class="modal" id="create_field">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create Field</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/offers/create/field" method="post">
                        @csrf
                        <input type="hidden" name="offer_id" value="{{$offer->id}}">
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter a field Name" name="field_name" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="price" class="form-label">Description:</label>
                            <textarea class="form-control" rows="5" id="comment" name="description" ></textarea>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="type" class="form-label">Type:</label>
                           <select name="type" class="form-control" id="typeCreate" onchange="fileInputsCreate()" required>
                               <option value="">Please select a Type</option>
                                <option value="Text">Text</option>
                                <option value="Textarea">Textarea</option>
                                <option value="Number">Number</option>
                                <option value="Select">Select</option>
                                <option value="Telephone">Telephone</option>
                                <option value="CheckBox">CheckBox</option>
                                <option value="Radio">Radio</option>
                                <option value="File">File</option>
                                <option value="Boolean">Boolean</option>
                                <option value="Image">Image</option>
                           </select>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="type" class="form-label">Field is Required</label>
                           <select name="required_field" id="" class="form-control" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                           </select>
                        </div>
                        <div>
                            <label for="type" class="form-label">Enabled</label>
                           <select name="enabled" id="" class="form-control" required>
                              
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                           </select>
                        </div>
                        <div>
                           <label for="type" class="form-label">Field has value</label>
                           <select name="has_value" id="" class="form-control" required>
                               <option value="">Please select a required option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                           </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Answers:</label>
                            <input type="text" class="form-control" id="answers" placeholder="Please input your answers in coma seperated format" name="answers" >
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Default Value:</label>
                            <input type="text" class="form-control" id="name" placeholder="Default Value" name="default_value" >
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Validation Rules:</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter a Validation Rules" name="validation_rules" >
                        </div>
                        <div id="fileDivCreate" style="display:none">
                            <div class="mb-3 mt-3">
                                <label for="name" class="form-label">Max file size:</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter a max file size" name="max_file_size">
                            </div>

                            <div class="mb-3 mt-3" >
                                <label for="name" class="form-label">File types:</label>
                                <input type="text" class="form-control" id="file_types" placeholder="Enter different file types in comma seperated format" name="file_types" >
                            </div>
                        </div>
                       

                        <br>
                        <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>

                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
        <script>
             function action(id, url){
                var d = confirm("Are you sure you want to carry out this action?");

                    if (d) {
                        window.location = url + "/" + id;
                    }

            }

            function fileInputsCreate()
            {
              
                var field_type = document.getElementById('typeCreate').value;
                var fileDiv = $("#fileDivCreate");
            
            
                    if(field_type == "File")
                    {
                        fileDiv.show();
                       
                    }else if(field_type == "Image")
                    {
                        fileDiv.show();
                    }
                    else{
                        fileDiv.hide();
                    }
            }

            function fileInputsUpdate()
            {
              
                var field_type = document.getElementById('typeUpdate').value;
                var fileDiv = $("#fileDivUpdate");
         
            
                    if(field_type == "File")
                    {
                        fileDiv.show();
                       
                    }else if(field_type == "Image")
                    {
                        fileDiv.show();
                    }
                    else{
                        fileDiv.hide();
                    }
            }
        </script>
</x-layouts.authenticated>
