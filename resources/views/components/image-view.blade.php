<div class="swiper-container">

    <div class="swiper-wrapper">

    @if ($urls)

        @foreach($urls as $image)

            <div class="swiper-slide">

            <img src="/storage/{{ $image }}" alt="Image" width="100%" class="w-full h-auto">

            </div>

        @endforeach

    @else

        <p>No image available</p>

    @endif

    </div>

    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>

    <!-- Add Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.swiper-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });

</script>
