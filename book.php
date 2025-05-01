<?php
session_start();
include 'db.php';

$name = $_SESSION['name'] ?? 'Guest';
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Default empty array for items
$items = [];

if (!empty($query)) {
    // Search specific items
    $stmt = $conn->prepare("
        SELECT * FROM books
        WHERE LOWER(title) LIKE LOWER(CONCAT('%', ?, '%')) 
        OR LOWER(author) LIKE LOWER(CONCAT('%', ?, '%'))
    ");
    $stmt->bind_param('ss', $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Show all items by default
    $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
    if ($result) {
        $items = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Search</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
:root {
    --primary-bg: #1e293b;
    --secondary-bg: #334155;
    --accent: #3b82f6;
    --text-light: #f8fafc;
    --shadow-sm: 0 2px 6px rgba(0,0,0,0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f5f5;
}

/* Sidebar */
.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: var(--primary-bg);
    color: var(--text-light);
    padding: 1.5rem;
    transition: 0.3s;
    z-index: 1000;
}

.sidebar h2 {
    margin-bottom: 2rem;
    font-size: 1.4rem;
    text-align: center;
}

.sidebar a {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    color: inherit;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.sidebar a:hover {
    background: var(--secondary-bg);
    transform: translateX(4px);
}

.sidebar i {
    min-width: 24px;
    margin-right: 12px;
    font-size: 1.1rem;
}

/* Mobile Overlay */
.mobile-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

/* Menu Toggle */
.menu-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    position: fixed;
    left: 20px;
    top: 20px;
    z-index: 1100;
    cursor: pointer;
}

.menu-toggle span {
    width: 30px;
    height: 3px;
    background: var(--primary-bg);
    border-radius: 3px;
    transition: 0.3s;
}

/* Profile */
.profile {
    display: flex;
    align-items: center;
    gap: 15px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    background: white;
    padding: 10px 15px;
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
}

.profile-container {
    display: flex;
    align-items: center;
    gap: 20px;
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 10px 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 5px 10px;
    border-radius: 6px;
}

.profile-info:hover {
    background-color: #f3f4f6;
}

.profile-info span {
    font-weight: 500;
    color: #1f2937;
}

.profile-icon {
    font-size: 1.5rem;
    color: #3b82f6;
}

.logout-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8fafc;
    color: #64748b;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.logout-link:hover {
    background: #f1f5f9;
    color: #ef4444;
}

.logout-link i {
    color: #ef4444;
}

/* Main Content */
.main-content {
    margin-left: 280px;
    padding: 100px 40px 20px;
    transition: 0.3s;
    min-height: 100vh;
}

/* Search Container */
.search-container {
    max-width: 800px;
    margin: 0 auto 40px;
}

.search-form {
    display: flex;
    gap: 10px;
}

input[type="text"] {
    flex: 1;
    padding: 12px 20px;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 8px;
}

button[type="submit"] {
    padding: 12px 24px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

/* Book Grid */
.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.book-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
    transition: 0.3s;
}

.book-card:hover {
    transform: translateY(-5px);
}

.book-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.book-title {
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: var(--primary-bg);
    font-weight: 600;
}

.book-info {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 12px;
}

.buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
    font-weight: 500;
    text-decoration: none;
    transition: 0.3s;
}

.btn-borrow {
    background: #28a745;
    color: white;
    border: none;
    cursor: pointer;
}

.btn-more {
    background: var(--accent);
    color: white;
}

.no-results {
    text-align: center;
    color: #64748b;
    padding: 40px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        left: -280px;
    }
    
    .sidebar.active {
        left: 0;
    }

    .menu-toggle {
        display: flex;
    }

    .main-content {
        margin-left: 0;
        padding: 100px 20px 20px;
    }

    .search-form {
        flex-direction: column;
    }

    button[type="submit"] {
        width: 100%;
    }

    .profile-container {
        top: 10px;
        right: 10px;
        padding: 8px 12px;
    }
    
    .profile-info span {
        display: none;
    }
    
    .logout-link span {
        display: none;
    }
}

@media (min-width: 769px) {
    .mobile-overlay {
        display: none !important;
    }
}


    </style>
</head>
<body>
    <!-- Mobile Menu -->
    <div class="menu-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

<!-- Profile Section -->
<div class="profile-container">
    <div class="profile-info" onclick="window.location.href='../html/settings.html'">
        <span><?= htmlspecialchars($name) ?></span>
        <i class="fas fa-user-circle profile-icon"></i>
    </div>
    <a href="logout.php" class="logout-link">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

<!-- Sidebar Section -->
<div class="sidebar">
    <h2>Student Portal</h2>
    <a href="student_dashboard.php"><i class="fas fa-home"></i>Home</a>
    <a href="book.php"><i class="fas fa-book"></i>Books</a>
    <a href="search.php" class="active"><i class="fas fa-search"></i>Search</a>
    <a href="e-resources.php"><i class="fas fa-download"></i>E-Resources</a>
    <a href="lecturers.php"><i class="fas fa-user-tie"></i>Lecturers</a>
    <a href="suggestions.php"><i class="fas fa-comments"></i>Suggestions</a>
    <a href="exams.php"><i class="fas fa-file-alt"></i>Exams</a>
    <a href="todo.php"><i class="fas fa-tasks"></i>To-Do List</a>
    <a href="chatbot.php"><i class="fas fa-robot"></i>Chatbot</a>
    <a href="blog.php"><i class="fas fa-blog"></i>Blog</a>
    <a href="settings.php"><i class="fas fa-cog"></i>Settings</a>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="search-container">
            <form class="search-form" method="GET">
                <input type="text" name="query" placeholder="Search by title or author..." value="<?= htmlspecialchars($query) ?>">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <?php if ($query !== '' && empty($items)): ?>
            <div class="no-results">
                <h2>No results found for "<strong><?= htmlspecialchars($query) ?></strong>"</h2>
            </div>
        <?php endif; ?>

        <?php if (!empty($items)): ?>
            <div class="book-grid">
                <?php foreach ($items as $item): ?>
                    <?php
                    // Set fallback image if not found
                    $imagePath = 'uploads/' . ($item['image'] ?? 'default.jpg');
                    if (!file_exists($imagePath)) {
                        $imagePath = 'uploads/default.jpg';
                    }
                    ?>
                    <div class="book-card">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Book Cover" class="book-image">
                        <h3 class="book-title"><?= htmlspecialchars($item['title']) ?></h3>
                        <p class="book-info">Author: <?= htmlspecialchars($item['author']) ?></p>
                        <div class="buttons">
                            <a href="book_details.php?id=<?= urlencode($item['id']) ?>" class="btn btn-more">More Info</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($query === ''): ?>
            <div class="no-results">
                <h2>No items available in the library yet.</h2>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.mobile-overlay');

        menuToggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('active');
            overlay.style.display = sidebar?.classList.contains('active') ? 'block' : 'none';
        });

        overlay?.addEventListener('click', () => {
            sidebar?.classList.remove('active');
            overlay.style.display = 'none';
        });
    </script>
</body>
</html>
