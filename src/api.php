<?php
header("Access-Control-Allow-Origin: http://localhost:5173");

// Allow specific HTTP methods (e.g., POST, GET, DELETE)
header("Access-Control-Allow-Methods: POST, GET, DELETE");

// Allow specific HTTP headers (if needed)
header("Access-Control-Allow-Headers: Content-Type");

// Set the response content type to JSON
header("Content-Type: application/json");
// Include your database configuration here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "note";

// Create a new note
function createNote($text, $color, $date) {
    global $servername, $username, $password, $dbname;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO notes (text, color, date) VALUES (:text, :color, :date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        return true; // Successfully added the note
    } catch (PDOException $e) {
        return false; // Error occurred while adding the note
    } finally {
        $conn = null;
    }
}

// Read all notes
function getNotes() {
    global $servername, $username, $password, $dbname;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM notes";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        return false; // Error occurred while fetching notes
    } finally {
        $conn = null;
    }
}

// Update a note (if needed)
function updateNote($id, $text, $color) {
    global $servername, $username, $password, $dbname;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE notes SET text = :text, color = :color WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return true; // Successfully updated the note
    } catch (PDOException $e) {
        return false; // Error occurred while updating the note
    } finally {
        $conn = null;
    }
}

// Delete a note
function deleteNote($id) {
    global $servername, $username, $password, $dbname;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM notes WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return true; // Successfully deleted the note
    } catch (PDOException $e) {
        return false; // Error occurred while deleting the note
    } finally {
        $conn = null;
    }
}

// Handle API requests here (POST, GET, PUT, DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to create a new note
    $data = json_decode(file_get_contents("php://input"), true);
    if (createNote($data['text'], $data['color'], $data['date'])) {
        echo json_encode(["message" => "Note created successfully"]);
    } else {
        echo json_encode(["message" => "Failed to create note"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to retrieve all notes
    $notes = getNotes();
    if ($notes !== false) {
        echo json_encode($notes);
    } else {
        echo json_encode(["message" => "Failed to retrieve notes"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update a note
    // You can implement this if needed
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a note
    // You can implement this if needed
}
?>
