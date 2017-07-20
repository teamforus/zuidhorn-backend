@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">Add category</h4>
<hr>
<form class="form">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Category name:</label>
                <input class="form-control" type="text" placeholder="Name">
                <p class="text-danger">Hello world!</p>
            </div>
            <div class="form-group">
                <label class="form-label">Parent category:</label>
                <select class="form-control" name="" value="1">
                    <option value="-1">-</option>
                    <option value="1">Option 01</option>
                    <option value="2">Option 02</option>
                    <option value="3">Option 03</option>
                </select>
            </div>
            <div class="form-group">
                <input class="btn btn-default" type="button" value="Submit">
            </div>
        </div>
    </div>
</form>
@endsection