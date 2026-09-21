Simply Rugby

A full-stack, database-driven rugby club management system developed using PHP OOP, a custom MVC architecture, custom routing and MySQL. The system provides authentication and role-based access control for administrators, coaches, players and parents.

The application includes dedicated dashboards, player management, reports, training scheduling, injury management and match management. Security controls were implemented to prevent unauthorised access to protected pages, data and functionality, including attempts to bypass the application through direct URL access.

Overview

Simply Rugby is a database-driven web application developed using Object-Oriented PHP with a custom-built MVC (Model-View-Controller) architecture rather than relying on an existing MVC framework.

A custom routing system handles incoming requests and directs them to the appropriate controllers and actions. The application also uses the Singleton design pattern for controlled access to shared resources such as the database connection.

The system supports multiple user roles, with functionality and permissions tailored to administrators, coaches, players and parents.

Features

🔐 Authentication & Role-Based Access

* Secure user authentication
* Role-based access control (RBAC)
* Separate functionality for administrators, coaches, players and parents
* Role-specific permissions
* Protected routes and pages
* Server-side authorisation checks
* Prevention of unauthorised direct URL access

🏗️ Custom MVC Architecture

* Custom MVC architecture developed in PHP
* Separation of Models, Views and Controllers
* Models responsible for database interaction and data operations
* Controllers responsible for application logic and request handling
* Views responsible for presentation and user interface
* Separation of concerns to improve maintainability

🛣️ Custom Routing

* Custom application routing system
* Routes mapped to controllers and actions
* Centralised request handling
* Protected routes for authenticated users
* Role-based route authorisation

🔄 Singleton Design Pattern

* Implemented the Singleton design pattern
* Controlled creation and access of shared application resources
* Used for managing the database connection

🗄️ Database Transactions & Data Integrity

* Implemented SQL database transactions using BEGIN, COMMIT and ROLLBACK
* Ensures related database operations are completed atomically
* Automatically rolls back changes when an operation fails
* Prevents partially completed database operations
* Uses validation and database constraints to help prevent duplicate records
* Maintains data consistency and integrity across related tables
* Helps ensure related records are created, updated or deleted safely as a single operation

👨‍👩‍👧 Parent & Player Management

* Parent dashboard
* Player dashboard
* Player profiles
* Player information management
* Player reports
* Parent access to relevant player information
* Parent-child player relationships

🏉 Team & Squad Management

* Manage rugby squads and teams
* Organise players within teams
* Manage team membership
* Manage player information and squad structures

📅 Training Management

* Create and manage training sessions
* Schedule training activities
* Manage training information
* Associate players with training sessions
* Track relevant player participation

🩹 Injury Management

* Record player injuries
* Manage injury information
* Monitor player injury records
* Associate injuries with individual players
* Restrict injury information based on user permissions

🏆 Match Management

* Create and manage matches
* Manage match information
* Associate matches with teams
* Track match-related data
* Manage participating players

📊 Player Reports

* Generate and view player reports
* Manage player performance-related information
* Present player information in an organised format
* Provide role-specific access to player reports

🛡️ Security & Data Protection

* Authentication and authorisation controls
* Role-based access to sensitive information
* Server-side validation
* Protected routes
* Prevention of direct URL access to restricted pages
* Controlled access to player and injury information
* GDPR-conscious handling of children’s personal data

Technologies

* PHP
* PHP OOP
* Custom MVC Architecture
* Custom Routing
* Singleton Design Pattern
* MySQL
* SQL Transactions
* HTML5
* CSS3
* JavaScript
* Tailwind CSS
* Composer
* NPM
* Git

Architecture

The application follows a custom MVC architecture designed to separate application responsibilities.

Model

* Handles database interaction and data-related operations
* Communicates with the MySQL database
* Provides structured access to application data

View

* Handles the presentation layer
* Displays application data to users
* Provides role-specific dashboards and interfaces

Controller

* Processes incoming requests
* Coordinates models and views
* Handles application logic
* Performs authentication and authorisation checks

Router

* Processes incoming URLs and requests
* Maps routes to the appropriate controllers and actions
* Controls access to protected application routes

Database Layer

* Uses a Singleton pattern for controlled access to the database connection
* Uses SQL transactions for operations involving multiple related queries
* Uses validation and database constraints to maintain data integrity and reduce duplicate records

Database

The application uses a relational MySQL database to manage structured rugby club data, including:

* Users
* Parents
* Players
* Teams
* Squads
* Training sessions
* Injuries
* Matches
* Player participation
* Player-related reports

Relationships between entities allow information to be associated and retrieved efficiently while maintaining data integrity.

Key Learning Outcomes

Through this project, I developed practical experience in:

* Object-oriented software development
* Designing and implementing a custom MVC architecture
* Developing a custom routing system
* Applying the Singleton design pattern
* Implementing SQL database transactions
* Using COMMIT and ROLLBACK for atomic database operations
* Database design and relational modelling
* SQL and MySQL
* Authentication and authorisation
* Role-based access control
* CRUD operations
* Database constraints and data integrity
* Form handling and validation
* Server-side security
* Protected routing
* Data protection considerations
* Full-stack web application development
* Separation of concerns
* Software architecture and maintainability
* Git-based version control
* Designing software for multiple user types

Project

GitHub: https://github.com/realveenna/simply-rugby
