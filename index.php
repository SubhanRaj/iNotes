<?PHP

$insert = false;
$update = false;
$delete = false;

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "iNotes";
// Create a connection

$conn = mysqli_connect($servername, $username, $password, $databasename);

// Die if connection was not successful
if (!$conn) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
}

// exit();
// Inserting data in database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {

        // Update the record

        $sno = $_POST["snoEdit"];
        // Convert sno edit to integer
        $sno = (int)$sno;
        $title = $_POST["note_titleEdit"];
        $description = $_POST["descriptionEdit"];

        // SQL query to update data in database

        $sql = "UPDATE `notes` SET `note_title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno;";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $update = true;
            echo "<br>";
            echo $sql;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
            echo "<br>";
            echo $sql;
        }
    } else {

        $title = $_POST["note_title"];
        $description = $_POST["description"];

        // SQL query to insert data in database
        $sql = "INSERT INTO `notes` (`note_title`, `description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // echo "The record has been inserted sucessfully<br>";
            $insert = true;
        } else {
            echo "The record was not inserted sucessfull becaue of this error ---> " . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="rgb(0,0,0)">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Data table css -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <title>iNotes : A PHP CRUD Notes App</title>
</head>

<body>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit This Note</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>

                </div>

                <form action="/iNotes/index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group mb-3">
                            <label for="note_title" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="note_titleEdit" name="note_titleEdit" aria-describedby="textHelp">

                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3" cols="10"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Note</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/iNotes">iNotes : A PHP CRUD Notes App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/phpmyadmin" target="_blank">PHPMyAdmin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Alert when user insert note sucessfully -->
    <?PHP

    if ($insert) {
        echo "<div class='alert alert-success warning alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been inserted sucessfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
    }

    ?>

    <!-- FOrm for taking notes -->
    <div class="container my-3">
        <h2>Add a Note</h2>
        <form action="/iNotes/index.php" method="POST">
            <div class="form-group mb-3">
                <label for="note_title" Note Title</label>
                    <input type="text" class="form-control" id="note_title" name="note_title" aria-describedby="textHelp">

            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" cols="10"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>
    <div class="container my-4">

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?PHP

                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    echo "<tr>
                    <th scope='row'>" . $sno . "</th>
                    <td>" . $row['note_title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td><button class='edit btn btn-sm btn-primary' id = " . $row['sno'] . "]>Edit</button> <a href='/del'>Delete</a></td>
                </tr>";
                    echo $sql;
                }

                ?>

            </tbody>
        </table>

    </div>
    <hr>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                tr = e.target.parentNode.parentNode;
                note_title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                console.log(note_title, description);
                note_titleEdit.value = note_title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.getAttribute("id");
                $('#editModal').modal('show');

                console.log(e.target.id)
                // $('#editModal').modal('toggle');


                // console.log(snoEdit);
                // Why snoEdt picks up ] after the value of serial number?
                // Because it is a string and not an integer
                // Convert snoEdit to integer
                // snoEdit = parseInt(snoEdit);
                // console.log(snoEdit);
                // Pic up serial number of the row
                // snoEdit = e.target.getAttribute("id");
                // $('#editModal').modal('toggle');
            })
        })
    </script>
</body>

</html>