@php
    use App\Models\InviteRequest\InviteRequest;

    /** @var InviteRequest[] $invites */
@endphp
<x-layout>
    <x-slot:title>Заявки</x-slot:title>

    <h1 class="mb-3">Заявки</h1>

    @if(count($invites) == 0)
        <div class="alert alert-info">
            Нет заявок
        </div>
    @else
        <table class="table table-with-actions table-striped">
            <tr>
                <th>Студент</th>
                <th>Дата</th>
                <th></th>
            </tr>
            @foreach ($invites as $invite)
                @php
                if ($invite->student == null) {
                    var_dump($invite->id); die;
                }
                @endphp
                <tr>
                    <td>{{$invite->student->name}}</td>
                    <td>{{$invite->student->created_at->format('d.m.Y H:i')}}</td>
                    <td class="actions-cell" style="width:300px">
                        <a href="{{route('teacher.invites.accept', $invite)}}" class="btn btn-primary">
                            Принять
                        </a>
                        <a href="{{route('teacher.invites.decline', $invite)}}" class="btn btn-danger">
                            Отклонить
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</x-layout>
