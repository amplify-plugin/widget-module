@pushonce("footer-script")
	<script src="{{ asset("vendor/backend/js/backend.js") }}"></script>
@endpushonce
<div {!! $htmlAttributes !!}>
    <div class="row">
        <div id="app" class="col-12">
            <div class="padding-top-2x mt-2 hidden-lg-up"></div>
            <contact-login-manage
                class_name="col-md-12"
                contact="{{ json_encode($contactModel) }}"
                warehouses="{{ json_encode($warehouses) }}"
                permissions="{{ json_encode($permissions) }}"
                axios_url="{{ route('frontend.contact-logins.update', $contactModel->id) }}"
                back_url="{{ route('frontend.contact-logins.index') }}"
                save_action="{{ json_encode($saveAction ?? ['active' => ['value' => 'save_and_back', 'label' => 'Save and Back'], 'options' => [] ]) }}">
            </contact-login-manage>
        </div>
    </div>
</div>
