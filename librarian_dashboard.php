<?php
require_once 'config.php';

// Check if librarian is logged in
if (!isLibrarian()) {
    header("location: index.php");
    exit;
}

// Add new book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $book_id = sanitize_input($_POST['book_id']);
    $title = sanitize_input($_POST['title']);
    $author = sanitize_input($_POST['author']);
    $genre = sanitize_input($_POST['genre']);
    $isbn = sanitize_input($_POST['isbn']);
    $quantity = sanitize_input($_POST['quantity']);
    $publication_year = sanitize_input($_POST['publication_year']);
 // First check if book_id already exists
 $check_sql = "SELECT book_id FROM books WHERE book_id = ?";
 $check_stmt = $conn->prepare($check_sql);
 $check_stmt->bind_param("s", $book_id);
 $check_stmt->execute();
 $result = $check_stmt->get_result();

 if ($result->num_rows > 0) {
     echo "Error: Book ID already exists";
 } else {
     $sql = "INSERT INTO books (book_id, title, author, genre, isbn, quantity, publication_year) 
             VALUES (?, ?, ?, ?, ?, ?, ?)";
     if ($stmt = $conn->prepare($sql)) {
         $stmt->bind_param("sssssii", $book_id, $title, $author, $genre, $isbn, $quantity, $publication_year);
         if ($stmt->execute()) {
             echo "Book added successfully!";
         } else {
             echo "Error adding book: " . $stmt->error;
         }
     }
 }
}

// The delete book functionality
if (isset($_POST['delete_book'])) {
    $book_id = sanitize_input($_POST['book_id']);
    
    // Check if book is currently borrowed
    $check_sql = "SELECT * FROM borrow_history WHERE book_id = ? AND return_date IS NULL";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Cannot delete book - it is currently borrowed by someone";
    } else {
        $sql = "DELETE FROM books WHERE book_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $book_id);
            if ($stmt->execute()) {
                echo "Book deleted successfully";
            } else {
                echo "Error deleting book: " . $stmt->error;
            }
        }
    }
}

// Update book
if (isset($_POST['update_book'])) {
    $book_id = sanitize_input($_POST['edit_book_id']);
    $title = sanitize_input($_POST['edit_title']);
    $author = sanitize_input($_POST['edit_author']);
    $genre = sanitize_input($_POST['edit_genre']);
    $publication_year = sanitize_input($_POST['edit_publication_year']);
    $quantity = sanitize_input($_POST['edit_quantity']);

    $sql = "UPDATE books SET title = ?, author = ?, genre = ?, publication_year = ?, quantity = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssiii", $title, $author, $genre, $publication_year, $quantity, $book_id);
        $stmt->execute();
    }
}

// Search functionality
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$search_condition = !empty($search) ? "WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'" : '';

// Calculate statistics
$total_books = 0;
$borrowed_books = 0;
$total_students = 0;

// Get total books
$total_books_query = "SELECT COUNT(*) as count FROM books";
$total_books_result = $conn->query($total_books_query);
if ($total_books_result) {
    $total_books = $total_books_result->fetch_assoc()['count'];
}

// Get borrowed books
$borrowed_books_query = "SELECT COUNT(*) as count FROM borrow_history WHERE return_date IS NULL";
$borrowed_books_result = $conn->query($borrowed_books_query);
if ($borrowed_books_result) {
    $borrowed_books = $borrowed_books_result->fetch_assoc()['count'];
}

// Get total students
$total_students_query = "SELECT COUNT(*) as count FROM students";
$total_students_result = $conn->query($total_students_query);
if ($total_students_result) {
    $total_students = $total_students_result->fetch_assoc()['count'];
}

// Fetch books with error handling
$books_sql = "SELECT * FROM books $search_condition ORDER BY title";
$books_result = $conn->query($books_sql);
if (!$books_result) {
    error_log("Error in books query: " . $conn->error);
    $books_result = false;
}

// Fetch borrowing history with error handling
$history_sql = "SELECT bh.*, b.title, u.name as user_name, 
                       bh.borrow_date, bh.return_date
                FROM borrow_history bh 
                JOIN books b ON bh.book_id = b.book_id 
                JOIN students u ON bh.student_id = u.student_id 
                ORDER BY bh.borrow_date DESC";
$history_result = $conn->query($history_sql);
if (!$history_result) {
    error_log("Error in history query: " . $conn->error);
    $history_result = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            background-color: #343a40;
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .stats-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px;">
        <h4 class="mb-4">Library System</h4>
        <div class="mb-4">
            <p>Welcome, <?php echo $_SESSION['name']; ?></p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="#books">Book Management</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#history">Borrowing History</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="index.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5>Total Books</h5>
                    <h2><?php echo $total_books; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5>Currently Borrowed</h5>
                    <h2><?php echo $borrowed_books; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5>Registered Students</h5>
                    <h2><?php echo $total_students; ?></h2>
                </div>
            </div>
        </div>

        <!-- Book Management Section -->
        <div class="table-container">
        <!-- Book Management Section -->
        <div class="table-container mb-4" id="books">
            <h3>Book Management</h3>
            <!-- Add New Book Button -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    Add New Book
                </button>
            <!-- Books Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>ISBN</th>
                            <th>Quantity</th>
                            <th>Publication Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($books_result): ?>
                            <?php while($book = $books_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['book_id']); ?></td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                                <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBookModal<?php echo $book['book_id']; ?>">
                                        Edit
                                    </button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                        <button type="submit" name="delete_book" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Book Modal -->
                            <div class="modal fade" id="editBookModal<?php echo $book['book_id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Book</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="post">
                                            <div class="modal-body">
                                                <input type="hidden" name="edit_book_id" value="<?php echo $book['book_id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="edit_title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Author</label>
                                                    <input type="text" name="edit_author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Genre</label>
                                                    <input type="text" name="edit_genre" class="form-control" value="<?php echo htmlspecialchars($book['genre']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Publication Year</label>
                                                    <input type="number" name="edit_publication_year" class="form-control" value="<?php echo htmlspecialchars($book['publication_year']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="edit_quantity" class="form-control" value="<?php echo htmlspecialchars($book['quantity']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_book" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Borrowing History Section -->
        <div class="table-container" id="history">
            <h3 class="mb-4">Borrowing History</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Book Title</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($history_result): ?>
                            <?php while($history = $history_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($history['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($history['title']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($history['borrow_date'])); ?></td>
                                <td><?php echo $history['return_date'] ? date('Y-m-d', strtotime($history['return_date'])) : '-'; ?></td>
                                <td>
                                    <?php if($history['return_date']): ?>
                                        <span class="badge bg-success">Returned</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Book ID</label>
                            <input type="text" name="book_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <input type="text" name="genre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publication Year</label>
                            <input type="number" name="publication_year" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>