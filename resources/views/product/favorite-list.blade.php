@php
    $formRoute = route('frontend.favourites.store');
@endphp

@pushonce("html-default")
    <div class="modal fade x-favorite-list" data-backdrop="static" data-keyboard="false" id="list-addons" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Favorites</h4>
                    <button class="close close-button" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{$formRoute}" method="POST" onsubmit="addItemToCustomerList(this, event); return false;">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <input type="hidden" id="product_qty" name="product_qty">
                        <div id="order-list-dropdown">
                            <div class="form-group">
                                <label for="list_name">
                                    Choose from Lists<span class="text-danger ">*</span>
                                </label>
                                <select class="form-control custom-select"
                                        name="list_id" id="list_id">
                                    <option value="">-- Select an item --</option>
                                </select>
                            </div>
                        </div>

                        <div id="new-order-list">
                            <div class="form-group">
                                <label for="list_name">
                                    Name<span class="text-danger">*</span>
                                </label>
                                <input name="list_name" class="form-control" type="text" id="list_name" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="list_name">
                                    Type<span class="text-danger ">*</span>
                                </label>
                                <select class="form-control custom-select" name="list_type" id="list_type">
                                    {!!$listTypeOptions()!!}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="list_desc">Description</label>
                                <textarea name="list_desc" class="form-control" type="text" id="list_desc"
                                            rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            <button class="btn btn-outline-warning btn-sm ml-0" id="new-list-button"
                                    type="button">
                                Add List <i class="icon-arrow-right"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm ml-0" id="order-list-dropdown-btn"
                                    type="button">
                                <i class="icon-arrow-left"></i> Existing Lists
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="icon-disc"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpushonce

@pushonce('footer-script')
    <script src="{{ asset('assets/js/widget/favorite-list.js') }}"></script>
@endpushonce
