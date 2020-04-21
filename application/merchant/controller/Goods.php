<?php

namespace app\merchant\controller;

use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsCard as CardModel;
use app\common\model\GoodsCategory as CategoryModel;
use service\MerchantLogService;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Goods extends Base
{
    // 商品列表
    public function index()
    {
        $this->setTitle('商品列表');
        ////////////////// 查询条件 //////////////////
        $query = [
            'cate_id' => input('cate_id/s', ''),
            'name' => input('name/s', ''),
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

    // 改变状态
    public function changeStatus()
    {
        if (!$this->request->isPost()) {
            return;
        }
        $goods_id = input('id/d', 0);
        $goods = GoodsModel::get(['id' => $goods_id, 'user_id' => $this->user->id]);
        if (!$goods) {
            $this->error('不存在该商品！');
        }
        if ($goods->is_freeze == 1) {
            $this->result('', 1, '该商品已被冻结，如果要上架，请修改相关商品信息再上架', 'json');
        }
        if ($goods->duijie_id != null) {
        	$res = GoodsModel::get(['id' => $goods->duijie_id]);
            if(!$res){
            	$this->result('', 1, '该商品上级商户已删除，请及时下架并删除，此处修改不生效', 'json');
            }
            if($res->is_freeze == 1){
            	$this->result('', 1, '该上级商品已被冻结，如果要上架，请修改相关商品信息再上架', 'json');
            }
        }
        $status = input('status/d', 0);
        $status = $status ? 1 : 0;
        $statusStr = $status == 1 ? '上架' : '下架';
        $goods->status = $status;
        $res = $goods->save();
        if ($res !== false) {
            MerchantLogService::write('修改商品状态', '将ID为' . $goods_id . '的商品' . $statusStr);
            $xj_goods = GoodsModel::where('duijie_id',$goods->id)->select();
            //同时修改下级的对接商品上下架状态
            if(!empty($xj_goods)){
            	$datas[] = null;
            	foreach($xj_goods as $k=>$val){ 
            		$datas[] = ['id'=>$val['id'],'status'=>$status];
				}
				$GoodsModel = new GoodsModel();
				$GoodsModel->saveAll($datas,true);
            }
            return J(0, 'success');
        } else {
            return J(1, 'error');
        }
    }

    // 删除商品
    public function del()
    {
        $goods_id = input('id/d', 0);
        $goods = GoodsModel::get(['id' => $goods_id, 'user_id' => $this->user->id]);
        if (!$goods) {
            return J(1, '不存在该商品！');
        }
        //对接商品判断 -- 删除方式不一样 对接直接硬删  非对接软删
        if(!empty($goods->duijie_id)){
        	if (GoodsModel::where(['id' => $goods_id])->delete()) {
	            MerchantLogService::write('删除对接的商品', '删除ID为' . $goods_id . '的商品');
	            return J(0, '删除成功！');
	        } else {
	            return J(1, '删除失败！');
	        }
        }else{
        	if ($goods->cards_stock_count > 0) {
	            return J(1, '该商品下存在虚拟卡，暂不能删除！');
	        }
	        if ($goods->duijie_id != null) {
	            return J(1, '该商品为对接商品，无法删除！');
	        }
	        $res = $goods->delete();
	        if ($res !== false) {
	            MerchantLogService::write('删除商品', '删除ID为' . $goods_id . '的商品');
	            //删除该商品则清理下级对接用户的该商品id
	            //清空下级对接 软删除
	            GoodsModel::where('duijie_id',$goods_id)->delete();
	            return J(0, '删除成功！');
	        } else {
	            return J(1, '删除失败！');
	        }
        }
    }

    //批量删除
    public function batch_del()
    {
        $good_ids = input('');
        $good_ids = isset($good_ids['ids']) ? $good_ids['ids'] : [];
        if (empty($good_ids)) {
            return J(1, '删除失败！');
        }
        $goods = GoodsModel::all(['id' => ['in', $good_ids], 'user_id' => $this->user->id]);
        if (!$goods) {
            return J(1, '不存在该卡！');
        }
        Db::startTrans();
        try {
            foreach ($goods as $key => $good) {
            	//判断对接  对接直接硬删 非对接软删
            	if(!empty($good->duijie_id)){
		        	if (GoodsModel::where(['id' => $good->id])->delete()) {
			            MerchantLogService::write('删除对接的商品', '删除ID为' . $good->id . '的商品');
			        } else {
			            throw new \Exception('批量删除失败，ID:' . $good->id);
			        }
            	}else{
            		$res = $good->delete();
	                if ($res !== false) {
	                    MerchantLogService::write('成功删除卡密', '成功删除卡密，ID:' . $good->id);
	                    GoodsModel::where('duijie_id',$good->id)->delete();
	                } else {
	                    throw new \Exception('批量删除失败，ID:' . $good->id);
	                }
            	}
            }
            Db::commit();
            return J(0, '删除成功！');
        } catch (\Exception $e) {
            Db::rollback();
            return J(1, $e->getMessage());
        }
    }

    /**
     * 商品回收站
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function trash()
    {
        $this->setTitle('商品回收站');
        ////////////////// 查询条件 //////////////////
        $query = [
            'cate_id' => input('cate_id/s', ''),
            'name' => input('name/s', ''),
        ];
        $where = $this->genereate_where($query);

        $goodsList = GoodsModel::onlyTrashed()->where($where)->order('sort desc,id desc')->paginate(30, false, [
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

    /**
     * 恢复商品
     */
    public function restore()
    {
        $goods_id = input('id/d', 0);
        $goods = GoodsModel::onlyTrashed()->where(['id' => $goods_id, 'user_id' => $this->user->id])->find();
        if (!$goods) {
            return J(1, '不存在该商品！');
        }
        $res = $goods->restore();
        if ($res !== false) {
            MerchantLogService::write('恢复商品', '恢复ID为' . $goods_id . '的商品');
            return J(0, '恢复成功！');
        } else {
            return J(1, '恢复失败！');
        }
    }

    /**
     * 恢复商品
     */
    public function batch_restore()
    {
        $good_ids = input('');
        $good_ids = isset($good_ids['ids']) ? $good_ids['ids'] : [];
        if (empty($good_ids)) {
            return J(1, '恢复失败！');
        }
        $goods = GoodsModel::onlyTrashed()->where(['id' => ['in', $good_ids], 'user_id' => $this->user->id])->select();
        if (!$goods) {
            return J(1, '不存在该卡！');
        }
        Db::startTrans();
        try {
            foreach ($good_ids as $id) {
                $res = GoodsModel::update(['delete_at' => null], ['id' => $id, 'user_id' => $this->user->id], 'delete_at');
                if ($res !== false) {
                    MerchantLogService::write('恢复商品', '恢复ID为' . $id . '的商品');
                } else {
                    throw new \Exception('批量恢复失败，ID:' . $id);
                }
            }
            Db::commit();
            return J(0, '恢复成功！');
        } catch (\Exception $e) {
            Db::rollback();
            return J(1, $e->getMessage());
        }
    }

    // 清空商品下所有未售的虚拟卡
    public function emptiedCards()
    {
        $goods_id = input('id/d', 0);
        $res = CardModel::update([
            'delete_at' => $_SERVER['REQUEST_TIME'],
        ], [
            'goods_id' => $goods_id,
            'user_id' => $this->user->id,
            'status' => 1,
        ]);
        if ($res !== false) {
            MerchantLogService::write('清空商品未售虚拟卡', '清空ID为' . $goods_id . '的商品未售的虚拟卡');
            return J(0, '清空成功！');
        } else {
            return J(1, '清空失败！');
        }
    }

    // 导出商品库存卡密
    public function dumpCards()
    {
        $goods_id = input('goods_id/d', 0);
        $status = input('status/d', 1);
        if ($status == 1) {
            $statusStr = '未售出';
        } elseif ($status == 2) {
            $statusStr = '已售出';
        } else {
            $this->error('未知导出状态！');
        }
        $goods = GoodsModel::get(['id' => $goods_id, 'user_id' => $this->user->id]);
        if (!$goods) {
            $this->error('不存在该商品！');
        }
        if(!empty($goods->duijie_id)){
        	$this->error('对接商品无法导出卡密！');
        }
        $number = input('number/d', 0);
        if ($number) {
            $cards = CardModel::limit($number)->where([
                'goods_id' => $goods_id,
                'user_id' => $this->user->id,
                'status' => $status,
                'delete_at' => null,
            ])->select();
        } else {
            $cards = CardModel::all([
                'goods_id' => $goods_id,
                'user_id' => $this->user->id,
                'status' => $status,
                'delete_at' => null,
            ]);
        }
        $fileType = input('file_type/d', 0);
        $title = ['序号', '卡号', '卡密', '状态', '添加时间'];
        $data = [];
        try {
            Db::startTrans();
            $del = input('del/d', 0);

            foreach ($cards as $k => $card) {
                $data[] = [
                    $k + 1,
                    $card->number,
                    $card->secret,
                    $statusStr,
                    date('Y-m-d H:i:s', $card->create_at),
                ];
                if ($del) {
                    $card->delete();
                }
            }
            $filename = "{$goods->name}的虚拟卡_" . date('Ymd');

            MerchantLogService::write('导出商品库存卡密', '导出ID为' . $goods_id . '的商品库存' . $statusStr . '卡密');

            Db::commit();
            if ($fileType == 0) {
                // csv 格式
                generate_excel($title, $data, $filename, $goods->name);
            } else {
                // text 文本形式
                generate_txt($title, $data, $filename, $goods->name);
            }
        } catch (Exception $e) {
            Db::rollback();
            $this->error('导出失败');
        }
    }

    /**
     * 生成查询条件
     */
    protected function genereate_where($params)
    {
        $where = [];
        $where['user_id'] = $this->user->id;
        $where['duijie_id'] = null;
        $action = $this->request->action();
        switch ($action) {
            case 'index':
            case 'trash':
                if ($params['cate_id'] !== '') {
                    $where['cate_id'] = ['=', $params['cate_id']];
                }
                if ($params['name'] !== '') {
                    $where['name'] = ['like', '%' . $params['name'] . '%'];
                }
                break;
        }
        return $where;
    }

    // 添加
    public function add()
    {
        if (!$this->request->isPost()) {
            $this->setTitle('添加商品');
            // 商品分类
            $categorys = CategoryModel::where(['user_id' => $this->user->id, 'status' => 1])->order('sort desc,id desc')->select();
            $this->assign('categorys', $categorys);
            return $this->fetch('edit');
        }

        if (input('price/f', 0) < input('cost_price/f', 0)) {
            $this->error('商品价格不能比进价低');
        }

        $data = [
            'user_id' => $this->user->id,
            'cate_id' => input('cate_id/d', 0),
            'theme' => input('theme/s', 'default'),
            'sort' => input('sort/d', 0),
            'name' => input('name/s', ''),
            'price' => input('price/f', 0),
            'cost_price' => input('cost_price/f', 0),
            'wholesale_discount' => input('wholesale_discount/d', 0),
            'wholesale_discount_list' => input('wholesale_discount_list/a', []),
            'limit_quantity' => input('limit_quantity/d', 1),
            'inventory_notify' => input('inventory_notify/d', 0),
            'inventory_notify_type' => input('inventory_notify_type/d', 1),
            'coupon_type' => input('coupon_type/d', 0),
            'sold_notify' => input('sold_notify/d', 0),
            'take_card_type' => input('take_card_type/d', 0),
            'visit_type' => input('visit_type/d', 0),
            'visit_password' => input('visit_password/s', ''),
            'is_duijie' => input('is_duijie/d', 0),
            'duijie_smilepic' => input('duijie_smilepic/s', ''),
            'duijie_price' => input('duijie_price/s', 0),
            'contact_limit' => input('contact_limit/s', ''),
            'content' => input('content/s', ''),
            'remark' => input('remark/s', ''),
            'sms_payer' => input('sms_payer/d', 0),
            'status' => 1,
            'create_at' => $_SERVER['REQUEST_TIME'],
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
        $category = CategoryModel::get(['id' => $data['cate_id'], 'user_id' => $this->user->id]);
        if (!$category) {
            $this->error('不存在该分类！');
        }
        //检查商品价格区间
        if (sysconf('goods_min_price') > 0 && $data['price'] < sysconf('goods_min_price')) {
            $this->error('商品价格不能少于' . sysconf('goods_min_price') . '元');
        }

        if (sysconf('goods_max_price') > 0 && sysconf('goods_max_price') < $data['price']) {
            $this->error('商品价格不能超过' . sysconf('goods_max_price') . '元');
        }

        $res = $this->validate($data, 'Goods');
        if ($res !== true) {
            $this->error($res);
        }
        $res = GoodsModel::create($data);
        if ($res !== false) {
            //创建成功，马上创建短链接
            GoodsModel::makeLink($data['user_id'], $res->id);
            MerchantLogService::write('添加商品成功', '添加商品成功，商品ID:' . $res->id . ',名称:' . $res->name . ',价格:' . $res->price . ',成本价:' . $res->cost_price);
            $this->redirect('/merchant/goods/index');
        } else {
            $this->error('添加失败！');
        }
    }

    // 编辑
    public function edit()
    {
        $goods_id = input('id/d', 0);
        $goods = GoodsModel::get(['id' => $goods_id, 'user_id' => $this->user->id]);
        if (!$goods) {
            $this->error('不存在该商品！');
        }
        if(!empty($goods->duijie_id)){
        	$this->error('对接商品请前往我的对接修改！');
        }
        if (!$this->request->isPost()) {
            $this->setTitle('添加商品');
            // 商品分类
            $categorys = CategoryModel::where(['user_id' => $this->user->id, 'status' => 1])->order('sort desc,id desc')->select();
            $this->assign('categorys', $categorys);
            $this->assign('goods', $goods);
            return $this->fetch('edit');
        }

        if (input('price/f', 0) < input('cost_price/f', 0)) {
            $this->error('商品价格不能比进价低');
        }

        $data = [
            'user_id' => $this->user->id,
            'cate_id' => input('cate_id/d', 0),
            'theme' => input('theme/s', 'default'),
            'sort' => input('sort/d', 0),
            'name' => input('name/s', ''),
            'price' => input('price/f', 0),
            'cost_price' => input('cost_price/f', 0),
            'wholesale_discount' => input('wholesale_discount/d', 0),
            'wholesale_discount_list' => input('wholesale_discount_list/a', []),
            'limit_quantity' => input('limit_quantity/d', 1),
            'inventory_notify' => input('inventory_notify/d', 0),
            'inventory_notify_type' => input('inventory_notify_type/d', 1),
            'coupon_type' => input('coupon_type/d', 0),
            'sold_notify' => input('sold_notify/d', 0),
            'take_card_type' => input('take_card_type/d', 0),
            'visit_type' => input('visit_type/d', 0),
            'visit_password' => input('visit_password/s', ''),
            'is_duijie' => input('is_duijie/d', 0),
            'duijie_smilepic' => input('duijie_smilepic/s', ''),
            'duijie_price' => input('duijie_price/s', 0),
            'contact_limit' => input('contact_limit/s', ''),
            'content' => input('content/s', ''),
            'remark' => input('remark/s', ''),
            'sms_payer' => input('sms_payer/d', 0),
        ];
        //对接信息修改判断
        //判断是否是禁止对接
        if(input('is_duijie/d', 0) === 0){
            //清空下级对接
            GoodsModel::where('duijie_id',$goods_id)->delete();
        }
        if(input('limit_quantity/d', 0) !== $goods->limit_quantity && input('is_duijie/d', 0) === 1){
           //商品最低起购数量
            $xj_goods = GoodsModel::where('duijie_id',$goods_id)->select();
            if(!empty($xj_goods)){
            	$datas[] = null;
            	foreach($xj_goods as $k=>$val){ 
            		$datas[] = ['id'=>$val['id'],'limit_quantity'=>input('limit_quantity/d', 0)];
				}
				$GoodsModel = new GoodsModel();
				$GoodsModel->saveAll($datas,true);
            }
        }
        if(input('duijie_price/d', 0) !== $goods->duijie_price && input('is_duijie/d', 0) === 1){
           //商品对接价格
            $xj_goods = GoodsModel::where('duijie_id',$goods_id)->select();
            if(!empty($xj_goods)){
            	$datas[] = null;
            	foreach($xj_goods as $k=>$val){ 
            		$datas[] = ['id'=>$val['id'],'cost_price'=>input('cost_price/f', 0)];
				}
				$GoodsModel = new GoodsModel();
				$GoodsModel->saveAll($datas,true);
            }
        }
        if(input('visit_type/d', 0) !== $goods->visit_type || input('visit_password/d', 0) !== $goods->visit_password && input('is_duijie/d', 0) === 1){
           //商品购买密码
            $xj_goods = GoodsModel::where('duijie_id',$goods_id)->select();
            if(!empty($xj_goods)){
            	$datas[] = null;
            	foreach($xj_goods as $k=>$val){ 
            		$datas[] = ['id'=>$val['id'],'visit_type'=>input('visit_type/d', 0),'visit_password'=>input('visit_password/d', 0)];
				}
				$GoodsModel = new GoodsModel();
				$GoodsModel->saveAll($datas,true);
            }
        }
        if ($goods->is_freeze == 1) {
            $data['is_freeze'] = 0;
        }
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
        $category = CategoryModel::get(['id' => $data['cate_id'], 'user_id' => $this->user->id]);
        if (!$category) {
            $this->error('不存在该分类！');
        }
        $res = $this->validate($data, 'Goods');
        if ($res !== true) {
            $this->error($res);
        }
        $res = GoodsModel::update($data, ['id' => $goods->id]);
        if ($res !== false) {
            MerchantLogService::write('编辑商品成功', '编辑商品成功，商品ID:' . $goods_id);
            $this->redirect('/merchant/goods/index');
        } else {
            $this->error('保存失败！');
        }
    }

    // 商品购买链接
    public function link()
    {
        $goods_id = input('id/d', 0);
        $goods = GoodsModel::get(['id' => $goods_id, 'user_id' => $this->user->id]);
        if (!$goods) {
            return J(1, '不存在该商品！');
        }
        $this->setTitle('购买链接');
        $this->assign('goods', $goods);
        $this->assign('short_link', $goods->shortLink);
        return $this->fetch();
    }
    
    // 对接商品列表页面渲染
    public function duijie_index()
    {
        $this->setTitle('对接商品列表');
        $query = [
            'cate_id' => input('cate_id/s', ''),
            'name' => input('name/s', ''),
            'user_id' => $this->user->id,
        ];
        //拼装查询条件
        if(!empty(input('cate_id/s', ''))){
        	$where['cate_id'] =  ['=', input('cate_id/s', '')];
        }
        if(!empty(input('name/s', ''))){
        	$where['name'] =  ['like', '%' . input('name/s', '') . '%'];
        }
        
        //输出自己已有的对接
        $where['user_id'] =  ['=', $this->user->id];
        $where['duijie_id'] = ['neq',''];
        $goodsList = GoodsModel::with('sjuser')->where($where)->order('sort desc,id desc')->paginate(30, false, [
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
    
    //对接商品编辑
    public function duijie_edit()
    {
    	//查询本商品信息
    	$goods = GoodsModel::where('id',input('shop_id',0))->find();
    	if(!$goods){
    		$this->error('商品不存在');
    	}
    	if($goods->is_freeze === 1){
    		$this->error('冻结中的商品无法编辑');
    	}
    	$sjgoods = GoodsModel::where('id',$goods->duijie_id)->find();
    	if(!$sjgoods){
    		$this->error('上级商品不存在,请尽快删除该商品');
    	}
        if($this->request->isPost()){
            if(empty($this->request->param('shop_id')) || empty($this->request->param('name')) || empty($this->request->param('price')) || empty($this->request->param('cate_id'))){
                $this->error('参数缺失');
            }
            //把上级商品信息插入我的商品信息中
            $data = [
                'user_id' => $this->user->id,
                'cate_id' => input('cate_id/d', 0),
                'theme' => input('theme/s', 'default'),
                'sort' => input('sort/d', 0),
                'name' => input('name/s', ''),
                'price' => input('price/s', 0),
            	'wholesale_discount' => input('wholesale_discount/d', 0),
            	'wholesale_discount_list' => input('wholesale_discount_list/a', []),
                'limit_quantity' => input('limit_quantity/d', 1),
                'inventory_notify' => input('inventory_notify/d', 0),
                'inventory_notify_type' => input('inventory_notify_type/d', 1),
                'coupon_type' =>  0,
                'take_card_type' => input('take_card_type/d', 0),
                'content' => input('content/s', ''),
                'remark' => input('remark/s', ''),
                'sms_payer' => input('sms_payer/d', 0),
            ];
            //分类判断
            if(empty(input('cate_id/d', 0))){
            	$this->error('请选择一个商品分类');
            }
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
            if(input('price/s', 0) < $sjgoods['duijie_smilepic']){
                $this->error('加价价格必须高于'.$sjgoods['duijie_smilepic']);
            }
            if(input('limit_quantity/d', 1) <$sjgoods['limit_quantity']){
            	$this->error('起购数量必须高于或等于上级起购数量'.$sjgoods['limit_quantity']);
            }
            $res = $this->validate($data, 'Goods');
            if ($res !== true) {
                $this->error($res);
            }
            //更新商品
            $res = GoodsModel::update($data, ['id' => $goods->id]);
            if ($res !== false) {
                MerchantLogService::write('修改代理商品成功', '添加代理商品成功，本商品ID:' . $goods->id . ',名称:' . $goods->name . ',加价价格:' . $goods->price . ',成本价:' . $sjgoods['duijie_price']);
                $this->success('修改成功');
            } else {
                $this->error('修改失败！');
            }
        }else{
            if(empty($this->request->param('shop_id'))){
                $this->error('参数缺失');
            }
            $categorys = CategoryModel::where(['user_id' => $this->user->id, 'status' => 1])->order('sort desc,id desc')->select();
            $this->assign('categorys', $categorys);
            $this->assign('shop_id',$this->request->param('shop_id'));
            $this->assign('sjgoods',$sjgoods);
            $this->assign('goods',$goods);
            return view();
        }
    }
    
    //对接商品查询上级QQ
    public function qq_info()
    {
    	if($this->request->ispost()){
    		$g = GoodsModel::where('id',input('id',''))->find();
    		if(!$g){
    			return "商品不存在";
    		}
    		$g = GoodsModel::where('id',$g->duijie_id)->find();
    		if(!$g){
    			return "上级商品不存在";
    		}
    		return Db::table('user')->where('id',$g->user_id)->find()['qq'];
    	}
    	return "方式错误";
    }
    
    //全网对接资源页面
    public function agentlist()
    {
    	$this->setTitle('全网资源对接');
        ////////////////// 查询条件 //////////////////
        //拼装查询条件
        $query = [
            'duijie_key' => input('duijie_key/s', ''),
            'name' => input('name/s', ''),
        ];
        if(!empty(input('name/s', ''))){
        	$where['name'] =  ['like', '%' . input('name/s', '') . '%'];
        }
        //上架状态
        $where['status'] = ['=','1'];
        //可被对接
        $where['is_duijie'] = ['=','1'];
        //非冻结状态
        $where['is_freeze'] = ['=','0'];
        //不输出自己的商品
        $where['user_id'] = ['neq',$this->user->id];
        //秘钥搜索
        if(!empty(input('duijie_key/s', ''))){
        	//对接秘钥转上级id
        	$user = Db::table('user')->where('duijie_key',trim(input('duijie_key/s', '')))->find();
        	if($user){
        		$where['user_id'] = ['=',$user['id']];
        	}else{
        		$where['user_id'] = ['=',0];
        	}
        }
        $goodsList = GoodsModel::where($where)->where("duijie_id is null or duijie_id=''")->order('sort desc,id desc')->paginate(30, false, [
            'query' => $query,
        ]);
        //将本人已对接的商品排除
        $myduijiegoodslist = GoodsModel::where('user_id',$this->user->id)->where("duijie_id is not null or duijie_id != ''")->select();
        foreach ($goodsList as $key => $val){
        	$goodsList[$key]['duijie_old'] = 0;
        	foreach ($myduijiegoodslist as $key2 => $val2){
        		if($val2->duijie_id === $val->id){
        			$goodsList[$key]['duijie_old'] = 1;
        		}
        	}
        }
        // 分页
        $page = $goodsList->render();
        $this->assign('page', $page);
        $this->assign('goodsList', $goodsList);
    	return view();
    }
    
    public function start_duijie_shop()
    {
    	//验证上级商品信息
    	$info = GoodsModel::where('id',input('shop_id/s', 0))->find();
    	if(!$info){
    		$this->error('上级商品不存在');
    	}
    	if($info->cards_stock_count <= 0){
    		$this->error('商品库存不足，无法对接');
    	}
    	if($info->status === 0){
    		$this->error('下架商品不允许被对接');
    	}
    	if($info->is_duijie === 0){
    		$this->error('上级商品不允许被对接');
    	}
    	if($info->is_freeze === 1){
    		$this->error('上级商品冻结中，无法对接');
    	}
    	if(!empty($info->duijie_id)){
    		$this->error('上级商品不允许被对接');
    	}
    	if(GoodsModel::where(['duijie_id' => input('shop_id/s', 0), 'user_id' => $this->user->id])->find()){
    		$this->error('该商品已对接，不能重复对接');
    	}
    	if($info->user_id === $this->user->id){
    		$this->error('无法对接自己的商品');
    	}
    	//判断上级用户状态
    	$uinfo = Db::table('user')->where('id',$info->user_id)->find();
    	if(!$uinfo){
    		$this->error('上级用户被冻结');
    	}
    	if($uinfo['is_freeze'] === 1){
    		$this->error('上级用户被冻结');
    	}
    	if($uinfo['status'] === 0){
    		$this->error('上级用户被冻结');
    	}
    	//商品和用户判断完成
    	if($this->request->ispost()){
    		if(empty($this->request->param('shop_id')) || empty($this->request->param('name')) || empty($this->request->param('price'))){
                $this->error('参数缺失');
            }
            //把上级商品信息插入我的商品信息中
            $data = [
                'user_id' => $this->user->id,
                'cate_id' => input('cate_id/d', 0),
                'theme' => input('theme/s', 'default'),
                'sort' => input('sort/d', 0),
                'name' => input('name/s', ''),
                'price' => input('price/s', 0),
                'cost_price' => $info['duijie_price'],
            	'wholesale_discount' => input('wholesale_discount/d', 0),
            	'wholesale_discount_list' => input('wholesale_discount_list/a', []),
                'limit_quantity' => input('limit_quantity/d', 1),
                'inventory_notify' => input('inventory_notify/d', 0),
                'inventory_notify_type' => input('inventory_notify_type/d', 1),
                'coupon_type' =>  0,
                'sold_notify' =>  input('sold_notify/d', 0),
                'take_card_type' => input('take_card_type/d', 0),
                'visit_type' => input('visit_type/d', 0),
            	'visit_password' => input('visit_password/s', ''),
                'is_duijie' => 0,
                'duijie_smilepic' => 0,
                'contact_limit' => $info->contact_limit,
                'content' => input('content/s', ''),
                'remark' => input('remark/s', ''),
                'sms_payer' => input('sms_payer/d', 0),
                'status' => 1,
                'create_at' => $_SERVER['REQUEST_TIME'],
                'duijie_id' => $info->id,
            ];
            //分类判断
            if(empty(input('cate_id/d', 0))){
            	$this->error('请选择一个商品分类');
            }
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
            if(input('price/s', 0) < $info->duijie_smilepic){
                $this->error('加价价格必须高于'.$info->duijie_smilepic);
            }
            $res = $this->validate($data, 'Goods');
            if ($res !== true) {
                $this->error($res);
            }
            $res = GoodsModel::create($data);
            if ($res !== false) {
                //创建成功，马上创建短链接
                GoodsModel::makeLink($data['user_id'], $res->id);
                MerchantLogService::write('添加代理商品成功', '添加代理商品成功，本商品ID:' . $res->id . ',名称:' . $res->name . ',加价价格:' . $res->price . ',成本价:' . $info->price);
                $this->success('代理商品成功','agentlist');
            } else {
                $this->error('添加失败！');
            }
    	}else{
    		$categorys = CategoryModel::where(['user_id' => $this->user->id, 'status' => 1])->order('sort desc,id desc')->select();
            $this->assign('categorys', $categorys);
            $this->assign('shop_id',$this->request->param('shop_id'));
            $this->assign('sjgoods',$info);
            return view();
    	}
    }
}
