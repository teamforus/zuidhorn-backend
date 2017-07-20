@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    Shopers
    <div class="btn-group pull-right"><a class="btn btn-default" href="./categories-edit.html"><em class="glyphicon glyphicon-plus"> </em> Add new</a><a class="btn btn-default" href="#"><em class="glyphicon glyphicon-upload"> </em> Import .csv</a>
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
                        <label>Category</label>
                        <select class="form-control" name="">
                          <option value="">Category 01</option>
                          <option value="">Category 02</option>
                          <option value="">Category 03</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Address</label>
                    <input class="form-control" name="" placeholder="Address">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="" placeholder="Name">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input class="btn btn-default" type="button" value="Filter">
                </div>
            </div>
        </div>
    </form>
</div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tHead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Categories</th>
                    <th class="text-right">ACTIONS</th>
                </tr>
            </tHead>
            <tBody>
                <tr>
                    <td><strong>#0001</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0002</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0003</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0004</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0005</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0006</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0007</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0008</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0009</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0010</strong></td>
                    <td>Lorem ipsum dolor.</td>
                    <td>Lorem ipsum 35/5.</td>
                    <td>1</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#" confirm-box data-box-title="Are you sure?" data-box-text="This action cannot be undone, double check before continue."><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
            </tBody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection