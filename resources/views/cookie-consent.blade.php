<div {!! $htmlAttributes !!}>
    <div class="modal-dialog modal-{{ $modalWidth }}">
        <div class="modal-content">
            <div class="modal-body" style="border-radius: 8px">
                <h4 class="modal-title mb-2 @if(strlen($title) == 0) d-none @endif">{{ $title }}</h4>
                @if(strlen($content) == 0)
                    <script> alert('Warning!! Cookie Consent Content is not setup yet.'); </script>
                @else
                    {!! $content !!}
                @endif
                <div class="d-flex gap-2 justify-content-center justify-content-md-end">
                    <button type="button" class="btn btn-success btn-sm my-0 font-weight-bold"
                            onclick="acceptCookie();">
                        <i class="icon-check mr-1"></i>
                        Accept
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm my-0 mr-0"
                            onclick="rejectCookie()">
                        <i class="icon-circle-cross mr-1"></i>
                        Decline
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('footer-script')
    <script>
            function acceptCookie() {
                window.localStorage.setItem('consented', 'true');
                $('#cookie-consent-modal').modal('hide');
            }

            function rejectCookie() {
                window.localStorage.setItem('consented', 'false');
                $('#cookie-consent-modal').modal('hide');
            }

            $(function() {
                let consent = window.localStorage.getItem('consented');
                if (consent == null || consent === 'false') {
                    $('#cookie-consent-modal').modal('toggle');
                }
            });
    </script>
@endPushOnce
