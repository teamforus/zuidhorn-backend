@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">Import CSV file</h4>
<hr>
<form class="form">
    <div class="row">
        <div class="col-md-12">
            <h5> <strong>1) </strong>First you need to select your .csv file.</h5>
            <p><a class="btn btn-default" href="#"><em class="glyphicon glyphicon-upload"> </em> Select file</a></p>
            <p class="text-success">File uploaded, size 5.6Mb, 45.735 rows found.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5> <strong>2) </strong>Next, you need to calculate each record's childrens count.</h5>
            <p><a class="btn btn-default" href="#"><em class="glyphicon glyphicon-play"> </em> Process</a></p>
            <p class="text-success">Success, all 45.735 rows parsed.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5> <strong>3) </strong>Now, you can download new .csv file that contains children count.</h5>
            <p>
                <a class="btn btn-default" href="#"> <em class="glyphicon glyphicon-download"></em> Download</a>
            </p>
            <p class="text-success">Downloaded.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5> <strong>4) </strong>And upload required columns to platform, but first select buget category.</h5>
        </div>
        <div class="col-md-4">
            <label class="form-label">Please select category:</label>
            <div class="form-group">
                <select class="form-control" name="" value="1">
                    <option value="1">Option 01</option>
                    <option value="2">Option 02</option>
                    <option value="3">Option 03</option>
                </select>
                <p class="text-danger">Hello world!</p>
            </div>
            <div class="form-group">
                <select class="form-control" name="" value="1">
                    <option value="1">Option 01</option>
                    <option value="2">Option 02</option>
                    <option value="3">Option 03</option>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="" value="1">
                    <option value="1">Option 01</option>
                    <option value="2">Option 02</option>
                    <option value="3">Option 03</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <p><a class="btn btn-default" href="#"><em class="glyphicon glyphicon-cloud-upload"> </em> Upload to sever</a></p>
            <p class="text-success">Success! Uploading done.</p>
        </div>
    </div>
</form>
@endsection