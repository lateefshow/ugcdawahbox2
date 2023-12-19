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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Pack - UGC Admin Dashboard</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
  $(document).ready(function() {
  function updateTotalDuration() {
            var totalSeconds = 0;

            $('.lec_duration').each(function() {
                var timeParts = $(this).val().split(':');
                
                if (timeParts.length === 3) {
                    totalSeconds += parseInt(timeParts[0], 10) * 3600; 
                    totalSeconds += parseInt(timeParts[1], 10) * 60;   
                    totalSeconds += parseInt(timeParts[2], 10);        
                }
            });

            var hours = Math.floor(totalSeconds / 3600);
            var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
            var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

            $('#totalDuration').text('Total Duration: ' +
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0')
            );
        }

        updateTotalDuration(); // Call it once on page load

        $('#addMore').click(function() {
            // ... Your existing code for adding more inputs...

            updateTotalDuration();
        });
  
  
  
    
        function updateTotalDuration() {
    var totalSeconds = 0;

    $('.lec_duration').each(function() {
        var timeParts = $(this).val().split(':');
        
        if (timeParts.length === 3) {
            totalSeconds += parseInt(timeParts[0], 10) * 3600; 
            totalSeconds += parseInt(timeParts[1], 10) * 60;   
            totalSeconds += parseInt(timeParts[2], 10);        
        }
    });

    var hours = Math.floor(totalSeconds / 3600);

    if (hours >= 5) {
        $('button[type="submit"]').prop('disabled', false);
    } else {
        $('button[type="submit"]').prop('disabled', true);
    }

    var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
    var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

    $('#totalDuration').text('Total Duration: ' +
        String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0')
    );
	
	$('#totalDuration2').val(
        String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0')
    );
}

    });
  </script>
	
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
      <li>Profile</li>
    </ul>
    
  </div>
</section>

<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Forms
    </h1>
    <button class="button light">Button</button>
  </div>
</section>

<section class="section main-section">
    <div class="card mb-6">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><i class="mdi mdi-ballot"></i></span>
          Forms
        </p>
      </header>
      <div class="card-content">
        <form method="POST" action="newpack.php">

          

          <div class="field">
            <script>
$(document).ready(function() {
    var currentMaxId = 1; // Starting with 1 inputs.

    $('input[id^="id_input_"]').on('input', handleInputChange);

    function handleInputChange() {
        var idValue = $(this).val();
        if (idValue.trim() !== "") {
            var inputId = $(this).attr('id');  
            $.post('', { [inputId]: idValue }, function(data) {
                $('tbody[data-target="'+ inputId +'"]').html($(data).find('tbody[data-target="'+ inputId +'"]').html());
            });
        }
    }

    $('#addMore').click(function() {
        currentMaxId += 1; 
        var newRow = `
        <tr>
            <td width="200">
                <form method="post" action="">
                    Enter Lecture ID ${currentMaxId}: <input type="text" id="id_input_${currentMaxId}" name="id_input_${currentMaxId}">
                </form>
            </td>
            <td width="800">
                <table border="0">
                    <thead>
                        <tr>
                            
                            <th>mp3_title</th>
                            <th>mp3_duration</th>
                        </tr>
                    </thead>
                    <tbody data-target="id_input_${currentMaxId}">
                    </tbody>
                </table>
            </td>
        </tr>
        `;

        $('#inputContainer').append(newRow);
        $('#id_input_' + currentMaxId).on('input', handleInputChange); // Attach event to new input
    });
});
</script>

    

    <?php if ($errorMsg): ?>
        <div style="color: red;"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <table width="1000" border="1">
    <tbody id="inputContainer">
    <?php foreach ($inputIds as $key => $inputId): 
          $i = str_replace('id_input_', '', $key);
    ?>
        <tr>
            <td width="200">
        <form method="post" action="">
                    Enter Lecture ID <?php echo $i; ?>: 
		<input type="text" id="id_input_<?php echo $i; ?>" value="<?php echo $inputId; ?>">

        </form>
            </td>
            <td width="800">
                <?php if ($errorMsg): ?>
                    <div style="color: red;"><?php echo htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>

                <table border="0">
                    <thead>
                        <tr>
                            
                            <th>mp3_title</th>
                            <th>mp3_duration</th>
							<th>ID</th>
                        </tr>
                    </thead>
                    <tbody data-target="id_input_<?php echo $i; ?>">
                        <?php if (isset($cursors['id_input_' . $i])): ?>
                            <?php foreach ($cursors['id_input_' . $i] as $document): ?>
                                <tr>
                                    <td><input size="70" class="form-control text-center lec_name" type="text" name="lec_names[]" value="<?php echo htmlspecialchars($document->mp3_title); ?>" readonly=""></td>
									
									<td><input size="10" class="form-control text-center lec_duration" id="mp3_duration" type="text" name="lec_durations[]" value="<?php echo htmlspecialchars($document->mp3_duration); ?>" readonly=""></td>
                                    
									<td><input size="10" class="form-control text-center lec_id" id="id" type="text" name="ids[]" value="<?php echo htmlspecialchars($document->id); ?>" readonly=""></td>
					  </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<br>
<button id="addMore" type="button" class="button red">Add More</button>  
	<button id="totalDuration" type="button" class="button green">Total Duration: 00:00:00</button>
	
	<br><br>
	<label for="duration" class="label">Total Durations:</label>
        <input class="input" name="totalDuration2" id="totalDuration2" readonly=""><br><br>
	
	<label for="createdDate" class="label">Created Year &amp; Week</label>
		<input type="text" class="input" name="createdYear" placeholder="Year"><br><br>
        <input type="text" class="input" name="createdWeek" placeholder="Week"><br><br>
		
	<label for="dueDate" class="label">Due Year &amp; Week</label>
		<input type="text" class="input" name="dueYear" value="2025" readonly=""><br><br>
        <input type="text" class="input" name="dueWeek" value="52" readonly=""><br><br>

        <label for="reviewer" class="label">Reviewer:</label>
		<input name="reviewer" type="text" class="input is-static" value="<?php echo $userData->ftname." ". $userData->ltname; ?>" readonly>
		<br><br>
		
		
      </div>
          <hr>

          <div class="field grouped">
            <div class="control">
              <button type="submit" class="button green">
                Create Pack
              </button>
            </div>
            <div class="control">
              <button type="reset" class="button red">
                Reset
              </button>
            </div>
          </div>
        </form>
  </div>
</div>

    
  <footer class="footer">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
    <div class="flex items-center justify-start space-x-3">
      <div>
        © 2023, Lateefshooo
      </div>
     
    </div>
    
  </div>
</footer>
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
