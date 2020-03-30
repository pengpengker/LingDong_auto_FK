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
                ];
            }
            $where = $this->genereate_where($query);
            $goodsList = GoodsModel::where($where)->order('sort desc,id desc')->paginate(30, false, [
                'query' => $query,
            ]);
            // 分页
            $page = $goodsList->render();
            $this->assign('page', $page);
            $this->assign('goodsList', $goodsList);
            // 商品分类
            $categorys = CategoryModel::where(['user_id' => $sj_uid])->order('sort desc,id desc')->select();
            $this->assign('categorys', $categorys);
            return $this->fetch();
        }
        $this->user->sj_duijie_key = $this->request->param('sj_duijie_key');
        if($this->request->param('sj_duijie_key')){
            if(!\app\common\model\User::where('duijie_key',$this->request->param('sj_duijie_key'))->find()){
                $this->error('没有该对接码');
            }
        }
        if($this->user->save()){
            $this->success('对接控制成功');
        }else{
            $this->error('对接控制失败');
        }
    }

    /**
     * 生成查询条件
     */
    protected function genereate_where($params)
    {
        $where = [];
        if(!$params['user_id']){
            $where['user_id'] = $this->user->id;
        }else{
            $where['user_id'] = $params['user_id'];
        }
        $action = $this->request->action();
        switch ($action) {
            case 'index':
                $where['is_duijie'] = '1';
            case 'myduijie':
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