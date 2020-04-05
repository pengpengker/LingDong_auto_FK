<?php


namespace app\merchant\controller;


use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsCategory as CategoryModel;
use service\MerchantLogService;
use think\Db;

class GoodsDuijie extends Base
{
    // 对接列表
    public function index()
    {
        $this->setTitle('对接中心');
        $query = [
            'cate_id' => input('cate_id/s', ''),
            'name' => input('name/s', ''),
            'user_id' => $this->user->id,
        ];
        $where = $this->genereate_where($query);
        $goodsList = GoodsModel::where($where)->order('sort desc,id desc')->paginate(30, false, [
            'query' => $query,
        ]);
        // 分页
        $page = $goodsList->render();
        $this->assign('page', $page);
        $this->assign('goodsList', $goodsList);

        // 商品分类
        $categorys = CategoryModel::where(['user_id' => $this->user->id])->order('sort desc,id desc')->select();
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    // 商品对接信息渲染
    public function myduijie()
    {
        if(!$this->request->isPost()){
            $this->setTitle('我的对接');
            $this->assign('djkey',$this->user->sj_duijie_key);
            //判断有没有对接
            if($this->user->sj_duijie_key){
                $sj_uid = \app\common\model\User::where('duijie_key',$this->user->sj_duijie_key)->find()['id'];
                $query = [
                    'cate_id' => input('cate_id/s', ''),
                    'name' => input('name/s', ''),
                    'user_id' => \app\common\model\User::where('duijie_key',$this->user->sj_duijie_key)->find()['id'],
                ];
            }else{
                $query = [
                    'cate_id' => input('cate_id/s', ''),
                    'name' => input('name/s', ''),
                    'user_id' => '0.5',
                ];
            }
            $where = $this->genereate_where($query);
            $goodsList = GoodsModel::where($where)->order('sort desc,id desc')->paginate(30, false, [
                'query' => $query,
            ]);
            //查询本人已对接的商品
            $duijie_list = GoodsModel::where('user_id',$this->user->id)->where('duijie_id','neq',0)->select();

            //循环判断$goodsList里的商品是否被上架
            foreach ($goodsList as $k => $v) {
                $goodsList[$k]['duijie_status'] = 0;
                $goodsList[$k]['duijie_name'] = '';
                $goodsList[$k]['duijia_secmoney'] = 0;
                foreach ($duijie_list as $ks => $vs) {
                    if($vs['duijie_id'] === $v['id']){
                        $goodsList[$k]['duijie_status'] = 1;
                        $goodsList[$k]['duijie_name'] = $vs['name'];
                        $goodsList[$k]['duijia_secmoney'] = $vs['price'];
                    }
                }
            }

            // 分页
            $page = $goodsList->render();
            $this->assign('page', $page);
            $this->assign('goodsList', $goodsList);
            // 商品分类
            if(!empty($sj_uid)){
                $categorys = CategoryModel::where(['user_id' => $sj_uid])->order('sort desc,id desc')->select();
                $this->assign('categorys', $categorys);
            }
            return $this->fetch();
        }
        $duijie_key = $this->user->sj_duijie_key;
        $this->user->sj_duijie_key = trim($this->request->param('sj_duijie_key'));
        if($this->request->param('sj_duijie_key')){
            if(!\app\common\model\User::where('duijie_key',$this->request->param('sj_duijie_key'))->find()){
                $this->error('没有该对接码');
            }
        }
        if($this->user->save()){
            if(empty($this->request->param('sj_duijie_key'))){
                //删除该商户下的全部对接商品
                GoodsModel::where('user_id',$this->user->id)->where('duijie_id','neq',0)->delete();
                MerchantLogService::write('商户对接控制', '取消对接'.$duijie_key);
            }else{
                MerchantLogService::write('商户对接控制', '开始对接'.$this->request->param('sj_duijie_key'));
            }
            $this->success('对接控制成功');
        }else{
            $this->error('对接控制失败');
        }
    }

    //对接商品
    public function start_duijie_shop()
    {
        $sjgoods = $this->virfly_duijie_data($this->request->param('shop_id'));
        if($this->request->isPost()){
            if(empty($this->request->param('shop_id')) || empty($this->request->param('name')) || empty($this->request->param('price'))){
                $this->error('参数缺失','myduijie');
            }
            //把上级商品信息插入我的商品信息中
            $data = [
                'user_id' => $this->user->id,
                'cate_id' => $sjgoods['cate_id'],
                'theme' => $sjgoods['theme'],
                'sort' => input('sort/d', 0),
                'name' => input('name/s', ''),
                'price' => input('price/s', 0),
                'cost_price' => $sjgoods['cost_price'],
                'wholesale_discount' => $sjgoods['wholesale_discount'],
                'wholesale_discount_list' => $sjgoods['wholesale_discount_list'],
                'limit_quantity' => $sjgoods['limit_quantity'],
                'inventory_notify' => $sjgoods['inventory_notify'],
                'inventory_notify_type' => $sjgoods['inventory_notify_type'],
                'coupon_type' =>  $sjgoods['coupon_type'],
                'sold_notify' =>  $sjgoods['sold_notify'],
                'take_card_type' => $sjgoods['take_card_type'],
                'visit_type' => $sjgoods['visit_type'],
                'visit_password' => $sjgoods['visit_password'],
                'is_duijie' => 0,
                'duijie_smilepic' => 0,
                'contact_limit' => $sjgoods['contact_limit'],
                'content' => $sjgoods['content'],
                'remark' => $sjgoods['remark'],
                'sms_payer' => $sjgoods['sms_payer'],
                'status' => 1,
                'create_at' => $sjgoods['create_at'],
                'duijie_id' => $sjgoods['id'],
            ];
            // 字词检查
            $res = check_wordfilter($data['name']);
            if ($res !== true) {
                $this->error('商品名包含敏感词汇“' . $res . '”！');
            }
            $res = check_wordfilter($data['content']);
            if ($res !== true) {
                $this->error('商品说明包含敏感词汇“' . $res . '”！');
            }
            $res = check_wordfilter($data['remark']);
            if ($res !== true) {
                $this->error('使用说明包含敏感词汇“' . $res . '”！');
            }
            //检查商品价格区间
            if (sysconf('goods_min_price') > 0 && $data['price'] < sysconf('goods_min_price')) {
                $this->error('商品价格不能少于' . sysconf('goods_min_price') . '元');
            }
            if (sysconf('goods_max_price') > 0 && sysconf('goods_max_price') < $data['price']) {
                $this->error('商品价格不能超过' . sysconf('goods_max_price') . '元');
            }
            //检查加价是否正常
            if(input('price/s', 0) <= 0){
                $this->error('代理商品必须加价');
            }
            if(input('price/s', 0) < $sjgoods['price']){
                $this->error('加价价格必须高于'.$sjgoods['price']);
            }
            $res = $this->validate($data, 'Goods');
            if ($res !== true) {
                $this->error($res);
            }
            $res = GoodsModel::create($data);
            if ($res !== false) {
                //创建成功，马上创建短链接
                GoodsModel::makeLink($data['user_id'], $res->id);
                MerchantLogService::write('添加代理商品成功', '添加代理商品成功，本商品ID:' . $res->id . ',名称:' . $res->name . ',加价价格:' . $res->price . ',成本价:' . $sjgoods['price']);
                $this->success('代理商品成功','myduijie');
            } else {
                $this->error('添加失败！');
            }
        }else{
            if(empty($this->request->param('shop_id'))){
                $this->error('参数缺失','myduijie');
            }
            $this->assign('shop_id',$this->request->param('shop_id'));
            $this->assign('sjgoods',$sjgoods);
            return view();
        }
    }

    //取消对接商品
    public function stop_duijie_shop()
    {
        $sjgoods = $this->virfly_duijie_data($this->request->param('shop_id'));
        if(GoodsModel::where('user_id',$this->user->id)->where('duijie_id',$this->request->param('shop_id'))->delete()){
            $this->success('取消对接成功','myduijie');
        }else{
            $this->error('取消对接失败','myduijie');
        }
    }

    //验证对接数据
    public function virfly_duijie_data($shop_id)
    {
        if(empty($shop_id)){
            $this->error('参数缺失','myduijie');
        }
        //判断我是否有对接
        if(empty($this->user->sj_duijie_key)){
            $this->error('请先设置一个对接钥匙吧','myduijie');
        }
        //查询上级对接用户
        $sjinfo = \app\common\model\User::where('duijie_key',$this->user->sj_duijie_key)->find();
        if(!$sjinfo){
            $this->error('上级对接用户异常，请更换对接对象','myduijie');
        }
        //查询该商品是否属于上级用户
        $sjgoods = GoodsModel::where('user_id',$sjinfo['id'])->where('id',$this->request->param('shop_id'))->find();
        if(!$sjgoods){
            $this->error('上级商户不存在该商品','myduijie');
        }
        //判断该商品是否可对接
        if($sjgoods['is_duijie'] !== 1 || !empty($sjgoods['duijie_id'])){
            $this->error('该商品不存在对接形式','myduijie');
        }
        return $sjgoods;
    }

    /**
     * 生成查询条件
     */
    protected function genereate_where($params)
    {
        $where = [];
        if(!empty($params['user_id'])){
            $where['user_id'] = ['=', $params['user_id']];
        }
        $action = $this->request->action();
        switch ($action) {
            case 'index':
                break;
            case 'myduijie':
                if ($params['cate_id'] !== '') {
                    $where['cate_id'] = ['=', $params['cate_id']];
                }
                if ($params['name'] !== '') {
                    $where['name'] = ['like', '%' . $params['name'] . '%'];
                }
                $where['status'] = ['=', 1];
                break;
        }
        $where['is_duijie'] = ['=', 1];;
        return $where;
    }
}