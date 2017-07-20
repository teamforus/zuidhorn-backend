@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    Bugets
    <div class="btn-group pull-right"><a class="btn btn-default" href="./categories-edit.html"><em class="glyphicon glyphicon-plus"> </em> Add new</a><a class="btn btn-default" href="#"><em class="glyphicon glyphicon-upload"> </em> Import .csv</a>
        <a class="btn btn-default" href="#"><em class="glyphicon glyphicon-download"> </em> Export .csv</a>
    </div>
</h4>
<hr>
<div class="alert alert-md alert-default">Lorem ipsum dolor sit amet.</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tHead>
                <tr>
                    <th>Id</th>
                    <th>Owner</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th class="text-right">ACTIONS</th>
                </tr>
            </tHead>
            <tBody>
                <tr>
                    <td><strong>#0001</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€163.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0002</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€352.00</td>
                    <td>2 categories.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0004</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€231.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0005</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€413.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0003</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€314.00</td>
                    <td>2 categories.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0006</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€213.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0007</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€431.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0008</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€231.00</td>
                    <td>3 categories</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0009</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€285.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>#0010</strong></td>
                    <td><a class="text-primary" href="#">Lorem ipsum dolor. </a></td>
                    <td>€195.00</td>
                    <td>Lorem ipsum.</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <a class="text-primary" href="#"> <em class="mdi mdi-pencil"></em>Edit</a>&nbsp; &nbsp;
                            <a class="text-primary" href="#"><em class="mdi mdi-delete"></em>Delete </a>
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