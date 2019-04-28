<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>内测列表页</title>
	<link rel="stylesheet" href="{{asset('css/page.css')}}" type="text/css">
	<script src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
<form action="" method='' class="">
	 <input type="text"  name="goods_name" value="{{$goods_name}}" placeholder="请输入姓名"/>
     <button>搜索</button>   
</form>
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
	<form action="">
		<table border="1px">
			<tr>
				<td>商品ID</td>
				<td>商品名称</td>
				<td>商品图片</td>
				<td>商品数量</td>
				<td>商品描述</td>
				<td>操作</td>
			</tr>
			@foreach($data as $k=>$v)
			<tr>
				<td>{{$v->goods_id}}</td>
				<td>{{$v->goods_name}}</td>
				<td width="10%" ><a href="/neice/info/{{$v->goods_id}}"><img src="http://uploads.weishop.com/{{$v->goods_img}}" height="50%" ></a></td>
				<td>{{$v->goods_number}}</td>
				<td>{{$v->keywords}}</td>
				<td>
				    <input type="hidden" value="{{$v->goods_id}}" id="goods_id" name="goods_id" >
					<a href="javascript:;"  id="del">删除</a>
					<a href="/neice/edit/{{$v->goods_id}}">修改 </a>
				</td>
			</tr>

			@endforeach
			 <tr> <td colspan="6"  >{{$data->appends($query)->links()}}</td></tr>
		</table>
	</form>
</body>
</html>
<script>
	$(document).on('click','#del',function(){
        // alert(11111);
        var goods_id = $(this).prev('input').val();
        // alert(goods_id);
        var _token = $('#_token').val();
        // alert(_token);
        $.ajax({
        method:'post',
        url:'/neice/delete',
        data:{goods_id:goods_id,_token:_token},
        dataType:'json'
      }).done(function(res){
      	if(res==1){
            alert('删除成功');
            history.go()
          }else{
            alert("删除失败");
      }

    });
	});
</script>