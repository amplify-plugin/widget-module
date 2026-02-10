<script>
    @if(!empty($level))
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof ShowNotification == 'function') {
            ShowNotification('{{$level}}', '{{$title}}', '{{$message}}');
        }
    });
    @endif
</script>

