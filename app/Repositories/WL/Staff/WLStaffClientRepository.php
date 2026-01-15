<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Company;
use App\Models\WL\Common\WL_Common_Department;
use App\Models\WL\Common\WL_Common_Team;
use App\Models\WL\Common\WL_Common_Staff;

use App\Models\WL\Common\WL_Common_Car;
use App\Models\WL\Common\WL_Common_Driver;

use App\Models\WL\Common\WL_Common_Client;
use App\Models\WL\Common\WL_Common_Project;
use App\Models\WL\Common\WL_Common_Order;

use App\Models\WL\Common\WL_Common_Finance;
use App\Models\WL\Common\WL_Common_Fee;

use App\Models\WL\Staff\WL_Staff_Record_Operation;
use App\Models\WL\Staff\WL_Staff_Record_Visit;


use App\Models\WL\CLient\WL_Client_Staff;

use App\Models\YH\YH_Item;
use App\Models\YH\YH_Task;
use App\Models\YH\YH_Pivot_Circle_Order;
use App\Models\YH\YH_Pivot_Item_Relation;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffClientRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $modelUser;
    private $modelItem;
    private $view_blade_403;
    private $view_blade_404;


    public function __construct()
    {
        $this->modelUser = new WL_Common_Staff;
        $this->modelItem = new YH_Item;

        $this->view_blade_403 = env('TEMPLATE_WL_STAFF').'entrance.errors.403';
        $this->view_blade_404 = env('TEMPLATE_WL_STAFF').'entrance.errors.404';

        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }


    // 登录情况
    public function get_me()
    {
        if(Auth::guard("wl_staff")->check())
        {
            $this->auth_check = 1;
            $this->me = Auth::guard("wl_staff")->user();
            view()->share('me',$this->me);
        }
        else $this->auth_check = 0;

        view()->share('auth_check',$this->auth_check);

        if(isMobileEquipment()) $is_mobile_equipment = 1;
        else $is_mobile_equipment = 0;
        view()->share('is_mobile_equipment',$is_mobile_equipment);
    }




    /*
     * 客户-管理 Client
     */
    // 【客户】返回-列表-数据
    public function v1__client__datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Client::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); }
            ])
            ->whereIn('client_category',[1,11,31])
            ->whereIn('client_type',[0,1,9,11,19,21,22,41,61]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");


        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
        }




        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->withTrashed()->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【客户】获取 GET
    public function v1__client__item_get($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'item_id.required' => 'item_id.required.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;

        $operate = $post_data["operate"];
        if($operate != 'item-get') return response_error([],"参数[operate]有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Client::withTrashed()
            ->with([
//                'company_er'=>function($query) { $query->select('id','name'); },
//                'channel_er'=>function($query) { $query->select('id','name'); },
//                'business_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【客户】保存 SAVE
    public function v1__client__item_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'client_category.required' => '请选择项目种类！',
            'name.required' => '请输入项目名称！',
//            'name.unique' => '该项目已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'client_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:dk_project,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11])) return response_error([],"你没有操作权限！");

        if($operate_type == 'create')
        {
            // 添加 ( $id==0，添加一个项目 )
            $is_exist = WL_Common_Client::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【客户名称】已存在，请勿重复添加！");

            $mine = new WL_Common_Client;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit')
        {
            // 编辑
            $mine = WL_Common_Client::find($operate_id);
            if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
        }
        else return response_error([],"参数有误！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Client--insert--fail");

            DB::commit();
            return response_success(['id'=>$mine->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }
    // 【客户】保存 SAVE
    public function v1__client__item_save_1($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => 'operate.required.',
            'client_category.required' => '请选择客户类型！',
            'name.required' => '请输入客户名称！',
//            'name.unique' => '该客户已存在！',
            'client_admin_name.required' => '请输入管理员名称！',
            'client_admin_mobile.required' => '请输入管理员登录电话！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'client_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:dk_client,username',
            'client_admin_name' => 'required',
            'client_admin_mobile' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,19,61])) return response_error([],"你没有操作权限！");

        $channel_id = $post_data["channel_id"];
        if($channel_id > 0)
        {
            $channel = DK_Company::find($channel_id);
            if($channel)
            {
                $client_data["company_id"] = $channel->superior_company_id;
                $client_data["channel_id"] = $channel_id;

                $business_id = $post_data["business_id"];
                if($business_id > 0)
                {
                    $business = DK_Company::find($business_id);
                    if($business)
                    {
                        if($business->superior_company_id == $channel_id)
                        {
                            $client_data["business_id"] = $business_id;
                        }
                        else return response_error([],"选择的【商务】不属于选择的【渠道】，请重新选择！");
                    }
                    else return response_error([],"选择的【商务】不存在，刷新页面重试！");
                }
                else
                {
                    $client_data["business_id"] = 0;
                }
            }
            else return response_error([],"选择的【渠道】不存在，刷新页面重试！");
        }


        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_name_exist = WL_Common_Client::select('id')->where('name',$post_data["name"])->count();
            if($is_name_exist) return response_error([],"该【客户名】已存在，请勿重复添加！");

//            $is_mobile_exist = WL_Common_Client::select('id')->where('mobile',$post_data["client_admin_mobile"])->count();
//            if($is_mobile_exist) return response_error([],"该电话已存在，请勿重复添加！");

//            $is_mobile_exist = WL_Client_Staff::select('id')->where('mobile',$post_data["client_admin_mobile"])->count();
//            if($is_mobile_exist) return response_error([],"该电话已存在，请勿重复添加！");

            $client = new WL_Common_Client;
            $client_data["user_category"] = $post_data["user_category"];
            $client_staff_data["user_type"] = 1;
            $client_data["active"] = 1;
            $client_data["creator_id"] = $me->id;
            $client_data["name"] = $post_data["name"];
            $client_data["mobile"] = $post_data["client_admin_mobile"];
            $client_data["client_admin_name"] = $post_data["client_admin_name"];
            $client_data["client_admin_mobile"] = $post_data["client_admin_mobile"];
            $client_data["is_ip"] = $post_data["is_ip"];
            $client_data["ip_whitelist"] = $post_data["ip_whitelist"];
            $client_data["password"] = password_encode("12345678");

//            $client_staff = new WL_Client_Staff;
//            $client_staff_data["user_category"] = 11;
//            $client_staff_data["user_type"] = 11;
//            $client_staff_data["active"] = 1;
//            $client_staff_data["name"] = $post_data["client_admin_name"];
//            $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
//            $client_staff_data["creator_id"] = 0;
//            $client_staff_data["password"] = password_encode("12345678");
        }
        else if($operate_type == 'edit') // 编辑
        {
            // 该客户是否存在
            $client = WL_Common_Client::find($operate_id);
            if(!$client) return response_error([],"该客户不存在，刷新页面重试！");

            $client_data["name"] = $post_data["name"];
            $client_data["mobile"] = $post_data["client_admin_mobile"];
            $client_data["client_admin_name"] = $post_data["client_admin_name"];
            $client_data["client_admin_mobile"] = $post_data["client_admin_mobile"];
            $client_data["is_ip"] = $post_data["is_ip"];
            $client_data["ip_whitelist"] = $post_data["ip_whitelist"];

            // 客户名是否存在
            $is_name_exist = WL_Common_Client::select('id')->where('id','<>',$operate_id)->where('name',$post_data["name"])->count();
            if($is_name_exist) return response_error([],"该客户名已存在，不能修改成此客户名！");

            // 客户管理员是否存在
            $client_staff = WL_Client_Staff::where('id', $client->client_admin_id)->first();
            if($client_staff)
            {
                // 客户管理员存在

                // 判断电话是否重复
                if($post_data["client_admin_mobile"] != $client_staff->mobile)
                {
                    $is_mobile_exist = WL_Client_Staff::select('id')->where('id','<>',$client->client_admin_id)->where('mobile',$post_data["client_admin_mobile"])->count();
                    if($is_mobile_exist) return response_error([],"该电话已存在，不能修改成此电话！");

                    $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
                }
                $client_staff_data["name"] = $post_data["client_admin_name"];
            }
            else
            {
                // 客户管理员不存在

//                $client_staff = new WL_Client_Staff;
//                $client_staff_data["user_category"] = 11;
//                $client_staff_data["user_type"] = 11;
//                $client_staff_data["active"] = 1;
//                $client_staff_data["client_id"] = $client->id;
//                $client_staff_data["name"] = $post_data["client_admin_name"];
//                $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
//                $client_staff_data["creator_id"] = 0;
//                $client_staff_data["password"] = password_encode("12345678");
            }

        }
        else return response_error([],"参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }


            $bool = $client->fill($client_data)->save();
            if($bool)
            {
                if($operate_type == 'create')
                {
                    $client_staff_data["client_id"] = $client->id;
                }

                $bool_1 = $client_staff->fill($client_staff_data)->save();
                if($bool_1)
                {
                    if($operate_type == 'create')
                    {
                        $client->client_admin_id = $client_staff->id;
                        $bool = $client->save();
                        if(!$bool) throw new Exception("update--client--fail");
                    }
                }
                else throw new Exception("insert--client-staff--fail");
            }
            else throw new Exception("insert--client--fail");

            DB::commit();
            return response_success(['id'=>$client->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }

    // 【客户】启用
    public function v1__client__item_enable($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'item_id.required' => 'item_id.required.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Client::find($id);
        if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Client--update--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }
    // 【客户】禁用
    public function v1__client__item_disable($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'item_id.required' => 'item_id.required.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Client::find($id);
        if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Client--update--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }




}