<?php
require 'vendor/autoload.php'; // Assuming you've installed the MongoDB PHP Library via Composer

// Start the session
session_start();

// Ensure the email is set in the session
if (!isset($_SESSION['email'])) {
    die('Email not set in session.');
}

$email = $_SESSION['email'];

try {
    // Establish database connection
    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    // Create a query to retrieve documents
    $dbname = 'ugcall';
    $collection = 'Users';
    $filter = ['email' => $email];
    $query = new MongoDB\Driver\Query($filter);

    // Execute the query
    $cursor = $client->executeQuery("$dbname.$collection", $query);
    $userData = current($cursor->toArray());

    // Check if user data is retrieved
    if (!$userData) {
        die('No user found with the provided email.');
    }

    $_SESSION['ftname'] = $userData->ftname;
    $_SESSION['ltname'] = $userData->ltname;
    $_SESSION['tel'] = $userData->tel;
    $_SESSION['role'] = $userData->role;
    //$_SESSION['gender'] = $userData->gender;

} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile - UGC Admin Dashboard</title>

  <!-- Tailwind is included -->
  <link rel="stylesheet" href="css/main.css?v=1628755089081">

  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png"/>
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png"/>
  <link rel="mask-icon" href="safari-pinned-tab.svg" color="#00b4b6"/>

  <meta name="description" content="Admin One - free Tailwind dashboard">

  <meta property="og:url" content="https://justboil.github.io/admin-one-tailwind/">
  <meta property="og:site_name" content="JustBoil.me">
  <meta property="og:title" content="Admin One HTML">
  <meta property="og:description" content="Admin One - free Tailwind dashboard">
  <meta property="og:image" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1920">
  <meta property="og:image:height" content="960">

  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:title" content="Admin One HTML">
  <meta property="twitter:description" content="Admin One - free Tailwind dashboard">
  <meta property="twitter:image:src" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
  <meta property="twitter:image:width" content="1920">
  <meta property="twitter:image:height" content="960">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130795909-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-130795909-1');
  </script>

</head>
<body>

<div id="app">

<nav id="navbar-main" class="navbar is-fixed-top">
  <div class="navbar-brand">
    <a class="navbar-item mobile-aside-button">
      <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
    </a>
    <div class="navbar-item">
      <div class="control"><input placeholder="Search everywhere..." class="input"></div>
    </div>
  </div>
  <div class="navbar-brand is-right">
    <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
      <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
    </a>
  </div>
  <div class="navbar-menu" id="navbar-menu">
    <div class="navbar-end">
      
      <div class="navbar-item dropdown has-divider has-user-avatar">
        <a class="navbar-link">
          <div class="user-avatar">
            <img src="https://avatars.dicebear.com/v2/initials/john-doe.svg" alt="John Doe" class="rounded-full">
          </div>
			
          <div class="is-user-name"><span><p><?php echo $userData->ftname." ". $userData->ltname; ?><span class="margin-p"></span></div>
          <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
        </a>
        <div class="navbar-dropdown">
          <a href="profile.html" class="navbar-item">
            <span class="icon"><i class="mdi mdi-account"></i></span>
            <span>My Profile</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-settings"></i></span>
            <span>Settings</span>
          </a>
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-email"></i></span>
            <span>Messages</span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Log Out</span>
          </a>
        </div>
      </div>
      <a href="#" class="navbar-item has-divider desktop-icon-only">
        <span class="icon"><i class="mdi mdi-help-circle-outline"></i></span>
        <span>About</span>
      </a>
      <a href="#" class="navbar-item has-divider desktop-icon-only">
        <span class="icon"><i class="mdi mdi-github-circle"></i></span>
        <span>GitHub</span>
      </a>
      <a href="logout.php"title="Log out" class="navbar-item desktop-icon-only">
        <span class="icon"><i class="mdi mdi-logout"></i></span>
        <span>Log out</span>
      </a>
    </div>
  </div>
</nav>

