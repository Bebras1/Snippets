# Snippet Manager

Snippet Manager is a robust and user-friendly web application for managing, editing, and sharing code snippets. This application leverages Docker for containerized deployment, ensuring consistency across environments. Users can view, create, rate, and suggest changes to code snippets seamlessly.

## Features

- **Snippet Management**:
  - Create, delete, and edit code snippets.
  - View snippets in an interactive code editor.

- **Rating System**:
  - Users can rate snippets between 1 and 5 stars.
  - Average ratings are displayed dynamically.

- **Suggestion System**:
  - Logged-in users via GitHub can suggest changes to existing snippets.
  - Suggestions are submitted to GitHub and tracked with pull requests.

- **Search and Filter**:
  - Search snippets by title to quickly find relevant content.

- **Dockerized Deployment**:
  - PostgreSQL database, Laravel backend, and Nginx server run in isolated containers.

## Screenshots

### Snippet List Page
![image](https://github.com/user-attachments/assets/af48a732-d2ff-4b49-89bb-97849c2facd6)

### Create Snippet Page
![image](https://github.com/user-attachments/assets/1bbf4c5d-a211-4457-ba7c-95ec0cae1eda)

## Technologies Used
- **Frontend**:
  - React.js with functional components
  - CodeMirror for code editing
  - CSS for custom styling

- **Backend**:
  - Laravel PHP Framework
  - PostgreSQL for database management
  - Docker for containerized deployment

- **APIs**:
  - GitHub API for repository integration
