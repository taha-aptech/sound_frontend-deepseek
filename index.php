<!-- index.php -->
<?php
include 'header.php';

// Fetch latest music (5 tracks)
$latest_music_query = "SELECT m.*, a.artist_name, g.genre_name, al.album_name 
                      FROM music m 
                      LEFT JOIN artist a ON m.artist_id = a.artist_id 
                      LEFT JOIN genre g ON m.genre_id = g.genre_id 
                      LEFT JOIN album al ON m.album_id = al.album_id 
                      ORDER BY m.created_at DESC 
                      LIMIT 5";
$latest_music_result = mysqli_query($conn, $latest_music_query);

// Fetch latest videos (5 videos)
$latest_videos_query = "SELECT v.*, a.artist_name, g.genre_name, al.album_name 
                       FROM video v 
                       LEFT JOIN artist a ON v.artist_id = a.artist_id 
                       LEFT JOIN genre g ON v.genre_id = g.genre_id 
                       LEFT JOIN album al ON v.album_id = al.album_id 
                       ORDER BY v.created_at DESC 
                       LIMIT 5";
$latest_videos_result = mysqli_query($conn, $latest_videos_query);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div data-aos="fade-up">
            <h1 class="display-4 fw-bold mb-4">Welcome to SOUND Entertainment</h1>
            <p class="lead mb-5">Discover the latest music and videos from your favorite artists</p>
            <a href="music.php" class="btn btn-primary btn-lg me-3">Explore Music</a>
            <a href="video.php" class="btn btn-outline-light btn-lg">Watch Videos</a>
        </div>
    </div>
</section>

<!-- Latest Music Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-right">Latest Music</h2>
        <div class="row">
            <?php while($music = mysqli_fetch_assoc($latest_music_result)): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="card music-card h-100">
                    <?php if($music['is_new']): ?>
                        <span class="new-badge">NEW</span>
                    <?php endif; ?>
                    <img src="<?php echo $music['thumbnail_img'] ?: 'images/thumbnail_img/default-music.jpg'; ?>" class="card-img-top" alt="<?php echo $music['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $music['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo $music['artist_name']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><?php echo $music['genre_name']; ?></small>
                            <audio controls class="w-75">
                                <source src="<?php echo $music['file_path']; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="music.php" class="btn btn-primary">View All Music</a>
        </div>
    </div>
</section>

<!-- Latest Videos Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title" data-aos="fade-right">Latest Videos</h2>
        <div class="row">
            <?php while($video = mysqli_fetch_assoc($latest_videos_result)): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="card video-card h-100">
                    <?php if($video['is_new']): ?>
                        <span class="new-badge">NEW</span>
                    <?php endif; ?>
                    <img src="<?php echo $video['thumbnail_img'] ?: 'images/thumbnail_img/default-video.jpg'; ?>" class="card-img-top" alt="<?php echo $video['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $video['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo $video['artist_name']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><?php echo $video['genre_name']; ?></small>
                            <a href="<?php echo $video['file_path']; ?>" target="_blank" class="btn btn-sm btn-primary">Watch</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="video.php" class="btn btn-primary">View All Videos</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="section-title">About SOUND Entertainment</h2>
                <p class="lead">Your ultimate destination for music and video entertainment.</p>
                <p>We bring you the latest and greatest in music and videos from around the world. Discover new artists, explore different genres, and enjoy high-quality entertainment.</p>
                <a href="about.php" class="btn btn-primary">Learn More</a>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <img src="images/about-img.jpg" alt="About SOUND Entertainment" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>