<div class="box">
    <div class="box-header">
        <div class="pull-left">
            <?php $show = 0; ?>
            @foreach($grid->rows() as $row)
                @foreach($grid->columnNames as $name)
                    @if($name == 'Số tín chỉ hiện tại' && $show == 0)
                        <?php $show++; ?>
                        <div class="btn btn-success btn-sm" style="font-weight: bold;"> Tổng số TC: {!! $row->column($name) !!} </div>
                    @endif
                @endforeach
            @endforeach
        </div>
        <div class="pull-left" style="margin-left: 10px;">
            <?php $showPoint = 0; ?>
            @foreach($grid->rows() as $row)
                @foreach($grid->columnNames as $name)
                    @if($name == 'Điểm TK ALL' && $showPoint == 0)
                        <?php $showPoint++; ?>
                        <div class="btn btn-danger btn-sm" style="font-weight: bold;"> Điểm TK: {!! $row->column($name) !!}</div>
                    @endif
                @endforeach
            @endforeach
        </div>
        <div class="box-header with-border">
            <div class="pull-right">
                {!! $grid->renderExportButton() !!}
                {!! $grid->renderCreateButton() !!}
            </div>
            <span>
            {!! $grid->renderHeaderTools() !!}
        </span>
        </div>

        {!! $grid->renderFilter() !!}

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding ">
        <table class="table table-hover table-striped table-bordered tableColor">
            <tr >
                @foreach($grid->columns() as $column)
                    @if($column->getLabel() != 'Số tín chỉ hiện tại' && $column->getLabel() != 'Điểm TK ALL' )
                        <th class="colorth" style="background-color: #00a65a;color: white;"><b>{{$column->getLabel()}}{!! $column->sorter() !!}</b></th>
                    @endif
                @endforeach
            </tr>

            @foreach($grid->rows() as $row)
                <tr {!! $row->getRowAttributes() !!}>
                    @foreach($grid->columnNames as $name)
                        @if($name != 'Số tín chỉ hiện tại' && $name != 'Điểm TK ALL')
                            <td {!! $row->getColumnAttributes($name) !!} >
                                {!! $row->column($name) !!}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach

            {!! $grid->renderFooter() !!}

        </table>
    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!} <br><br>

    </div>
    <!-- /.box-body -->
</div>
