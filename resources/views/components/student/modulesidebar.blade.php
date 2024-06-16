@props(['module', 'part' => null])

<x-slot:sidebar>
    <a href="{{ route('student.modules.module', $module)  }}"
       class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">{{ $module->name }}</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        @foreach ($module->parts as $i => $p)
            <li class="nav-item">
                    <span
                        @class([
                            'nav-link',
                            'text-white' => $part == null || $part->id !== $p->id,
                            'text-secondary' => $part != null && $part->id === $p->id,
                        ])>
                        {{ $i + 1 }}. {{ $p->name }}
                    </span>
            </li>
        @endforeach
    </ul>
    <hr>
</x-slot:sidebar>
