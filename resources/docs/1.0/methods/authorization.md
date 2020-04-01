# Авторизация пользователей
Метод: `GET`

URL: `/api/authorize`

Для авторизации достаточно отправить в GET запросе все полученные GET параметры при запуске VK Mini Apps.

Из официальной документации ВКонтакте: ``
vk_user_id=494075&vk_app_id=6736218&vk_is_app_user=1&vk_are_notifications_enabled=1&vk_language=ru&vk_access_token_settings=&vk_platform=android&sign=exTIBPYTrAKDTHLLm2AwJkmcVcvFCzQUNyoa6wAjvW6k
``

По этим данным будет валидироваться хеш токен, по которому и будет производиться дальнейшая авторизация при работе с этим API. 

## Возвращаемый JSON:
| Параметр | Описание |
| ------------ | ------------ |
| status | Статус (`ok`, `error`) |
| registered | Существовал ли до этого пользователь в БД (`true`/`false`) |
| user | [Объект пользователя из БД](/{{route}}/{{version}}/objects/user "Объект пользователя") |
