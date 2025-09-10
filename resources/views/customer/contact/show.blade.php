<div {!! $htmlAttributes !!}>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Name</p>
                    <p> {{ $contact->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Email</p>
                    <p> {{ $contact->email ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Phone</p>
                    <p> {{ $contact->phone ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Order Limit</p>
                    <p>{{ $contact->order_limit ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Daily Budget Limit</p>
                    <p> {{ $contact->daily_budget_limit ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <p class="font-weight-bold">Monthly Budget Limit</p>
                    <p> {{ $contact->monthly_budget_limit ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <p class="font-weight-bold">Roles</p>
                    <p>
                        @if($contact->roles->isEmpty())
                            -
                        @else
                            {{ implode(', ', $contact->roles->pluck('name')->toArray()) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
