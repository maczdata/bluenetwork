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
            {{ __('Offers') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="container p-4">
        <div class="row">
            <div class="col-sm-4">  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Create Offer
                                    </button>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-4"></div>
        </div>
     
        <div class="row pt-4">
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Desciption</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created At</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($offers) > 0)
                            @foreach($offers as $offer)
                        <tr>
                                <td>{{$offer->name}}</td>
                                <td>{{$offer->description}}</td>
                                <td>N {{$offer->price}}</td>
                                <td>{{$offer->start_date}}</td>
                                <td>{{$offer->end_date}}</td>
                                <td>{{$offer->created_at}}</td>
                                <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#offer_{{$offer->id}}">
                                                Edit Offer
                                                </button>
                                    <a class="btn btn-dark btn-sm" href="/offers/view-more/{{$offer->id}}">View More</a>
                                </td>
                            </tr>
                            <!-- The Modal -->
                            <div class="modal" id="offer_{{$offer->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Offer </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form action="/offers/update/{{$offer->id}}" method="post">
                                            @csrf
                                            <div class="mb-3 mt-3">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control" id="email" placeholder="Enter Offer Name" name="name" value="{{old('name') ?? $offer->name }}" required>
                                            </div>

                                            <div class="mb-3 mt-3">
                                                <label for="price" class="form-label">Price:</label>
                                                <input type="number" class="form-control" id="email" placeholder="Enter Offer Price" name="price"  value="{{old('price') ?? $offer->price }}" required>
                                            </div>

                                            <div class="mb-3 mt-3">
                                                <label for="price" class="form-label">Description:</label>
                                                <textarea class="form-control" rows="5" id="comment" name="description" required>{{old('description') ?? $offer->description }}</textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <label for="startDate" class="form-label">Start Date:</label>
                                                    <input type="date" class="form-control"  name="start_date" value="{{old('start_date') ?? $offer->start_date }}" required>
                                                </div>
                                                <div class="col">
                                                    <label for="startDate" class="form-label">End Date:</label>
                                                    <input type="date" class="form-control"  name="end_date"  value="{{old('end_date') ?? $offer->end_date }}" required>
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
</x-layouts.authenticated>
