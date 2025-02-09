# Symfony Games Statistic Management

This project is a web application developed in Symfony using Docker for easy deployment and dependency management. It includes a user authentication system and functionality to handle game entities (e.g. player creation).

## Inroduction

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
3. If you want, upload fixtures to database by running `docker exec -it container_id php bin/console doctrine:fixtures:load`
4. Open Postman json file in Postman and test the API endpoints
5. Run `docker compose down --remove-orphans` to stop and delete the Docker containers.

