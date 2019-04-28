<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>内测详情页</title>
</head>
<body>
	<table border="1px">
		<tr>
			<td>商品名称</td>
			<td>{{$goodsInfo->goods_name}}</td>
		</tr>
		<tr>
			<td>商品图片</td>
			<td><img src="http://uploads.weishop.com/{{$goodsInfo->goods_img}}" height="50%" ></td>
		</tr>
		<tr>
			<td>商品数量</td>
			<td>{{$goodsInfo->goods_number}}</td>
		</tr>
		<tr>
			<td>商品描述</td>
			<td>{{$goodsInfo->keywords}}</td>
		</tr>
	</table>
</body>
</html>