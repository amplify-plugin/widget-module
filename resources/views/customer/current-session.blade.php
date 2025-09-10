<div {!! $htmlAttributes !!}>
    <div class="card" style="padding: 10px; background-color: #F2F8FD">
        <div class="card-header d-flex justify-content-between gap-2">
            <h5 class="card-title">User Information</h5>
            <i class="icon-arrow-down" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></i>
        </div>
        <div class="card-body p-0" id="collapseExample">
            <div class="flex-row">
                @foreach($sessionInfo as $label => $value)
                    <div class="flex-column border border-primary mb-2" style="padding: 4px 10px; border-radius: 8px">
                        <small class="text-primary mb-1">{{ $label }}</small>
                        <p class="text-primary font-weight-bolder" style="font-size: 20px">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck" style="accent-color: #4A7CA5">
                    <label class="form-check-label" for="gridCheck">
                        Keep me Logged In
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
