<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:50:"./application/admin/view2/article\articleList.html";i:1488176436;s:44:"./application/admin/view2/public\layout.html";i:1488176436;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="__PUBLIC__/static/css/main.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/css/page.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="__PUBLIC__/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__PUBLIC__/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>

<script type="text/javascript" src="__PUBLIC__/static/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="__PUBLIC__/static/js/admin.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/flexigrid.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/static/js/jquery.mousewheel.js"></script>
<script src="__PUBLIC__/js/myFormValidate.js"></script>
<script src="__PUBLIC__/js/myAjax2.js"></script>
<script src="__PUBLIC__/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
//   						layer.closeAll();
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }
    
    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }   
    
    function get_help(obj){
        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'), 
        });
    }
    
    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    					layer.closeAll();
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);	
    }
</script>  

</head>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>文章管理</h3>
        <h5>网站系统文章索引与管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
    <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span title="收起提示" id="explanationZoom" style="display: block;"></span>
    </div>
    <ul>
      <li>文章管理, 由总平台设置管理.</li>
    </ul>
  </div>
  <div class="flexigrid">
    <div class="mDiv">
      <div class="ftitle">
        <h3>文章列表</h3>
        <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
      </div>
      <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
	  <form class="navbar-form form-inline" action="<?php echo U('Admin/Article/articleList'); ?>" method="post">      
      <div class="sDiv">
        <div class="sDiv2">
          <select  name="cat_id" class="select">
            <option value="">选择文章类别</option>
            <?php if(is_array($cats) || $cats instanceof \think\Collection): if( count($cats)==0 ) : echo "" ;else: foreach($cats as $key=>$vo): ?>
            <option value="<?php echo $vo['cat_id']; ?>" <?php if($vo[cat_id] == $cat_id): ?>selected<?php endif; ?>><?php echo $vo['cat_name']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>            
          </select>
          <input type="text" size="30" name="keywords" class="qsbox" placeholder="搜索相关数据...">
          <input type="submit" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="sign" axis="col0">
                <div style="width: 24px;"><i class="ico-check"></i></div>
              </th>
              <th align="left" abbr="article_title" axis="col3" class="">
                <div style="text-align: left; width: 240px;" class="">标题</div>
              </th>
              <th align="left" abbr="ac_id" axis="col4" class="">
                <div style="text-align: left; width: 150px;" class="">文章分类</div>
              </th>
              <th align="center" abbr="article_show" axis="col5" class="">
                <div style="text-align: center; width: 80px;" class="">显示</div>
              </th>
              <th align="center" abbr="article_time" axis="col6" class="">
                <div style="text-align: center; width: 160px;" class="">发布时间</div>
              </th>
              <th align="center" axis="col1" class="handle">
                <div style="text-align: center; width: 150px;">操作</div>
              </th>
              <th style="width:100%" axis="col7">
                <div></div>
              </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div class="tDiv">
      <div class="tDiv2">
        <div class="fbutton"> <a href="<?php echo U('Admin/Article/article'); ?>">
          <div class="add" title="新增文章">
            <span><i class="fa fa-plus"></i>新增文章</span>
          </div>
          </a> 
          </div>
      </div>
      <div style="clear:both"></div>
    </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
        <table>
          <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
              <tr>
                <td class="sign">
                  <div style="width: 24px;"><i class="ico-check"></i></div>
                </td>
                <td align="left" class="">
                  <div style="text-align: left; width: 240px;"><?php echo getSubstr($vo['title'],0,33); ?></div>
                </td>
                <td align="left" class="">
                  <div style="text-align: left; width: 150px;"><?php echo $vo['category']; ?></div>
                </td>
                <td align="center" class="">
                  <div style="text-align: center; width: 80px;">
                    <?php if($vo[is_open] == 1): ?>
                      <span class="yes" onClick="changeTableVal('Article','article_id','<?php echo $vo['article_id']; ?>','is_open',this)" ><i class="fa fa-check-circle"></i>是</span>
                      <?php else: ?>
                      <span class="no" onClick="changeTableVal('Article','article_id','<?php echo $vo['article_id']; ?>','is_open',this)" ><i class="fa fa-ban"></i>否</span>
                    <?php endif; ?>
                  </div>
                </td>
                <td align="center" class="">
                  <div style="text-align: center; width: 160px;"><?php echo $vo['add_time']; ?></div>
                </td>
                <td align="center" class="handle">
                  <div style="text-align: center; width: 170px; max-width:170px;"> <a class="btn blue"  href="<?php echo U('Home/Article/detail',array('article_id'=>$vo['article_id'])); ?>"><i class="fa fa-search"></i>查看</a>
                    <?php if(!in_array(($vo['article_id']), is_array($article_able_id)?$article_able_id:explode(',',$article_able_id))): ?> <a class="btn red"  href="javascript:void(0)" data-url="<?php echo U('Article/aticleHandle'); ?>" data-id="<?php echo $vo['article_id']; ?>" onClick="delfun(this)"><i class="fa fa-trash-o"></i>删除</a> <?php endif; if(in_array(($vo['article_id']), is_array($article_able_id)?$article_able_id:explode(',',$article_able_id))): ?> <a class="btn red"  href="javascript:alert('该文章不得删除!');"><i class="fa fa-trash-o"></i>删除</a> <?php endif; ?>
                    <a href="<?php echo U('Article/article',array('act'=>'edit','article_id'=>$vo['article_id'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>编辑</a> 
                  </div>
                </td>
                <td align="" class="" style="width: 100%;">
                  <div>&nbsp;</div>
                </td>
              </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
      </div>
      <div class="iDiv" style="display: none;"></div>
    </div>
    <!--分页位置--> 
    <?php echo $pager->show(); ?> </div>
</div>
<script>
    $(document).ready(function(){	
	    // 表格行点击选中切换
	    $('#flexigrid > table>tbody >tr').click(function(){
		    $(this).toggleClass('trSelected');
		});
		
		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
		
	});


    function delfun(obj) {
      // 删除按钮
      layer.confirm('确认删除？', {
        btn: ['确定', '取消'] //按钮
      }, function () {
        $.ajax({
          type: 'post',
          url: $(obj).attr('data-url'),
          data: {act: 'del', article_id: $(obj).attr('data-id')},
          dataType: 'json',
          success: function (data) {
            if (data) {
              $(obj).parent().parent().parent().remove();
              layer.closeAll();
            } else {
              layer.alert('删除失败', {icon: 2});  //alert('删除失败');
            }
          }
        })
      }, function () {
        layer.closeAll();
      });
    }
</script>
</body>
</html>