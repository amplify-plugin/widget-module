@pushonce('plugin-script')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js') }}"></script>
@endpushonce
@php
    push_js(function () {
        return <<<JS
            document.addEventListener('DOMContentLoaded', function() {
                let calendarEl = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'title',
                        right: 'prev,next',
                    },
                    initialView: 'dayGridMonth',
                    selectable: true,
                    dayMaxEvents: true,
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.title,
                            container: "body"
                        });
                    },
                    events: function (info, successCallback, failureCallback) {
                        $.ajax({
                            type: "POST",
                            url: "/get/calendar/events",
                            data: {
                                '_token': $('#csrf-token').data('token'),
                                start: info.start.toISOString(),
                                end: info.end.toISOString()
                            },
                            dataType: "json",
                            success: function (res) {
                                let events = [];

                                $(res.data).each(function () {
                                    events.push({
                                        title: this.title,
                                        url: this.booking_url,
                                        start: this.start_date,
                                        end: this.end_date
                                    });
                                });

                                successCallback(events);
                            },
                            error: function (err) {
                                failureCallback(err);
                            }
                        });
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.open(info.event.url);
                        }
                    }
                });
                calendar.render();
            });
        JS;
    }, 'footer-script');
@endphp

<!-- CALENDAR VIEW  -->
<div id="calendar" {!! $htmlAttributes !!}></div>

<script>
    function checkForClassAndCallFunction() {
        if ($('.fc-daygrid-block-event').length > 0) {
            replaceLink();
        }
    }

    function replaceLink() {

        $('.fc-daygrid-block-event').attr('href', '#');
    }

    var interval = setInterval(checkForClassAndCallFunction, 1000);

    setTimeout(function() {
        clearInterval(interval);
    }, 100000);

</script>

<style>
    .fc-event-title {
        text-overflow: ellipsis !important;
    }
</style>
