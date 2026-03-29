# Buku Induk Digital (Digital Student Master Record)

A modern, robust, and premium web application for managing student master records, specifically designed for educational institutions in Indonesia. This application streamlines the management of student lifecycles, from admission to graduation, with deep integration for Dapodik imports.

Created with ❤️ by **[@wahyusmktel](https://github.com/wahyusmktel)**

---

## ✨ Features

- **🚀 Smart Dapodik Integration**: Seamlessly import student data directly from Excel files exported from the Dapodik application.
- **📚 Digital Master Book**: A comprehensive digital record of all students, including personal details, parental information, and academic history.
- **🏢 Class Management (Rombel)**: Organize students into classes (Rombongan Belajar) with active filtering and automated member tracking.
- **📅 Academic Session Tracking**: Manage multiple academic years and semesters with easy data migration between sessions.
- **🎓 Student Lifecycle**: Automatically track and archive students as they transition through statuses:
  - **Active**: Currently enrolled students.
  - **Graduated (Lulus)**: Students who have completed their education.
  - **Transferred (Keluar/Mutasi)**: Students who have moved to other schools.
- **🔐 Secure Role-Based Access (RBAC)**: Fine-grained permissions for Super Admins, Operators, and Administrative Staff (Tata Usaha) using *Spatie Laravel Permission*.
- **💎 Premium UI/UX**: A beautiful, responsive interface built with *Tailwind CSS* and *Alpine.js*, featuring vibrant aesthetics and smooth interactions.

---

## 🛠️ Technology Stack

- **Framework**: [Laravel 13](https://laravel.com)
- **Language**: PHP 8.3+
- **Database**: MySQL / MariaDB (or SQLite for development)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Frontend Logic**: [Alpine.js](https://alpinejs.dev)
- **Permissions**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **Excel Processing**: [Laravel Excel (Maatwebsite)](https://laravel-excel.com)

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- A database (MySQL/MariaDB)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/wahyusmktel/buku-induk.git
   cd buku-induk
   ```

2. **Run the automated setup**:
   ```bash
   composer run setup
   ```
   *This command will install dependencies, generate the app key, run migrations, and build the frontend assets.*

3. **Configure the environment**:
   Edit the `.env` file to set your database credentials and other configuration settings.

---

## 💻 Development

To run the application locally with all services (server, queue, Vite, etc.) concurrently:

```bash
composer run dev
```

---

## 📄 License

This project is licensed under the MIT License - see the `LICENSE` file for details.

---

## 🤝 Contributing

Contributions are welcome! If you have suggestions or want to report a bug, please feel free to open an issue or submit a pull request.

---

**Buku Induk Digital** - Streamlining Education Management for the Future.
