@pushonce("footer-script")
	<script src="{{ mix("js/backend.js", "vendor/backend") }}"></script>
@endpushonce
<div {!! $htmlAttributes !!}>
    <div id="app">
        <div class="padding-top-2x mt-2 hidden-lg-up"></div>
        <customer-quick-list-update
            class_name="col-md-12"
            contact_list="{{ $contactList }}"
            product_list="{{ $productList }}"
            quicklist="{{ json_encode($quickListModel) }}"
            axios_url="{{ ($quickListModel->id) ? route('frontend.quick-lists.update', $quickListModel->id) : route('frontend.quick-lists.store') }}"
            method="{{ ($quickListModel->id) ? 'put' : 'post' }}"
            back_url="{{ route('frontend.quick-lists.index') }}"
            save_action="{{ json_encode($saveAction ?? ['active' => ['value' => 'save_and_back', 'label' => 'Save and Back'], 'options' => [] ]) }}">
        </customer-quick-list-update>
    </div>
</div>
