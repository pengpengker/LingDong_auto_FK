<?php


namespace app\merchant\controller;


use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsCategory as CategoryModel;

class GoodsDuijie extends Base
{
    // 对接列表
    public function index()
    {
        $this->setTitle('对接中心');
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
    
    // 商品对接信息渲染
    public function myduijie()
    {
        $this->setTitle('我的对接');
        return $this->fetch();
    }

    /**
     * 生成查询条件
     */
    protected function genereate_where($params)
    {
        $where = [];
        $where['user_id'] = $this->user->id;
        $action = $this->request->action();
        switch ($action) {
            case 'index':
                $where['is_duijie'] = '1';
            case 'trash':
                if ($params['cate_id'] !== '') {
                    $where['cate_id'] = ['=', $params['cate_id']];
                }
                if ($params['name'] !== '') {
                    $where['name'] = ['like', '%' . $params['name'] . '%'];
                }
                $where['is_duijie'] = '1';
                break;
        }
        return $where;
    }
}