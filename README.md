# Gateway Service Setup Guide

## Prerequisites

Make sure you have the following installed on your system:

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [PHP](https://www.php.net/) (8.3 or higher)
- [Composer](https://getcomposer.org/)
- [Postman](https://www.postman.com/)
- [DBeaver](https://dbeaver.io/)

## Setup Instructions

1. **Clone repository**
    ```sh
    # Using https
    git clone https://github.com/JAACarvajal/techlint-gateway.git
    ```
2. **Create your `.env` file**
   Copy the example environment file and adjust variables as needed:
   ```sh
   cp .env.example .env
   # Edit .env as required
   ```

3. **To install dependencies, you can choose either of the following methods:**

   - **A. On your local machine:**
     1. Run `composer install` in your project directory
     2. Run:
        ```sh
        docker compose up --build -d
        ```
   
   - **B. Inside the Docker container:**
     1. Run:
        ```sh
        docker compose up --build -d
        ```
     2. Enter the `app-techlint-gateway` container (via Docker CLI or Docker Desktop)
     3. Inside the container, run:
        ```sh
        composer install
        ```

4. **Generate Laravel application key**
   ```sh
   # Go inside the app-techlint-gateway container then run:
   php artisan key:generate
   ```


5. **Run migrations**
    ```sh
    # Go inside the app-techlint-gateway container then run:
    php artisan migrate
    ```

6. **Check health endpoint:**  
   Access [http://localhost:54000/api/health-check](http://localhost:54000/api/health-check) in your browser or with Postman.

---

## Usage Instructions

#### Prerequisites

- Make sure you have registered an account and have logged in using the **Authentication service**

#### Steps

1. **Import the API collection:**  
   - Download or obtain the provided Postman collection file sent in the email.
   - In Postman, click **Import** and select the collection file.

2. **Available Endpoints in the Gateway Collection:**
    - Auth
        - **POST** `Login`
        - **POST** `Logout`
        - **GET** `Check`
    - IP management
        - **POST** `Create`
        - **DELETE** `Delete`
        - **PUT** `Update`
        - **GET** `List`
    - User
        - **POST** `Create`
    - Audit Log
        - **GET** `List`
    - **GET** `Health check`

3. **Set the API base URL:**  
   - Make sure to set the correct base URL (e.g., `http://localhost:54000/api/`) in your Postman environment or in each request (an **environment file** is provided in the email)
   - Make sure to get the **token** from the authentication service (using login endpoint) and use it in the authorization tab

4. **Send requests:**  
   - Select an endpoint from the `Gateway` folder in Postman.
   - Fill in any required parameters or request bodies.
   - Click **Send** to test the endpoint

**Note:**  
- Examples are provided per request
- Pre-script request are provided in the postman collection
