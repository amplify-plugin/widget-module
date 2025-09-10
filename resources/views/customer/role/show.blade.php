<div {!! $htmlAttributes !!}>
<div class="card">
    <div class="card-body">
        <div class="form-group">
            <label class="font-weight-bold">Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
                   class="form-control-plaintext @error('name') is-invalid @enderror" disabled>
        </div>
        <div class="form-group mx-3">
            <label class="font-weight-bold ml-n3">Permissions</label>
            <div class="row">
                @foreach ($permissionArray as $key => $option_group)
                    <div class="col-12 py-2 pl-0 border-bottom">
                        <span class="font-weight-bold">{{ ucwords(str_replace("-", " ", $key)) }}</span>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            @foreach($option_group as $value => $label)
                                <div class="col-md-4 col-sm-6 col-12 m-2 mt-0">
                                    <i class="icon-check mr-1"></i> {{ ucwords(str_replace(['.', '-', 'show'], [' ', ' ', 'detail'], $label)) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
