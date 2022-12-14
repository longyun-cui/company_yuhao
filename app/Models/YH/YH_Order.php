<?php
namespace App\Models\YH;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class YH_Order extends Model
{
    use SoftDeletes;
    //
    protected $table = "yh_order";
    protected $fillable = [
        'active', 'status', 'category', 'type', 'form', 'sort',
        'item_active', 'item_status', 'item_result', 'item_category', 'item_type', 'item_form',
        'owner_active', 'is_show', 'is_published', 'is_completed',
        'owner_id', 'creator_id', 'updater_id', 'publisher_id', 'completer_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'p_id', 'parent_id',
        'org_id', 'admin_id',
        'item_id', 'menu_id',
        'order_category', 'order_type',
        'name', 'title', 'subtitle', 'description', 'content', 'remark', 'custom', 'custom2', 'custom3',
        'amount', 'oil_card_amount', 'invoice_amount', 'invoice_point',
        'information_fee', 'customer_management_fee', 'time_limitation_deduction',
        'driver_fine',
        'outside_car_price',
        'income_total', 'expenditure_total', 'income_to_be_confirm', 'expenditure_to_be_confirm',
        'travel_distance', 'time_limitation_prescribed',
        'route_type', 'route_id', 'route', 'route_fixed', 'route_temporary',
        'pricing_id',
        'client_id',
        'car_owner_type', 'car_id', 'trailer_id', 'container_id', 'container_type', 'outside_car', 'outside_trailer',
        'trailer_type', 'trailer_length', 'trailer_volume', 'trailer_weight', 'trailer_axis_count',
        'departure_place', 'destination_place', 'stopover_place', 'stopover_place_1', 'stopover_place_2',
        'assign_time',
        'should_departure_time', 'should_arrival_time',
        'actual_departure_time', 'actual_arrival_time',
        'stopover_departure_time', 'stopover_arrival_time',
        'stopover_1_departure_time', 'stopover_1_arrival_time',
        'stopover_2_departure_time', 'stopover_2_arrival_time',
        'receipt_status', 'receipt_need', 'receipt_address', 'GPS',
        'subordinate_company', 'order_number', 'payee_name', 'arrange_people', 'car_supply', 'car_managerial_people',
        'driver', 'copilot', 'driver_name', 'copilot_name', 'driver_phone', 'copilot_phone', 'weight',
        'company', 'fund', 'mobile', 'city', 'address',
        'link_url', 'cover_pic', 'attachment_name', 'attachment_src', 'tag',
        'visit_num', 'share_num', 'favor_num', 'comment_num',
        'published_at', 'completed_at'
    ];
    protected $dateFormat = 'U';

    protected $dates = ['created_at','updated_at','deleted_at'];
//    public function getDates()
//    {
////        return array(); // ???????????????
//        return array('created_at','updated_at');
//    }


    // ?????????
    function owner()
    {
        return $this->belongsTo('App\Models\YH\YH_User','owner_id','id');
    }
    // ?????????
    function creator()
    {
        return $this->belongsTo('App\Models\YH\YH_User','creator_id','id');
    }
    // ?????????
    function updater()
    {
        return $this->belongsTo('App\Models\YH\YH_User','updater_id','id');
    }
    // ?????????
    function completer()
    {
        return $this->belongsTo('App\Models\YH\YH_User','completer_id','id');
    }
    // ??????
    function user()
    {
        return $this->belongsTo('App\Models\YH\YH_User','user_id','id');
    }


    // ??????
    function client_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Client','client_id','id');
    }


    // ????????????
    function route_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Route','route_id','id');
    }


    // ??????
    function pricing_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Pricing','pricing_id','id');
    }


    // ??????
    function car_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','car_id','id');
    }
    // ??????
    function trailer_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','trailer_id','id');
    }
    // ??????
    function container_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','container_id','id');
    }




    // ??????
    function attachment_list()
    {
        return $this->hasMany('App\Models\YH\YH_Attachment','order_id','id');
    }




    // ????????????
    function finance_list()
    {
        return $this->hasMany('App\Models\YH\YH_Finance','order_id','id');
    }




    // ????????????
    function pivot_item_relation()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Item','item_id','id');
    }

    // ????????????
    function others()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Item','item_id','id');
    }

    // ??????
    function collections()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Collection','item_id','id');
    }

    // ????????????
    function forward_item()
    {
        return $this->belongsTo('App\Models\YH\YH_Item','item_id','id');
    }




    // ?????????????????????
    function pivot_collection_item_users()
    {
        return $this->belongsToMany('App\Models\YH\YH_User','pivot_user_item','item_id','user_id');
    }




    // ????????? ???????????????
    function menu()
    {
        return $this->belongsTo('App\Models\YH\YH_Menu','menu_id','id');
    }

    // ????????? ???????????????
    function menus()
    {
        return $this->belongsToMany('App\Models\YH\YH_Menu','pivot_menu_item','item_id','menu_id');
    }


    /**
     * ?????????????????????????????????
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'itemable');
    }

    /**
     * ?????????????????????????????????
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




    /**
     * ???????????????
     */
//    public function update_batch_in($setColumn,$setValue,$whereColumn,$whereValue)
//    {
//        $sql ="UPDATE ".$this->table." SET ".$setColumn." = ".$setValue." WHERE ".$whereColumn." = ".$whereValue;
//        return DB::update(DB::raw($sql);
//    }
    
    
}
