<div {!! $htmlAttributes !!}>
<div class="card">
    <div class="card-body">
        <form method="post" action="{{ $action_route }}">
            @csrf
            @method($action_method)
            <input type="hidden" name="customer_id" value="{{ customer()->id }}"/>
            <div class="form-group">
                <label>Name<span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $favourite->name ?? '') }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Type<span class="text-danger">*</span></label>
                <select name="list_type" class="form-control custom-select">
                    <option
                        value="global"
                        @selected('global' == old('list_type', $favourite->list_type ?? ''))>
                        Global
                    </option>
                    <option
                        value="personal"
                        @selected('personal' == old('list_type', $favourite->list_type ?? ''))>
                        Personal
                    </option>
                </select>
                @error('list_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" rows="5"
                          name="description">{{ old('description', $favourite->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-0">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-success">
                        <span class="pe-7s-diskette"></span>
                        <span>Save</span>
                    </button>
                </div>
                <a href="{{ route('frontend.favourites.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
