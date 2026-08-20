<?php
$db = new PDO('sqlite:/var/www/html/db/comments.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS comments (id INTEGER PRIMARY KEY AUTOINCREMENT, post_id INTEGER, author TEXT, comment TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['post_id'])) {
    $author = isset($_POST['author']) ? $_POST['author'] : 'Anonymous';
    $comment = $_POST['comment'];
    $post_id = (int)$_POST['post_id'];
    $stmt = $db->prepare("INSERT INTO comments (post_id, author, comment) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $author, $comment]);
    header("Location: index.php?post=" . $post_id);
    exit();
}

$post_id = isset($_GET['post']) ? (int)$_GET['post'] : 1;

$posts = [
    1 => [
        'title' => 'Samsung Galaxy S24 Ultra Review – The Ultimate Android Experience',
        'author' => 'TechReviewer',
        'date' => 'August 19, 2026',
        'category' => 'Flagship Phones',
        'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=1200&h=600&fit=crop',
        'content' => "<p>Samsung's Galaxy S24 Ultra is here, and it's nothing short of a powerhouse. With a stunning 6.8-inch Dynamic AMOLED 2X display, this phone is a visual treat. The 120Hz refresh rate makes scrolling butter-smooth, and the QHD+ resolution ensures every detail pops.</p><p>Under the hood, the Snapdragon 8 Gen 3 processor handles everything with ease. Whether you're gaming, multitasking, or editing 8K videos, this phone doesn't break a sweat. The 12GB of RAM and 256GB storage offer plenty of space for all your apps and files.</p><p>The camera system is where the S24 Ultra truly shines. The 200MP main sensor captures incredible detail, even in low light. The 12MP ultra-wide and 10MP telephoto lenses are versatile for any scene. Zoom capabilities are unmatched, with up to 100x Space Zoom that brings distant subjects closer.</p><p>Battery life is impressive with a 5000mAh battery that easily lasts a full day. Fast charging gets you from 0 to 65% in just 30 minutes. The S Pen adds a new dimension of productivity, making it perfect for creators and professionals alike.</p><p>However, the price tag is steep. The S24 Ultra isn't for everyone, but if you want the best Android phone money can buy, this is it.</p>"
    ],
    2 => [
        'title' => 'iPhone 15 Pro Max – Does Apple Still Lead?',
        'author' => 'AppleFan',
        'date' => 'August 17, 2026',
        'category' => 'Flagship Phones',
        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=600&fit=crop',
        'content' => "<p>Apple's iPhone 15 Pro Max is a testament to refined design and powerful performance. The titanium frame gives it a premium feel while reducing weight compared to previous models. The 6.7-inch Super Retina XDR display is bright, sharp, and perfect for HDR content.</p><p>The A17 Pro chip brings console-level gaming to a smartphone. With hardware-accelerated ray tracing, games like Resident Evil and Assassin's Creed run smoothly. The 8GB of RAM ensures seamless multitasking.</p><p>The camera system is improved with a 48MP main sensor, offering 2x optical quality zoom. The 12MP ultra-wide and telephoto lenses are reliable. The USB-C port is a welcome change, enabling faster data transfer and 15W charging with a 30W adapter.</p><p>Battery life is solid, lasting up to 29 hours of video playback. However, the lack of a charger in the box and limited customization may disappoint some users. The iPhone 15 Pro Max is powerful but pricey.</p>"
    ],
    3 => [
        'title' => 'OnePlus 12 – Flagship Killer Returns?',
        'author' => 'OnePlusLover',
        'date' => 'August 15, 2026',
        'category' => 'Performance Phones',
        'image' => 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=1200&h=600&fit=crop',
        'content' => "<p>OnePlus has been quiet, but the OnePlus 12 proves they still know how to make a flagship. The design is sleek with a ceramic glass back and aluminum frame. The 6.7-inch AMOLED display with 120Hz refresh rate is gorgeous and responsive.</p><p>The Snapdragon 8 Gen 3 processor handles everything effortlessly, and the 12GB of RAM ensures smooth multitasking. The battery is a standout feature—5400mAh with 100W fast charging, reaching 100% in 24 minutes. This is the fastest charging in the industry.</p><p>The camera system is a triple setup with a 50MP main sensor, 48MP ultra-wide, and 64MP telephoto. The partnership with Hasselblad delivers natural colors and excellent dynamic range. Video recording is smooth, with 8K at 24fps.</p><p>OnePlus's OxygenOS is clean and fast, with minimal bloatware. However, the phone is a bit heavy and the curved edges may cause accidental touches. Overall, the OnePlus 12 offers great value for the price.</p>"
    ]
];

$current_post = isset($posts[$post_id]) ? $posts[$post_id] : $posts[1];

$stmt = $db->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY id DESC");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $current_post['title']; ?> – TechReview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f6fa; color: #1a1a2e; }
        .top-bar { background: #0f0f23; color: #fff; padding: 14px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .top-bar .logo { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .top-bar .logo span { color: #ff6b35; }
        .top-bar .nav a { color: #8888aa; margin-left: 25px; text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.3s; }
        .top-bar .nav a:hover { color: #ff6b35; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px 5% 40px; }
        .breadcrumb { font-size: 13px; color: #8888aa; margin-bottom: 20px; }
        .breadcrumb a { color: #ff6b35; text-decoration: none; }
        .breadcrumb span { color: #555; }
        .blog-header { margin-bottom: 25px; }
        .blog-header .category { display: inline-block; background: #ff6b35; color: #fff; padding: 4px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
        .blog-header h1 { font-size: 36px; font-weight: 800; color: #0f0f23; line-height: 1.2; margin-bottom: 6px; }
        .blog-header .meta { color: #8888aa; font-size: 14px; }
        .blog-header .meta span { margin-right: 18px; }
        .featured-image { width: 100%; height: 400px; border-radius: 16px; overflow: hidden; margin-bottom: 30px; background: #e8e8e8; }
        .featured-image img { width: 100%; height: 100%; object-fit: cover; }
        .blog-content { font-size: 17px; line-height: 1.9; color: #333; margin-bottom: 40px; }
        .blog-content h2 { font-size: 22px; margin: 30px 0 12px; color: #0f0f23; }
        .blog-content p { margin-bottom: 18px; }
        .divider { border: none; height: 1px; background: #e8e8e8; margin: 30px 0; }
        .comments-section { background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .comments-section h3 { font-size: 22px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .comments-section h3 span { background: #0f0f23; color: #fff; padding: 2px 12px; border-radius: 50px; font-size: 14px; }
        .comment-form { display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px; }
        .comment-form .row { display: flex; gap: 12px; }
        .comment-form input[type="text"] { flex: 1; padding: 12px 16px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; transition: border 0.3s; }
        .comment-form input[type="text"]:focus { outline: none; border-color: #ff6b35; }
        .comment-form textarea { width: 100%; padding: 14px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; resize: vertical; min-height: 90px; transition: border 0.3s; font-family: 'Inter', sans-serif; }
        .comment-form textarea:focus { outline: none; border-color: #ff6b35; }
        .comment-form button { background: #ff6b35; color: #fff; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s; align-self: flex-start; }
        .comment-form button:hover { background: #e55a2b; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,53,0.3); }
        .comment { padding: 18px 0; border-bottom: 1px solid #f0f0f0; }
        .comment:last-child { border-bottom: none; }
        .comment .author { font-weight: 700; font-size: 15px; color: #0f0f23; }
        .comment .time { color: #8888aa; font-size: 13px; margin-left: 12px; font-weight: 400; }
        .comment .text { margin-top: 6px; font-size: 15px; color: #444; line-height: 1.6; word-wrap: break-word; }
        .comment .text .flag-highlight { background: #ffeb3b; color: #d32f2f; font-weight: 700; padding: 2px 8px; border-radius: 4px; border: 1px solid #ff6b35; display: inline-block; animation: pulse 1.5s ease-in-out infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .empty-comments { text-align: center; padding: 40px 0; color: #8888aa; }
        .empty-comments .icon { font-size: 48px; margin-bottom: 12px; }
        .related-posts { margin-top: 40px; }
        .related-posts h3 { font-size: 20px; margin-bottom: 16px; }
        .related-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .related-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.3s; border: 1px solid #eee; }
        .related-card:hover { transform: translateY(-4px); }
        .related-card .img { height: 120px; background: #e8e8e8; overflow: hidden; }
        .related-card .img img { width: 100%; height: 100%; object-fit: cover; }
        .related-card .info { padding: 14px; }
        .related-card .info h4 { font-size: 14px; font-weight: 600; line-height: 1.3; }
        .related-card .info a { color: #0f0f23; text-decoration: none; }
        .related-card .info a:hover { color: #ff6b35; }
        .footer { text-align: center; padding: 30px 5%; color: #8888aa; font-size: 14px; border-top: 1px solid #e8e8e8; margin-top: 40px; }
        @media (max-width: 600px) { .featured-image { height: 200px; } .blog-header h1 { font-size: 24px; } .comment-form .row { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="logo">Tech<span>Review</span></div>
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="#">Reviews</a>
            <a href="#">Compare</a>
            <a href="#">News</a>
            <a href="#">About</a>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <a href="#">Reviews</a> / <span><?php echo substr($current_post['title'], 0, 50); ?>...</span>
        </div>

        <div class="blog-header">
            <div class="category"><?php echo $current_post['category']; ?></div>
            <h1><?php echo $current_post['title']; ?></h1>
            <div class="meta">
                <span>📅 <?php echo $current_post['date']; ?></span>
                <span>✍️ By <?php echo $current_post['author']; ?></span>
                <span>💬 <?php echo count($comments); ?> comments</span>
            </div>
        </div>

        <div class="featured-image">
            <img src="<?php echo $current_post['image']; ?>" alt="<?php echo $current_post['title']; ?>">
        </div>

        <div class="blog-content">
            <?php echo $current_post['content']; ?>
        </div>

        <hr class="divider">

        <div class="comments-section">
            <h3>💬 Comments <span><?php echo count($comments); ?></span></h3>

            <form method="POST" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <div class="row">
                    <input type="text" name="author" placeholder="Your Name" value="Anonymous">
                </div>
                <textarea name="comment" placeholder="Share your thoughts..."></textarea>
                <button type="submit">Post Comment</button>
            </form>

            <?php if(empty($comments)): ?>
                <div class="empty-comments">
                    <div class="icon">💬</div>
                    <p>No comments yet. Be the first to share your thoughts!</p>
                </div>
            <?php else: ?>
                <?php foreach($comments as $row): ?>
                    <div class="comment">
                        <div class="author"><?php echo htmlspecialchars($row['author']); ?> <span class="time"><?php echo $row['created_at']; ?></span></div>
                        <div class="text">
                            <?php 
                            $comment_text = $row['comment'];
                            if (preg_match('/<script>/i', $comment_text)) {
                                echo $comment_text;
                                echo '<br><span class="flag-highlight">🏆 FLAG: FLAG{a4ac3504506652d1533b0fe3d724988b}</span>';
                            } else {
                                echo $comment_text;
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="related-posts">
            <h3>📖 You Might Also Like</h3>
            <div class="related-grid">
                <?php foreach($posts as $id => $p): if($id == $post_id) continue; ?>
                <div class="related-card">
                    <div class="img"><img src="<?php echo $p['image']; ?>" alt="<?php echo $p['title']; ?>"></div>
                    <div class="info">
                        <h4><a href="?post=<?php echo $id; ?>"><?php echo $p['title']; ?></a></h4>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>TechReview &copy; 2026 · In-depth phone reviews and tech analysis</p>
    </div>
</body>
</html>
