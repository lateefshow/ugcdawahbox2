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

$errorMsg = null;

// Check if there's an 'id' parameter in the URL
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$packId = isset($_POST['packId']) ? $_POST['packId'] : null;
// Assuming $highlights is set or initialized somewhere in your code
// If not, you can initialize it as an empty array like this:
$highlights = isset($highlights) ? $highlights : [];

try {
    // Fetch the pack data based on the packId
    $filter = ['id' => (int) $packId];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery('ugcall.Contents', $query);
    $document = current($cursor->toArray());

    // ... (Rest of your code)
	
$packId = isset($_POST['packId']) ? $_POST['packId'] : null;
// Assuming $highlights is set or initialized somewhere in your code
// If not, you can initialize it as an empty array like this:
$highlights = isset($highlights) ? $highlights : [];

// Check if the packId is set and is a valid format (modify this according to your specific needs)
if ($packId) {
    $filter = ['id' => (int) $packId];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('ugcall.Contents', $query);
}
	

} catch (MongoDB\Driver\Exception\Exception $e) {
    $errorMsg = "Error: " . $e->getMessage();
}
?>


<?php

// Initialize MongoDB connection
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Initialize variables
$errorMsg = null;
$packId = isset($_POST['packId']) ? $_POST['packId'] : null;
$highlights = [];

try {
    // Fetch the pack data based on the packId
    if ($packId) {
        $filter2 = ['id' => (int) $packId];
        $options2 = [];
        $query2 = new MongoDB\Driver\Query($filter2, $options2);
        $cursor2 = $manager->executeQuery('ugcall.Contents', $query2);
        $document2 = current($cursor2->toArray());

        // Check if the 'highlights' field exists in the document
        if (property_exists($document2, 'highlights')) {
            $highlights = $document2->highlights;
        }
    }

} catch (MongoDB\Driver\Exception\Exception $e) {
    $errorMsg = "Error: " . $e->getMessage();
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
      <li>View Content </li>
    </ul>
    
  </div>
</section>

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Details of the Inputted Content ID 
    </h1>
    <!-- Add this button where you display pack details -->
	<?php if (isset($document)): ?>
<a href="viewonecontent_edit.php?packId=<?php echo $document->id; ?>"><div class="button blue">Edit Content</div></a>
	<?php endif; ?>
<!--<a href="viewonepack_edit.php"><div class="button blue">Edit Pack</div></a>-->

  </div>
</section>

<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header"></header>
        
		<?php foreach ($cursor as $document): ?>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="button green"><div class="label" style="font-size:36px">Contents ID:</div></td>
    <td width="70%" class="button red"><div class="label" style="font-size:36px"><?php echo $document->id; ?></div></td>
  </tr>
</table>
		</div>
		
		
	<table width="100%" border="1">
		<div>
	
  <tr>
    <td width="20%" class="white"><div><strong>Content Reviewer:</strong></div></td>
    <td width="80%" class="white"><div><?php echo $document->username; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Content User ID:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->userid; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Quotes:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->quotes; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Reviewer ID:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->reviewer; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Content Created at:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->created_at; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Comment:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->comment; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Ratings:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->ratings; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Content Status:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->status; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Lecture Link:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->source_lecture; ?></div></td>
  </tr>

		</div>
		<div style="background-color:#000000">
	
  <tr>
    <td width="20%"><div><strong>Content Summary:</strong></div></td>
    <td width="80%"><div><?php echo $document->detailed_summary; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Full Content Transcription:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->full_text_transcription; ?></div></td>
  </tr>

		</div>
		<div>
		
  <tr>
    <td width="20%" class="show"><div><strong>Key Learning Points:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->key_learning_point; ?></div></td>
  </tr>

		</div>
</table>		
		<hr>
		<div> &nbsp; </div>
		
	
	
	
	<div>
		<table width="100%" border="1">
  <tr>
    <td width="100%" class="button blue"><div class="label" style="font-size:36px">Contents Audit</div></td>
    <div></div></td>
  </tr>
</table>
		</div>
	<?php 
            // Extract the content audit arrays
            $ids = $document->audit_and_improve->suggested_title ?? [];
            $lec_names = $document->audit_and_improve->categories ?? [];
            $lec_durations = $document->audit_and_improve->keyword ?? [];
			$lec_durations = $document->audit_and_improve->verse ?? [];
			$lec_durations = $document->audit_and_improve->suggestions ?? [];
        ?>
			
	<div>
		<table width="100%" border="1" bgcolor="#99FF00">
  <tr>
                    <td width="20%"><strong>Suggested Title:</strong></td>
    <td width="80%"><?php echo $document->audit_and_improve->suggested_title ?? []; ?></td>
          </tr>
            
			<tr>
    <td width="20%" class="show"><div><strong>Categories:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->audit_and_improve->categories ?? []; ?></div></td>
  </tr>
  
  <tr>
    <td width="20%" class="show"><div><strong>Keyword:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->audit_and_improve->keyword ?? []; ?></div></td>
  </tr>
  
  <tr>
    <td width="20%" class="show"><div><strong>Verse:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->audit_and_improve->verse ?? []; ?></div></td>
  </tr>
  
  <tr>
    <td width="20%" class="show"><div><strong>Suggestions:</strong></div></td>
    <td width="80%" class="show"><div><?php echo $document->audit_and_improve->suggestions ?? []; ?></div></td>
  </tr>
</table>
		</div>
		
                
	
	<div> &nbsp; </div>
	

	<div>
		<table width="100%" border="1">
  <tr>
    <td width="100%" class="button blue"><div class="label" style="font-size:36px">Highlights</div></td>
    <div></div></td>
  </tr>
</table>
		</div>


<?php if ($errorMsg): ?>
    <p>Error: <?= $errorMsg; ?></p>
<?php elseif ($packId && $highlights): ?>

    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Highlight Type</th>
                <th>Highlight Description</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($highlights as $index => $highlight): ?>
    <tr>
        <td><?= $highlight->{'title' . $index} ?? ''; ?></td>
        <td><?= $highlight->{'start_time' . $index} ?? ''; ?></td>
        <td><?= $highlight->{'end_time' . $index} ?? ''; ?></td>
        <td><?= $highlight->{'highlight_type' . $index} ?? ''; ?></td>
        <td><?= $highlight->{'highlight_description' . $index} ?? ''; ?></td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>

<?php else: ?>
    <p>No highlights found for the specified packId.</p>
<?php endif; ?>




		<br>
		<br>
		
		<hr>
    <?php endforeach; ?>
		
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
