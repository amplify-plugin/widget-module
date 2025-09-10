<script>
    @if(!empty($level))
    $(document).ready(function () {
        if (typeof ShowNotification == 'function') {
            ShowNotification('{{$level}}', '{{$title}}', '{{$message}}');
        }
    });
    @endif
</script>

