<x-layout class="bg-dark">
    <x-slot:title>Вход</x-slot>

    <main class="d-flex flex-column align-items-center justify-content-center h-100">
        <div class="card" style="width: 450px">
            <div class="card-header">Авторизация</div>
            <div class="card-body">
                <h5 class="card-title">Вход в систему обучения</h5>
                <form method="post">
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
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <div class="form-floating">
                            <input type="password" tabindex="2" name="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
                            <label for="exampleInputPassword1" class="form-label">Пароль</label>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" tabindex=3" name="remember" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Запомнить меня</label>
                    </div>

                    <button type="submit" tabindex="4" class="btn btn-primary" style="width:100px">Войти</button>
                    <a href="{{ route('registration') }}" class="btn btn-link">Регистрация</a>
                    <a href="{{ route('password.request') }}" class="btn btn-link">Забыли пароль?</a>
                </form>
            </div>
        </div>
    </main>

</x-layout>
