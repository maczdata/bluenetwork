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
        <title> Edit Account Level &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} edit account level">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Account Level') }} -({{$accountLevels->name}})
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-body">
                <h4>Edit Account Level</h4><br>
                <form action="/account-levels/update/{{$accountLevels->id}}" method="post">
                    @csrf
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') ?? $accountLevels->name}}">
                    <br>
                    <label for="">Enable</label>
                    <select name="enabled" id="" class="form-control">
                        <option value="">select an option</option>
                       
                        <option value="1" {{$accountLevels->enabled == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$accountLevels->enabled == 0 ? "selected" : "" }}>No</option>
                    </select>
                    <br>
                    <label for="">Withdrawal limit</label>
                    <input type="number" class="form-control" name="withdrawal_limit" value="{{ old('withdrawal_limit') ?? $accountLevels->withdrawal_limit}}">
                    <br>
                    <label for="">Daily limit</label>
                    <input type="number" class="form-control" name="daily_limit" value="{{ old('daily_limit') ?? $accountLevels->daily_limit}}">
                    <br>
                    <label for="">Transaction limit</label>
                    <input type="number" class="form-control" name="transaction_limit" value="{{ old('transaction_limit') ?? $accountLevels->transaction_limit}}">
                    <br>
                    <input type="submit" value="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
        <br>
        
       
    </div>
     
</x-layouts.authenticated>
