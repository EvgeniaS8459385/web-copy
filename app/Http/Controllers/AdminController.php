<?php

namespace App\Http\Controllers;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use App\Models\Module\ModulePartQuestionAnswer;
use App\Models\News\Article;
use App\Models\StudentGroup\StudentGroup;
use App\Models\User;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Функция для отображения списка модулей.
    public function showModules(): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewAny', Module::class);
        // Возвращает представление (html) со списком модулей.
        return view('admin.modules', ['modules' => Module::all()]);
    }

    // Функция для создания модуля.
    public function createModule(): View
    {
        // Проверяет права доступа.
        Gate::authorize('create', Module::class);
        // Возвращает представление (html) с формой создания модуля.
        return view('admin.module_form');
    }

    // Функция для редактирования модуля
    // Принимает модуль, который нужно отредактировать.
    public function editModule(Module $module): View
    {
        // Проверяет права доступа.
        Gate::authorize('update', $module);

        // Возвращает представление (html) с формой редактирования модуля.
        return view('admin.module_form', [
            // Передает в представление модуль.
            'module' => $module,
            // Передает в представление список частей модуля.
            'parts' => $module->parts,
        ]);
    }

    // Функция для создания модуля.
    // Принимает данные из формы создания модуля.
    public function storeModule(Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('create', Module::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // Создает модуль в базе данных.
        $module = Module::factory()->create($validated);

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для обновления модуля.
    // Принимает данные из формы редактирования модуля.
    public function updateModule(Module $module, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('update', $module);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // Обновляет данные модуля.
        $module->fill($validated);

        // Сохраняет изменения в базе данных.
        $module->save();

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для удаления модуля.
    // Принимает модуль, который нужно удалить.
    public function deleteModule(Module $module): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('delete', $module);
        // Удаляет модуль из базы данных.
        $module->delete();
        // Перенаправляет на страницу со списком модулей.
        return redirect()->route('admin.modules.show');
    }

    // Функция для отображения списка студентов.
    public function showStudents(): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewAnyStudent', User::class);

        // Возвращает представление (html) со списком студентов.
        return view('admin.students', [
            'students' => User::students()->get(),
        ]);
    }

    // Функция для создания студента.
    public function createStudent(): View
    {
        // Проверяет права доступа.
        Gate::authorize('createStudent', User::class);

        // Возвращает представление (html) с формой создания студента.
        return view('admin.student_form', [
            'groups' => StudentGroup::all(),
            'teachers' => User::teachers()->get(),
        ]);
    }

    // Функция для сохранения студента.
    // Принимает данные из формы создания студента.
    public function storeStudent(Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('createStudent', User::class);
        // Создает студента в базе данных.
        $this->storeUser($request, User::factory()->student());
        // Перенаправляет на страницу со списком студентов.
        return redirect()->route('admin.students.show');
    }

    // Функция для редактирования студента.
    // Принимает студента, которого нужно отредактировать.
    public function editStudent(User $student): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewStudent', $student);
        // Возвращает представление (html) с формой редактирования студента.
        return view('admin.student_form', [
            'student' => $student,
            'groups' => StudentGroup::all(),
            'teachers' => User::teachers()->get(),
        ]);
    }

    // Функция для обновления студента.
    // Принимает студента, которого нужно обновить и данные из формы.
    public function updateStudent(User $student, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('updateStudent', $student);
        // Обновляет данные студента.
        $this->updateUser($student, $request);
        // Перенаправляет на страницу со списком студентов.
        return redirect()->route('admin.students.show');
    }

    // Функция для удаления студента.
    // Принимает студента, которого нужно удалить.
    public function deleteStudent(User $student): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('deleteStudent', $student);
        // Удаляет студента из базы данных.
        $student->delete();
        // Перенаправляет на страницу со списком студентов.
        return redirect()->route('admin.students.show');
    }


    // Функция для отображения списка преподавателей.
    public function showTeachers(): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewAnyTeacher', User::class);

        // Получает список преподавателей.
        $teachers = User::teachers()->get();

        // Возвращает представление (html) со списком преподавателей.
        return view('admin.teachers', [
            'teachers' => $teachers,
        ]);
    }

    // Функция для создания преподавателя.
    public function createTeacher(): View
    {
        // Проверяет права доступа.
        Gate::authorize('createTeacher', User::class);

        // Возвращает представление (html) с формой создания преподавателя.
        return view('admin.teacher_form');
    }

    // Функция для сохранения преподавателя.
    // Принимает данные из формы создания преподавателя.
    public function storeTeacher(Request $request)
    {
        // Проверяет права доступа.
        Gate::authorize('createTeacher', User::class);

        // Используем фабрику для создания преподавателя.
        // Фабрика создает пользователя с ролью преподавателя.
        $teacher = User::factory()->teacher();

        try {
            // Сохраняем данные преподавателя.
            $this->storeUser($request, $teacher);
        } catch (Exception $exception) {
            var_dump($exception->getMessage()); die;
        }


        // Перенаправляем на страницу со списком преподавателей.
        return redirect()->route('admin.teachers.show');
    }


    // Функция для редактирования преподавателя.
    // Принимает преподавателя, которого нужно отредактировать.
    public function editTeacher(User $teacher): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewTeacher', $teacher);

        // Возвращает представление (html) с формой редактирования преподавателя.
        return view('admin.teacher_form', ['teacher' => $teacher]);
    }

    // Функция для обновления преподавателя.
    // Принимает преподавателя, которого нужно обновить и данные из формы.
    public function updateTeacher(User $teacher, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('updateTeacher', $teacher);

        // Обновляет данные преподавателя.
        $this->updateUser($teacher, $request);

        // Перенаправляет на страницу со списком преподавателей.
        return redirect()->route('admin.admins.show');
    }

    // Функция для удаления преподавателя.
    // Принимает преподавателя, которого нужно удалить.
    public function deleteTeacher(User $teacher): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('deleteTeacher', $teacher);

        // Удаляет преподавателя из базы данных.
        $teacher->delete();

        // Перенаправляет на страницу со списком преподавателей.
        return redirect()->route('admin.teachers.show');
    }

    // Функция для отображения списка администраторов.
    public function showAdmins(): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewAnyAdmin', User::class);

        // Получает список администраторов.
        $admins = User::admins()->get();

        // Возвращает представление (html) со списком администраторов.
        return view('admin.admins', ['admins' => $admins]);
    }

    // Функция для создания администратора.
    public function createAdmin(): View
    {
        // Проверяет права доступа.
        Gate::authorize('createAdmin', User::class);

        // Возвращает представление (html) с формой создания администратора.
        return view('admin.admin_form');
    }

    // Функция для сохранения администратора.
    // Принимает данные из формы создания администратора.
    public function storeAdmin(Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('createAdmin', User::class);

        // Используем фабрику для создания администратора.
        // Фабрика создает пользователя с ролью администратора.
        $admin = User::factory()->admin();

        // Сохраняем данные администратора.
        $this->storeUser($request, $admin);

        // Перенаправляем на страницу со списком администраторов.
        return redirect()->route('admin.admins.show');
    }

    // Функция для редактирования администратора.
    // Принимает администратора, которого нужно отредактировать.
    public function editAdmin(User $admin): View
    {
        // Проверяет права доступа.
        Gate::authorize('viewAdmin', $admin);

        // Возвращает представление (html) с формой редактирования администратора.
        return view('admin.admin_form', ['admin' => $admin]);
    }

    // Функция для обновления администратора.
    // Принимает администратора, которого нужно обновить и данные из формы.
    public function updateAdmin(User $admin, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('updateAdmin', $admin);

        // Обновляет данные администратора.
        $this->updateUser($admin, $request);

        // Перенаправляет на страницу со списком администраторов.
        return redirect()->route('admin.admins.show');
    }

    // Функция для удаления администратора.
    // Принимает администратора, которого нужно удалить.
    public function deleteAdmin(User $admin): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('deleteAdmin', $admin);

        // Удаляет администратора из базы данных.
        $admin->delete();

        // Перенаправляет на страницу со списком администраторов.
        return redirect()->route('admin.admins.show');
    }

    // Функция для создания раздела модуля.
    // Принимает модуль, к которому нужно добавить раздел.
    public function createModulePart(Module $module): View
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePart::class);

        // Возвращает представление (html) с формой создания раздела модуля.
        return view('admin.modulepart_form', ['module' => $module]);
    }

    // Функция для сохранения раздела модуля.
    // Принимает модуль, к которому нужно добавить раздел и данные из формы.
    public function storeModulePart(Module $module, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePart::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        // Создает раздел модуля в базе данных.
        // Фабрика создает раздел модуля с порядковым номером на 1 больше, чем у последнего раздела модуля.
        ModulePart::factory()
            // Привязывает раздел к модулю.
            ->forModule($module)
            ->withOrder($module->parts()->max('order') + 1)
            ->create($validated);

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для редактирования раздела модуля.
    // Принимает модуль и раздел, который нужно отредактировать.
    public function editModulePart(Module $module, ModulePart $part): View
    {
        // Проверяет права доступа.
        Gate::authorize('update', $part);

        // Возвращает представление (html) с формой редактирования раздела модуля.
        return view('admin.modulepart_form', [
            'module' => $module,
            'part' => $part,
            // Передает в представление список вопросов раздела.
            'questions' => $part->questions,
        ]);
    }

    // Функция для обновления раздела модуля.
    // Принимает модуль, раздел и данные из формы.
    public function updateModulePart(Module $module, ModulePart $part, Request $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('update', $part);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'time_limit' => ['required', 'integer', 'min:1'],
            'date_limit' => ['required', 'date'],
        ]);

        // Обновляет данные раздела модуля.
        $part->fill($validated);

        // Сохраняет изменения в базе данных.
        $part->save();

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для удаления раздела модуля.
    // Принимает модуль и раздел, который нужно удалить.
    public function deleteModulePart(Module $module, ModulePart $part): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('delete', $part);

        // Удаляет раздел модуля из базы данных.
        $part->delete();

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для повышения порядка раздела модуля.
    // Принимает модуль и раздел, порядок которого нужно повысить.
    public function raiseOrderModulePart(Module $module, ModulePart $part): RedirectResponse
    {
        // Повышает порядок раздела модуля.
        $this->raiseOrder($module->partsUnordered(), $part);

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для понижения порядка раздела модуля.
    // Принимает модуль и раздел, порядок которого нужно понизить.
    public function reduceOrderModulePart(Module $module, ModulePart $part): RedirectResponse
    {
        // Понижает порядок раздела модуля.
        $this->reduceOrder($module->partsUnordered(), $part);

        // Перенаправляет на страницу редактирования модуля.
        return redirect()->route('admin.modules.edit', $module);
    }

    // Функция для создания вопроса раздела модуля.
    // Принимает модуль и раздел, к которому нужно добавить вопрос.
    public function createQuestion(Module $module, ModulePart $part): View
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePartQuestion::class);

        // Возвращает представление (html) с формой создания вопроса раздела модуля.
        return view('admin.modulepartquestion_form', [
            'module' => $module,
            'part' => $part,
        ]);
    }

    // Функция для сохранения вопроса раздела модуля.
    // Принимает модуль, раздел и данные из формы.
    public function storeQuestion(Module     $module,
                                  ModulePart $part,
                                  Request    $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePartQuestion::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);

        // Создает вопрос раздела модуля в базе данных.
        // Фабрика создает вопрос раздела модуля с порядковым номером на 1 больше, чем у последнего вопроса раздела модуля.
        ModulePartQuestion::factory()
            // Привязывает вопрос к разделу модуля.
            ->forModulePart($part)
            ->withOrder($part->questions()->max('order') + 1)
            // Устанавливает тип вопроса "Один вариант ответа".
            ->withType(ModulePartQuestion::TYPE_SINGLE_CHOICE)
            ->create($validated);

        // Перенаправляет на страницу редактирования раздела модуля.
        return redirect()->route('admin.modules.parts.edit', [$module, $part]);
    }

    // Функция для редактирования вопроса раздела модуля.
    // Принимает модуль, раздел и вопрос, который нужно отредактировать.
    public function editQuestion(Module             $module,
                                 ModulePart         $part,
                                 ModulePartQuestion $question): View
    {
        // Проверяет права доступа.
        Gate::authorize('update', $question);

        // Возвращает представление (html) с формой редактирования вопроса раздела модуля.
        return view('admin.modulepartquestion_form', [
            'module' => $module,
            'part' => $part,
            'question' => $question,
            // Передает в представление список ответов на вопрос.
            'answers' => $question->answers,
        ]);
    }

    // Функция для обновления вопроса раздела модуля.
    // Принимает модуль, раздел, вопрос и данные из формы.
    public function updateQuestion(Module             $module,
                                   ModulePart         $part,
                                   ModulePartQuestion $question,
                                   Request            $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('update', $question);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);

        // Обновляет данные вопроса раздела модуля.
        $question->fill($validated);

        // Сохраняет изменения в базе данных.
        $question->save();

        // Перенаправляет на страницу редактирования раздела модуля.
        return redirect()->route('admin.modules.parts.edit', [$module, $part]);
    }

    // Функция для удаления вопроса раздела модуля.
    // Принимает модуль, раздел и вопрос, который нужно удалить.
    public function deleteQuestion(Module             $module,
                                   ModulePart         $part,
                                   ModulePartQuestion $question): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('delete', $question);

        // Удаляет вопрос раздела модуля из базы данных.
        $question->delete();

        // Перенаправляет на страницу редактирования раздела модуля.
        return redirect()->route('admin.modules.parts.edit', [$module, $part]);
    }

    // Функция для повышения порядка вопроса раздела модуля.
    // Принимает модуль, раздел и вопрос, порядок которого нужно повысить.
    public function raiseOrderQuestion(Module             $module,
                                       ModulePart         $part,
                                       ModulePartQuestion $question): RedirectResponse
    {
        // Повышает порядок вопроса раздела модуля.
        $this->raiseOrder($part->questionsUnordered(), $question);

        // Перенаправляет на страницу редактирования раздела модуля.
        return redirect()->route('admin.modules.parts.edit', [$module, $part]);
    }

    // Функция для понижения порядка вопроса раздела модуля.
    // Принимает модуль, раздел и вопрос, порядок которого нужно понизить.
    public function reduceOrderQuestion(Module             $module,
                                        ModulePart         $part,
                                        ModulePartQuestion $question): RedirectResponse
    {
        // Понижает порядок вопроса раздела модуля.
        $this->reduceOrder($part->questionsUnordered(), $question);

        // Перенаправляет на страницу редактирования раздела модуля.
        return redirect()->route('admin.modules.parts.edit', [$module, $part]);
    }

    // Функция для создания ответа на вопрос раздела модуля.
    // Принимает модуль, раздел и вопрос, к которому нужно добавить ответ.
    public function createAnswer(Module             $module,
                                 ModulePart         $part,
                                 ModulePartQuestion $question): View
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePartQuestionAnswer::class);

        // Возвращает представление (html) с формой создания ответа на вопрос раздела модуля.
        return view('admin.modulepartquestionanswer_form', [
            'module' => $module,
            'part' => $part,
            'question' => $question,
        ]);
    }

    // Функция для сохранения ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и данные из формы.
    public function storeAnswer(Module             $module,
                                ModulePart         $part,
                                ModulePartQuestion $question,
                                Request            $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('create', ModulePartQuestionAnswer::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);

        // Создает ответ на вопрос раздела модуля в базе данных.
        // Фабрика создает ответ на вопрос раздела модуля с порядковым номером на 1 больше,
        // чем у последнего ответа на вопрос раздела модуля.
        ModulePartQuestionAnswer::factory()
            // Привязывает ответ к вопросу раздела модуля.
            ->forQuestion($question)
            ->withOrder($question->answers()->max('order') + 1)
            // Устанавливает, что ответ является правильным, если вопрос не имеет ответов.
            ->withCorrect($question->answers->isEmpty())
            ->create($validated);

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для редактирования ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и ответ, который нужно отредактировать.
    public function editAnswer(Module             $module,
                               ModulePart         $part,
                               ModulePartQuestion $question,
                               ModulePartQuestionAnswer $answer): View
    {
        // Проверяет права доступа.
        Gate::authorize('update', $answer);

        // Возвращает представление (html) с формой редактирования ответа на вопрос раздела модуля.
        return view('admin.modulepartquestionanswer_form', [
            'module' => $module,
            'part' => $part,
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    // Функция для обновления ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос, ответ и данные из формы.
    public function updateAnswer(Module             $module,
                                 ModulePart         $part,
                                 ModulePartQuestion $question,
                                 ModulePartQuestionAnswer $answer,
                                 Request            $request): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('update', $answer);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);

        // Обновляет данные ответа на вопрос раздела модуля.
        $answer->fill($validated);

        // Сохраняет изменения в базе данных.
        $answer->save();

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для удаления ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и ответ, который нужно удалить.
    public function deleteAnswer(Module             $module,
                                 ModulePart         $part,
                                 ModulePartQuestion $question,
                                 ModulePartQuestionAnswer $answer): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('delete', $answer);

        // Удаляет ответ на вопрос раздела модуля из базы данных.
        $answer->delete();

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для повышения порядка ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и ответ, порядок которого нужно повысить.
    public function raiseOrderAnswer(Module             $module,
                                     ModulePart         $part,
                                     ModulePartQuestion $question,
                                     ModulePartQuestionAnswer $answer): RedirectResponse
    {
        // Повышает порядок ответа на вопрос раздела модуля.
        $this->raiseOrder($question->answersUnordered(), $answer);

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для понижения порядка ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и ответ, порядок которого нужно понизить.
    public function reduceOrderAnswer(Module             $module,
                                      ModulePart         $part,
                                      ModulePartQuestion $question,
                                      ModulePartQuestionAnswer $answer): RedirectResponse
    {
        // Понижает порядок ответа на вопрос раздела модуля.
        $this->reduceOrder($question->answersUnordered(), $answer);

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для установки правильного ответа на вопрос раздела модуля.
    // Принимает модуль, раздел, вопрос и ответ, который нужно установить как правильный.
    public function setIsCorrectAnswer(Module             $module,
                                       ModulePart         $part,
                                       ModulePartQuestion $question,
                                       ModulePartQuestionAnswer $answer): RedirectResponse
    {
        // Проверяет права доступа.
        Gate::authorize('update', $answer);

        // Проходит по всем ответам на вопрос раздела модуля.
        $question->answers->each(function (ModulePartQuestionAnswer $a) use ($answer) {
            // Устанавливает, что ответ является правильным, если он равен переданному ответу.
            $a->is_correct = $a->is($answer);
            // Сохраняет изменения в базе данных.
            $a->save();
        });

        // Перенаправляет на страницу редактирования вопроса раздела модуля.
        return redirect()->route('admin.modules.parts.questions.edit', [$module, $part, $question]);
    }

    // Функция для отображения списка групп студентов.
    public function showStudentGroups(): View {
        // Проверяет права доступа.
        Gate::authorize('viewAny', StudentGroup::class);

        // Возвращает представление (html) со списком групп студентов.
        return view('admin.studentgroups', ['groups' => StudentGroup::all()]);
    }

    // Функция для создания группы студентов.
    public function createStudentGroup(): View {
        // Проверяет права доступа.
        Gate::authorize('create', StudentGroup::class);

        // Возвращает представление (html) с формой создания группы студентов.
        return view('admin.studentgroup_form');
    }

    // Функция для сохранения группы студентов.
    // Принимает данные из формы создания группы студентов.
    public function storeStudentGroup(Request $request): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('create', StudentGroup::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Создает группу студентов в базе данных.
        StudentGroup::factory()->create($validated);

        // Перенаправляет на страницу со списком групп студентов.
        return redirect()->route('admin.studentgroups.show');
    }

    // Функция для редактирования группы студентов.
    // Принимает группу студентов, которую нужно отредактировать.
    public function editStudentGroup(StudentGroup $group): View {
        // Проверяет права доступа.
        Gate::authorize('update', $group);

        // Возвращает представление (html) с формой редактирования группы студентов.
        return view('admin.studentgroup_form', ['group' => $group]);
    }

    // Функция для обновления группы студентов.
    // Принимает группу студентов, которую нужно обновить и данные из формы.
    public function updateStudentGroup(StudentGroup $group, Request $request): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('update', $group);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Обновляет данные группы студентов.
        $group->fill($validated);

        // Сохраняет изменения в базе данных.
        $group->save();

        // Перенаправляет на страницу со списком групп студентов.
        return redirect()->route('admin.studentgroups.show');
    }

    // Функция для удаления группы студентов.
    // Принимает группу студентов, которую нужно удалить.
    public function deleteStudentGroup(StudentGroup $group): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('delete', $group);

        // Удаляет группу студентов из базы данных.
        $group->delete();

        // Перенаправляет на страницу со списком групп студентов.
        return redirect()->route('admin.studentgroups.show');
    }

    // Функция для сохранения пользователя.
    // Принимает данные из формы создания пользователя и фабрику для создания пользователя.
    // Фабрика создает пользователя с ролью студента, преподавателя или администратора.
    private function storeUser(Request $request, UserFactory $factory): void
    {
        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],

        ]);

        if ($request->has('student_group_id')) {
            $validated['student_group_id'] = $request->validate([
                'student_group_id' => ['required', 'exists:student_groups,id'],
            ])['student_group_id'];
        }
        if ($request->has('teacher_id')) {
            $validated['teacher_id'] = $request->validate([
                'teacher_id' => ['required', 'exists:users,id']
            ])['teacher_id'];
        }

        // Создает пользователя в базе данных.
        $factory->create($validated);
    }

    // Функция для отображения списка новостей.
    public function showArticles(): View {
        // Проверяет права доступа.
        Gate::authorize('viewAny', Article::class);

        // Возвращает представление (html) со списком новостей.
        return view('admin.news', ['news' => Article::all()]);
    }

    // Функция для создания новости.
    public function createArticle(): View {
        // Проверяет права доступа.
        Gate::authorize('create', Article::class);

        // Возвращает представление (html) с формой создания новости.
        return view('admin.newsform');
    }

    // Функция для сохранения новости.
    // Принимает данные из формы создания новости.
    public function storeArticle(Request $request): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('create', Article::class);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'picture' => ['required', 'file'],
        ]);

        // Сохраняет картинку новости.
        $validated['picture'] = $validated['picture']->store('news');

        // Создает новость в базе данных.
        Article::create($validated);

        // Перенаправляет на страницу со списком новостей.
        return redirect()->route('admin.news.show');
    }

    // Функция для редактирования новости.
    // Принимает новость, которую нужно отредактировать.
    public function editArticle(Article $article): View {
        // Проверяет права доступа.
        Gate::authorize('update', $article);

        // Возвращает представление (html) с формой редактирования новости.
        return view('admin.newsform', ['article' => $article]);
    }

    // Функция для обновления новости.
    // Принимает новость, которую нужно обновить и данные из формы.
    public function updateArticle(Article $article, Request $request): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('update', $article);

        // Проверяет валидность данных из формы.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        // Если в форме была загружена новая картинка, то сохраняет ее.
        if ($request->has('picture')) {
            $validated['picture'] = $request
                ->validate(['picture' => ['required', 'file']])['picture']
                ->store('news');
        }

        // Обновляет данные новости.
        $article->fill($validated);

        // Сохраняет изменения в базе данных.
        $article->save();

        // Перенаправляет на страницу со списком новостей.
        return redirect()->route('admin.news.show');
    }

    // Функция для удаления новости.
    // Принимает новость, которую нужно удалить.
    public function deleteArticle(Article $article): RedirectResponse {
        // Проверяет права доступа.
        Gate::authorize('delete', $article);

        // Удаляет новость из базы данных.
        $article->delete();

        // Перенаправляет на страницу со списком новостей.
        return redirect()->route('admin.news.show');
    }

    // Функция для обновления данных пользователя.
    // Принимает пользователя, которого нужно обновить и данные из формы.
    private function updateUser(User $user, Request $request): void
    {
        // Если пользователь является студентом с самообучением, то обновляются только имя и email.
        if ($user->isSelfStudent()) {
            // Проверяет валидность данных из формы.
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
            ]);
        } else {
            // Проверяет валидность данных из формы.
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'student_group_id' => ['required', 'exists:student_groups,id'],
                'teacher_id' => ['required', 'exists:users,id'],
            ]);
        }

        // Обновляет данные пользователя.
        $user->fill($validated);

        // Если в форме был введен новый пароль, то обновляет его.
        if ($request->get('password') != '') {
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:8'],
            ]);
            $user->forceFill($validated);
        }

        // Сохраняет изменения в базе данных.
        $user->save();
    }

    // Функция для повышения порядка модели.
    // Принимает коллекцию моделей и модель, порядок которой нужно повысить.
    private function raiseOrder(HasMany $models, Model $model): void
    {
        // Проверяет права доступа.
        Gate::authorize('update', $model);

        // Находит следующую модель в коллекции.
        $nextModel = $models->where('order', '>', $model->order)->orderBy('order')->first();
        if (!$nextModel) {
            // Если следующей модели нет, то бросает исключение (ошибка).
            throw new Exception('Cannot raise order of last ' . $model::class);
        }

        // Проверяет права доступа.
        Gate::authorize('update', $nextModel);

        // Меняет местами порядковые номера моделей.
        $order = $model->order;
        $model->order = $nextModel->order;
        $nextModel->order = $order;

        // Сохраняет изменения в базе данных для обеих моделей в одной транзакции.
        // Если произойдет ошибка, то изменения не сохранятся.
        DB::transaction(function () use ($model, $nextModel) {
            $model->save();
            $nextModel->save();
        });
    }

    // Функция для понижения порядка модели.
    // Принимает коллекцию моделей и модель, порядок которой нужно понизить.
    private function reduceOrder(HasMany $models, Model $model): void
    {
        // Проверяет права доступа.
        Gate::authorize('update', $model);

        // Находит предыдущую модель в коллекции.
        $previousModel = $models->where('order', '<', $model->order)->orderByDesc('order')->first();
        if (!$previousModel) {
            // Если предыдущей модели нет, то бросает исключение (ошибка).
            throw new Exception('Cannot reduce order of first ' . $model::class);
        }

        // Проверяет права доступа.
        Gate::authorize('update', $previousModel);

        // Меняет местами порядковые номера моделей.
        $order = $model->order;
        $model->order = $previousModel->order;
        $previousModel->order = $order;

        // Сохраняет изменения в базе данных для обеих моделей в одной транзакции.
        // Если произойдет ошибка, то изменения не сохранятся.
        DB::transaction(function () use ($model, $previousModel) {
            $model->save();
            $previousModel->save();
        });
    }
}
