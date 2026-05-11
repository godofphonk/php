# CI/CD Pipeline Documentation

## Обзор

Этот документ описывает CI/CD пайплайн для Laravel приложения, настроенный с использованием GitHub Actions.

## Триггеры пайплайна

Пайплайн запускается при:
- **Push** в ветки: `main`, `master`, `development`, `dev`, `develop`, `uat`, `qa`
- **Pull Request** в эти же ветки

## Структура пайплайна

Пайплайн состоит из 5 основных jobs, выполняющихся последовательно:

### 1. Tests (Тестирование)

**Цель**: Запуск всех тестов и проверка покрытия кода

**Шаги**:
1. Checkout кода из репозитория
2. Установка PHP 8.3 с необходимыми расширениями и Xdebug для покрытия
3. Копирование `.env.ci` как `.env` для тестового окружения
4. Установка зависимостей через Composer
5. Генерация ключа приложения
6. Запуск PHPUnit с генерацией отчета о покрытии
7. Проверка порога покрытия (минимум 50%)

**Gate условия**:
- ❌ Пайплайн завершается с ошибкой, если:
  - Любой тест провалился
  - Покрытие кода < 50%

**Используемая конфигурация**: `.env.ci`
- База данных: SQLite in-memory
- Debug режим: отключен
- Окружение: testing

---

### 2. Static Analysis (Статический анализ)

**Цель**: Проверка кода на ошибки с помощью PHPStan/Larastan

**Зависимости**: Выполняется после успешного завершения `tests`

**Шаги**:
1. Checkout кода
2. Установка PHP 8.3
3. Копирование `.env.ci`
4. Установка зависимостей
5. Генерация ключа приложения
6. Запуск PHPStan с уровнем 5

**Gate условия**:
- ❌ Пайплайн завершается с ошибкой при любой ошибке (не предупреждении) PHPStan

**Конфигурация**: `phpstan.neon`
- Уровень анализа: 5
- Анализируемые директории: `app/`

---

### 3. Linting (Проверка стиля кода)

**Цель**: Проверка соответствия кода стандартам PSR-12/Laravel

**Зависимости**: Выполняется после успешного завершения `tests`

**Шаги**:
1. Checkout кода
2. Установка PHP 8.3
3. Установка зависимостей
4. Запуск Laravel Pint в тестовом режиме (без автоисправления)

**Gate условия**:
- ❌ Пайплайн завершается с ошибкой при любом нарушении правил линтера

**Инструмент**: Laravel Pint
- Пресет: Laravel (включает PSR-12)
- Режим: test (только проверка, без изменений)

---

### 4. Deploy Simulation (Симуляция деплоя)

**Цель**: Симуляция процесса деплоя в соответствующее окружение

**Зависимости**: Выполняется только если все предыдущие jobs (`tests`, `static-analysis`, `linting`) успешны

**Условия запуска**:
- Только для push событий (не для Pull Requests)
- Только для веток: `main`, `master`, `development`, `dev`, `develop`, `uat`, `qa`

**Маппинг веток на окружения**:
- `main` / `master` → **PRODUCTION** (`.env.prod`)
- `uat` / `qa` → **UAT** (`.env.uat`)
- `development` / `dev` / `develop` → **DEVELOPMENT** (`.env.dev`)

**Шаги**:
1. Checkout кода
2. Определение целевого окружения на основе ветки
3. Копирование соответствующего `.env` файла
4. Вывод сообщения о деплое
5. Отображение первых 5 строк `.env` для верификации

**Дополнительная защита для production**:
- Для веток `main`/`master` настроен GitHub Environment `production`
- Требуется ручной аппрув от reviewer перед выполнением деплоя
- Настраивается в Settings → Environments → production → Required reviewers

---

### 5. Notify (Уведомление)

**Цель**: Отправка уведомления о результатах пайплайна

**Зависимости**: Выполняется всегда после всех jobs (даже при ошибках)

