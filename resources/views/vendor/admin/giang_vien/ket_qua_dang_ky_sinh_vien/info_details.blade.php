<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <div class="nav-tabs-custom" >
                <div class="tab-content fields-group">
                    <div class="tab-pane active" id="tab-form-1" >
                        <div class="gridResultAll">
                            @include('vendor.admin.giang_vien.ket_qua_dang_ky_sinh_vien.table_ket_qua')
                        </div>
                        <div class="gridTimeTable">
                            @include('vendor.admin.giang_vien.ket_qua_dang_ky_sinh_vien.info_view_mon_hoc')
                        </div>
                        {{--@include('vendor.admin.giang_vien.ket_qua_dang_ky_sinh_vien.info_grid_thoi_khoa_bieu')--}}
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
