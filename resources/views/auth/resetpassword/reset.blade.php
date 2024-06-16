<x-layout class="bg-dark">
    <x-slot:title>Новый пароль</x-slot>

    <main class="d-flex flex-column align-items-center justify-content-center h-100">
        <div class="card" style="width: 450px">
            <div class="card-body">
                <h5 class="card-title">Новый пароль</h5>
                <form action="{{route('password.store')}}" method="post">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">

                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <div class="form-floating {{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                autofocus
                                id="exampleInputPassword1"
                                placeholder="Пароль"
                                tabindex="1"
                            >
                            <label for="exampleInputPassword1" class="form-label">Пароль</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    </div>

                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <div class="form-floating {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                id="exampleInputPassword2"
                                placeholder="Подтверждение пароля"
                                tabindex="2"
                            >
                            <label for="exampleInputPassword2" class="form-label">Подтверждение пароля</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('password_confirmation') }}
                        </div>

                    </div>

                    <button type="submit" tabindex="4" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
        </div>
    </main>
</x-layout>
