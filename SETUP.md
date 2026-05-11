# Инструкции по настройке CI/CD пайплайна

## Шаг 1: Создание GitHub репозитория

1. Создайте новый репозиторий на GitHub:
   ```bash
   # Инициализируйте git, если еще не сделали
   git init
   
   # Добавьте все файлы
   git add .
   
   # Сделайте первый коммит
   git commit -m "Initial commit with CI/CD pipeline"
   
   # Создайте репозиторий на GitHub и добавьте remote
   git remote add origin https://github.com/ваш-username/ваш-репозиторий.git
   
   # Отправьте код
   git push -u origin main
   ```

## Шаг 2: Создание веток

Создайте долгоживущие ветки для разных окружений:

```bash
# Создание ветки development
git checkout -b development
git push origin development

# Создание ветки uat
git checkout -b uat
git push origin uat

# Вернитесь на main
git checkout main
```

## Шаг 3: Настройка GitHub Environments для Production

Для ручного аппрува деплоя в production:

1. Перейдите в **Settings** вашего репозитория
2. Выберите **Environments** в левом меню
3. Нажмите **New environment**
4. Введите имя: `production`
5. Нажмите **Configure environment**
6. Включите **Required reviewers**
7. Добавьте себя и других maintainers как reviewers
8. Нажмите **Save protection rules**

## Шаг 4: Настройка Branch Protection (рекомендуется)

Защитите основные ветки от прямых push:

1. Перейдите в **Settings** → **Branches**
2. Нажмите **Add branch protection rule**
3. В **Branch name pattern** введите: `main`
4. Включите:
   - ✅ **Require a pull request before merging**
   - ✅ **Require status checks to pass before merging**
   - Выберите: `Tests`, `Static Analysis (PHPStan)`, `Code Linting (Laravel Pint)`
   - ✅ **Require branches to be up to date before merging**
5. Нажмите **Create**
6. Повторите для веток `development` и `uat`

## Шаг 5: Проверка пайплайна

### Тест 1: Успешный пайплайн

```bash
# Внесите небольшое изменение
echo "# Test" >> README.md
git add README.md
git commit -m "Test: successful pipeline"
git push origin development
```

Перейдите в **Actions** на GitHub и убедитесь, что все шаги проходят успешно.

### Тест 2: Нарушение в тестах

Создайте падающий тест:

```bash
# Откройте tests/Unit/ExampleTest.php и измените
# $this->assertTrue(true); на $this->assertTrue(false);

git add tests/Unit/ExampleTest.php
git commit -m "Test: failing test"
git push origin development
```

Пайплайн должен завершиться с ошибкой на шаге **Tests**.

### Тест 3: Нарушение в linter

Добавьте код с нарушением стиля:

```bash
# Откройте любой файл в app/ и добавьте лишние пробелы или нарушите форматирование

git add .
git commit -m "Test: linting violation"
git push origin development
```

Пайплайн должен завершиться с ошибкой на шаге **Code Linting**.

**Важно**: После тестов верните изменения обратно!

## Шаг 6: Тестирование деплоя в разные окружения

### Development
```bash
git checkout development
# Внесите изменения
git add .
git commit -m "Deploy to development"
git push origin development
```

Пайплайн должен использовать `.env.dev`

### UAT
```bash
git checkout uat
git merge development
git push origin uat
```

Пайплайн должен использовать `.env.uat`

### Production (с ручным аппрувом)
```bash
git checkout main
git merge uat
git push origin main
```

1. Пайплайн остановится перед шагом **Deploy Simulation**
2. Перейдите в **Actions** → выберите запущенный workflow
3. Нажмите **Review deployments**
4. Выберите `production` и нажмите **Approve and deploy**
5. Пайплайн продолжит выполнение с `.env.prod`

## Шаг 7: Локальная разработка

Перед push всегда проверяйте локально:

```bash
# Запуск тестов
php artisan test --no-coverage

# Проверка покрытия (требует Xdebug или PCOV)
vendor/bin/phpunit --coverage-text

# Статический анализ
vendor/bin/phpstan analyse

# Проверка стиля кода
vendor/bin/pint --test

# Автоисправление стиля
vendor/bin/pint
```

## Шаг 8: Получение скриншотов для проверки

### Скриншот 1: Успешный пайплайн
1. Перейдите в **Actions**
2. Выберите успешный workflow run
3. Сделайте скриншот с зелеными галочками для всех jobs

### Скриншот 2: Нарушение в тестах
1. Создайте падающий тест (см. Тест 2 выше)
2. Перейдите в **Actions**
3. Выберите failed workflow
4. Сделайте скриншот с красным крестиком на шаге **Tests**

### Скриншот 3: Нарушение в linter
1. Добавьте код с нарушением стиля (см. Тест 3 выше)
2. Перейдите в **Actions**
3. Выберите failed workflow
4. Сделайте скриншот с красным крестиком на шаге **Code Linting**

## Структура файлов для проверки

Убедитесь, что в репозитории присутствуют:

```
.
├── .github/
│   └── workflows/
│       └── ci.yml                    # GitHub Actions workflow
├── .env.dev                          # Development окружение
├── .env.uat                          # UAT окружение
├── .env.prod                         # Production окружение
├── .env.ci                           # CI окружение
├── .gitignore                        # С исключениями для .env файлов
├── phpstan.neon                      # Конфигурация PHPStan
├── phpunit.xml                       # Конфигурация PHPUnit с покрытием
├── PIPELINE.md                       # Документация пайплайна
└── tests/                            # Тесты с покрытием ≥50%
    ├── Feature/
    │   ├── CategoryTest.php
    │   ├── MasterclassTest.php
    │   └── RegistrationTest.php
    └── Unit/
        ├── CategoryModelTest.php
        ├── MasterclassModelTest.php
        ├── RegistrationModelTest.php
        └── UserModelTest.php
```

## Дополнительно: Настройка уведомлений (опционально)

Для настройки уведомлений в Slack, Telegram или Email, см. раздел "Дополнительные возможности" в `@/home/gospodin/Desktop/php/9/PIPELINE.md`.

## Troubleshooting

### Пайплайн не запускается
- Проверьте, что файл `.github/workflows/ci.yml` существует
- Убедитесь, что вы push в правильную ветку (main, development, uat)

### Тесты падают в CI, но работают локально
- Проверьте, что используется `.env.ci`
- Убедитесь, что все миграции применяются

### PHPStan ошибки
- Запустите локально: `vendor/bin/phpstan analyse`
- Исправьте типизацию или понизьте уровень в `phpstan.neon`

### Покрытие не достигает 50%
- Добавьте больше тестов
- Проверьте отчет: `vendor/bin/phpunit --coverage-html coverage-html`
- Откройте `coverage-html/index.html` в браузере

## Полезные команды

```bash
# Проверка статуса пайплайна через GitHub CLI
gh run list

# Просмотр логов последнего run
gh run view --log

# Запуск workflow вручную (если настроено)
gh workflow run ci.yml
```

---

**Готово!** Ваш CI/CD пайплайн настроен и готов к использованию.
