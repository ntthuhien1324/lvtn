<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <div class="nav-tabs-custom" >
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab-form-1" data-toggle="tab" aria-expanded="true">
                            Học kỳ <i class="fa fa-exclamation-circle text-red hide"></i>
                        </a>
                    </li>
                </ul>
                <div class="tab-content fields-group">
                    <div class="tab-pane active" id="tab-form-1" >
                        <div class="gridResultAll">
                            @include('user.ket_qua_dang_ky.table_ket_qua')
                        </div>
                        <div class="gridTimeTable">
                            @include('user.ket_qua_dang_ky.info_view_mon_hoc')
                        </div>
                        @include('user.ket_qua_dang_ky.info_grid_tkb')
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