**Шаги**:
1. Подготовка статуса на основе результатов всех jobs
2. Вывод детальной информации:
   - Общий статус (SUCCESS/FAILURE)
   - Ветка и коммит
   - Статус каждого job

**Вывод включает**:
- ✅ SUCCESS - все шаги успешны
- ❌ FAILURE - хотя бы один шаг провалился
- Детальный статус каждого job

---

## Конфигурационные файлы окружений

### `.env.dev` (Development)
```
APP_ENV=development
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=dev-db.example.com
```

### `.env.uat` (UAT/QA)
```
APP_ENV=uat
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=uat-db.example.com
```

### `.env.prod` (Production)
```
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=prod-db.example.com
```

### `.env.ci` (CI Pipeline)
```
APP_ENV=testing
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

**Примечание**: `APP_KEY` может быть пустым в файлах окружений, так как генерируется автоматически в пайплайне.

---

## Настройка GitHub Repository

### 1. Создание веток

```bash
git checkout -b development
git push origin development

git checkout -b uat
git push origin uat
```

### 2. Настройка GitHub Environments

Для production деплоя с ручным аппрувом:

1. Перейдите в **Settings** → **Environments**
2. Создайте environment `production`
3. Включите **Required reviewers**
4. Добавьте maintainers как reviewers
5. Сохраните изменения

### 3. Защита веток (рекомендуется)

В **Settings** → **Branches** → **Branch protection rules**:
- Требовать успешное прохождение status checks перед merge
- Требовать review перед merge в `main`/`master`

---

## Локальная проверка перед push

### Запуск тестов с покрытием
```bash
vendor/bin/phpunit --coverage-text
```

### Запуск PHPStan
```bash
vendor/bin/phpstan analyse
```

### Запуск Laravel Pint (проверка)
```bash
vendor/bin/pint --test
```

### Запуск Laravel Pint (автоисправление)
```bash
vendor/bin/pint
```

---

## Troubleshooting

### Тесты падают в CI, но работают локально
- Проверьте, что используется `.env.ci` с SQLite
- Убедитесь, что все миграции применяются корректно
- Проверьте зависимости от внешних сервисов

### Покрытие ниже 50%
- Добавьте больше unit и feature тестов
- Проверьте, что Xdebug включен для генерации покрытия
- Используйте `--coverage-html` для детального анализа

### PHPStan ошибки
- Запустите локально: `vendor/bin/phpstan analyse`
- Проверьте типизацию в коде
- При необходимости добавьте PHPDoc комментарии

### Pint ошибки
- Запустите `vendor/bin/pint` для автоисправления
- Проверьте соответствие PSR-12
- Коммитьте исправленный код

---

## Метрики и мониторинг

### Покрытие кода
- Минимальный порог: **50%**
- Отчеты генерируются в `coverage.xml` и `coverage-html/`
- Текстовый вывод в консоль пайплайна

### Время выполнения
- Tests: ~2-3 минуты
- Static Analysis: ~1-2 минуты
- Linting: ~30 секунд
- Deploy Simulation: ~30 секунд
- **Общее время**: ~5-7 минут

---

## Дополнительные возможности

### Уведомления (опционально)

Для отправки уведомлений maintainers можно добавить:

1. **Slack уведомления**:
```yaml
- name: Slack Notification
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    webhook_url: ${{ secrets.SLACK_WEBHOOK }}
```

2. **Email уведомления**:
```yaml
- name: Send email
  uses: dawidd6/action-send-mail@v3
  with:
    server_address: smtp.gmail.com
    server_port: 465
    username: ${{ secrets.EMAIL_USERNAME }}
    password: ${{ secrets.EMAIL_PASSWORD }}
    subject: Pipeline ${{ job.status }}
    to: maintainers@example.com
```

3. **Telegram уведомления**:
```yaml
- name: Telegram Notification
  uses: appleboy/telegram-action@master
  with:
    to: ${{ secrets.TELEGRAM_TO }}
    token: ${{ secrets.TELEGRAM_TOKEN }}
    message: Pipeline ${{ job.status }} on ${{ github.ref }}
```

---
