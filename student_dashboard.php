<?php
require_once 'config.php';

// Check if student is logged in
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit;
}

// Borrow book functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrow_book'])) {
    $book_id = sanitize_input($_POST['book_id']);
    
    // Check if book is available
    $check_sql = "SELECT * FROM books WHERE book_id = ? AND quantity > 0";
    $stmt = $conn->prepare($check_sql);
    if ($stmt === false) {
        $error_message = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Update book quantity
                $update_sql = "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                if ($update_stmt === false) {
                    throw new Exception("Failed to prepare update statement: " . $conn->error);
                }
                $update_stmt->bind_param("s", $book_id);
                $update_stmt->execute();
                
                // Record borrowing
                $borrow_sql = "INSERT INTO borrow_history (book_id, user_id, borrow_date) VALUES (?, ?, NOW())";
                $borrow_stmt = $conn->prepare($borrow_sql);
                if ($borrow_stmt === false) {
                    throw new Exception("Failed to prepare borrow statement: " . $conn->error);
                }
                $borrow_stmt->bind_param("ss", $book_id, $_SESSION['user_id']);
                $borrow_stmt->execute();
                
                $conn->commit();
                $success_message = "Book borrowed successfully!";
            } catch (Exception $e) {
                $conn->rollback();
                $error_message = "Error occurred while borrowing the book: " . $e->getMessage();
            }
        } else {
            $error_message = "Book is not available for borrowing.";
        }
    }
}

// Return book functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    $borrow_id = sanitize_input($_POST['borrow_id']);
    $book_id = sanitize_input($_POST['book_id']);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update book quantity
        $update_sql = "UPDATE books SET quantity = quantity + 1 WHERE book_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            throw new Exception("Failed to prepare update statement: " . $conn->error);
        }
        $update_stmt->bind_param("s", $book_id);
        $update_stmt->execute();
        
        // Update return date
        $return_sql = "UPDATE borrow_history SET return_date = NOW() WHERE book_id = ? AND user_id = ? AND return_date IS NULL";
        $return_stmt = $conn->prepare($return_sql);
        if ($return_stmt === false) {
            throw new Exception("Failed to prepare return statement: " . $conn->error);
        }
        $return_stmt->bind_param("ss", $borrow_id, $_SESSION['user_id']);
        $return_stmt->execute();
        
        $conn->commit();
        $success_message = "Book returned successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error occurred while returning the book: " . $e->getMessage();
    }
}


// Search functionality
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$search_condition = !empty($search) ? "WHERE title LIKE CONCAT('%', ?, '%') OR author LIKE CONCAT('%', ?, '%') OR isbn LIKE CONCAT('%', ?, '%')" : '';

// Fetch available books
if (empty($search)) {
    $books_sql = "SELECT * FROM books ORDER BY title";
    $books_stmt = $conn->prepare($books_sql);
} else {
    $books_sql = "SELECT * FROM books WHERE title LIKE CONCAT('%', ?, '%') OR author LIKE CONCAT('%', ?, '%') OR isbn LIKE CONCAT('%', ?, '%') ORDER BY title";
    $books_stmt = $conn->prepare($books_sql);
    if ($books_stmt === false) {
        $error_message = "Database error: " . $conn->error;
    } else {
        $books_stmt->bind_param("sss", $search, $search, $search);
    }
}

if (isset($books_stmt) && $books_stmt !== false) {
    $books_stmt->execute();
    $books_result = $books_stmt->get_result();
} else {
    $books_result = false;
}

// Fetch borrowed books
$borrowed_sql = "SELECT bh.*, b.title, b.author, b.isbn 
                 FROM borrow_history bh 
                 JOIN books b ON bh.book_id = b.book_id 
                 WHERE bh.user_id = ? 
                 ORDER BY bh.borrow_date DESC";
$borrowed_stmt = $conn->prepare($borrowed_sql);
if ($borrowed_stmt === false) {
    $error_message = "Database error: " . $conn->error;
} else {
    $borrowed_stmt->bind_param("i", $_SESSION['user_id']);
    $borrowed_stmt->execute();
    $borrowed_result = $borrowed_stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px;">
        <h4 class="mb-4">Library System</h4>
        <div class="mb-4">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="#available">Available Books</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#borrowed">My Books</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="index.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Available Books Section -->
        <div class="table-container" id="book_id">
            <h3 class="mb-4">Available Books</h3>
            
            <!-- Search Form -->
            <form class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search books..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <?php if ($books_result): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Genre</th>
                            <th>Publication Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $books_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                            <td><?php echo htmlspecialchars($book['genre']); ?></td>
                            <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                            <td>
                                <?php if($book['quantity'] > 0): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>">
                                    <button type="submit" name="borrow_book" class="btn btn-sm btn-primary">Borrow</button>
                                </form>
                                <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Not Available</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center">Error loading books. Please try again later.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Borrowed Books Section -->
        <div class="table-container" id="borrowed">
            <h3 class="mb-4">My Books</h3>
            <div class="table-responsive">
                <?php if (isset($borrowed_result)): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($borrowed = $borrowed_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($borrowed['title']); ?></td>
                            <td><?php echo htmlspecialchars($borrowed['author']); ?></td>
                            <td><?php echo htmlspecialchars($borrowed['isbn']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($borrowed['borrow_date'])); ?></td>
                            <td>
                                <?php echo $borrowed['return_date'] ? date('Y-m-d', strtotime($borrowed['return_date'])) : '-'; ?>
                            </td>
                            <td>
                                <?php if(!$borrowed['return_date']): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="borrow_id" value="<?php echo htmlspecialchars($borrowed['book_id']); ?>">
                                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($borrowed['book_id']); ?>">
                                    <button type="submit" name="return_book" class="btn btn-sm btn-success">Return</button>
                                </form>
                                <?php else: ?>
                                <span class="badge bg-success">Returned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-center">Error loading borrowed books. Please try again later.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
