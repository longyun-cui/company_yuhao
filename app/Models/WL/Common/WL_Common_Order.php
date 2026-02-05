<?php
namespace App\Models\WL\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class WL_Common_Order extends Model
{
    use SoftDeletes;
    //
    protected $table = "wl__common__order";
    protected $fillable = [
        'active', 'status', 'result',
        'category', 'type', 'group',
        'item_active', 'item_status', 'item_result',
        'item_category', 'item_type', 'item_group',

        'order_active', 'order_status', 'order_result',
        'order_category', 'order_type', 'order_group',

        'owner_active', 'is_show',

        'owner_id', 'creator_id', 'updater_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'parent_id', 'p_id',

        'is_published', 'publisher_id', 'published_at', 'published_date',
        'is_verified', 'verifier_id', 'verified_at', 'verified_date',
        'is_audited', 'auditor_id', 'audited_at', 'audited_date',
        'is_confirmed', 'confirmor_id', 'confirmed_at', 'confirmed_date',
        'is_completed', 'completer_id', 'completed_at', 'completed_date',

        'create_type',

        'name', 'username', 'nickname', 'true_name', 'short_name', 'alias_name',
        'title', 'subtitle', 'description', 'content', 'remark', 'tag', 'custom', 'custom2', 'custom3', 'attachment', 'portrait_img', 'cover_pic',


        'circle_id',
        'route_type',
        'route_id',
        'route',
        'pricing_id',

        'client_id',
        'project_id',

        'car_owner_type',
        'car_id',
        'trailer_id',
        'container_id',
        'container_type',

        'assign_date',
        'task_date',

        'external_car',
        'external_trailer',

        'driver_id',
        'copilot_id',

        'driver_name',
        'driver_phone',
        'copilot_name',
        'copilot_phone',

        'trailer_type', 'trailer_length', 'trailer_volume', 'trailer_weight', 'trailer_axis_count',

        'transport_departure_place',
        'transport_destination_place',
        'transport_stopover_place',
        'transport_stopover_place_1',
        'transport_stopover_place_2',


        'transport_mileage',
        'transport_distance',
        'transport_time_limitation',

        'should_departure_time',
        'should_arrival_time',
        'actual_departure_time',
        'actual_arrival_time',
        'stopover_departure_time',
        'stopover_arrival_time',
        'stopover_1_departure_time',
        'stopover_1_arrival_time',
        'stopover_2_departure_time',
        'stopover_2_arrival_time',


        'financial_income_total',
        'financial_income_to_be_confirm',
        'financial_expense_total',
        'financial_expense_to_be_confirm',
        'financial_deduction_total',
        'financial_fine_total',


        'settlement_period',
        'freight_amount',
        'deposit_amount',
        'oil_card_amount',

        'invoice_amount',
        'invoice_point',
        'reimbursable_amount',
        'customer_management_fee',
        'administrative_fee',
        'time_limitation_deduction',
        'driver_fine',
        'information_fee',
        'information_fee_description',

        'ETC_price',

        'follow_content',
        'last_operation_date',
        'last_operation_datetime',

        'oil_amount', 'oil_unit_price', 'oil_fee',
        'toll_main_etc', 'toll_east_etc', 'toll_south_etc',
        'toll_main_cash', 'toll_east_cash', 'toll_south_cash',
        'oil_main_cost', 'oil_east_cost', 'oil_south_cost',
        'shipping_cost', 'urea_cost', 'salary_cost', 'others_cost',
        'income_real_first_amount', 'income_real_first_time', 'income_real_final_amount', 'income_real_final_time', 'income_result',
        'outside_car_price', 'outside_car_first_amount', 'outside_car_first_time', 'outside_car_final_amount', 'outside_car_final_time',
        'income_total', 'expenditure_total', 'income_to_be_confirm', 'expenditure_to_be_confirm',

        'empty_route', 'empty_route_type', 'empty_route_id', 'empty_route_temporary',
        'empty_distance',
        'empty_oil_price', 'empty_oil_amount',
        'empty_refueling_pay_type', 'empty_refueling_charge', 'empty_toll_cash', 'empty_toll_ETC',

        'receipt_status', 'receipt_need', 'receipt_address', 'GPS', 'is_delay',
        'subordinate_company', 'order_number', 'payee_name', 'arrange_people', 'car_supply', 'car_managerial_people',

        'weight',

        'visit_num', 'share_num', 'favor_num',  'follow_num', 'fans_num',

    ];
    protected $dateFormat = 'U';

    protected $hidden = ['content','custom'];

    protected $dates = ['created_at','updated_at','deleted_at'];
//    public function getDates()
//    {
////        return array(); // 原形返回；
//        return array('created_at','updated_at');
//    }


    // 拥有者
    function owner()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','owner_id','id');
    }
    // 创作者
    function creator()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','creator_id','id');
    }
    // 更改者
    function updater()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','updater_id','id');
    }
    // 审核者
    function verifier()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','verifier_id','id');
    }
    // 完成者
    function completer()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','completer_id','id');
    }
    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','user_id','id');
    }


    // 客户
    function client_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Client','client_id','id');
    }
    // 项目
    function project_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Project','project_id','id');
    }


    // 环线
    function circle_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Circle','circle_id','id');
    }


    // 固定线路
    function route_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Route','route_id','id');
    }
    // 固定线路
    function empty_route_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Route','empty_route_id','id');
    }


    // 定价
    function pricing_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Pricing','pricing_id','id');
    }


    // 车辆
    function car_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Car','car_id','id');
    }
    // 车挂
    function trailer_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Car','trailer_id','id');
    }
    // 车厢
    function container_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Car','container_id','id');
    }


    // 司机
    function driver_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','driver_id','id');
    }
    // 副驾
    function copilot_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','copilot_id','id');
    }




    // 附件
    function attachment_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Attachment','order_id','id');
    }




    // 财务记录
    function finance_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Finance','order_id','id');
    }
    function finance_income_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Finance','order_id','id');
    }
    function finance_expense_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Finance','order_id','id');
    }




    // 其他人的
    function pivot_item_relation()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Item','item_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Item','item_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Collection','item_id','id');
    }

    // 转发内容
    function forward_item()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Item','item_id','id');
    }




    // 与我相关的话题
    function pivot_collection_item_users()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_item','item_id','user_id');
    }




    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Menu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Menu','pivot_menu_item','item_id','menu_id');
    }


    /**
     * 获得此文章的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'itemable');
    }

    /**
     * 获得此文章的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




    /**
     * 自定义更新
     */
//    public function update_batch_in($setColumn,$setValue,$whereColumn,$whereValue)
//    {
//        $sql ="UPDATE ".$this->table." SET ".$setColumn." = ".$setValue." WHERE ".$whereColumn." = ".$whereValue;
//        return DB::update(DB::raw($sql);
//    }
    
    
}
