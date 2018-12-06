<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <div class="nav-tabs-custom" >
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab-form-1" data-toggle="tab" aria-expanded="true">
                            Lớp HP <i class="fa fa-exclamation-circle text-red hide"></i>
                        </a>
                    </li>
                </ul>
                <div class="tab-content fields-group">
                    <div class="tab-pane active" id="tab-form-1" >
                        <div class="grid-subject-register">
                            @include('vendor.admin.giang_vien.quan_ly_diem.table_giang_vien_mon_hoc')
                        </div>
                        @include('vendor.admin.giang_vien.quan_ly_diem.table_lop_hoc_phan')
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
