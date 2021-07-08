<div class="col-lg-4 col-md-4 col-sm-12 pl-0 pr-0 d-flex">

    <a href="{{ $item->getUrl($params['template_id'], $link, $show_as ?? '') }}" target="_blank" class="item item_bottom" style="background: rgb({{ $item->getRGBText() }}); color: white;">
        <div class="container__wrapper">
            <div class="container__content">
                <div class="card_col_1">
                    @if($item->getImage())
                    <img class="card-img" src="https://informerspro.ru{{ $item->getImage()->getUrl('thumb') }}" width="312px">                    
                    @endif                    
                    <div class="item__gradient" style="background: linear-gradient(rgba({{ $item->getRGBText() }}, 0) 0%, rgb({{ $item->getRGBText() }}) 100%);"></div>
                </div>
                <div class="card-img-out">
                    <h5 class="card-title">{{ str_replace("[CITY]", $city, $item->getTitle()) }}</h5>
                </div>
            </div>
        </div>
    </a>
</div>
