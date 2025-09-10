<form
    class="row" method="post" class="@if(isset($errors) && !empty($errors->isEmpty())) was-validated @endif"
    action="{{ route('frontend.switch-account.update', $account->id) }}"
>
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            @if(config('amplify.basic.enable_multi_customer_manage', false))
                <div class="form-group">
                    <label for="active_customer_id">{{ __('Switch Customer Account') }}</label>
                    <select
                        class="custom-select form-control @error('active_customer_id') is-invalid @enderror"
                        name="active_customer_id"
                        id="active_customer_id"
                    >

                        @foreach($customers  as $id => $customer)
                            <option
                            value="{{ $customer->customer_id }}"
                            @selected(old('active_customer_id', $account->active_customer_id) == $customer->customer_id)>
                                {{ $customer->customer_name }}
                                @if($customer->customer_id == $account->customer_id)
                                   (Default)
                                @endif
                            </option>
                        @endforeach
                    </select>

                    @error('active_customer_id')
                        <p class="text-danger my-2">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <button class="btn btn-primary" type="submit">Switch</button>
        </div>
    </div>
</form>
