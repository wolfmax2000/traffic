<div class="col-lg-8 col-md-8 col-sm-12 pl-0 pr-0 d-flex">
    <a href="{{ $item->getUrl($params['template_id'], $link, $show_as ?? '') }}" target="_blank" class="item item-sec-row " style="background: rgb({{ $item->getRGBText() }}); color: white;">
        <div class="card_col_1">
            @if($item->getImage())
                <img class="card-img" src="https://informerspro.ru{{ $item->getImage()->getUrl('thumb') }}" width="312px">
            @endif                            
        </div>
        <div class="card-img-out">
            <h5 class="card-title">{{ str_replace("[CITY]", $city, $item->getTitle()) }}</h5>
            <div class="item__gradient" style="background: radial-gradient(100% 500% at 100% center, rgba({{ $item->getRGBText() }}, 0) 55%, rgb({{ $item->getRGBText() }}) 75%);"></div>
        </div>
    </a>
</div>
