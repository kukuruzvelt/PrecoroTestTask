# Precoro Test task

### Prerequisites

Before you begin, ensure you have the following installed on your system:
- Git
- Docker Compose
- Symfony CLI
- Make
- Composer

### Steps

1. **Clone the Repository**

   Start by cloning the repository to your local machine. Note, that the recommended way of doing it is using SSH. Check [this link](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account) for more information.

   ```bash
   git clone git@github.com:kukuruzvelt/PrecoroTestTask.git
   cd PrecoroTestTask
   ```

2. **Start the project**

   Use the make command to start the project. It will install dependencies, run migrations to the DB and start the app.

   ```bash
   make start
   ```

   **It will be better to wait a few minutes after this command executes**

   That's it! You can go to `http://127.0.0.1:8000` to access the app.

4. **Stopping the Application**

   To stop the application and shut down the containers, run the following command `make down`.