# Simply Rugby Club Management System

 Simply Rugby Club Management System is a web-based application developed for Simply Rugby, a rugby club based in Glasgow. The system provides a centralised platform for managing the club's administrative and rugby-related activities while also providing an online presence for the club.

 The application supports multiple users in real time and provides role-specific access to features for club administrators, club secretaries, coaches, senior players, parents of junior players, and members of the public.

 # Live Application

 Live Website: https://simply-rugby.infinityfree.io/
 
 # Login Credentials 
 
 | Role | Squad | Section | Email | Password |
|---|---|---|---|---|
| Parent | | | `sarah.Murray14@gmail.com` | `Password123!` |
| Coach | Mini | | `gary@test.com` | `Password123!` |
| Coach/Parent | Midi | Junior | `laura.mcgregor@example.com` | `Password123!` |
| Coach | Senior | | `johnnybravo@email.com` | `Password123!` |
| Fixture Secretary | | Senior | `brooke.perry@email.com` | `Password123!` |
| Section Secretary | | Junior | `mark.lauren@test.com` | `Password123!` |
| Senior Player | | | `hamish.mcneill04@gmail.com` | `Password123!` |
| Club Chairman | | | `jane.doe@temp.com` | `Password123!` |
| Membership Secretary | | | `jennifer123@test.com` | `Password123!` |


 # Overview

 The Simply Rugby Club Management System was developed to provide an efficient and centralised way of managing club members, players, training activities, fixtures, injuries, attendance, and player performance.

 The system allows authorised users to manage and access information relevant to their role while using access-control mechanisms to protect sensitive records.

 ## Main Features

 - Member registration and management
- Player profile management
- Training session management
- Match fixture management
- Training attendance tracking
- Player injury records
- Player skills and performance records
- Role-based access to system features
- Attribute-based access control for sensitive records
- Online club information and public-facing content
- Dashboard and data visualisation
- Secure database transactions
- Real-time access through a web browser

 # User Roles

 The system is designed to support several types of users:

 | User Role | Example Access |
| --- | --- |
| Club Administrator | Manage users, members, players, and system information |
| Club Secretary | Manage administrative information, members, and fixtures |
| Coach | Manage training sessions, attendance, injuries, and player performance |
| Senior Player | Access relevant player and training information |
| Parent of Junior Player | Access information relating to their junior player |
| Public User | View publicly available club information |

Access to records and features is controlled according to the user's role and relevant attributes.

 # System Architecture

 The application follows the Model-View-Controller (MVC) architectural pattern.

 ## Model

 The Model layer manages the application's data and communication with the MySQL database.

 ## View

 The View layer provides the user interface and presentation of information to users.

 ## Controller

 The Controller layer handles user requests, application logic, and communication between the Models and Views.

 This structure helps separate application responsibilities and makes the system easier to maintain and extend.

 # Security and Access Control

 The system incorporates Role-Based Access Control (RBAC), Attribute-Based Access Control (ABAC), and routing to control access to system features and records.

 ## Role-Based Access Control

 RBAC controls access to system features based on the user's assigned role. For example, coaches can access coaching-related functionality while public users are restricted to publicly available information.

 ## Attribute-Based Access Control

 ABAC provides more detailed control by considering attributes associated with users, records, or other contextual information when determining whether access should be permitted.

 ## Routing

 Routing controls access to different pages and functions. Protected routes verify that users are authorised before allowing access.

 Together, these approaches help ensure that users can only access authorised features and records.

 # Database

 The application uses MySQL for database management.

 A Singleton design pattern is used to maintain a single database connection throughout the application. This provides a consistent approach to database connectivity across different parts of the application.

 Database transactions are also used when multiple related database operations need to be performed together. This helps maintain data integrity by ensuring that related operations are completed consistently.

 # Technologies Used

 ## Backend

 - PHP
- MySQL
- Object-Oriented Programming (OOP)
- MVC Architecture

 ## Frontend

 - HTML
- JavaScript
- Tailwind CSS
- Flowbite

 ## Data Visualisation

 - Chart.js
- Flowbite ApexCharts

 ## Dependency Management

 - Composer

 # Data Visualisation

 The system uses charts and graphical representations to help users understand club and player information.

 The application uses:

 - Chart.js
- Flowbite ApexCharts

 These libraries are used to present relevant statistics and performance information in an accessible format.

 # Object-Oriented Programming

 The application was developed using Object-Oriented Programming principles in PHP.

 OOP is used to structure the application into reusable classes and objects, helping improve:

 - Maintainability
- Code organisation
- Reusability
- Separation of responsibilities
- Scalability

 The application also makes use of design patterns, including the Singleton pattern for database connectivity.

 # Deployment

 The application is currently hosted online and can be accessed through a web browser on a range of devices.

 Live application:

 https://simply-rugby.infinityfree.io/

 # Browser and Device Support

 The system is designed as a web-based application and can be accessed through modern web browsers on different device types, including:

 - Desktop computers
- Laptops
- Tablets
- Mobile devices

 The user interface uses Tailwind CSS and Flowbite components to support a responsive and accessible design.

 # Project Objectives

 The main objectives of the Simply Rugby Club Management System are to:

 - Centralise club administration and rugby-related information
- Reduce manual management of club records
- Provide authorised users with access to relevant information
- Improve the management of training sessions and attendance
- Maintain player injury and performance records
- Manage fixtures and player information efficiently
- Provide useful data visualisation for club information
- Improve the club's online presence
- Provide a system that can be accessed through a web browser

 # Future Improvements

 Potential future improvements could include:

 - Email and notification functionality
- Automated fixture reminders
- More advanced player performance analytics
- Online membership and payment processing
- Improved reporting functionality
- Mobile-specific enhancements
- Integration with external rugby competition or fixture systems

# Disclaimer:
This project has been developed solely for educational and academic purposes as part of a college project. The information, content, and materials presented are intended for demonstration and learning purposes only and should not be considered professional advice or an official representation of any organization.
