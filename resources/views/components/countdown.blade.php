@php use Illuminate\Support\Carbon; @endphp
@props([
    'seconds',
    'start' => null,
])
@php
/** @var int $seconds */
/** @var Carbon $start */
@endphp
<span id="countdown"></span>

<script>
    (function() {
        function render(seconds) {
            if (seconds <= 0) {
                document.getElementById('countdown').textContent = 'время вышло';
                return;
            }
            const min = Math.floor(seconds / 60);
            const sec = seconds % 60;
            document.getElementById('countdown').textContent = `${min}:${sec.toString().padStart(2, '0')}`;
        }

        @if ($start)
            const end = {{$start->unix()*1000}} + {{ $seconds }} * 1000;

            function update() {
                const countdown = Math.floor((end - Date.now()) / 1000);
                render(countdown);
                return countdown;
            }

            const interval = setInterval(() => {
                const countdown = update()
                if (countdown <= 0) {
                    clearInterval(interval);
                }
            }, 1000);

            update();
        @else
            render({{ $seconds }});
        @endif
    })()
</script>
