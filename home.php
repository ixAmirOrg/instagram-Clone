<?php
session_start();
require_once "includes/init.php";            // Auth و Url
require_once "includes/connection.php";      // $conn
require_once "classes/time_elapsed_string.php"; // تابع time_elapsed_string()

// بررسی لاگین
if (!Auth::isLoggedIn()) {
    Url::redirect('/login.php');
    exit;
}

// واکشی پست‌ها
$sql = "SELECT posts.*, users.username, users.avatar 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// آرایهٔ نهایی پست‌ها
$posts = [];

while ($row = $result->fetch_assoc()) {
    $post_id    = (int)$row['id'];
    $avatar     = !empty($row['avatar']) ? $row['avatar'] : './images/default_avatar.png';
    $username   = htmlspecialchars($row['username']);
    $image      = htmlspecialchars($row['image']);
    $caption    = htmlspecialchars($row['caption']);
    $created_at = $row['created_at'];

    // تعداد لایک
    $likeStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM likes WHERE post_id = ?");
    $likeStmt->bind_param("i", $post_id);
    $likeStmt->execute();
    $likes = $likeStmt->get_result()->fetch_assoc()['cnt'] ?? 0;
    $likeStmt->close();

    // تعداد کامنت
    $cmtStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM comments WHERE post_id = ?");
    $cmtStmt->bind_param("i", $post_id);
    $cmtStmt->execute();
    $comments_count = $cmtStmt->get_result()->fetch_assoc()['cnt'] ?? 0;
    $cmtStmt->close();

    // زمان نسبی
    $time_ago = time_elapsed_string($created_at);

    $posts[] = [
        'id'             => $post_id,
        'avatar'         => $avatar,
        'username'       => $username,
        'image'          => $image,
        'caption'        => $caption,
        'created_at'     => $created_at,
        'time_ago'       => $time_ago,
        'likes_count'    => $likes,
        'comments_count' => $comments_count,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Clone</title>
    <link rel="stylesheet" href="./sass/vender/bootstrap.css">
    <link rel="stylesheet" href="./sass/vender/bootstrap.min.css">
    <link rel="stylesheet" href="./owlcarousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="./owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css">
    <link rel="stylesheet" href="./sass/main.css">
</head>
<body>

    <div class="post_page">
        <!--***** nav menu start ****** -->
        <div class="nav_menu">
            <div class="fix_top">
                <!-- nav for big->medium screen -->
                <div class="nav">
                    <div class="logo">
                        <a href="./home.php">
                            <img class="d-block d-lg-none small-logo" src="./images/instagram.png" alt="logo">
                            <img class="d-none d-lg-block" src="./images/logo_menu.png" alt="logo">
                        </a>
                    </div>
                    <div class="menu">
                        <ul>
                            <li>
                                <a class="active" href="home.php">
                                    <img src="./images/accueil.png">
                                    <span class="d-none d-lg-block ">Home</span>
                                </a>
                            </li>
                            <li id="search_icon">
                                <a href="#">
                                    <img src="./images/search.png">
                                    <span class="d-none d-lg-block search">Search </span>
                                </a>
                            </li>
                            <li>
                                <a href="./explore.html">
                                    <img src="./images/compass.png">
                                    <span class="d-none d-lg-block ">Explore</span>
                                </a>
                            </li>
                            <li>
                                <a href="./reels.html">
                                    <img src="./images/video.png">
                                    <span class="d-none d-lg-block ">Reels</span>
                                </a>
                            </li>
                            <li>
                                <a href="./messages.html">
                                    <img src="./images/send.png">
                                    <span class="d-none d-lg-block ">Messages</span>
                                </a>
                            </li>
                            <li class="notification_icon">
                                <a href="#">
                                    <img src="./images/love.png">
                                    <span class="d-none d-lg-block ">Notifications</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#create_modal">
                                    <img src="./images/tab.png">
                                    <span class="d-none d-lg-block ">Create</span>
                                </a>

                            </li>
                            <li>
                                <a href="./profile.php">
                                    <img class="circle story" src="./images/profile_img.jpg">
                                    <span class="d-none d-lg-block ">Profile</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="more">
                        <div class="btn-group dropup">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="./images/menu.png">
                                <span class="d-none d-lg-block ">More</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">
                                        <span>Settings</span>
                                        <img src="./images/reglage.png">
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Your activity</span>
                                        <img src="./images/history.png">
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Saved</span>
                                        <img src="./images/save-instagram.png">
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Switch apperance</span>
                                        <img src="./images/moon.png">
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Report a problem</span>
                                        <img src="./images/problem.png">
                                    </a></li>
                                <li><a class="dropdown-item bold_border" href="#">
                                        <span>Switch accounts</span>
                                    </a></li>
                                <li><a class="dropdown-item" href="./logout.php">
                                        <span>Log out</span>
                                    </a></li>
                            </ul>
                        </div>
                        <!--  -->

                    </div>
                </div>
                <!-- nav for small screen  -->
                <div class="nav_sm">
                    <div class="content">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="logo" src="./images/logo_menu.png">
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">
                                        <span>Following</span>
                                        <img src="./images/add-friend.png">
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Favorites</span>
                                        <img src="./images/star.png">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="left">
                            <div class="search_bar">
                                <div class="input-group">
                                    <div class="form-outline">
                                        <div>
                                            <img src="./images/search.png" alt="search">
                                        </div>
                                        <input type="search" id="form1" class="form-control" placeholder="Search" />
                                    </div>
                                </div>
                            </div>
                            <div class="notifications notification_icon">
                                <a href="./notification.html">
                                    <img src="./images/love.png">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- nav for ex-small screen  -->
                <div class="nav_xm">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img class="logo" src="./images/logo_menu.png">
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                    <span>Following</span>
                                    <img src="./images/add-friend.png">
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <span>Favorites</span>
                                    <img src="./images/star.png">
                                </a></li>
                        </ul>
                    </div>
                    <div class="left">

                        <img src="./images/send.png">
                        <a href="./notification.html">
                            <img class="notification_icon" src="./images/love.png">
                        </a>

                    </div>
                </div>
            </div>
            <!-- menu in the botton for smal screen  -->
            <div class="nav_bottom">
                <a href="./home.php"><img src="./images/accueil.png"></a>
                <a href="./explore.html"><img src="./images/compass.png"></a>
                <a href="./reels.html"><img src="./images/video.png"></a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#create_modal"><img src="./images/tab.png"></a>
                <a href="profile.php"><img class="circle story" src="./images/profile_img.jpg"></a>
            </div>
        </div>
        <!-- search  -->
        <div id="search" class="search_section">
            <h2>Search</h2>
            <form method="post">
                <input type="text" placeholder="Search">
            </form>
            <div class="find">
                <div class="desc">
                    <h4>Recent</h4>
                    <p><a href="#">Clear all</a></p>
                </div>
                <div class="account">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">AmirAli</p>
                                <p class="second_name">ixAmir</p>
                            </div>
                        </div>
                        <div class="clear">
                            <a href="#">X</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- search  -->
        <!-- notification -->
        <div id="notification" class="notification_section">
            <h2>Notifications</h2>
            <div class="notifications">
                <div class="notif follow_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">
                                    AmirAli
                                    <span class="desc">started following you.</span>
                                    <span class="time">2h</span>
                                </p>

                            </div>
                        </div>
                        <div class="follow_you">
                            <button class="follow_text">Follow</button>
                        </div>
                    </div>
                </div>
                <div class="notif follow_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">
                                    AmirAli
                                    <span class="desc">started following you.</span>
                                    <span class="time">2h</span>
                                </p>

                            </div>
                        </div>
                        <div class="follow_you">
                            <button class="follow_text">Follow</button>
                        </div>
                    </div>
                </div>
                <div class="notif story_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <div class="info">
                                    <p class="name">
                                        AmirAli
                                        <span class="desc">liked your story.</span>
                                        <span class="time">2d</span>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="story_like">
                            <img src="./images/img2.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="notif follow_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">
                                    AmirAli
                                    <span class="desc">started following you.</span>
                                    <span class="time">2h</span>
                                </p>

                            </div>
                        </div>
                        <div class="follow_you">
                            <button class="follow_text">Follow</button>
                        </div>
                    </div>
                </div>
                <div class="notif story_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <div class="info">
                                    <p class="name">
                                        AmirAli
                                        <span class="desc">liked your story.</span>
                                        <span class="time">2d</span>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="story_like">
                            <img src="./images/img2.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="notif follow_notif">
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">
                                    AmirAli
                                    <span class="desc">started following you.</span>
                                    <span class="time">2h</span>
                                </p>

                            </div>
                        </div>
                        <div class="follow_you">
                            <button class="follow_text">Follow</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--***** nav menu end ****** -->

        <div class="second_container">
            <!--***** posts_container start ****** -->
            <div class="main_section">
                <div class="posts_container">
                    <div class="stories">
                        <div class="owl-carousel items">
                        </div>
                    </div>
                    <div class="posts">
  <?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
      <div class="post">
        <div class="info">
          <div class="person">
            <img src="<?= $post['avatar'] ?>" alt="">
            <a href="#"><?= $post['username'] ?></a>
            <span class="circle">.</span>
            <span><?= $post['time_ago'] ?></span>
          </div>
          <div class="more">
            <img src="./images/show_more.png" alt="more">
          </div>
        </div>
        <div class="image">
          <img src="<?= $post['image'] ?>" alt="">
        </div>
        <div class="desc">
          <div class="icons">
            <div class="icon_left d-flex">
              <div class="like">
                <img class="not_loved" src="./images/love.png">
                <img class="loved"    src="./images/heart.png">
              </div>
              <div class="chat">
                <button type="button" class="btn p-0"
                        data-bs-toggle="modal"
                        data-bs-target="#comment_modal_<?= $post['id'] ?>">
                  <img src="./images/bubble-chat.png" alt="comment">
                </button>
              </div>
              <div class="send">
                <button type="button" class="btn p-0"
                        data-bs-toggle="modal"
                        data-bs-target="#send_message_modal">
                  <img src="./images/send.png" alt="send">
                </button>
              </div>
            </div>
            <div class="save not_saved">
              <img class="hide saved"     src="./images/save_black.png">
              <img class="not_saved"       src="./images/save-instagram.png">
            </div>
          </div>
          <div class="liked">
            <a class="bold" href="#"><?= $post['likes_count'] ?> likes</a>
          </div>
          <div class="post_desc">
            <p>
              <a class="bold" href="#"><?= $post['username'] ?></a>
              <?= $post['caption'] ?>
            </p>
            <p>
              <a class="gray" href="#"
                 data-bs-toggle="modal"
                 data-bs-target="#comment_modal_<?= $post['id'] ?>">
                View all <?= $post['comments_count'] ?> comments
              </a>
            </p>
            <input type="text" placeholder="Add a comment...">
          </div>
        </div>
      </div>

      <!-- Modal کامنت‌های این پست -->
      <div class="modal fade" id="comment_modal_<?= $post['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Comments</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="comments">
                <?php
                  $cstmt = $conn->prepare("
                    SELECT c.comment, c.created_at, u.username, u.avatar
                    FROM comments c
                    JOIN users u ON c.user_id = u.id
                    WHERE c.post_id = ?
                    ORDER BY c.created_at DESC
                  ");
                  $cstmt->bind_param("i", $post['id']);
                  $cstmt->execute();
                  $cres = $cstmt->get_result();
                  while ($c = $cres->fetch_assoc()):
                ?>
                  <div class="comment mb-3">
                    <div class="d-flex">
                      <div class="img">
                        <img src="<?= htmlspecialchars($c['avatar'] ?? './images/profile_img.jpg') ?>"
                             alt="" style="width:40px;height:40px;border-radius:50%">
                      </div>
                      <div class="content ms-2">
                        <div class="d-flex align-items-center">
                          <h6 class="mb-0 me-2"><?= htmlspecialchars($c['username']) ?></h6>
                          <small class="text-muted"><?= time_elapsed_string($c['created_at']) ?></small>
                        </div>
                        <p class="mb-0"><?= htmlspecialchars($c['comment']) ?></p>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              </div>
            </div>
            <div class="modal-footer">
              <form action="update_comment.php" method="POST" class="w-100 d-flex">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <img src="<?= htmlspecialchars($_SESSION['avatar'] ?? './images/profile_img.jpg') ?>"
                     alt="" style="width:40px;height:40px;border-radius:50%;margin-right:8px;">
                <input name="comment-input" type="text" class="form-control me-2"
                       placeholder="Add a comment..." required>
                <button type="submit" class="btn btn-primary">Send</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center">No posts available.</p>
  <?php endif; ?>
</div>

                    </div>
                </div>
            </div>
            <!--***** posts_container end ****** -->

            <!--***** followers_container start ****** -->
            <div class="followers_container">
                <div>
                    <div class="cart">
                        <div>
                            <div class="img">
                                <img src="./images/profile_img.jpg" alt="">
                            </div>
                            <div class="info">
                                <p class="name">AmirAli</p>
                                <p class="second_name">ixAmir</p>
                            </div>
                        </div>
                        <div class="switch">
                            <a href="#">Switch</a>
                        </div>
                    </div>
                    <div class="suggestions">
                        <div class="title">
                            <h4>Suggestions for you</h4>
                            <a class="dark" href="#">See All</a>
                        </div>
                        <div class="cart">
                            <div>
                                <div class="img">
                                    <img src="./images/profile_img.jpg" alt="">
                                </div>
                                <div class="info">
                                    <p class="name">AmirAli</p>
                                    <p class="second_name">ixAmir</p>
                                </div>
                            </div>
                            <div class="switch">
                                <button class="follow_text" href="#">follow</button>
                            </div>
                        </div>
                        <div class="cart">
                            <div>
                                <div class="img">
                                    <img src="./images/profile_img.jpg" alt="">
                                </div>
                                <div class="info">
                                    <p class="name">AmirAli</p>
                                    <p class="second_name">ixAmir</p>
                                </div>
                            </div>
                            <div class="switch">
                                <button class="follow_text" href="#">follow</button>
                            </div>
                        </div>
                        <div class="cart">
                            <div>
                                <div class="img">
                                    <img src="./images/profile_img.jpg" alt="">
                                </div>
                                <div class="info">
                                    <p class="name">AmirAli</p>
                                    <p class="second_name">ixAmir</p>
                                </div>
                            </div>
                            <div class="switch">
                                <button class="follow_text" href="#">follow</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--***** followers_container end ****** -->

        </div>

        <!-- Modal for sending posts-->
        <div class="modal fade" id="send_message_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Share</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="send">
                            <div class="search_person">
                                <p>To:</p>
                                <input type="text" placeholder="Search">
                            </div>
                            <p>Suggested</p>
                            <div class="poeple">
                                <div class="person">
                                    <div class="d-flex">
                                        <div class="img">
                                            <img src="./images/profile_img.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="person">
                                                <h4>namePerson</h4>
                                                <span>ixAmir</span>
                                            </div>
                                        </div>
                                    </div>
                                    <di class="circle">
                                        <span></span>
                                </div>
                            </div>
                            <div class="person">
                                <div class="d-flex">
                                    <div class="img">
                                        <img src="./images/profile_img.jpg" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="person">
                                            <h4>namePerson</h4>
                                            <span>ixAmir</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="circle">
                                    <span></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Send</button>
                    </div>
                </div>

            </div>
        </div>

 
        <!--Create model-->
        <div class="modal fade" id="create_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title w-100 fs-5 d-flex align-items-end justify-content-between"
                            id="exampleModalLabel">
                            <span class="title_create">Create new post</span>
                            <button class="next_btn_post btn_link"></button>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img class="up_load" src="./images/upload.png" alt="upload">
                        <p>Drag photos and videos here</p>
                        <button class="btn btn-primary btn_upload">
                            select from your computer
                            <form id="upload-form">
                                <input class="input_select" type="file" id="image-upload" name="image-upload">
                            </form>
                        </button>
                        <div id="image-container" class="hide_img">
                        </div>
                        <div id="image_description" class="hide_img">
                            <div class="img_p"></div>
                            <div class="description">
                                <div class="cart">
                                    <div>
                                        <div class="img">
                                            <img src="./images/profile_img.jpg">
                                        </div>
                                        <div class="info">
                                            <p class="name">AmirAli</p>
                                        </div>
                                    </div>
                                </div>
                                <form>
                                    <textarea type="text" id="emoji_create" placeholder="write your email"></textarea>
                                </form>
                            </div>
                        </div>
                        <div class="post_published hide_img">
                            <img src="./images/uploaded_post.gif" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
    const post_data = <?php echo json_encode($post_data); ?>;
    </script>
    
    <!-- <script src="./sass/vender/bootstrap.bundle.js"></script>
    <script src="./sass/vender/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="./owlcarousel/jquery.min.js"></script>
    <script src="./owlcarousel/owl.carousel.min.js"></script>
    <script src="./js/carousel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>