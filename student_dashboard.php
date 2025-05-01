<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['userId'])) {
    header("Location: ../../php/login.php");
    exit();
}

// Get the user's name from the session
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/styles STD.css">

</head>
<body>
<div class="profile">
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

<style>
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

/* Mobile responsiveness */
@media (max-width: 768px) {
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

</style>

    <!-- Mobile Menu -->
    <div class="menu-toggle">
        <span></span>
        <span></span>
        <span></span>
    </div>


    
 
  </div>
  <div class="mobile-overlay"></div>

  <div class="sidebar">
    <h2>Student Portal</h2>
    <a href="student_dashboard.php"><i class="fas fa-home"></i>Home</a>
    <a href="book.php"><i class="fas fa-book"></i>Books</a>
    <a href="e-resources.php"><i class="fas fa-download"></i>E-Resources</a>
        <a href="Lecturer.html"><i class="fas fa-user-tie"></i>Lecturers</a>
    <a href="#"><i class="fas fa-chalkboard-teacher"></i>Suggestions</a>
    <a href="#"><i class="fas fa-file-alt"></i>Exams</a>
   <!-- <a href="#"><i class="fas fa-tasks"></i>To-Do List</a>-->
    <a href="../html/chat bot.html"><i class="fas fa-robot"></i>Chatbot</a>
    <a href="blog.html"><i class="fas fa-blog"></i>Blog</a>
    <a href="../html/settings.html"><i class="fa fa-cog"></i>Settings</a>
    </div>
  <div class="main-content">
    <div class="welcome-header">
      <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>

<!--Reusable Search Bar -->
<form action="../php/book.php" method="GET" class="library-search-bar">
  <input
    type="text"
    name="query"
    placeholder="Search books, papers, thesis, settings..."
    required
  />
  <button type="submit">Search</button>
</form>

<style>
  .library-search-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 30px auto;
    gap: 12px;
    max-width: 600px;
    padding: 10px;
  }

  .library-search-bar input[type="text"] {
    flex: 1;
    padding: 12px 16px;
    font-size: 16px;
    border-radius: 12px;
    border: 1px solid #ccc;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  }

  .library-search-bar button {
    padding: 12px 18px;
    font-size: 16px;
    border-radius: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .library-search-bar button:hover {
    background-color: #0056b3;
  }

  @media (max-width: 600px) {
    .library-search-bar {
      flex-direction: column;
      gap: 10px;
    }

    .library-search-bar input[type="text"],
    .library-search-bar button {
      width: 100%;
    }
  }
</style>


    <div class="reminder-alert">
      ⏰ 
      <a href="#" style="color: black;"><i class="fa fa-clock-o" ></i>Book Return Reminders: "2 books due in 3 days"</a>
    </div>

    <div class="cards">
      <div class="card">
        <i class="fas fa-microphone-alt"></i>
        <h3>Lectures from Teachers</h3>
      </div>
      <div class="card">
        <i class="fas fa-file-contract"></i>
        <h3>Latest Research Papers</h3>
      </div>
      <div class="card">
        <i class="fas fa-calendar-check"></i>
        <h3>Exam Schedule</h3>
      </div>
      <div class="card">
        <i class="fas fa-book-open"></i>
        <h3>E-Resources</h3>
      </div>
      <div class="card">
        <i class="fas fa-comments"></i>
        <h3>Chatbot Assistance</h3>
      </div>
      <div class="card">
        <i class="fas fa-tasks"></i>
        <h3>My To-Do List</h3>
      </div>
    </div>



  <script src="../js/script STD.js"></script>
</body>
</html>