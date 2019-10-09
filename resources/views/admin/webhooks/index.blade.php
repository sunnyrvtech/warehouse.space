@extends('admin/layouts.app')

@section('content')
<div class="row form-group">
    <div class="col-md-12">
        <!--<a href="" class="btn btn-primary">Add New</a>-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="ui celled table" id="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                </tr>
                @forelse ($webhookinfo as $webhook)
                <tr>
                    <td>{{ $webhook->topic }}</td>
                </tr>
                @empty
                <tr>
                    <td><p>No records found !</p></td>
                </tr>
                @endforelse

            </thead>
        </table>

    </div>
</div>
@push('scripts')
<script type="text/javascript">
   
</script>
@endpush
@endsection
