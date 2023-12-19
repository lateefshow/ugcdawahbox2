<?php
require 'vendor/autoload.php'; 

session_start();

if (!isset($_SESSION['email'])) {
    die('Email not set in session.');
}

$email = $_SESSION['email'];

try {
    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $dbname = 'ugcall';
    $collection = 'Users';
    $filter = ['email' => $email];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $client->executeQuery("$dbname.$collection", $query);
    $userData = current($cursor->toArray());

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

$errorMsg = null;

if (empty($_POST)) {
    $inputIds = ['id_input_1' => null];
} else {
    $inputIds = array_filter($_POST, function($key) {
        return strpos($key, 'id_input_') === 0;
    }, ARRAY_FILTER_USE_KEY);
}

foreach ($inputIds as $key => $value) {
    $inputIds[$key] = (int) $value;
}

$cursors = [];

try {
    foreach ($inputIds as $inputKey => $inputId) {
        if ($inputId !== null) {
            $filter = ['id' => $inputId];
            $query = new MongoDB\Driver\Query($filter);
            $cursors[$inputKey] = $client->executeQuery('ugcall.tbl_mp3', $query);
        }
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    $errorMsg = "Error: " . $e->getMessage();
}

?>


<?php

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$packId = isset($_GET['packId']) ? $_GET['packId'] : null;

// Check if the packId is set and is a valid format
if ($packId) {
    $filter = ['id' => (int) $packId];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('ugcall.Contents', $query);
    $packData = current($cursor->toArray()); // Assuming there is only one matching document
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted values and update the MongoDB document
    $updatedData = [
        '$set' => [
            'packName' => $_POST['packName'],
            'duration' => $_POST['duration'],
			'status' => $_POST['status'], 
			'quotes' => $_POST['quotes'],
			'comment' => $_POST['comment'],
			
            // Add more fields as needed
        ],
    ];

    $bulkWrite = new MongoDB\Driver\BulkWrite();
    $bulkWrite->update(['id' => (int) $packId], $updatedData, ['upsert' => false]);

    $manager->executeBulkWrite('ugcall.Contents', $bulkWrite);

    // Redirect to the success page
    header('Location: onecontent_edit_successful.php?packId=' . $packId);
exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View One Content - UGC Admin Dashboard</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
  <!-- Tailwind is included -->
  <link rel="stylesheet" href="css/main.css">

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


<!-- ... [Your Navigation and other page sections] -->
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
      <li class="--set-active-profile-html">
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
          <li class="unactive">
            <a href="onepack.php">
              <span>View One Pack</span>
            </a>
          </li>
		  <li class="unactive">
            <a href="viewallpack.php">
              <span>View All Packs</span>
            </a>
          </li>
        </ul>
      </li>
	  
	  <li class="active">
        <a class="dropdown">
          <span class="icon"><i class="mdi mdi-view-list"></i></span>
          <span class="menu-item-label">Pack Categories</span>
          <span class="icon"><i class="mdi mdi-plus"></i></span>
        </a>
        <ul>
          <li>
            <a href="createpack.php">
              <span>Old Pack</span>
            </a>
          </li>
          <li class="unactive">
            <a href="onepack.php">
              <span>New Pack</span>
            </a>
          </li>
		  <li class="unactive">
            <a href="viewallpack.php">
              <span>Accepted Packs</span>
            </a>
          </li>
        </ul>
      </li>
	  
	  <li>
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
      <li>View Content</li>
    </ul>
    
  </div>
</section>

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Details of the Inputted Content ID 
    </h1>
    
  </div>
</section>

<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header"></header>
        
		<?php if (isset($packData)): ?>
                <form method="post" action="viewonecontent_edit.php?packId=<?php echo $packData->id; ?>">
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="button green"><div class="label">Content ID:</div></td>
    <td width="70%" class="button red"><div class="label"><?php echo $packData->id; ?></div></td>
  </tr>
</table>
		</div>
		
<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div><strong>Content Reviewer:</strong></div></td>
    <td width="70%" class="show"><div><input name="username" type="text" class="input is-static" value="<?php echo $packData->username; ?>" readonly></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Content User ID:</strong></div></td>
    <td width="70%" class="show"><div><input name="userid" type="text" class="input is-static" value="<?php echo $packData->userid; ?>" readonly></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Quotes:</strong></td>
    <td width="70%" class="show"><div>
	<textarea name="quotes" style="width:100%; height:200px"><?php echo $packData->quotes; ?></textarea>
	</div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Reviewer ID:</strong></div></td>
    <td width="70%" class="show"><div><input name="reviewer" type="text" class="input is-static" value="<?php echo $packData->reviewer; ?>" readonly></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Content Created at:</strong></div></td>
    <td width="70%" class="show"><div><input name="created_at" type="text" class="input is-static" value="<?php echo $packData->created_at; ?>" readonly></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Comment:</strong></div></td>
    <td width="70%" class="show"><div><input name="comment" type="text" class="input is-static" value="<?php echo $packData->comment; ?>"></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Ratings:</strong></div></td>
    <td width="70%" class="show"><div><input name="ratings" type="text" class="input is-static" value="<?php echo $packData->ratings; ?>" readonly></div></td>
  </tr>
  
  <tr>
    <td width="30%" class="show"><div><strong>Content Current Status:</strong></div></td>
    <td width="70%" class="show"><div><input name="status3" type="text" class="input is-static" value="<?php echo $packData->status; ?>" readonly></div></td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Change Content Status:</strong></div></td>
    <td width="70%" class="show">
	<div class="select">
                <select name="status">
                  <option>new</option>
                  <option>old</option>
                  <option>pending</option>
				  <option>accepted</option>
                </select>
              </div>
	</td>
  </tr>

  <tr>
    <td width="30%" class="show"><div><strong>Lecture Link:</strong></div></td>
    <td width="70%" class="show"><div><input name="source_lecture" type="text" class="input is-static" value="<?php echo $packData->source_lecture; ?>" readonly></div></td>
  </tr>
  
  <tr>
    <td width="30%" class="show"><div><strong>Content Summary:</strong></div></td>
    <td width="70%" class="show"><div>
	<textarea name="detailed_summary" style="width:100%; height:700px"><?php echo $packData->detailed_summary; ?></textarea>
	</div></td>
  </tr>
  
  <tr>
    <td width="30%" class="show"><div><strong>Full Content Transcription:</strong></div></td>
    <td width="70%" class="show"><div><input name="full_text_transcription" type="text" class="input is-static" value="<?php echo $packData->full_text_transcription; ?>" readonly></div></td>
  </tr>
  
  <tr>
    <td width="30%" class="show"><div><strong>Key Learning Points:</strong></div></td>
    <td width="70%" class="show"><div>
	<textarea name="key_learning_point" style="width:100%; height:200px"><?php echo $packData->key_learning_point; ?></textarea>
	</div></td>
  </tr>
</table>
		
		
		
		<hr>
		<div> &nbsp; </div>
		
	
	
	
	<div>
	<div>
		<table width="100%" border="1">
  <tr>
    <td width="100%" class="button blue"><div class="label">Pack Contents</div></td>
    <div></div></td>
  </tr>
</table>
		</div>

        <?php 
            // Extract the content arrays
            $ids = $document->content->ids ?? [];
            $lec_names = $document->content->lec_names ?? [];
            $lec_durations = $document->content->lec_durations ?? [];
        ?>

        <!-- Display the table -->
        <table width="100%" border="1">
            <tr align="center">
                <th align="center">Lecture ids</th>
                <th align="center">Lecture Title</th>
                <th align="center">Lecture Durations</th>
            </tr>
            
            <?php for ($i = 0; $i < count($ids); $i++): ?>
                <tr>
                    <td><?php echo isset($ids[$i]) ? $ids[$i] : ''; ?></td>
                    <td><?php echo isset($lec_names[$i]) ? $lec_names[$i] : ''; ?></td>
                    <td><?php echo isset($lec_durations[$i]) ? $lec_durations[$i] : ''; ?></td>
                </tr>
            <?php endfor; ?>
        </table>
		<br>
		<br>
		<hr>
    
	<button width="100%" class="button blue" type="submit" name="saveChanges">Save Changes</button>
	</form>
	
            <?php endif; ?>


	</div>
		<div></div>
		  <hr>
		</header>
		
	
  </div>
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
