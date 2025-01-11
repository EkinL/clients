# Symfony Project with Webpack Encore and Docker

This is a Symfony project configured with Webpack Encore for asset management and Docker for containerization.

## Prerequisites

- Docker
- Docker Compose
- Node.js
- npm or yarn

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-repo/symfony-webpack-docker.git
    cd symfony-webpack-docker
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies:**

    ```bash
    npm install
    # or
    yarn install
    ```

4. **Build assets:**

    ```sh
    npm run dev
    # or
    yarn dev
    ```

5. **Start Docker containers:**

    ```sh
    docker-compose up -d
    ```

6. **Run database migrations:**

    ```sh
    docker-compose exec php bin/console doctrine:migrations:migrate
    ```

## Usage

- Access the application at `http://localhost`
- Webpack Encore assets are located in the  directory and compiled to 

## Development

- **Watch for changes and recompile assets:**

    ```sh
    npm run watch
    # or
    yarn watch
    ```

- **Run tests:**

    ```sh
    docker-compose exec php bin/phpunit
    ```

## Configuration

- **Environment variables:**

    Copy  to `.env.local` and adjust the settings as needed.

- **Webpack Encore:**

    Configuration is located in .

- **Docker:**

    Configuration is located in .

## Contributing

@EkinL

## License

This project is licensed under the MIT License.