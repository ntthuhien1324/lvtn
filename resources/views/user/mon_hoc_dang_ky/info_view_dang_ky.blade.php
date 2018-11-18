<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab-form-1" data-toggle="tab" aria-expanded="true">
                            Lớp học phần <i class="fa fa-exclamation-circle text-red hide"></i>
                        </a>
                    </li>
                </ul>
                <div class="tab-content fields-group">
                    <div class="tab-pane active" id="tab-form-1">
                        @include('user.mon_hoc_dang_ky.table_mon_hoc_dang_ky')
                        @include('user.mon_hoc_dang_ky.info_view_mon_hoc')
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>