<div class="row datatable-body datatable-wrapper car-location-list-clone" data-datatable-item-category="car" data-item-name="车辆">


    <div class="col-md-12 datatable-search-row  datatable-search-box">


        <div class=" pull-left">

            @if(in_array($me->user_type,[0,1,9,11,19,41,81,61]))
            <button type="button" onclick="" class="btn btn-filter car--batch--api-location-update"
                    data-form-id="form--for--car--item-edit"
                    data-modal-id="modal--for--car--item-edit"
                    data-title="更新定位"
            >
                <i class="fa fa-circle-o-notch"></i> 更新定位
            </button>
            @endif
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-play"></i> 启用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-stop"></i> 禁用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-download"></i> 导出</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-trash-o"></i> 批量删除</button>

        </div>


        <div class="pull-right">


            <input type="text" class="search-filter form-filter filter-keyup" name="car-id" placeholder="ID" />
            <input type="text" class="search-filter form-filter filter-keyup" name="car-name" placeholder="车牌" />

            @if(in_array($me->user_type,[0,1,9,11]))
            <select class="search-filter form-filter filter-md select2-box-c" name="car-category">
                <option value="1">车</option>
            </select>
            @endif

{{--            @if(in_array($me->user_type,[0,1,9,11]))--}}
{{--            <select class="search-filter form-filter filter-md select2-box-c" name="car-department-district">--}}
{{--                <option value="-1">选择大区</option>--}}
{{--                @foreach($department_district_list as $v)--}}
{{--                    <option value="{{ $v->id }}">{{ $v->name }}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            @endif--}}

            <select class="search-filter form-filter filter-md select2-box-c" name="car-item-status">
                <option value ="-1">全部</option>
                <option value ="1">启用</option>
                <option value ="9">禁用</option>
            </select>


            <button type="button" class="btn btn-default btn-filter filter-submit">
                <i class="fa fa-search"></i> 搜索
            </button>

            <button type="button" class="btn btn-default btn-filter filter-empty">
                <i class="fa fa-remove"></i> 清空
            </button>

            <button type="button" class="btn btn-default btn-filter filter-refresh">
                <i class="fa fa-circle-o-notch"></i> 刷新
            </button>

            <button type="button" class="btn btn-default btn-filter filter-cancel">
                <i class="fa fa-undo"></i> 重置
            </button>


        </div>


    </div>


    <div class="col-md-12 datatable-body">
        <div class="box box-primary box-solid-" style="box-shadow:0 0;">

            <div class="box-header with-border- margin-top-16px padding-top-8px _none">
                <h3 class="box-title datatable-title"></h3>
            </div>

            <div class="box-body no-padding">
                <div class="tableArea full margin-top-8px">
                    <table class='table table-striped table-bordered table-hover order-column'>
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box-header">
            </div>

        </div>
    </div>


</div>