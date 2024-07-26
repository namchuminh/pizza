@extends('Admin.layouts.app')
@section('title', 'Chi tiết hóa đơn')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Hóa Đơn</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">Quản Lý Hóa Đơn</a></li>
                    <li class="breadcrumb-item active">Chi Tiết Hóa Đơn</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
    <!-- /.row -->
    <div class="row">
        <div class="col-12">
        <div class="card">
            <h4 class="text-center mt-3">Thông Tin Hóa Đơn</h4>
            <div style="line-height: 20px;word-spacing: 2px;" class="m-3 detail-order">
                <span style="display: flex;">
                    <b>Mã Hóa Đơn: </b>
                    <p style="margin-left: 10px;" id="code"></p>
                </span>
                <span style="display: flex;">
                    <b>Tên Khách Hàng: </b>
                    <p style="margin-left: 10px;" id="customer"></p>
                </span>
                <span style="display: flex;">
                    <b>Số Điện Thoại </b>
                    <p style="margin-left: 10px;" id="phone"></p>
                </span>
                <span style="display: flex;">
                    <b>Địa Chỉ Nhận: </b>
                    <p style="margin-left: 10px;" id="address"></p>
                </span>
                <span style="display: flex;">
                    <b>Thời Gian: </b>
                    <p style="margin-left: 10px;" id="created_at"></p>
                </span>
                <span style="display: flex;">
                    <b>Thanh Toán: </b>
                    <p style="margin-left: 10px;" id="payment"></p>
                </span>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="not_print">Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá Bán</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="text-right mt-2 d-flex justify-content-end mr-4">
                <span class="d-flex m-1">
                    <b>Tạm Tính: </b>
                    <p style="margin-left: 5px;" id="tempSum"></p>
                </span>
                <span class="d-flex m-1">
                    <b>Giảm Giá: </b>
                    <p style="margin-left: 5px;" id="coupon"></p>
                </span>
                <span class="d-flex m-1">
                    <b>Tổng Tiền: </b>
                    <p style="margin-left: 5px;" id="total_amount"></p>
                </span>
            </div>
            </div>
            <div class="card-footer clearfix" style="background: white;">
                <a class="btn btn-success not_print" href="{{ route('admin.order.index') }}">Quay Lại</a>
                <button class="btn btn-primary not_print" onclick="window.print()">In Hóa Đơn</button>
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        var id = window.location.search.split('id=')[1];
        var tempSum = 0;
        function fetchData() {
            $.ajax({
                url: `{{ $api_url }}orders/${id}/detail/`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    let rowNumber = 1;

                    $('tbody').empty();
                    // Populate the table with data
                    response.forEach(item => {

                        let sale_price = Number(item.product.sale_price);
                        let sum = sale_price * Number(item.quantity)
                        tempSum += sum;
                        $('tbody').append(`
                            <tr>
                                <td>${rowNumber++}</td>
                                <td class="not_print"><img src="{{ asset('storage') }}/${item.product.image}" alt="${item.product.image}" style="width: 150px; height: 150px;"></td>
                                <td>${item.product.name}</td>
                                <td>${sale_price.toLocaleString('vi-VN')}đ</td>
                                <td>${item.quantity} cái</td>
                                <td>${sum.toLocaleString('vi-VN')}đ</td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            // Retry the fetch data request with the new token
                            fetchData();
                        });
                    } else {
                        window.location.href = '{{ route('admin.order.index') }}';
                    }
                }
            });
        }

        function fetchDataOrder() {
            $.ajax({
                url: `{{ $api_url }}orders/${id}`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    $("#code").text(response.order_code);
                    $("#customer").text(response.user.name);
                    $("#phone").text(response.phone);
                    $("#address").text(response.address);
                    $("#created_at").text(response.created_at);
                    $("#payment").text(response.payment == 0 ? "Chưa Thanh Toán" : "Đã Thanh Toán");

                    let total_amount = Number(response.total_amount);

                    $("#total_amount").text(total_amount.toLocaleString('vi-VN') + "đ");
                    $("#coupon").text(response.coupon == null ? "0%" : response.coupon.value + "%");
                    $("#tempSum").text(tempSum.toLocaleString('vi-VN') + "đ");

                    if (response.status == 1) {
                        $(".card-footer").append('<a id="status" class="btn btn-info not_print" href="#" style="color: white;">Duyệt Đơn Hàng</a>');
                    } else if (response.status == 2) {
                        $(".card-footer").append('<a id="status" class="btn btn-info not_print" href="#" style="color: white;">Đã Giao Đơn</a>');
                    } 

                    if ((response.status != 0) && (response.status != 3)) {
                        $(".card-footer").append('<a id="cancel" class="btn btn-danger not_print ml-1" href="#" style="color: white;">Hủy Đơn Hàng</a>');
                    } 
                    
                    $('#cancel').click(function(e){
                        e.preventDefault();
                        cancelOrder(id);
                        $("#cancel").remove();
                        $("#status").remove();
                    })

                    $('#status').click(function(e){
                        e.preventDefault();
                        statusOrder(id);
                    })
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            // Retry the fetch data request with the new token
                            fetchDataOrder();
                        });
                    } else {
                        window.location.href = '{{ route('admin.order.index') }}';
                    }
                }
            });
        }

        fetchData();
        fetchDataOrder();

        function cancelOrder(orderId) {
            $.ajax({
                url: `{{ $api_url }}orders/${orderId}/cancel`,
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 5000
                    };
                    toastr.success('Hủy đơn hàng thành công!', 'Thành Công');
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            cancelOrder(orderId);
                        });
                    } else {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 5000
                        };
                        toastr.error('Hủy đơn hàng thất bại!', 'Thất Bại');
                    }
                }
            });
        }

        function statusOrder(orderId) {
            $.ajax({
                url: `{{ $api_url }}orders/${orderId}/status`,
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                },
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: 5000
                    };
                    toastr.success('Cập nhật trạng thái đơn hàng thành công!', 'Thành Công');

                    if(response.status == 2){
                        $("#status").text('Đã Giao Đơn');
                    }else if(response.status == 3){
                        $("#cancel").remove();
                        $("#status").remove();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        refreshToken().done(function() {
                            cancelOrder(orderId);
                        });
                    } else {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            positionClass: 'toast-top-right',
                            timeOut: 5000
                        };
                        toastr.error('Cập nhật trạng thái đơn hàng thất bại!', 'Thất Bại');
                    }
                }
            });
        }

        function refreshToken() {
            return $.ajax({
                url: `{{ $api_url }}refresh`,
                method: 'POST',
                data: {
                    'refresh_token': localStorage.getItem('refresh_token')
                }
            }).done(function(response) {
                localStorage.setItem('access_token', response.access_token);
            }).fail(function(xhr) {
                // Clear localStorage and redirect to login
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                window.location.href = '{{ route('admin.login') }}'; // Replace with your login route
            });
        }
    })
</script>
@endsection
@section('css')
<style type="text/css">
  @media print{
    .main-footer{
      display: none !important;
    }

    .content-wrapper{
      background-color: white;
    }

    .not_print{
      display: none !important;
    }

  }
</style>
@endsection