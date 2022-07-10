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
        <title>User levels &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Users account list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User levels') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="container p-4">
        <!-- <div class="row">
            <div class="col-sm-4">  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Create Offer
                                    </button>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-4"></div>
        </div> -->
     
        <div class="row pt-4">
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @if(count($users) > 0)
                            @foreach($users as $user)
                                @if($user->profile)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{optional(optional(optional($user)->profile)->accountLevel)->name}}</td>
                                        <td> <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#user_{{$user->id}}">
                                                        Edit Accout level
                                                        </button>
                                        
                                        </td>
                                    </tr>
                                    <!-- The Modal -->
                                    <div class="modal" id="user_{{$user->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">{{$user->first_name }} Account Level </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="/user/manage/level/{{$user->id}}" method="post">
                                                    @csrf
                                                

                                                

                                                    <div class="mb-3 mt-3">
                                                        <label for="price" class="form-label">Account Level:</label>
                                                        <select name="level_id" class="form-control" required id="">
                                                            <option value="">Please select a level</option>
                                                            @foreach($accountLevel as $level)
                                                                <option value="{{$level->id}}" {{$level->id == old('level_id') || $level->id == $user->profile->account_level_id ? "selected" : ""}}>{{$level->name}}</option>
                                                            @endforeach
                                                        </select>
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
                                @endif
                            @endforeach
                    @endif
                    
                </tbody>
            </table>
        </div>
    </div>
    
</x-layouts.authenticated>
