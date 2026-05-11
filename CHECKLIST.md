# Чеклист для проверки CI/CD пайплайна

## ✅ Файлы для сдачи

### 1. Ссылка на репозиторий
- [ ] Репозиторий создан на GitHub/GitLab
- [ ] Код загружен в репозиторий
- [ ] Ветки созданы: `main`, `development`, `uat`

### 2. Файл конфигурации пайплайна
- [x] `.github/workflows/ci.yml` создан
- [x] Содержит все необходимые jobs:
  - [x] Tests
  - [x] Static Analysis (PHPStan)
  - [x] Code Linting (Laravel Pint)
  - [x] Deploy Simulation
  - [x] Notify

### 3. Файлы конфигурации окружений
- [x] `.env.dev` - Development окружение
- [x] `.env.uat` - UAT окружение
- [x] `.env.prod` - Production окружение
- [x] `.env.ci` - CI окружение
- [x] Все обязательные переменные присутствуют:
  - [x] APP_NAME
  - [x] APP_ENV
  - [x] APP_DEBUG
  - [x] APP_URL
  - [x] DB_CONNECTION
  - [x] DB_HOST
  - [x] DB_PORT
  - [x] DB_DATABASE
  - [x] DB_USERNAME
  - [x] DB_PASSWORD
  - [x] APP_KEY (может быть пустым с пометкой)

### 4. PIPELINE.md
- [x] Файл создан
- [x] Содержит описание всех шагов пайплайна
- [x] Описаны триггеры
- [x] Описаны gate условия
- [x] Описана симуляция деплоя
- [x] Описаны конфигурационные файлы

### 5. Скриншоты

#### Скриншот 1: Успешный пайплайн
- [ ] Все jobs зеленые (passed)
- [ ] Видны все 5 jobs: Tests, Static Analysis, Linting, Deploy Simulation, Notify
- [ ] Показан branch и commit

**Как получить:**
```bash
git checkout development
echo "# Success test" >> README.md
git add README.md
git commit -m "Test: successful pipeline"
git push origin development
```
Перейдите в Actions → выберите последний run → сделайте скриншот

#### Скриншот 2: Нарушение в тестах
- [ ] Job "Tests" красный (failed)
- [ ] Видно сообщение об ошибке
- [ ] Показано, что покрытие < 50% или тест провалился

**Как получить:**
```bash
# Временно сломайте тест
# В tests/Unit/ExampleTest.php измените:
# $this->assertTrue(true); → $this->assertTrue(false);

git add tests/Unit/ExampleTest.php
git commit -m "Test: failing test"
git push origin development
```
Перейдите в Actions → выберите failed run → сделайте скриншот

**Не забудьте вернуть изменения!**

#### Скриншот 3: Нарушение в linter
- [ ] Job "Code Linting" красный (failed)
- [ ] Видны нарушения стиля кода
- [ ] Показаны файлы с проблемами

**Как получить:**
```bash
# Временно добавьте нарушение стиля
# В любом файле app/ добавьте лишние пробелы или нарушите форматирование
# Например, в app/Models/User.php добавьте лишние пустые строки

git add app/Models/User.php
git commit -m "Test: linting violation" --no-verify
git push origin development
```
Перейдите в Actions → выберите failed run → сделайте скриншот

**Не забудьте вернуть изменения!**

## ✅ Дополнительные проверки

### Тесты
- [x] Покрытие кода ≥ 50%
- [x] Все тесты проходят локально: `php artisan test --no-coverage`
- [x] Созданы Unit тесты для моделей
- [x] Созданы Feature тесты для контроллеров

### Статический анализ
- [x] PHPStan настроен: `phpstan.neon`
- [x] Анализ проходит без ошибок: `vendor/bin/phpstan analyse`
- [x] Уровень анализа: 4 или выше

### Linting
- [x] Laravel Pint настроен
- [x] Код соответствует стандартам: `vendor/bin/pint --test`
- [x] Используется пресет Laravel/PSR-12

### Конфигурация
- [x] `.gitignore` настроен правильно
- [x] `.env` не попадает в репозиторий
- [x] `.env.dev`, `.env.uat`, `.env.prod`, `.env.ci` в репозитории
- [x] `phpunit.xml` настроен для покрытия

### Пайплайн
- [x] Запускается при push в `main`, `development`, `uat`
- [x] Запускается при Pull Request
- [x] Deploy Simulation использует правильный .env для каждой ветки
- [x] Production требует ручного аппрува (GitHub Environment)
- [x] Notify показывает результаты всех jobs

## 📋 Команды для финальной проверки

```bash
# 1. Проверка тестов
php artisan test --no-coverage

# 2. Проверка PHPStan
vendor/bin/phpstan analyse

# 3. Проверка Pint
vendor/bin/pint --test

# 4. Проверка .gitignore
git status  # .env не должен быть в списке

# 5. Проверка веток
git branch -a

# 6. Проверка конфигурационных файлов
ls -la .env.*
```

## 📦 Структура для сдачи

```
Проект/
├── Ссылка на GitHub/GitLab репозиторий
├── .github/workflows/ci.yml (или .gitlab-ci.yml)
├── .env.dev
├── .env.uat
├── .env.prod
├── .env.ci
├── PIPELINE.md
└── Скриншоты/
    ├── 1-successful-pipeline.png
    ├── 2-test-violation.png
    └── 3-linter-violation.png
```

## ⚠️ Важные замечания

1. **Не коммитьте** основной `.env` файл
2. **Верните изменения** после создания скриншотов с ошибками
3. **Проверьте**, что все тесты проходят перед финальным push
4. **Убедитесь**, что GitHub Environment `production` настроен для ручного аппрува
5. **Проверьте**, что все файлы `.env.*` содержат корректные значения

## 🎯 Критерии успеха

- ✅ Пайплайн запускается автоматически при push
- ✅ Все шаги выполняются последовательно
- ✅ Тесты проверяют покрытие ≥ 50%
- ✅ PHPStan находит ошибки типизации
- ✅ Pint проверяет стиль кода
- ✅ Deploy Simulation использует правильные .env файлы
- ✅ Production требует ручного аппрува
- ✅ Notify отправляет результаты

---

**Готово к сдаче!** ✨
