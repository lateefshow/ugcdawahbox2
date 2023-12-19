<?php
require 'vendor/autoload.php'; 

session_start();

if (!isset($_SESSION['email'])) {
    die('Email not set in session.');
}

$email = $_SESSION['email'];

$client = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// ... [Rest of your initial PHP code]
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

try {
    // Establish database connection
    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    // Create a query to retrieve documents
    $dbname = 'ugcall';
    $collection = 'Pack';
    //$query = new MongoDB\Driver\Query([]);
	$filter2 = ['status' => 'new'];
	$query = new MongoDB\Driver\Query($filter2);
    
    // Execute the query
    $cursor = $client->executeQuery("$dbname.$collection", $query);

} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error: " . $e->getMessage());
}

?>

<?php
/* // 1. Determine the total number of records
$command = new MongoDB\Driver\Command(['count' => 'Pack']);
$result = $client->executeCommand('ugcall', $command);
$totalRecords = $result->toArray()[0]->n;

// 2. Determine the current page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// 3. Set a limit and calculate the offset
$recordsPerPage = 5;
$offset = ($currentPage - 1) * $recordsPerPage;

// 4. Modify the query
$options = ['limit' => $recordsPerPage, 'skip' => $offset];
$query = new MongoDB\Driver\Query([], $options);
$cursor = $client->executeQuery("$dbname.$collection", $query);

// ... [Rest of your HTML code]
*/



/*// 1. Determine the total number of records with "old" status
$filterOld = ['status' => 'old'];
$commandOld = new MongoDB\Driver\Command(['count' => 'Pack', 'query' => $filterOld]);
$resultOld = $client->executeCommand('ugcall', $commandOld);
$totalRecordsOld = $resultOld->toArray()[0]->n;

// 2. Determine the current page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// 3. Set a limit and calculate the offset
$recordsPerPage = 5;
$offset = ($currentPage - 1) * $recordsPerPage;

// 4. Modify the query to fetch only records with "old" status
$options = ['limit' => $recordsPerPage, 'skip' => $offset];
$queryOld = new MongoDB\Driver\Query($filterOld, $options);
$cursorOld = $client->executeQuery("$dbname.$collection", $queryOld);
*/

// 1. Determine the total number of records with "old" status
$filter = ['status' => 'old'];
$command = new MongoDB\Driver\Command(['count' => 'Pack', 'query' => $filter]);
$result = $client->executeCommand('ugcall', $command);
$totalRecords = $result->toArray()[0]->n;

// 2. Determine the current page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// 3. Set a limit and calculate the offset
$recordsPerPage = 5;
$offset = ($currentPage - 1) * $recordsPerPage;

// 4. Modify the query to fetch only records with "old" status
$options = ['limit' => $recordsPerPage, 'skip' => $offset];
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $client->executeQuery("$dbname.$collection", $query);

// Now you can use $totalRecords in your pagination logic
$totalPages = ceil($totalRecords / $recordsPerPage);


?>

<!DOCTYPE html>
<html lang="en">
<!-- ... [Your HTML HEAD section] -->
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>All Old Packs - UGC Admin Dashboard</title>
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
<body>
<!-- ... [Your HTML BODY content above the pagination section] -->
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
      <li>All Old Packs</li>
    </ul>
    
  </div>
</section>

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      View All Old Packs
    </h1>
  </div>
  
		
		<div style="padding-top:20px">
		<a href="old_pack.php"><div class="button green active" style="font-size:26px">Old Pack</div></a>
		<a href="new_pack.php"><div class="button red" style="font-size:26px">New Pack</div></a>
		<a href="accepted_pack.php"><div class="button blue" style="font-size:26px">Accepted Pack</div></a>
		<a href="pending_pack.php"><div class="button red" style="font-size:26px">Pending Pack</div></a>
		</div>
</section>

<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header"></header>
        
		
		<?php foreach ($cursor as $document): ?>
            <?php
                // Check the status and skip if it doesn't match the desired category
                $status = $document->status ?? '';
                if ($status !== 'old') {
                    continue; // Skip this document if not in the 'old' category
                }
            ?>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="button green"><div class="label" style="font-size:36px">Pack ID:</div></td>
    <td width="70%" class="button red"><div class="label" style="font-size:36px"><?php echo $document->id; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Name:</div></td>
    <td width="70%" class="show"><div><?php echo $document->packName; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Duration:</div></td>
    <td width="70%" class="show"><div><?php echo $document->duration; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack reviewer:</div></td>
    <td width="70%" class="show"><div><?php echo $document->reviewer; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Created Week:</div></td>
    <td width="70%" class="show"><div><?php echo $document->createdWeek; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Due Date:</div></td>
    <td width="70%" class="show"><div><?php echo $document->dueDate; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Due Week:</div></td>
    <td width="70%" class="show"><div><?php echo $document->dueWeek; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Status:</div></td>
    <td width="70%" class="show"><div><?php echo $document->status; ?></div></td>
  </tr>
</table>
		</div>
		<div>
		<table width="100%" border="1">
  <tr>
    <td width="30%" class="show"><div>Pack Created Month:</div></td>
    <td width="70%" class="show"><div><?php echo $document->month; ?></div></td>
  </tr>
</table>
		</div>
		
		
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
    <?php endforeach; ?>
		
		<div></div>
		  <hr>
		</header>
		
		
   <?php
   // Generate pagination links here
   $totalPages = ceil($totalRecords / $recordsPerPage);
   for ($i = 1; $i <= $totalPages; $i++) {
       if ($i == $currentPage) {
           // 6. Display pagination links only for packs with "old" status
echo "<div align='center'> <div class='label'>Next Page</div>";
$totalPagesOld = ceil($totalRecords / $recordsPerPage);
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        echo "<button class='button green active'>$i</button> "; // the current page number
    } else {
        echo "<a href='?page=$i'><button class='button red'>$i</button></a> "; // other pages as links
    }
}
echo "</div>";
	   }
   }
   ?>
</div>
	
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
