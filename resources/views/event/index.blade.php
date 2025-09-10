@php
    $request = request();
    $webinars = \Amplify\System\Backend\Models\Webinar::query();

    if ($request->filled('q')) {
        $webinars->where('title', 'LIKE',  "%{$request->q}%");
    }

    if ($request->filled('location')) {
        $webinars->where('address_name', 'LIKE',  "%{$request->location}%");
    }

    if ($request->filled('daterange')){
        [$startDateTime, $endDateTime] = explode(' - ', $request->daterange);

        $webinars->whereBetween('start_date_time', [
            \Carbon\Carbon::parse($startDateTime)->format('Y-m-d g:i:s'),
            \Carbon\Carbon::parse($endDateTime)->format('Y-m-d g:i:s')
        ]);
    }

    if ($request->filled('types')) {
        $webinars->whereHas('webinarType', function ($query) use($request) {
            $query->whereIn('id', $request->types);
        });
    }

    $webinars = $webinars->with(['webinarType' ,'bannerZone' => function($q){
        $q->with(['banners' => function($q){
            $q->where('enabled' , 1)->whereNotNull('image');
        }]);
    }])->orderBy('start_date_time', "DESC")->paginate(12);
@endphp
@pushonce('custom-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/widget/event-list.css') }}">
@endpushonce
<div {!! $htmlAttributes !!}>
    <div class="event-section">
        <div class="d-flex flex-column gap-3 mb-3">
            @foreach ($webinars as $webinar)
                <div class="border rounded-12 p-3 d-flex flex-column flex-sm-row align-items-lg-center gap-3">
                    <a href="{{ route('frontend.events.show', $webinar->slug) }}"
                       class="event-img flex-shrink-0 overflow-hidden">
                        <img class="w-100 h-100"
                             src="{{ !empty($webinar->bannerZone->banners[0]) ? $webinar->bannerZone->banners[0]->image :  $webinar->cover_photo }}"
                             alt="">
                    </a>
                    <div class="d-flex flex-column justify-content-center gap-5">
                        <a class="fs-18"
                           href="{{ route('frontend.events.show', $webinar->slug) }}">{{ $webinar->title }}</a>
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="fs-14 d-flex align-items-center mr-2">
                                <i class="pe-7s-date mr-2 fs-18 fw-700 text-primary"></i>
                                {{ $webinar->start_date_time->format(config('amplify.basic.date_format')) . ' - ' . $webinar->end_date_time->format(config('amplify.basic.date_format')) }}
                            </div>
                            <div class="fs-14 d-flex align-items-center mr-2">
                                <i class="pe-7s-clock mr-2 fs-18 fw-700 text-primary"></i>
                                {{ $webinar->start_date_time->format('g:i A') }}
                            </div>
                        </div>

                        <div class="fs-14 d-flex align-items-center">
                            <i class="pe-7s-map-marker mr-2 fs-18 fw-700 text-primary"></i>
                            {{ $webinar->address_name ?? 'Not Avaliable' }}
                        </div>

                        <p class="webkit-line-1 fs-14 fw-400">{{ $webinar->short_description }}</p>

                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ $webinar->booking_url ?? '' }}"
                               class="btn btn-primary d-flex align-items-center justify-content-center rounded-12 m-0 w-160"
                               target="_blank"><i class="icon-link mr-2"></i>{{ $webinar->booking_label ?? "Book URL" }}
                            </a>
                            <a href="{{ route('frontend.events.show', $webinar->slug) }}"
                               class="btn border d-flex align-items-center justify-content-center border rounded-12 m-0 w-160">Details
                                <i class="icon-arrow-right ml-2"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="e-pagination">
        {{ $webinars->links() }}
    </div>
</div>

