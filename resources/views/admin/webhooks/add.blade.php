@extends('admin/layouts.app')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
            <form role="form" class="form-horizontal" action="{{ route('webhook-store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field()}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 control-label" for="name">Webhook Name:</label>
                            <div class="col-sm-9 col-md-9">
                                <select class="form-control" name="Webhook_name">
                                    <option value="">select....</option>
                                    @foreach($webhook_array as $key=>$value)
                                        <option id="{{ $key }}">{{ $value['name'] }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="id" value="{{ $id }}" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 text-center">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection