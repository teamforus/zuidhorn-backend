@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    Permissions
    <div class="btn-group pull-right">
        <a class="btn btn-default" href="./categories-edit.html"><em class="glyphicon glyphicon-plus"> </em> Add new</a>
        <a class="btn btn-default" href="#"><em class="glyphicon glyphicon-upload"> </em> Import .csv</a>
        <a class="btn btn-default" href="#"><em class="glyphicon glyphicon-download"> </em> Export .csv</a>
    </div>
</h4>
<hr>
<div class="row">
    <div class="col-md-12">
        <form class="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select System User:</label>
                        <select class="form-control">
                          <option value="">User 001</option>
                          <option value="">User 002</option>
                          <option value="">User 003</option>
                          <option value="">User 004</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    <input class="btn btn-default" type="button" value="Select">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <hr>
                    <p>
                        <label for="perms">
                            <input type="checkbox" id="perms"> Manage Permissions
                        </label>
                    </p>
                    <p>
                        <label for="citizens">
                            <input type="checkbox" id="citizens"> Manage Citizens
                        </label>
                    </p>
                    <p>
                        <label for="categories">
                            <input type="checkbox" id="categories"> Manage Categories
                        </label>
                    </p>
                    <p>
                        <label for="bugets">
                            <input type="checkbox" id="bugets"> Manage Bugets
                        </label>
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input class="btn btn-default" type="button" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection