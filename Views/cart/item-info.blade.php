<div {!! $htmlAttributes !!}>
    <div class="product-item">
        <a class="product-thumb" href="{url}">
            <img src="{image}" class="img-thumbnail" alt="{name}">
        </a>
        <div class="product-info">
            <p class="product-title">
                <a href="{url}">{name}</a>
            </p>
            {!! $slot !!}
        </div>
    </div>
</div>
