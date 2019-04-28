
    @foreach($goodsInfo as $k=>$v)
        <dl >
            <dt><a href="proinfo{{$v->goods_id}}"><img src="/uploads/{{$v->goods_img}}/" width="100" height="100" /></a></dt>
            <dd>
                <h3><a href="proinfo">{{$v->goods_name}}</a></h3>
                <div class="prolist-price"><strong>¥{{$v->shop_price}}</strong> <span>¥{{$v->market_price}}</span></div>
            </dd>
            <div class="clearfix"></div>
        </dl>
    @endforeach

