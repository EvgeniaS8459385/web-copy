<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage\ChatMessage;
use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\ModuleCompletion\ModulePartCompletion;
use App\Models\News\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    // Функция для отображения всех модулей (главной страницы студента).
    public function showModules(): View
    {
        // Получаем все модули из базы данных.
        $modules = Module::all();

        // Получаем 3 последних новости из базы данных.
        $articles = Article::orderBy('created_at', 'desc')->take(3)->get();

        // Возвращаем представление (html) страницы списка модулей.
        return view('student.modules', [
            'modules' => $modules,
            'articles' => $articles,
        ]);
    }

    // Функция для отображения страницы модуля.
    // Принимает объект модуля, который нужно отобразить.
    public function showModule(Module $module): View
    {
        // Получаем текущего студента.
        $user = $this->user();

        // Получаем модель прогресса модуля для текущего студента.
        $moduleCompletion = $user->moduleCompletion($module);

        // Если студент еще не начал модуль, то отображаем страницу модуля.
        if ($moduleCompletion == null) {
            return view('student.module.module', [
                'module' => $module,
            ]);
        }

        // Если модуль уже пройден, то отображаем страницу успешного прохождения модуля.
        if ($moduleCompletion->isCompleted()) {
            return view('student.module.success', [
                'module' => $module,
            ]);
        }

        // Получаем раздел модуля, который студент сейчас проходит.
        $modulePart = $moduleCompletion->modulePart;

        // Получаем модель прогресса раздела модуля для текущего студента.
        /** @var ModulePartCompletion $modulePartCompletion */
        $modulePartCompletion = $moduleCompletion->modulePartCompletions()
            ->where('module_part_id', $modulePart->id)
            ->first();

        // Если студент прикрепил отчет или студент на самообучении и тест завершен,
        // то отображаем страницу успешного прохождения раздела модуля.
        if (
            $modulePartCompletion->isReportAttached()
            || ($user->isSelfStudent() && $modulePartCompletion->isTestEnded())
        ) {
            return view('student.module.partsuccess', [
                'module' => $module,
                'modulePart' => $modulePart,
                'modulePartCompletion' => $modulePartCompletion,
                'student' => $user,
            ]);
        }

        // Если тест завершен, то отображаем страницу отчета.
        if ($modulePartCompletion->isTestEnded()) {
            return view('student.module.report', [
                'module' => $module,
                'modulePart' => $modulePart,
            ]);
        }

        // Если тест начат, то отображаем страницу теста.
        if ($modulePartCompletion->isTestStarted()) {
            return view('student.module.test', [
                'module' => $module,
                'modulePart' => $modulePart,
                'modulePartCompletion' => $modulePartCompletion,
            ]);
        }

        // Если теория завершена, то отображаем страницу подготовки к тесту.
        if ($modulePartCompletion->isTheoryCompleted()) {
            return view('student.module.testpreparation', [
                'module' => $module,
                'modulePart' => $modulePart,
            ]);
        }

        // Если теория не завершена, то отображаем страницу теории.
        return view('student.module.part', [
            'module' => $module,
            'modulePart' => $modulePart,
        ]);
    }

    // Функция для завершения раздела модуля.
    // Принимает объект модуля, в котором нужно завершить раздел.
    public function completeModulePart(Module $module): RedirectResponse
    {
        // Получаем текущего студента, который проходит модуль
        // и модель прогресса модуля для текущего студента.
        $moduleCompletion = $this->user()->moduleCompletion($module);

        // Получаем раздел модуля, который студент сейчас проходит.
        $modulePart = $moduleCompletion->modulePart;

        // Завершаем раздел модуля.
        $moduleCompletion->completePart($modulePart);

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', $module);
    }

    // Функция для начала модуля.
    // Принимает объект модуля, который нужно начать.
    public function startModule(Module $module): RedirectResponse
    {
        // Получаем текущего студента.
        $student = $this->user();

        // Получаем первый раздел модуля.
        /** @var ModulePart $firstPart */
        $firstPart = $module->parts()->first();

        // Создаем модель прогресса модуля для текущего студента в базе данных.
        $student->moduleCompletions()->create([
            'student_id' => $student->id,
            'module_id' => $module->id,
            'module_part_id' => $firstPart->id,
        ]);

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', [$module]);
    }


    // Функция для завершения теории.
    // Принимает объект модуля, в котором нужно завершить теорию.
    public function completeTheory(Module $module): RedirectResponse
    {
        // Получаем модель прогресса раздела модуля для текущего студента.
        $modulePartCompletion = $this->user()
            ->moduleCompletion($module)
            ->currentModulePartCompletion();

        // Завершаем теорию.
        $modulePartCompletion->completeTheory();

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', $module);
    }

    // Функция для начала теста.
    // Принимает объект модуля, в котором нужно начать тест.
    public function startTest(Module $module): RedirectResponse
    {
        // Получаем модель прогресса раздела модуля для текущего студента.
        $modulePartCompletion = $this->user()
            ->moduleCompletion($module)
            ->currentModulePartCompletion();

        // Начинаем тест.
        $modulePartCompletion->beginTest();

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', $module);
    }

    // Функция для завершения теста.
    // Принимает объект модуля, в котором нужно завершить тест.
    public function endTest(Module $module, Request $request): RedirectResponse
    {
        // Получаем модель прогресса раздела модуля для текущего студента.
        $modulePartCompletion = $this->user()
            ->moduleCompletion($module)
            ->currentModulePartCompletion();

        // Получаем раздел модуля, который студент сейчас проходит.
        $modulePart = $modulePartCompletion->modulePart;

        // Проверяем, что все вопросы теста были отвечены.
        $rules = [];
        foreach ($modulePart->questions as $question) {
            $rules["answers.{$question->id}"] = 'required';
        }
        $request->validate($rules);

        // В транзакции сохраняем ответы на вопросы и завершаем тест (если что-то пойдет не так, то откатываем изменения).
        DB::transaction(function () use ($modulePart, $modulePartCompletion, $request) {
            // Если время на тест истекло, то завершаем тест.
            if ($modulePartCompletion->isTimeOver()) {
                $modulePartCompletion->endTest();
                return;
            }

            // Сохраняем ответы на вопросы.
            foreach ($modulePart->questions as $question) {
                $answer = $question->answers()->find($request->input("answers.{$question->id}"));
                $modulePartCompletion->attachAnswer(
                    $question,
                    $answer,
                );
            }

            // Завершаем тест.
            $modulePartCompletion->endTest();
        });

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', $module);
    }

    // Функция для прикрепления отчета.
    // Принимает объект модуля и данные формы с файлом отчета.
    public function attachReport(Module $module, Request $request): RedirectResponse
    {
        // Проверяем, что отчет был прикреплен.
        $request->validate([
            'report' => 'required|file',
        ]);

        // Получаем модель прогресса раздела модуля для текущего студента.
        $modulePartCompletion = $this->user()
            ->moduleCompletion($module)
            ->currentModulePartCompletion();

        // Прикрепляем отчет.
        $modulePartCompletion->attachReport($request->file('report'));

        // Перенаправляем студента на страницу модуля.
        return redirect()->route('student.modules.module', $module);
    }

    // Функция для отправки сообщения преподавателю.
    // Принимает данные формы с текстом сообщения.
    public function sendMessage(Request $request): RedirectResponse
    {
        // Получаем текущего студента и его преподавателя.
        $student = $this->user();
        $teacher = $student->teacher;

        // Проверяем, что сообщение было отправлено.
        $request->validate([
            'message' => 'required|string',
        ]);

        // Создаем сообщение в базе данных.
        ChatMessage::create([
            'sender_id' => $student->id,
            'receiver_id' => $teacher->id,
            // Текст сообщения из формы.
            'message' => $request->input('message'),
        ]);

        // Перенаправляем студента на предыдущую страницу с сообщением об успешной отправке.
        return redirect()->back()->with('success', 'Сообщение успешно отправлено преподавателю');
    }

    // Функция для отображения новости.
    // Принимает объект новости, которую нужно отобразить.
    public function showArticle(Article $article): View
    {
        // Возвращаем представление (html) страницы новости.
        return view('student.newsarticle', [
            'article' => $article,
        ]);
    }

    // Функция для пометки всех сообщений отправленных студенту как прочитанные.
    public function markMessagesAsRead(): RedirectResponse
    {
        // Получаем текущего студента.
        $student = $this->user();

        // Получаем все сообщения, которые были отправлены студенту.
        $messages = $student->chatMessages()
            ->where('receiver_id', $student->id);

        // Помечаем все сообщения как прочитанные.
        foreach ($messages as $message) {
            $message->markAsRead();
        }

        // Перенаправляем студента на предыдущую страницу.
        return redirect()->back();
    }
}
