@php
    $locations = \Amplify\System\Backend\Models\Webinar::select('address_name')->distinct()->pluck('address_name');
    $webinarTypes = \Amplify\System\Backend\Models\WebinarType::all();

    push_js(function () {
        return <<<JS
            document.querySelector('#filter-section').addEventListener('click', () => {
                document.querySelector('body').classList.toggle('event-body')
                const body = document.querySelector('body');
                if (document.querySelector('.event-body')) {
                    // Create overlay
                    const newDiv     = document.createElement('div');
                    newDiv.className = 'event-body-overlay';
                    body.appendChild(newDiv);

                    // Remove overlay
                    document.querySelector('.event-body-overlay').addEventListener('click', () => {
                        document.querySelector('body').classList.remove('event-body')
                        body.removeChild(body.querySelector('.event-body-overlay'));
                    })
                } else {
                    // Remove overlay
                    body.removeChild(body.querySelector('.event-body-overlay'));
                }

            });

            $('#see-more').on('click', (e)=>{
                e.stopPropagation();
                $('.see-more-gradient').toggleClass('see-more');
            });

            $(function() {
                $('input[name="daterange"]').daterangepicker({
                    autoUpdateInput: false,
                    timePicker: true,
                    locale: {
                        format: 'YYYY-M-DD hh:mm:ss A',
                        cancelLabel: 'Clear'
                    },
                    opens: 'left'
                });

                $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
                });

                $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            });

        JS;
    }, 'footer-script');
@endphp
<div {!! $htmlAttributes !!}>
    <form class="mb-3">
        <div class="d-flex justify-content-end gap-3">
            <div class="w-100 d-flex gap-2 align-items-center px-2 border rounded-8 search-section">
                <i class="fs-24 pe-7s-search d-none d-sm-block"></i>
                <i class="fs-24 pe-7s-search d-sm-none" data-toggle="modal" data-target="#searchModal"></i>
                <input type="search" name="q" value="{{ request()->q }}"
                       class="border-0 bg-transparent p-0 form-control" placeholder="Search">

                <!--SEARCH MODAL-->
                <div class="modal fade" id="searchModal" tabindex="-1" role="dialog"
                     aria-labelledby="searchModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content overflow-hidden">
                            <div class="modal-header align-items-center">
                                <input placeholder="Search.." type="text" class="form-control">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="dropdown w-100 filter-section">
                <div class="d-flex gap-2 align-items-center border rounded-8 h-100">
                    <div class="px-2 d-flex align-items-center w-100" id="filter-section">
                        <i class="fs-24 mr-1 pe-7s-filter"></i>
                        <span>Filter</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right w-100" id="dropdownSearchMenu">
                        <div class="px-4 modal-header pb-0 border-0 align-items-center mb-4">
                            <div class="align-items-center d-flex justify-content-between w-100">
                                <h4 class="modal-title" id="eventFilterModalLabel">Filter</h4>
                            </div>
                        </div>
                        <div class="px-4">
                            <div class="form-group position-relative">
                                <h5 class="mb-2">Date</h5>
                                <input id="daterange" type="text" name="daterange" value="{{ request()->daterange }}"
                                       placeholder="Select Date" class="form-control"
                                />
                                <i class="fa-regular fa-calendar calendar-icon"></i>
                            </div>

                            <div class="form-group">
                                <h5 class="mb-2">Location</h5>
                                <select name="location" class="form-control">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $place)
                                        @if (!empty($place))
                                            <option @selected(request("location") == $place)>{{ $place }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            @if (!$webinarTypes->isEmpty())
                                <h5 class="mt-4 mb-3">Types</h5>
                                <div class="d-flex flex-column gap-5 mb-2 see-more-gradient position-relative overflow-hidden">
                                    @foreach($webinarTypes as $webinarType)
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input
                                                        class="form-check-input" type="checkbox"
                                                        name="types[]" value="{{ $webinarType->id }}"
                                                        @if(in_array($webinarType->id, request()->types ?? [])) checked @endif
                                                >{{ $webinarType->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- @if (count($webinarTypes) > 4)
                                    <div class="text-primary" id="see-more" type="button">
                                        <span>Show more...</span>
                                        <span>Show less</span>
                                    </div>
                                @endif --}}
                            @endif


                            <div class="d-flex mt-3 mb-2 gap-2 justify-content-end">
                                <a href="?" class="btn-primary px-3 py-2 rounded text-decoration-none">Reset</a>
                                <button type="submit" class="border-0 btn-primary d-inline-block px-3 py-2 rounded">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
