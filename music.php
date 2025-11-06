<!-- music.php -->
<?php
include 'header.php';

// Get search parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$genre = isset($_GET['genre']) ? intval($_GET['genre']) : 0;
$artist = isset($_GET['artist']) ? intval($_GET['artist']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : 0;

// Build query
$music_query = "SELECT m.*, a.artist_name, g.genre_name, al.album_name, al.cover_image
               FROM music m 
               LEFT JOIN artist a ON m.artist_id = a.artist_id 
               LEFT JOIN genre g ON m.genre_id = g.genre_id 
               LEFT JOIN album al ON m.album_id = al.album_id 
               WHERE 1=1";

if (!empty($search)) {
    $music_query .= " AND (m.title LIKE '%$search%' OR a.artist_name LIKE '%$search%' OR al.album_name LIKE '%$search%')";
}

if ($genre > 0) {
    $music_query .= " AND m.genre_id = $genre";
}

if ($artist > 0) {
    $music_query .= " AND m.artist_id = $artist";
}

if ($year > 0) {
    $music_query .= " AND m.year = $year";
}

$music_query .= " ORDER BY m.created_at DESC";
$music_result = mysqli_query($conn, $music_query);

// Get filters for dropdowns
$genres = mysqli_query($conn, "SELECT * FROM genre ORDER BY genre_name");
$artists = mysqli_query($conn, "SELECT * FROM artist ORDER BY artist_name");
$years = mysqli_query($conn, "SELECT DISTINCT year FROM music ORDER BY year DESC");
?>

<div class="container py-5">
    <h1 class="mb-4" data-aos="fade-right">Music Library</h1>
    
    <!-- Search and Filter Section -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <form method="GET" action="music.php">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search music..." value="<?php echo $search; ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="genre" class="form-select">
                            <option value="0">All Genres</option>
                            <?php while($g = mysqli_fetch_assoc($genres)): ?>
                                <option value="<?php echo $g['genre_id']; ?>" <?php echo $genre == $g['genre_id'] ? 'selected' : ''; ?>>
                                    <?php echo $g['genre_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="artist" class="form-select">
                            <option value="0">All Artists</option>
                            <?php while($a = mysqli_fetch_assoc($artists)): ?>
                                <option value="<?php echo $a['artist_id']; ?>" <?php echo $artist == $a['artist_id'] ? 'selected' : ''; ?>>
                                    <?php echo $a['artist_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="year" class="form-select">
                            <option value="0">All Years</option>
                            <?php while($y = mysqli_fetch_assoc($years)): ?>
                                <option value="<?php echo $y['year']; ?>" <?php echo $year == $y['year'] ? 'selected' : ''; ?>>
                                    <?php echo $y['year']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Music Grid -->
    <div class="row">
        <?php if(mysqli_num_rows($music_result) > 0): ?>
            <?php while($music = mysqli_fetch_assoc($music_result)): ?>
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card music-card h-100">
                        <?php if($music['is_new']): ?>
                            <span class="new-badge">NEW</span>
                        <?php endif; ?>
                        <img src="images/<?php echo $music['cover_image'] ?: 'images/thumbnail_img/default-music.jpg'; ?>" class="card-img-top" alt="<?php echo $music['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $music['title']; ?></h5>
                            <p class="card-text text-muted"><?php echo $music['artist_name']; ?></p>
                            <p class="card-text small"><?php echo $music['album_name'] ? 'Album: ' . $music['album_name'] : ''; ?></p>
                            <p class="card-text small text-muted">Genre: <?php echo $music['genre_name']; ?> | Year: <?php echo $music['year']; ?></p>
                            
                            <div class="media-player mt-3">
                                <audio controls class="w-100">
                                    <source src="<?php echo $music['file_path']; ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                            
                            <!-- Ratings and Reviews Section -->
                            <div class="mt-3">
                                <?php include 'rating_review_section.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <h3>No music found</h3>
                <p>Try adjusting your search criteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>