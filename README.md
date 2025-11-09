# Innovation Pipeline Platform

This project is an advanced, custom-built web application designed to manage a company's internal innovation workflow. It provides a complete system for collecting, reviewing, pricing, and managing new ideas, from public submission to final approval.

Built on the Laravel 12 / Livewire 3 stack, it features a secure, invitation-only environment with role-based permissions for different teams.

---

## 🚀 Key Features

* **KPI Dashboard:** A main dashboard showing "at-a-glance" statistics like *Total Ideas*, *Pending Review*, and *Total Approved Budget*.
* **Advanced Pipeline Grid:** A fully responsive (desktop/mobile) data grid for managing all ideas.
* **Live Search & Filtering:** Instantly search all ideas or filter them by their current **Status** (New, Approved, Pending Pricing, etc.).
* **Click-to-Sort Columns:** Sort the entire pipeline by **Cost**, **Pain Score** (`Schmerz`), **Priority** (`Umsetzung`), or **Status**.
* **Secure Role-Based Editing:**
    * **Admins** can edit all fields.
    * **Work-Bees** (Your Team) can only edit "Yellow" columns (Status, Schmerz, Prio).
    * **Developers** can only edit "Red" columns (Lösung, Kosten, Dauer).
* **Invitation-Only System:** Public registration is disabled. The Admin invites new team members and assigns them a role ("Work-Bees" or "Developer").
* **Collaboration Hub:** A detailed page for each idea featuring:
    * **Team Comments:** A real-time commenting system for discussion.
    * **Full Activity Log (Audit Trail):** A complete history of all changes made to an idea (e.g., "User changed Cost from $800 to $950").
* **Custom Branding:**
    * A custom, professional welcome page.
    * A modern, full-screen UI for all login/register pages.
    * A dedicated "Site Logo Manager" to upload the company logo.
* **Image Compression:** All user profile photos and site logos are automatically compressed (using `Intervention/Image`) to ensure fast load times.

## 💻 Tech Stack

* **Framework:** Laravel 12
* **UI:** Livewire 3, Tailwind CSS
* **Auth & Teams:** Laravel Jetstream (Teams Stack)
* **Key Packages:**
    * `spatie/laravel-activitylog` (for history/audit trails)
    * `intervention/image-laravel` (for image compression)

---

## 🔧 Installation (Local Development)

1.  **Clone the repository:**
    ```bash
    git clone [https://your-repository-url.com/innovation-hub.git](https://your-repository-url.com/innovation-hub.git)
    cd innovation-hub
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Install JS Dependencies:**
    ```bash
    npm install
    npm run dev
    ```

4.  **Create Environment File:**
    * Copy the example `.env` file.
    ```bash
    cp .env.example .env
    ```

5.  **Generate App Key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure `.env` File:**
    * Update your `DB_` settings (e.G., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    * Set `MAIL_MAILER=log` for local testing (this will write invitation emails to `storage/logs/laravel.log`).
    * Set `APP_URL=http://127.0.0.1:8000` (or your local URL).

7.  **Run Database Migrations:**
    * This will create all tables (`users`, `teams`, `ideas`, `activity_log`, etc.).
    ```bash
    php artisan migrate
    ```

8.  **Create Storage Link:**
    * This is **critical** for viewing the site logo and profile photos.
    ```bash
    php artisan storage:link
    ```

9.  **Run the Server:**
    * **Important:** On Windows, you may need to run this in a terminal **"As Administrator"** for the `storage:link` to work correctly.
    ```bash
    php artisan serve
    ```

---

## 🔑 Getting Started: Creating the First Admin

Public registration is disabled. You must create the first user (the Admin) manually using Tinker.

1.  Run Tinker:
    ```bash
    php artisan tinker
    ```

2.  Paste the following code into Tinker and press Enter. This will create your Admin user and their first team.

    ```php
    // 1. Create the Admin User
    $user = App\Models\User::create([
        'name' => 'Admin User', // Change this to your name
        'email' => 'admin@example.com', // Change this to your email
        'password' => Hash::make('password') // Change 'password' to a secure password
    ]);

    // 2. Create the User's Personal Team
    $team = App\Models\Team::forceCreate([
        'user_id' => $user->id,
        'name' => $user->name."'s Team",
        'personal_team' => true,
    ]);

    // 3. Attach the User to the Team as Admin
    $user->teams()->attach($team, ['role' => 'admin']);
    $user->current_team_id = $team->id;
    $user->save();

    echo 'Admin user created successfully!';
    ```

3.  Type `exit` to leave Tinker.

4.  You can now log in at `http://127.0.0.1:8000/login` with the email and password you just created.

## 🚀 Workflow

1.  Log in as the Admin user.
2.  Go to **"Team Settings"** (in the top-right user menu).
3.  Invite new team members using their email and assign them the **"Work-Bees"** or **"Developer"** role.
4.  Find the invitation links in `storage/logs/laravel.log`.
5.  Send the links to your team members so they can register.
6.  Start submitting ideas via the **`/submit-idea`** page.
7.  Manage all incoming ideas from the **`/pipeline`** page.
