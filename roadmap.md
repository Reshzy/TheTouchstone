# 📘 The Touchstone CMS - Summary & Development Roadmap

## 📝 Project Summary
**The Touchstone** is a campus publication content management system (CMS) for *Cagayan State University – Sanchez Mira Campus*. It allows administrators, editors, and writers to create, edit, publish, and manage campus news and articles, inspired by user-friendly social media experiences like Facebook. The platform features role-based access, article feeds, threaded comments, and a modern dashboard.

---

## 🚀 Goals
- Centralize and organize campus publication content
- Empower the publication team to manage articles effectively
- Provide students and readers with timely, accessible campus news
- Mimic familiar UX patterns from platforms like Facebook

---

## 🧩 Core Features
- ✅ Role-Based Access Control (Admin, Editor, Writer, Reader)
- ✅ Full CRUD for articles (create, edit, delete, publish)
- ✅ Categories for articles
- ✅ Commenting system
- ✅ Responsive, clean UI using Tailwind CSS
- ✅ Admin dashboard with stats & management tools
- ✅ Article status (Draft, Pending, Published)
- ✅ Author attribution

---

## 🛠️ Technical Stack
- **Backend Framework:** Laravel 10
- **Frontend:** Blade Templates + Tailwind CSS
- **Database:** MySQL (XAMPP for local dev)
- **Authentication:** Laravel Breeze / Fortify
- **Hosting:** Shared hosting or VPS (e.g., Hostinger, DigitalOcean)
- **Optional Libraries:** Trix/TinyMCE (WYSIWYG), Livewire (for dynamic components)

---

## 📅 Development Roadmap

### 📍 Phase 1: Planning & Setup
- [x] Define user roles and permissions
- [x] Set up Laravel project and auth system
- [x] Design database schema (articles, users, categories, comments)

### 📍 Phase 2: Admin Dashboard
- [x] Create admin layout and navigation sidebar
- [x] Implement user management
- [x] Build dashboard metrics (article count, pending, etc.)

### 📍 Phase 3: Article Management (CRUD)
- [x] Article listing (with pagination)
- [x] Article creation with category selection
- [x] Edit and delete functionalities
- [x] Status field for draft/pending/published
- [x] Show author name in listings

### 📍 Phase 4: Comments and Engagement
- [ ] Add threaded comment system under articles
- [ ] Restrict commenting to authenticated users
- [ ] Notifications for authors on new comments

### 📍 Phase 5: Reader Experience
- [ ] Public news feed layout (latest articles)
- [ ] Article search and filter by category
- [ ] Responsive design testing (mobile/tablet)
- [ ] Author profile pages with bio and published posts

### 📍 Phase 6: Deployment
- [ ] Register domain and choose hosting provider
- [ ] Migrate DB and deploy Laravel app
- [ ] Set up backups and security measures (HTTPS, CSRF, etc.)

### 📍 Phase 7: Maintenance & Upgrades
- [ ] Add WYSIWYG editor for content
- [ ] Optimize performance and images
- [ ] Track engagement (views, likes)
- [ ] Enable analytics (e.g. Google Analytics)

---

## ✅ Future Features
- 🔔 Push notifications or email alerts
- 📥 Article submission queue (with approval system)
- 📊 Analytics dashboard for Admins
- 🌐 Multi-language support
- 📅 Event publishing module (optional)

---

## 🙌 Notes
- Mimicking social platforms like Facebook is encouraged for user familiarity
- Keep the UI clean, simple, and mobile-first
- Focus on security, especially for login and CRUD actions

---

> "We Reveal What Is Real" — The Touchstone

