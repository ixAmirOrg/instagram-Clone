<?php
session_start();
require_once "includes/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// گرفتن اطلاعات کاربر
$stmt = $conn->prepare("SELECT username, first_name, last_name, avatar, bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$full_name = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
$username = htmlspecialchars($user['username']);
$bio = nl2br(htmlspecialchars($user['bio']));
$avatar = !empty($user['avatar']) ? $user['avatar'] : 'images/default-avatar.png';

// گرفتن پست‌های کاربر
$post_stmt = $conn->prepare("SELECT image FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$post_stmt->bind_param("i", $user_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

$posts = [];
while ($row = $post_result->fetch_assoc()) {
    $posts[] = $row['image'];
}
$post_count = count($posts);
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
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <span>Favorites</span>
                                        <img src="./images/star.png">
                                    </a></li>
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
                <a  href="#" data-bs-toggle="modal" data-bs-target="#create_modal"><img src="./images/tab.png"></a>
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

        <div class="profile_container">
            <div class="profile_info">
                <div class="cart">
                    <div class="img">
                        <img src="<?php echo $avatar; ?>" alt="">
                    </div>
                    <div class="info">
                        <p class="name">
                            <?php echo $full_name; ?>
                            <button class="edit_profile" onclick="location.href='edit_profile.php'">
                                Edit profile 
                            </button>
                        </p>
                        <div class="general_info">
                            <p><span><?php echo $post_count; ?></span> post</p>
                            <p><span>25K</span> followers</p> <!-- در آینده داینامیک -->
                            <p><span>137</span> following</p> <!-- در آینده داینامیک -->
                        </div>
                        <p class="nick_name">@<?php echo $username; ?></p>
                        <p class="desc">
                            <?php echo $bio; ?>
                        </p>
                    </div>

                </div>
            </div>
            <div class="highlights">
                <div class="highlight">
                    <div class="img">
                        <img src="./images/profile_img.jpg" alt="">
                    </div>
                    <p>conseils</p>
                </div>
                <div class="highlight highlight_add">
                    <div class="img">
                        <img src="./images/plus.png" alt="">
                    </div>
                    <p>New</p>
                </div>
            </div>
            <hr>
            <div class="posts_profile">
                <ul class="nav-pills w-100 d-flex justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item mx-2" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                            <img src="./images/feed.png" alt="posts">
                            POSTS
                        </button>
                    </li>
                    <li class="nav-item mx-2" role="presentation">
                      <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                        <img src="./images/save-instagram.png" alt="saved posts">
                        SAVED
                      </button>
                    </li>
                    <li class="nav-item mx-2" role="presentation">
                      <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                        <img src="./images/tagged.png" alt="tagged posts">
                        TAGGED
                      </button>
                    </li>
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                        <div id="posts_sec" class="post">
                            <?php if (empty($posts)): ?>
                                <p>هیچ پستی هنوز منتشر نشده.</p>
                            <?php else: ?>
                                <?php foreach ($posts as $img_path): ?>
                                    <div class="item">
                                        <img class="img-fluid item_img" src="<?php echo htmlspecialchars($img_path); ?>" alt="Post Image">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                        <div id="saved_sec" class="post">
                            <div class="item">
                                <img class="img-fluid item_img" src="https://i.ibb.co/6WvdZS9/account12.jpg" alt="">
                            </div>
                            <div class="item">
                                <img class="img-fluid item_img" src="https://i.ibb.co/pJ8thst/account13.jpg" alt="">
                            </div>
                            
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                        <div id="tagged" class="post">
                            <div class="item">
                                <img class="img-fluid item_img" src="https://i.ibb.co/kD6tj9T/account2.jpg" alt="">
                            </div>
                            <div class="item">
                                <img class="img-fluid item_img" src="https://i.ibb.co/SPTNbJL/account5.jpg" alt="">
                            </div>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>    
    <script src="./owlcarousel/jquery.min.js"></script>
    <script src="./owlcarousel/owl.carousel.min.js"></script>
    <script src="./js/carousel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>