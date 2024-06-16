<x-layout class="bg-dark">
    <x-slot:title>Сброс пароля</x-slot>

    <main class="d-flex flex-column align-items-center justify-content-center h-100">
        <div class="card" style="width: 450px">
            <div class="card-body">
                <h5 class="card-title">Сброс пароля</h5>
                <form action="{{route("password.email")}}" method="post">
                    @csrf

                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text">@</span>
                        <div class="form-floating {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                autofocus
                                id="exampleInputEmail1"
                                placeholder="Адрес электронной почты"
                                value="{{ old('email') }}"
                                tabindex="1"
                            >
                            <label for="exampleInputEmail1" class="form-label">Адрес электронной почты</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    </div>

                    <button type="submit" tabindex="4" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </main>
</x-layout>