<aside class="aside is-placed-left is-expanded">
  <div class="aside-tools">
    <div>
      <b class="font-black">UGC DAWAHBOX</b>
    </div>
  </div>
  <div class="menu is-menu-main">
    <p class="menu-label">General</p>
    <ul class="menu-list">
      <li class="active">
        <a href="home.php">
          <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
          <span class="menu-item-label">Dashboard</span>
        </a>
      </li>
	  
	  <li>
        <a class="dropdown">
          <span class="icon"><i class="mdi mdi-view-list"></i></span>
          <span class="menu-item-label">Pack</span>
          <span class="icon"><i class="mdi mdi-plus"></i></span>
        </a>
        <ul>
          <li>
            <a href="createpack.php">
              <span>Create Pack</span>
            </a>
          </li>
          <li>
            <a href="onepack.php">
              <span>View One Pack</span>
            </a>
          </li>
		  <li>
            <a href="viewallpack.php">
              <span>View All Packs</span>
            </a>
          </li>
        </ul>
      </li>
	  
	  <li class="--set-active-profile-html">
        <a href="profile.php">
          <span class="icon"><i class="mdi mdi-account-circle"></i></span>
          <span class="menu-item-label">Profile</span>
        </a>
      </li>
	  
    </ul>
	
    <p class="menu-label">Collections</p>
    <ul class="menu-list">
      <li class="--set-active-tables-html">
        <a href="#">
          <span class="icon"><i class="mdi mdi-table"></i></span>
          <span class="menu-item-label">User Collections</span>
        </a>
      </li>
      <li>
        <a class="dropdown">
          <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
          <span class="menu-item-label">Contents Collection</span>
          <span class="icon"><i class="mdi mdi-plus"></i></span>
        </a>
        <ul>
          <li class="unactive">
            <a href="onecontent.php">
              <span>View One Content</span>
            </a>
          </li>
		  <li class="unactive">
            <a href="viewallcontent.php">
              <span>View All Contents</span>
            </a>
          </li>
        </ul>
      </li>
      
    </ul>
    <p class="menu-label">About</p>
    <ul class="menu-list">
      
      <li>
        <a href="#" class="has-icon">
          <span class="icon"><i class="mdi mdi-help-circle"></i></span>
          <span class="menu-item-label">About</span>
        </a>
      </li>
      
	  
    </ul>
  </div>
</aside>

<section class="is-title-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <ul>
      <li>Admin</li>
      <li>Profile</li>
    </ul>
    
  </div>
</section>

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Profile
    </h1>
    
  </div>
</section>

  <section class="section main-section">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
      <div class="card">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-account-circle"></i></span>
            Edit Profile
          </p>
        </header>
        <div class="card-content">
          <form>
            <div class="field">
              <label class="label">Avatar</label>
              <div class="field-body">
                <div class="field file">
                  <label class="upload control">
                    <a class="button blue">
                      Upload
                    </a>
                    <input type="file">
                  </label>
                </div>
              </div>
            </div>
            <hr>
            <div class="field">
              <label class="label">Name</label>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <input type="text" autocomplete="on" name="name" placeholder="Name" class="input" required>
                  </div>
                  <p class="help">Required. Your name</p>
                </div>
              </div>
            </div>
            <div class="field">
              <label class="label">E-mail</label>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <input type="email" autocomplete="on" name="email" placeholder="user@example.com" class="input" required>
                  </div>
                  <p class="help">Required. Your e-mail</p>
                </div>
              </div>
            </div>
            <hr>
            <div class="field">
              <div class="control">
                <button type="submit" class="button green">
                  Submit
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-account"></i></span>
            Profile
          </p>
        </header>
        <div class="card-content">
          <div class="image w-48 h-48 mx-auto">
            <img src="https://avatars.dicebear.com/v2/initials/john-doe.svg" alt="John Doe" class="rounded-full">
          </div>
          <hr>
          <div class="field">
            <label class="label">Name</label>
            <div class="control">
              <input name="text" type="text" class="input is-static" value="<?php echo $userData->ftname." ". $userData->ltname; ?>" readonly>
            </div>
          </div>
          <hr>
          <div class="field">
            <label class="label">E-mail</label>
            <div class="control">
              <input type="text" readonly value="<?php echo $userData->email; ?>" class="input is-static">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-lock"></i></span>
          Change Password
        </p>
      </header>
      <div class="card-content">
        <form>
          <div class="field">
            <label class="label">Current password</label>
            <div class="control">
              <input type="password" name="password_current" autocomplete="current-password" class="input" required>
            </div>
            <p class="help">Required. Your current password</p>
          </div>
          <hr>
          <div class="field">
            <label class="label">New password</label>
            <div class="control">
              <input type="password" autocomplete="new-password" name="password" class="input" required>
            </div>
            <p class="help">Required. New password</p>
          </div>
          <div class="field">
            <label class="label">Confirm password</label>
            <div class="control">
              <input type="password" autocomplete="new-password" name="password_confirmation" class="input" required>
            </div>
            <p class="help">Required. New password one more time</p>
          </div>
          <hr>
          <div class="field">
            <div class="control">
              <button type="submit" class="button green">
                Submit
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

<footer class="footer">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
    <div class="flex items-center justify-start space-x-3">
      <div>
        © 2023, Lateefshooo
      </div>
     
    </div>
    
  </div>
</footer>


</div>

<!-- Scripts below are for demo only -->
<script type="text/javascript" src="js/main.min.js?v=1628755089081"></script>


<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '658339141622648');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1"/></noscript>

<!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>
</html>
