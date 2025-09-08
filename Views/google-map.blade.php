<div {!! $htmlAttributes !!}>
    <iframe style="width:100%; height:100%; border:0; margin: 5px;" loading="lazy" allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps/embed/v1/place?key={{config('amplify.google.google_map_api_key')}}&q={{ urlencode($address ?? 'Landon') }}">
    </iframe>
</div>
