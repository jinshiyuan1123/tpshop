<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: 当燃   2016-05-10
 */ 
namespace app\mobile\controller;
use think\Db;
use think\Page;

class Activity extends MobileBase {
    public function index(){      
        return $this->fetch();
    }
   /**
    * 商品详情页
    */ 
    public function group(){
        //form表单提交
        C('TOKEN_ON',true);  
        $goodsLogic = new \app\home\logic\GoodsLogic();
        $goods_id = I("get.id/d",66);
        
        $group_buy_info = M('GroupBuy')->where(['goods_id'=>$goods_id,'start_time'=>['<=',time()],'end_time'=>['>=',time()]])->find(); // 找出这个商品
        if(empty($group_buy_info)) 
        {
            //$this->error("此商品没有团购活动",U('Home/Goods/goodsInfo',array('id'=>$goods_id)));
        }
                    
        $goods = M('Goods')->where("goods_id", $goods_id)->find();
        $goods_images_list = M('GoodsImages')->where("goods_id", $goods_id)->select(); // 商品 图册
                
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id", $goods_id)->select(); // 查询商品属性表
                        
        // 商品规格 价钱 库存表 找出 所有 规格项id
        $keys = M('SpecGoodsPrice')->where("goods_id", $goods_id)->getField("GROUP_CONCAT(`key` SEPARATOR '_') ");
        if($keys)
        {
             $specImage =  M('SpecImage')->where(['goods_id'=>$goods_id,'src'=>['<>','']])->getField("spec_image_id,src");// 规格对应的 图片表， 例如颜色
             $keys = str_replace('_',',',$keys);             
             $sql  = "SELECT a.name,a.order,b.* FROM __PREFIX__spec AS a INNER JOIN __PREFIX__spec_item AS b ON a.id = b.spec_id WHERE b.id IN(:keys) ORDER BY a.order";
             $filter_spec2 = DB::query($sql,['keys'=>$keys]);
             foreach($filter_spec2 as $key => $val)
             {                                  
                 $filter_spec[$val['name']][] = array(
                     'item_id'=> $val['id'],
                     'item'=> $val['item'],
                     'src'=>$specImage[$val['id']],
                     );                 
             }            
        }                
        $spec_goods_price  = M('spec_goods_price')->where("goods_id", $goods_id)->getField("key,price,store_count"); // 规格 对应 价格 库存表
        M('Goods')->where("goods_id", $goods_id)->save(array('click_count'=>$goods['click_count']+1 )); // 统计点击数
        $commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计
        $goods_collect_count = M('goods_collect')->where(array("goods_id"=>$goods_id))->count(); //商品收藏数
        $this->assign('group_buy_info',$group_buy_info);
        $this->assign('spec_goods_price', json_encode($spec_goods_price,true)); // 规格 对应 价格 库存表
        $this->assign('commentStatistics',$commentStatistics);
        $this->assign('goods_attribute',$goods_attribute);
        $this->assign('goods_attr_list',$goods_attr_list);
        $this->assign('filter_spec',$filter_spec);
        $this->assign('goods_images_list',$goods_images_list);
        $this->assign('goods',$goods);
        $this->assign('goods_collect_count',$goods_collect_count); //商品收藏人数
        return $this->fetch();
    } 
    
    
    /**
     * 团购活动列表
     */
    public function group_list()
    {
        $istype =I('get.type');
        //以最新新品排序
        if($istype == 'new'){
            $orderby = 'start_time desc';
        }
    	$count =  M('GroupBuy')->where(time()." >= start_time and ".time()." <= end_time ")->count();// 查询满足要求的总记录数
        $pagesize = C('PAGESIZE');  //每页显示数
    	$Page = new Page($count,$pagesize); // 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();  // 分页显示输出
    	$this->assign('page',$show);    // 赋值分页输出
        $list = M('GroupBuy')->where(time()." >= start_time and ".time()." <= end_time ")->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();   // 找出这个商品
        $this->assign('list', $list);
        if(I('is_ajax')) {
            return $this->fetch('ajax_group_list');      //输出分页
        }
        return $this->fetch();
    }

    /**
     * 活动商品列表
     */
    public function discount_list(){
        $prom_id =I('id');    //活动ID
        $where = array(     //条件
            'prom_type'=>3,
            'prom_id'=>$prom_id,
        );
        $pagesize = C('PAGESIZE');  //每页显示数
    	$count =  M('goods')->where($where)->count(); // 查询满足要求的总记录数
    	$Page = new Page($count,$pagesize); //分页类
        $prom_list = M('goods')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select(); //活动对应的商品
    	$this->assign('prom_list', $prom_list);
        if(I('is_ajax')){
            return $this->fetch('ajax_discount_list');
        }
    	return $this->fetch();
    }

    /**
     * 商品活动页面
     * $author lxl
     * $time 2017-1
     */
    public function promote_goods(){
        $now_time = time();
        $where = " start_time <= $now_time and end_time >= $now_time ";
        $count = M('prom_goods')->where($where)->count();  // 查询满足要求的总记录数
        $pagesize = C('PAGESIZE');  //每页显示数
        $Page  = new Page($count,$pagesize); //分页类
        $promote = M('prom_goods')->field('id,name,start_time,end_time,prom_img')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();    //查询活动列表
        $this->assign('promote',$promote);
        if(I('is_ajax')){
            return $this->fetch('ajax_promote_goods');
        }
        return $this->fetch();
    }

    /**
     * 秒杀列表
     * * $author lxl
     * $time 2017-1
     */
    public function seckill_list(){
        //首页index方法里面有下面代码
        $now_time = time();  //当前时间
        if(is_int($now_time/7200)){      //双整点时间，如：10:00, 12:00
            $start_time = $now_time;
        }else{
            $start_time = floor($now_time/7200)*7200; //取得前一个双整点时间
        }
        $end_time = $start_time+7200;   //结束时间
        $count1 = DB::query("select count(g.goods_id) as count from __PREFIX__goods as g inner join __PREFIX__flash_sale as f on g.goods_id = f.goods_id where start_time = $start_time and end_time = $end_time");     //查询符合要求的总记录数
        $pagesize = C('PAGESIZE');  //每页分页数
        $count = $count1[0]['count'];    //统计数量
        $Page = new Page($count ,$pagesize);    //分页类
        $seckill_list=DB::query("select f.goods_id,f.title,f.price,f.order_num,f.goods_num,g.goods_name,g.market_price from __PREFIX__goods as g inner join __PREFIX__flash_sale as f on g.goods_id = f.goods_id where start_time = $start_time and end_time = $end_time limit ".$Page->firstRow.",".$Page->listRows."");     //获取商品
        $this->assign('seckill_list',$seckill_list);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        $this->assign('now_time',$now_time);
        if(I('is_ajax')){
            return $this->fetch('ajax_seckill_list');
        }
        return $this->fetch();
    }

}