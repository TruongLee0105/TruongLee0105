@extends('admin.layout')
@section('titlepage', 'Control Pannel')
@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-3">
                <h2>Thêm dịch vụ</h2>

                <form class="needs-validation" action="{{ route('add_category_sv') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Tên dịch vụ</label>
                        <input class="form-control" type="text" id="name" name="name" required>
                        <div class="invalid-feedback">
                            Vui lòng nhập tên dịch vụ.
                        </div>
                    </div>

                    <button class="btn" style="background-color: #FF9999; color: white; border: none;" type="submit">Thêm dịch vụ</button>
                    </form>
            </div>

            <div class="col-lg-9">
                <div class="row" >
                    <div class="col-lg-6">
                        <h2>Danh Sách dịch vụ</h2>
                    </div>
                    <div class="col-lg-6">

                    </div>
                </div>
                <table class="table table-striped table-bordered" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table" style="background-color: #ffe0e0;">
                        <tr>
                            <th>ID</th>
                            <th>Tên dịch vụ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allService as $item)  
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <form action="{{ route('edit_category_sv', $item->id) }}" method="GET" class="d-inline-block">
                                        <button class="btn btn-sm btn-warning" type="submit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('delete_category_sv', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->

            </div>
        </div>
    </div>

</main>
@endsection