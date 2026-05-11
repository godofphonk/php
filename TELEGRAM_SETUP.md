# Настройка Telegram уведомлений для CI/CD

## Шаг 1: Создание Telegram бота

1. Откройте Telegram и найдите бота **@BotFather**
2. Отправьте команду `/newbot`
3. Введите имя бота (например: `My Laravel CI Bot`)
4. Введите username бота (например: `my_laravel_ci_bot`)
5. BotFather пришлет вам **токен бота** - сохраните его!
   
   Пример токена: `1234567890:ABCdefGHIjklMNOpqrsTUVwxyz`

## Шаг 2: Получение Chat ID

### Вариант 1: Личные сообщения (для себя)

1. Найдите вашего бота в Telegram по username
2. Нажмите **Start** или отправьте любое сообщение
3. Откройте в браузере:
   ```
   https://api.telegram.org/bot<ВАШ_ТОКЕН>/getUpdates
   ```
   Замените `<ВАШ_ТОКЕН>` на токен из Шага 1
4. Найдите в ответе `"chat":{"id":123456789}`
5. Это ваш **Chat ID** (число, может быть отрицательным)

### Вариант 2: Группа (для команды)

1. Создайте группу в Telegram
2. Добавьте вашего бота в группу
3. Сделайте бота администратором группы
4. Отправьте любое сообщение в группу
5. Откройте в браузере:
   ```
   https://api.telegram.org/bot<ВАШ_ТОКЕН>/getUpdates
   ```
6. Найдите `"chat":{"id":-123456789}` (обычно отрицательное число для групп)
7. Это **Chat ID** вашей группы

## Шаг 3: Добавление секретов в GitHub

1. Перейдите в ваш репозиторий на GitHub
2. Откройте **Settings** → **Secrets and variables** → **Actions**
3. Нажмите **New repository secret**
4. Добавьте два секрета:

   **Секрет 1:**
   - Name: `TELEGRAM_BOT_TOKEN`
   - Value: `1234567890:ABCdefGHIjklMNOpqrsTUVwxyz` (ваш токен)

   **Секрет 2:**
   - Name: `TELEGRAM_CHAT_ID`
   - Value: `123456789` (ваш Chat ID)

## Шаг 4: Проверка

1. Сделайте коммит и push в любую ветку (master, development, uat)
2. Пайплайн запустится автоматически
3. После завершения вы получите сообщение в Telegram!

## Пример сообщения

```
✅ SUCCESS

Repository: godofphonk/php
Branch: master
Commit: abc123def456
Author: godofphonk

All pipeline steps completed successfully!

📊 Pipeline Results:
• Tests: success
• Static Analysis: success
• Linting: success
• Deploy: success

🔗 Details: https://github.com/godofphonk/php/actions/runs/12345
```

## Troubleshooting

### Бот не отправляет сообщения

1. Проверьте, что токен и Chat ID правильные
2. Убедитесь, что вы нажали **Start** в боте (для личных сообщений)
3. Убедитесь, что бот - администратор группы (для групповых чатов)
4. Проверьте секреты в GitHub Settings

### Как найти Chat ID проще

Используйте бота **@userinfobot**:
1. Найдите @userinfobot в Telegram
2. Отправьте ему `/start`
3. Он покажет ваш Chat ID

Для группы:
1. Добавьте @userinfobot в группу
2. Он покажет ID группы

## Дополнительно: Несколько получателей

Чтобы отправлять уведомления нескольким maintainers:

1. Создайте группу в Telegram
2. Добавьте всех maintainers в группу
3. Добавьте бота в группу и сделайте его администратором
4. Используйте Chat ID группы в секретах

---

**Готово!** Теперь все maintainers будут получать уведомления о результатах пайплайна в Telegram! 🎉
