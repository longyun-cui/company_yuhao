<?php
namespace App\Models\WL\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WL_Common_Motorcade extends Model
{
    use SoftDeletes;
    //
    protected $table = "wl__common__motorcade";
    protected $fillable = [
        'active', 'status',
        'category', 'type',

        'item_active', 'item_status',
        'item_category', 'item_type',

        'motorcade_active', 'motorcade_status',
        'motorcade_category', 'motorcade_type',

        'department_id',

        'superior_team_id',
        'leader_id',

        'owner_id', 'creator_id', 'updater_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'p_id', 'parent_id',
        'admin_id',

        'name', 'username', 'nickname', 'true_name', 'short_name', 'alias_name',
        'title', 'subtitle', 'description', 'content', 'remark', 'tag', 'custom', 'custom2', 'custom3', 'attachment', 'portrait_img', 'cover_pic',

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
    // 创作者
    function updater()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','updater_id','id');
    }
    // 创作者
    function completer()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','completer_id','id');
    }
    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','user_id','id');
    }




    // 部门
    function department_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Department','department_id','id');
    }


    // 【一对多】下级团队
    function subordinate_team_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Team','superior_team_id','id');
    }

    // 【反向一对多】上级团队
    function superior_team_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Team','superior_team_id','id');
    }




    // 【一对一】负责人
    function leader()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','leader_id','id');
    }




    // 【一对多】团队成员
    function district_staff_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','team_id','id');
    }
    // 【一对多】小组成员
    function group_staff_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','group_id','id');
    }
    // 【一对多】小队成员
    function group_unit_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','unit_id','id');
    }




    // 【多对多】审核人关联的项目
    function pivot_department_user()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','dk_pivot_user_department','department_id','user_id');
//            ->wherePivot('relation_type', 1);
//            ->withTimestamps();
    }


    // 多对多 审核人关联的项目
    function pivot_department_project()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Project','dk_pivot_team_project','team_id','project_id');
//            ->wherePivot('relation_type', 1);
//            ->withTimestamps();
    }


    // 工单
    function order_list_for_team()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','team_id','id');
    }
    // 工单
    function order_list_for_group()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','group_id','id');
    }
    // 工单
    function order_list_for_unit()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','unit_id','id');
    }




    // 驾驶员
    function driver_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','driver_id','id');
    }




    // 车辆订单
    function car_order_list()
    {
        return $this->hasMany('App\Models\DK\YH_Order','car_id','id');
    }
    // 车挂订单
    function trailer_order_list()
    {
        return $this->hasMany('App\Models\DK\YH_Order','trailer_id','id');
    }
    // 车辆订单【当前】
    function car_order_list_for_current()
    {
        return $this->hasMany('App\Models\DK\YH_Order','car_id','id');
    }
    // 车挂订单【当前】
    function trailer_order_list_for_current()
    {
        return $this->hasMany('App\Models\DK\YH_Order','trailer_id','id');
    }
    // 车辆订单【已完成】
    function car_order_list_for_completed()
    {
        return $this->hasMany('App\Models\DK\YH_Order','car_id','id');
    }
    // 车挂订单【已完成】
    function trailer_order_list_for_completed()
    {
        return $this->hasMany('App\Models\DK\YH_Order','trailer_id','id');
    }
    // 车辆订单【未来】
    function car_order_list_for_future()
    {
        return $this->hasMany('App\Models\DK\YH_Order','car_id','id');
    }
    // 车挂订单【未来】
    function trailer_order_list_for_future()
    {
        return $this->hasMany('App\Models\DK\YH_Order','trailer_id','id');
    }




    // 附件
    function attachment_list()
    {
        return $this->hasMany('App\Models\DK\YH_Attachment','item_id','id');
    }




    // 其他人的
    function pivot_item_relation()
    {
        return $this->hasMany('App\Models\DK\YH_Pivot_User_Item','item_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\DK\YH_Pivot_User_Item','item_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\DK\YH_Pivot_User_Collection','item_id','id');
    }

    // 转发内容
    function forward_item()
    {
        return $this->belongsTo('App\Models\DK\YH_Item','item_id','id');
    }




    // 与我相关的话题
    function pivot_collection_item_users()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_item','item_id','user_id');
    }




    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\DK\YH_Menu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\DK\YH_Menu','pivot_menu_item','item_id','menu_id');
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
}
