<x-layout class="bg-dark">
    <x-slot:title>Регистрация</x-slot>

    <main class="d-flex flex-column align-items-center justify-content-center h-100">
        <div class="card" style="width: 450px">
            <div class="card-header">Регистрация</div>
            <div class="card-body">
                <h5 class="card-title">Регистрация в системе обучения</h5>

                @if(Session::get('error') != "")
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
                @endif

                <form action="{{ route('register')  }}" method="post">
                    @csrf

                    <div class="input-group has-validation mb-3">
                        <div class="form-floating {{ $errors->has('name') ? 'is-invalid' : '' }}">
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                autofocus
                                id="exampleInputName"
                                placeholder="ФИО"
                                value="{{ old('email') }}"
                                tabindex="1"
                            >
                            <label for="exampleInputName" class="form-label">ФИО</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    </div>

                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text">@</span>
                        <div class="form-floating {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <input
                                type="email"
                                name="email"
                                class="form-control"
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
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <div class="form-floating">
                            <input type="password" tabindex="3" name="password_confirmation" class="form-control" id="exampleInputPassword2" placeholder="Подтверждение пароля">
                            <label for="exampleInputPassword2" class="form-label">Подтверждение пароля</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <select name="is_self_student" class="form-select" id="role" tabindex="4">
                                <option value="0">Обучение в вузе</option>
                                <option value="1">Самообучение</option>
                            </select>
                            <label for="role" class="form-label">Тип обучения</label>
                        </div>
                        <div class="invalid-feedback">
                            {{ $errors->first('role') }}
                        </div>
                    </div>
                    <div id="student-subform">
                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <select name="teacher_id" class="form-select" id="teacher" tabindex="4">
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <label for="teacher" class="form-label">Преподаватель</label>
                            </div>
                            <div class="invalid-feedback">
                                {{ $errors->first('teacher_id') }}
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating">
                                <select name="student_group_id" class="form-select" id="group" tabindex="5">
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                <label for="group" class="form-label">Группа</label>
                            </div>
                            <div class="invalid-feedback">
                                {{ $errors->first('student_group_id') }}
                            </div>
                        </div>
                    </div>

                    <button type="submit" tabindex="4" class="btn btn-primary">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            if (this.value === 'student') {
                document.getElementById('student-subform').style.display = 'block';
            } else {
                document.getElementById('student-subform').style.display = 'none';
            }
        });
    </script>

</x-layout>
