<div {!! $htmlAttributes !!}>
    <div class="row">
        <div class="col-md-4 col-10 mx-auto">
            <x-product.product-gallery :image="$product?->product_image"/>
        </div>
        <div class="col-md-8">
            <x-product.item-number :product="$product" format="{product_code}" element="h4"
                                   class="text-primary font-weight-bold my-3"/>

            <h2 class="product-title lead">{{ $product->Product_Name ?? '' }}</h2>

            <x-product-manufacture-image :product="$product"/>

            <x-product.price
                    element="div"
                    class="w-100 product-price"
                    :product="$product"
                    :value="$product->ERP?->Price"
                    :uom="$product->ERP?->UnitOfMeasure ?? 'EA'"
                    :std-price="$product->Msrp->toFloat()"/>

            <div class="product-full-summary">
                {!! $product->short_description ?? '' !!}
            </div>

            <div class="row margin-top-1x">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="size">Men's size</label>
                        <select class="form-control" id="size">
                            <option>Chooze size</option>
                            <option>11.5</option>
                            <option>11</option>
                            <option>10.5</option>
                            <option>10</option>
                            <option>9.5</option>
                            <option>9</option>
                            <option>8.5</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="color">Choose color</label>
                        <select class="form-control" id="color">
                            <option>White/Red/Blue</option>
                            <option>Black/Orange/Green</option>
                            <option>Gray/Purple/White</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <select class="form-control" id="quantity">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="mb-3">

            <div class="d-flex justify-content-between gap-2 product-card border-0 p-0">
                <x-product-social-media-link :product="$product"/>
                <div class="product-buttons">
                    <x-product.quick-action :product="$product"/>
                </div>
            </div>
        </div>
    </div>

    <x-product.information-tabs
            :product="$product"
            :header-class="''"
            :tabs="[
                'description',
                 'sku' => ['label' => 'Products'],
                'feature' => ['label' => 'Features', 'style' => 'list'],
                'specification' => ['label' => 'Specifications', 'style' => 'list'],
                'related-products' => ['label' => 'Related'],
            ]">
        <x-slot:before>
            <svg width="0" height="0">
                <defs>
                    <clipPath id="tabClip" clipPathUnits="objectBoundingBox">
                        <path d="
                            M-0.01,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1.01,1
                        "/>
                    </clipPath>
                    <clipPath id="tabClip2" clipPathUnits="objectBoundingBox">
                        <path d="
                            M0,1
                            C0.08,0.9 0.08,0 0.17,0
                            H0.83
                            C0.92,0 0.92,0.9 1,1
            "/>

                    </clipPath>
                </defs>
            </svg>
        </x-slot:before>
    </x-product.information-tabs>
</div>
