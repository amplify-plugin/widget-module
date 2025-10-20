<div {!! $htmlAttributes !!}>
<div class="card">
    <div class="card-body">
        <form method="post" action="{{ $action_route }}">
            @csrf
            @method($action_method)
            <input type="hidden" name="team_id" value="{{customer()->id}}">
            <input type="hidden" name="guard_name" value="customer">
            <div class="form-group">
                <label>Name<span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <x-customer.role.partials.permissions :role="$role"/>

            <div class="form-group" style="margin-bottom: 0 !important;">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-success">
                        <span class="pe-7s-diskette"></span>
                        <span>Save</span>
                    </button>
                </div>
                <a href="{{ route('frontend.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
