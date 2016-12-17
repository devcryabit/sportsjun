@extends('admin.layouts.app')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <h3>Testimonials</h3>
            <div class="pull-right">
                <a href="{{ route('admin.home.infolists.add','testimonials') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Weight</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @if ($lists->count())
                    @foreach ($lists as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td><img src="{{Helper::getImagePath($item->image,$item->type)}}" class="img-responsive" style="height:100px"/></td>
                            <td>{{$item->description}}</td>
                            <td><input type="number" value="{{ $item->weight }}"/></td>
                            <td>
                                <a href="{{route('admin.home.infolists.edit',['id'=>$item->id])}}" class="btn btn-info" >edit</a>
                                <button class="btn btn-danger"
                                        data-url="{{route('admin.home.infolists.delete',[$item->id])}}"
                                        data-title="Testimonials"
                                        data-confirm="Delete {title}"
                                        onclick="return removeConfirm(this);">X</button>    </td>
                        </tr>

                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            There no items.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>


        </div>
    </div>
    <script>
        $('#page-wrapper').css('margin', '0px');
    </script>
@endsection
