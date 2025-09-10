<div {!! $htmlAttributes !!}>
    <div class="row">
        <!-- Feature Brands Section -->
        <div class="col-12 text-center mb-4">
            <p class="feature-brands-title">Feature Brands</p>
        </div>

        <!-- Responsive Images Section -->
        <div class="col-12 d-flex flex-wrap justify-content-center mb-5">
            @for($i=0; $i<8; $i++)
                <div class="col-md-3 col-6 mb-4 feature-brands-img-section">
                    <img src="" alt="Brand Image" class="img-fluid feature-brands-img">
                </div>
            @endfor
        </div>
    </div>

</div>
