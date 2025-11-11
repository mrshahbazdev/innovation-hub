### Innovation Pipeline Platform

This is an advanced, custom-built web application to manage a company's internal innovation workflow. It features a dual-interface system:

User Frontend (Jetstream & Livewire): A portal for all users to register, browse, and join global teams. Once in a team, they can submit, edit, and delete their own ideas. Their editing rights on other's ideas are controlled by Jetstream Team Roles (like 'work-bees' or 'developer').

Admin Panel (Filament): A powerful, secure backend for *Super Admins* (is_admin: true) and *Managers* (with spatie/laravel-permission roles) to control the entire application, including all users, all teams, all ideas, and all site permissions.

🚀 Key Features

### User Frontend Features

User Registration: Users can register via the public /register link. New users do not get a personal team.

Global Team Browser: A dedicated /browse-teams page where users can see all available teams (created by the Admin) and join/leave them.

Active Team Submissions: A *Post Idea* link in the navigation menu that allows users to submit ideas directly to their currently active team (via the /teams/{team}/view page).

User-Facing Pipeline: A responsive /pipeline page where users can see ideas.

Super Admins see all ideas from all teams.

Normal Users see only the ideas from their currently active team.

Ownership & Permissions:

Users can edit and delete their own ideas.

Users can get *Edit* rights on other's ideas if they are given a specific Jetstream role (like 'work-bees') within that team.

Detailed Idea View: A full page for each idea with a Comments Section and a full Activity Log (History).

Admin Panel (/admin) Features

Powered by Filament (v4): A beautiful, fast, and secure admin panel.

Super Admin Access: The panel is only accessible to users with the is_admin flag set to true or who have the access_admin_panel permission.

Dynamic Role & Permission System (Spatie):

Full **CRUD** (Create, Read, Update, Delete) management for Permissions (e.g., viewAny Idea).

Full **CRUD** management for Roles (e.g., *Manager*, *HR*).

Ability to assign any combination of permissions to any role.

Full User Management:

Full **CRUD** management for all Users.

Ability to assign spatie roles (like *Manager*) to users.

Ability to set a user's is_admin status.

Ability to manage profile photos and team assignments.

Full Team Management:

Full **CRUD** management for all Teams.

Form is simplified so Admins can create global teams without worrying about user_id.

Full Idea Management:

Full, filterable, and searchable table of all ideas in the system.

Columns are responsive for mobile use.

### General Features

Custom Branding: Custom homepage, advanced full-screen background login/register UI, and a *Site Logo Manager*.

Image Compression: All user profile photos and the site logo are automatically compressed on upload using Intervention/Image.

💻 Tech Stack

Framework: Laravel 12

UI: Livewire 4, Tailwind **CSS**, Alpine.js

User Panel: Laravel Jetstream (Teams Stack)

Admin Panel: Filament 4

Roles & Permissions: spatie/laravel-permission (for Admin Panel) & Jetstream Roles (for Frontend)

Image Handling: intervention/image-laravel

🔧 Installation (Local Development)

Clone the repository:

git clone [https://github.com/mrshahbazdev/innovation-hub.git](https://github.com/mrshahbazdev/innovation-hub.git) cd innovation-hub

Install **PHP** Dependencies (with Filament v4):

composer install

Install JS Dependencies:

npm install npm run build

Create Environment File:

cp .env.example .env

Generate App Key:

php artisan key:generate

Configure .env File:

Update your DB_ settings.

Set MAIL_MAILER=log (or your **SMTP** credentials).

Set APP_URL=[http://**127**.0.0.1:**8000**](http://**127**.0.0.1:**8000**)

Run Database Migrations:

This will create all tables (users, teams, ideas, roles, permissions, activity_log, etc.).

php artisan migrate

Create Storage Link:

Critical: This is required for viewing the site logo and profile photos.

php artisan storage:link

Run the Server:

Important: On Windows, run your terminal *As Administrator* for the storage:link (symlink) to work correctly.

php artisan serve

🔑 Post-Installation Setup (Admin & Permissions)

You must create the Super Admin and the core permissions manually.

## Create Super Admin User

Run Tinker:

php artisan tinker

Paste this code into Tinker and press Enter. This creates your admin user (is_admin: true) and gives them a personal team so they can also use the frontend.

// 1. Create the Admin User with 'is_admin' flag
$user = App\Models\User::create([
    'name' => 'Mr Shahbaz', // Change this to your name
    'email' => '[admin@example.com](mailto:admin@example.com)', // Change this to your email
    'password' => Hash::make('password'), // Change this to a secure password
    'is_admin' => true // This makes them a Super Admin
]);

// 2. Create the Admin's Personal Team
$team = App\Models\Team::forceCreate([
    'user_id' => $user->id,
    'name' => $user->name.*'s Team*,
    'personal_team' => true,
]);

// 3. Attach the User to the Team $user->teams()->attach($team, ['role' => 'admin']); $user->current_team_id = $team->id; $user->save();

echo 'Super Admin user created successfully!';

Type exit to leave Tinker.

## Create Core Permissions

Log in to the Admin Panel (/admin) with your new Super Admin account.

Go to the *Permissions* page from the left menu.

Click *New permission* and create all of the following permissions exactly as named:

access_admin_panel (This is the *key* to the admin panel for other roles)

viewAny Idea

create Idea

update Idea

delete Idea

viewAny User

create User

update User

delete User

viewAny Team

create Team

update Team

delete Team

viewAny Role

create Role

update Role

delete Role

## Clear Permission Cache (Critical)

After creating all permissions, run this command in your terminal. This forces the application to recognize the new permissions.

php artisan permission:cache-reset

Your application is now fully set up.

🚀 Workflow

### Admin Workflow

Log in to /admin.

Go to *Teams* and create your global teams (e.g., *Sales Department*, *Developers*).

Go to *Roles* and create a new role (e.g., *Team Manager*).

Edit the *Team Manager* role and assign it permissions (e.g., access_admin_panel, viewAny Idea, update Idea).

Go to *Users* and assign the *Team Manager* role to a user.

(Optional) For frontend roles, go to the Frontend (/) -> *Team Settings* -> *Team Members* and assign Jetstream roles (like 'work-bees' or 'developer').

### Normal User Workflow

User registers at /register.

User logs in and lands on the /dashboard.

User goes to *Browse Teams* and joins one or more teams.

User uses the top-right *Team Switcher* to select an active team.

User clicks *Post Idea* (in nav menu) to submit an idea to their active team.

User goes to the *Innovation Pipeline* to see ideas from that team, and can edit/delete their own submissions.
