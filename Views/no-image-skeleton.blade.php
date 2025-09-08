<script>
    const FALLBACK_IMG_SRC = '{{ $imagesrc }}';
    window.addEventListener('load', function () {
        document.querySelectorAll('img').forEach(function (imageElement) {
            let imageSrc = imageElement.src;
            if (imageSrc) {
                imageSrc = imageSrc.toString().trim();
                imageElement.dataset.src = imageSrc;
                switch (imageSrc) {
                    case '':
                    case '#' : {
                        imageElement.src = FALLBACK_IMG_SRC;
                        break;
                    }
                    default : {
                        if (imageElement.complete && imageElement.naturalHeight === 0) {
                            imageElement.src = FALLBACK_IMG_SRC;
                        }
                    }
                }
            } else {
                imageElement.src = FALLBACK_IMG_SRC;
            }
        });
    });
</script>
