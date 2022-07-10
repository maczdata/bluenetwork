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
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
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
            {{ __('User Offers') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="container p-4">
       
     
        <div class="row pt-4">
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>User</th>
                        <th>Amount</th>
                         <th>status</th>
                        <th>Bought date</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($userOffers) > 0)
                            @foreach($userOffers as $offer)
                                <tr>
                                    <td>{{optional(optional($offer)->offer)->name}}</td>
                                    <td>{{optional(optional($offer)->offer)->name}}</td>
                                    <td>₦ {{$offer->amount}}</td>
                                    <td>@if($offer->status == "completed")
                                                <label class="text-white bg-green-500 inline-block rounded-full  px-2 py-1 text-xs font-bold">Completed</label>
                                        @elseif($offer->status == "cancel")
                                        <label style ="background-color:red" class="text-white bg-danger-500 inline-block rounded-full px-2 py-1 text-xs font-bold">Cancelled</label>
                                        @elseif($offer->status == "processing")
                                        <label style ="background-color:blue" class="text-white bg-danger-500 inline-block rounded-full px-2 py-1 text-xs font-bold">Processing</label>
                                        @else
                                        <label style ="background-color:orange" class="text-white bg-warning-500 inline-block rounded-full  px-2 py-1 text-xs font-bold">Pending</label>
                                        @endif
                                    </td>
                                    <td>{{$offer->created_at}}</td>
                                    <td> 
                                        <div class="dropdown">
                                            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_form_{{$offer->id}}"> View  </button>
                                                    @if($offer->status == "pending")
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick=" action('{{$offer->id}}', '/offers/mark/user-offer/start-processing')">Start Processing</a>
                                                    @elseif($offer->status == "processing")
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick=" action('{{$offer->id}}', '/offers/mark/user-offer/complete')">Mark as completed</a>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick=" action('{{$offer->id}}', '/offers/mark/user-offer/cancelled')">Cancel</a>
                                                    @endif
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick=" action('{{$offer->id}}', '/offers/delete/user-offer')">Delete</a>
                                                </div>
                                        </div>
                                        
                                       
                                       
                                    
                                    </td>
                                </tr>
                              <!-- The Modal -->
                                <div class="modal" id="view_form_{{$offer->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">View form filled Data</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            @if(is_null($offer->fields))
                                                No Data for the form Found
                                            @else
                                                @foreach($offer->fields as $field)
                                                
                                                    @if($field->type == "File" ||  $field->type == "Image")
                                                        
                                                            <a href=" {{optional($field)->getMedia('filled_field'.$field->id)?->first()->original_url}}" class="btn btn-block btn-dark" download>Download File</a>
                                                    @else
                                                     <p>Value: {{$field->filled_field}} <p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    @endif
                    
                </tbody>
            </table>
        </div>
    </div>
        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create Offer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/offers/create" method="post">
                        @csrf
                        <div class="mb-3 mt-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="email" placeholder="Enter Offer Name" name="name" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="price" class="form-label">Price:</label>
                            <input type="number" class="form-control" id="email" placeholder="Enter Offer Price" name="price" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="price" class="form-label">Description:</label>
                            <textarea class="form-control" rows="5" id="comment" name="description" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="startDate" class="form-label">Start Date:</label>
                                <input type="date" class="form-control" placeholder="Enter email" name="start_date" required>
                            </div>
                            <div class="col">
                                <label for="startDate" class="form-label">End Date:</label>
                                <input type="date" class="form-control" placeholder="Enter password" name="end_date" required>
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
        </script>
</x-layouts.authenticated>
