### Логика работы с таблицей users

Сервис разделен на 3 слоя (вторая вариация архитектуры, когда мы в рамках отдельного модуля заворачиваем отдельно слои. Т.е вместо Domain -> User, Infrastructure -> Repository -> User мы работаем так User -> Domain, User -> Infrastructure, разделяя логику на модули): 

* Domain - основная логика работы (конфигурацию доктрины из анатоций можно перенести в конфиг, чтобы мы не были завязанны на доктрину по коду)

* Infrastructure - Симфонийская логика, реализация интефейса репозитория для работы с бд

* Representation - тут можно разделить разные представления Api, html

Основная логика работы с записью и обновлением Юзера лежит в репозитории UserRepository. Как предлагает DDD. В целом можно было вынести это в отдельные сервисы кейсы и делать каждую операцию отдельной командой.
Также чтобы был еще один уровень прослойки для различных декораторов ответа. Можно создать UserManager, который уже будет дергаться в верхних слоях.

Для журналирования создал отдельное синхронное событие UserUpdated. В целом этот хендлер может пригодится не только для журналирования, но и для других обработчиков, которые должны запускаться после обновления юзера.
Похожее событие можно создать и для создания юзера. В рамках задачи не пригодилось, поэтому оно не было добавлено. В случае тяжелых обработок событие можно переключить на асинхронную отправку через брокеры сообщений.

Валидация выполнена библиотекой симфони валидатор, но в целом может использоваться любая другая. 

Написано по одному интеграционному и юнит тесту для демонстрации. 
