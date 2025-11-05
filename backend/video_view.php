<?php
include 'dbconnect.php';
include 'auth_check.php';
include 'header.php';


?>

<?php
$message = "";

// CREATE / UPDATE Video
if (isset($_POST['save_video'])) {
    $video_id     = $_POST['video_id'] ?? '';
    $title        = trim($_POST['title']);
    $artist_id    = $_POST['artist_id'];
    $album_id     = $_POST['album_id'];
    $genre_id     = $_POST['genre_id'];
    $language_id  = $_POST['language_id'];
    $year         = trim($_POST['year']);
    $description  = trim($_POST['description']);
    $is_new       = isset($_POST['is_new']) ? 1 : 0;
    $created_at   = date('Y-m-d H:i:s');

    if (!preg_match("/^[A-Za-z0-9\s]+$/", $title)) {
        $message = "<div class='alert alert-danger'>Only letters, numbers, and spaces allowed in title!</div>";
    } else {
        // Handle File Upload
        $fileName  = $_FILES['file_path']['name'];
        $tmpName   = $_FILES['file_path']['tmp_name'];
        $thumbName = $_FILES['thumbnail_img']['name'];
        $thumbTmp  = $_FILES['thumbnail_img']['tmp_name'];

        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTarget  = $uploadDir . basename($fileName);
        $thumbTarget = $uploadDir . basename($thumbName);

        if (!empty($fileName)) move_uploaded_file($tmpName, $fileTarget);
        if (!empty($thumbName)) move_uploaded_file($thumbTmp, $thumbTarget);

        $fileToSave  = !empty($fileName) ? $fileName : ($_POST['old_file'] ?? '');
        $thumbToSave = !empty($thumbName) ? $thumbName : ($_POST['old_thumb'] ?? '');

        // Insert or Update
        if ($video_id == "") {
            $sql = "INSERT INTO video (title, artist_id, album_id, genre_id, language_id, year, file_path, description, thumbnail_img, is_new, created_at)
                    VALUES ('$title', '$artist_id', '$album_id', '$genre_id', '$language_id', '$year', '$fileToSave', '$description', '$thumbToSave', '$is_new', '$created_at')";
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-success'>Video added successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            $sql = "UPDATE video SET 
                        title='$title',
                        artist_id='$artist_id',
                        album_id='$album_id',
                        genre_id='$genre_id',
                        language_id='$language_id',
                        year='$year',
                        file_path='$fileToSave',
                        description='$description',
                        thumbnail_img='$thumbToSave',
                        is_new='$is_new'
                    WHERE video_id=$video_id";
            if (mysqli_query($conn, $sql)) {
                $message = "<div class='alert alert-warning'>Video updated successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// DELETE Video
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM video WHERE video_id=$id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Video deleted successfully!'); window.location.href='video_crud.php';</script>";
        exit;
    }
}

// FETCH ALL VIDEOS
$result = mysqli_query($conn, "SELECT * FROM video ORDER BY video_id ASC");

// FETCH Dropdown Data
$artists   = mysqli_query($conn, "SELECT artist_id, artist_name FROM artist");
$albums    = mysqli_query($conn, "SELECT album_id, album_name FROM album");
$genres    = mysqli_query($conn, "SELECT genre_id, genre_name FROM genre");
$languages = mysqli_query($conn, "SELECT language_id, language_name FROM language");
?>

<div class="container bg-white p-4 rounded shadow-sm mt-4">
    <h3 class="text-center mb-3">Video Management</h3>

    <?= $message; ?>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal" onclick="openAddModal()">+ Add Video</button>
    </div>

    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Genre</th>
                <th>Language</th>
                <th>Year</th>
                <th>File</th>
                <th>Thumbnail</th>
                <th>Is New</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['video_id']; ?></td>
                <td><?= htmlspecialchars($row['title']); ?></td>
                <td><?= $row['artist_id']; ?></td>
                <td><?= $row['album_id']; ?></td>
                <td><?= $row['genre_id']; ?></td>
                <td><?= $row['language_id']; ?></td>
                <td><?= htmlspecialchars($row['year']); ?></td>
                <td>
                    <?php if (!empty($row['file_path'])) { ?>
                        <a href="uploads/<?= htmlspecialchars($row['file_path']); ?>" target="_blank">View</a>
                    <?php } else { echo "No File"; } ?>
                </td>
                <td>
                    <?php if (!empty($row['thumbnail_img'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($row['thumbnail_img']); ?>" width="70" height="70" style="object-fit:cover;">
                    <?php } else { echo "No Image"; } ?>
                </td>
                <td><?= $row['is_new'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <button class="btn btn-sm btn-warning"
                        onclick="openEditModal('<?= $row['video_id']; ?>','<?= htmlspecialchars($row['title']); ?>','<?= $row['artist_id']; ?>','<?= $row['album_id']; ?>','<?= $row['genre_id']; ?>','<?= $row['language_id']; ?>','<?= $row['year']; ?>','<?= htmlspecialchars($row['description']); ?>','<?= $row['file_path']; ?>','<?= $row['thumbnail_img']; ?>','<?= $row['is_new']; ?>')">
                        <i class='fas fa-edit'></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['video_id']; ?>)">
                        <i class='fas fa-trash-alt'></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Video</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="video_id" id="video_id">
            <input type="hidden" name="old_file" id="old_file">
            <input type="hidden" name="old_thumb" id="old_thumb">

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Artist</label>
                    <select name="artist_id" id="artist_id" class="form-select" required>
                        <option value="">Select Artist</option>
                        <?php while ($a = mysqli_fetch_assoc($artists)) { ?>
                            <option value="<?= $a['artist_id']; ?>"><?= htmlspecialchars($a['artist_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Album</label>
                    <select name="album_id" id="album_id" class="form-select">
                        <option value="">Select Album</option>
                        <?php while ($al = mysqli_fetch_assoc($albums)) { ?>
                            <option value="<?= $al['album_id']; ?>"><?= htmlspecialchars($al['album_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Genre</label>
                    <select name="genre_id" id="genre_id" class="form-select">
                        <option value="">Select Genre</option>
                        <?php while ($g = mysqli_fetch_assoc($genres)) { ?>
                            <option value="<?= $g['genre_id']; ?>"><?= htmlspecialchars($g['genre_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Language</label>
                    <select name="language_id" id="language_id" class="form-select">
                        <option value="">Select Language</option>
                        <?php while ($l = mysqli_fetch_assoc($languages)) { ?>
                            <option value="<?= $l['language_id']; ?>"><?= htmlspecialchars($l['language_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Video File</label>
                    <input type="file" name="file_path" id="file_path" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Thumbnail Image</label>
                    <input type="file" name="thumbnail_img" id="thumbnail_img" class="form-control">
                </div>
                <div class="col-md-6 mt-2">
                    <input type="checkbox" name="is_new" id="is_new">
                    <label for="is_new">Mark as New</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="save_video" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openAddModal() {
    document.getElementById("modalTitle").innerText = "Add Video";
    document.querySelectorAll("input, textarea, select").forEach(el => el.value = "");
    document.getElementById("is_new").checked = false;
}

function openEditModal(id, title, artist, album, genre, lang, year, desc, file, thumb, isNew) {
    document.getElementById("modalTitle").innerText = "Edit Video";
    document.getElementById("video_id").value = id;
    document.getElementById("title").value = title;
    document.getElementById("artist_id").value = artist;
    document.getElementById("album_id").value = album;
    document.getElementById("genre_id").value = genre;
    document.getElementById("language_id").value = lang;
    document.getElementById("year").value = year;
    document.getElementById("description").value = desc;
    document.getElementById("old_file").value = file;
    document.getElementById("old_thumb").value = thumb;
    document.getElementById("is_new").checked = isNew == 1;
    var modal = new bootstrap.Modal(document.getElementById('videoModal'));
    modal.show();
}

function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this video?")) {
        window.location.href = 'video_crud.php?delete=' + id;
    }
}
</script>

<?php include 'footer.php'; ?>
