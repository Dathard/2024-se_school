# Підготовка до використання

Перед початком використання додатку слід провести його налаштування яке складається із 3-х етапів:
* Встановлення сomposer залежностей
* Налаштування бази даних
* Налаштування автоматичної розсилки листів

## Встановлення сomposer залежностей

Першим кроком є встановлення всіх необхідних залежностей для роботи додатку. Для цього виконайте команду:

```sh
composer install
```

Це дозволить налаштувати автозавантаження і встановити всі необхідні бібліотеки.

## Налаштування бази даних

Для конфігурації додатку потрібно виконати команду `php bin/run install` з відповідними параметрами, зазначеними у таблиці нижче:

| Назва Параметру | Обовʼязковий | Опис                                                |
|-----------------|--------------|-----------------------------------------------------|
| --db-host       | Так          | Повне доменне ім'я або IP-адреса сервера бази даних |
| --db-name       | Так          | Назва бази даних яку ви хочете використовувати      |
| --db-user       | Так          | Ім'я користувача власника бази даних                |
| --db-password   | Так          | Пароль власника бази даних                          |

#### Приклад:
```sh
php bin/run install --db-host=localhost --db-name=mydatabase --db-user=myuser --db-password=mypassword
```

## Налаштування автоматичної розсилки листів з актуальним курсом

Для розсилки листів використовується команда:

```sh
php bin/run send-email-notifications
```

Для того щоб процес відбувався в автоматичному режимі потрібно налаштувати запуск вказаної команди з допомогою крону.

### Налаштування крону

Щоб налаштувати автоматичний запуск команди php `bin/run send-email-notifications`, додайте наступний рядок у ваш файл крону (з допомогою команди `crontab -e`):

```
0 0 * * * php /path/to/your/application/bin/run send-email-notifications
```

Вказана інструкція запускатиме команду щодня опівночі. Переконайтеся, що шлях `/path/to/your/application/` вказує на правильний шлях до вашого додатку.
