<div {!! $htmlAttributes !!}>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ $action_route }}">
                @csrf
                @method($action_method)
                <input type="hidden" name="customer_id" value="{{ customer()->id }}" />
                <div class="row">
                    <div class="col-md-6">
                        {!! \Form::rText('address_name', __('Address Name'), old('address_name', $address->address_name ?? ''), true, [
                            'maxlength' => 100,
                            'title'     => "Letters required; allowed: letters, numbers, spaces, - . , ' & ( ) /",
                            'data-allow' => "address_name"
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rText('address_code', __('Address Code'), old('address_code', $address->address_code ?? ''), true, [
                            'maxlength' => 50,
                            'title'     => "Letters, numbers, spaces, hyphens (-), and underscores (_) only",
                            'data-allow'=> "code"
                        ]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label>Street Address<span class="text-danger">*</span></label>
                    <input type="text" name="address_1"
                           placeholder="Street Address 1"
                           value="{{ old('address_1', $address->address_1 ?? '') }}"
                           class="my-1 form-control @error('address_1') is-invalid @enderror"
                           maxlength="120"
                           required
                           title="Allowed: letters, numbers, spaces, - . , # / '"
                           data-allow="address_line">
                    @error('address_1')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <input type="text" name="address_2"
                           placeholder="Street Address 2"
                           value="{{ old('address_2', $address->address_2 ?? '') }}"
                           class="my-1 form-control @error('address_2') is-invalid @enderror"
                           maxlength="120"
                           title="Allowed: letters, numbers, spaces, - . , # / '"
                           data-allow="address_line">
                    @error('address_2')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <input type="text" name="address_3"
                           placeholder="Street Address 3"
                           value="{{ old('address_3', $address->address_3 ?? '') }}"
                           class="my-1 form-control @error('address_3') is-invalid @enderror"
                           maxlength="120"
                           title="Allowed: letters, numbers, spaces, - . , # / '"
                           data-allow="address_line">
                    @error('address_3')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {!! \Form::rText('city', 'City', old('city', $address->city ?? ''), true, [
                            'maxlength' => 80,
                            'title'     => "Letters, spaces, hyphen, apostrophe, dot only",
                            'data-allow'=> "city"
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rSelect(
                             'country_code',
                             'Country',
                             $countries,
                             old('country_code', $address->country_code ?? null),   // <-- preselect here
                             true,
                             [
                                 'onchange'    => 'updateState(this.value, null);', // <-- clear state when country changes
                                 'placeholder' => 'Select a country'
                             ]
                         ) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rSelect('state', 'State', [], null, true) !!}
                    </div>
                    <div class="col-md-6">
                        {!! \Form::rText('zip_code', 'Zip/Postal Code', old('zip_code', $address->zip_code ?? ''), false, [
                            'maxlength' => 10,
                            'title'     => "Digits, hyphen only (4–10)",
                        ]) !!}
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0 !important;">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-success">
                            <span class="pe-7s-diskette"></span>
                            <span>Save</span>
                        </button>
                    </div>
                    <a href="{{ route('frontend.addresses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@pushonce('footer-script')
    <script>
        document.addEventListener('DOMContentLoaded', () =>
            updateState('{{ old('country_code', $address->country_code ?? 'null') }}',
                '{{ old('state', $address->state ?? 'null') }}')
        );

        function updateState(countryCode, selectState = 'null') {

            countryCode = countryCode === 'null' ? null : countryCode;

            selectState = selectState === 'null' ? null : selectState;

            const stateDropdown = $('select[name="state"]');

            if (!countryCode) {
                stateDropdown.empty();
                return;
            }

            $.get(`/get-states-by-country-code/${countryCode}`, {}, function(response) {
                stateDropdown.empty();
                stateDropdown.append(`<option value="">Select a State</option>`);
                $.each(response.states, function(index, state) {
                    let selected = (state.iso2 === selectState) ? 'selected' : '';
                    stateDropdown.append(`<option value="${state.iso2}" ${selected}>${state.name}</option>`);
                });
            }).catch((err) => {
                ShowNotification('error', 'Registration', err.response.data.message ?? 'The given data is invalid.');
                console.error('Error fetching states:', err);
            });
        }

        document.addEventListener('DOMContentLoaded', () => updateState('{{ old('country_code', 'null') }}', '{{ old('state', 'null') }}'));

        (function () {
            // live sanitizer by whitelist type
            var filters = {
                // must include at least one letter (we only sanitize characters; server enforces the “must contain letter”)
                address_name: /[^A-Za-z0-9\s\-\.\',&()\/]/g,
                // Letters, numbers, spaces, hyphens (-), and underscores (_) only
                code:         /[^A-Za-z0-9 _-]/g,
                // alphanumeric only, no spaces
                alnum:        /[^A-Za-z0-9]/g,
                // addresses: allow letters, numbers, space, hyphen, dot, comma, #, slash, apostrophe
                address_line: /[^A-Za-z0-9\s\-\.,#\/']/g,
                // letters + spaces + hyphen + apostrophe + dot
                city:         /[^A-Za-z\s\-\.']/g,
                // digits only
                postal_code:       /[0-9\-]/g
            };

            document.addEventListener('input', function (e) {
                var el = e.target;
                var allow = el.getAttribute && el.getAttribute('data-allow');
                if (!allow || !filters[allow]) return;
                var before = el.value;
                var after  = before.replace(filters[allow], '');
                if (after !== before) {
                    var s = el.selectionStart, d = before.length - after.length;
                    el.value = after;
                    // try keep caret position sensible
                    if (s != null) el.setSelectionRange(Math.max(0, s - d), Math.max(0, s - d));
                }
            }, true);
        })();
    </script>
@endpushonce
