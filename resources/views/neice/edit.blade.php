<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>内测修改页</title>
		<script src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
<form action="" enctype="multipart/form-data">
	<table border="1px">
	<input type="hidden" name="goods_id" id="goods_id" value="{{$data->goods_id}}">
	<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
		<tr>
			<td>商品名称</td>
			<td ><input type="text" id="goods_name" value="{{$data->goods_name}}"></td>
		</tr>
		<tr>
			<td>商品图片</td>
			<td>
			  <img src="http://uploads.weishop.com/{{$data->goods_img}}" height="50%" >
			  <input type="file" name="goods_img" id="log" ></td>
              <input type="hidden" name="goods_img" value="{{$data->goods_img}}">
			</td>
		</tr>
		<tr>
			<td>商品数量</td>
			<td>
				<input type="text" value="{{$data->goods_number}}"  id="goods_number">
			</td>
		</tr>
		<tr>
			<td>商品描述</td>
			<td>
				<input type="text" value="{{$data->keywords}}"  id="keywords">
			</td>
		</tr>
		<tr>
			<td>
				<input type="button" value="修改" id="xg">
			</td>
		</tr>
	</table>
</form>
</body>
</html>
<script>
$(document).on('click','#xg',function(){
	var goods_name = $('#goods_name').val();
	// alert(goods_name);
	var goods_number = $('#goods_number').val();
	var keywords = $('#keywords').val();
	var _token = $('#_token').val();
	var goods_id = $('#goods_id').val();
	var log = $('#log').val();
	$.ajax({
		method:'post',
		url:'/neice/update',
		data:{goods_id:goods_id,goods_name:goods_name,keywords:keywords,goods_number:goods_number,_token:_token,goods_img:log},
		dataType:'json',

	}).done(function(res){
      	if(res==1){
            alert('修改成功');
            location.href="/neice/index";
          }else if(res== 2) {
            alert("修改失败");
        }
      });
});
	
</script>