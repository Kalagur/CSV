# Для того, чтобы развернуть проект необходимо выполнить следующие действия

1. Установить [Docker и Docker Compose](https://docs.docker.com/compose/install/)

2. Клонировать проект в папку у себя на локальной машине.
> git clone https://github.com/Kalagur/CSV.git

3. Выполнить команду копирования конфигурационного файла
> cp .env.example .env

4. Выполнить команду сборки контейнеров
> docker-compose up -d --build

Если при выполнении выводится сообщение:
***ERROR: Couldn't connect to Docker daemon at http+docker://localunixsocket - is it running?
If it's at a non-standard location, specify the URL with the DOCKER_HOST environment variable.***

