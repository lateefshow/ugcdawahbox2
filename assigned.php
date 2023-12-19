
<html lang="en" dir="ltr">

<head>
    <!-- <meta http-equiv="refresh" content="1200;url=../../api.php?logout"> -->
    <meta charset="UTF-8">
    <title> Assigned</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
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

    <!-- Boxicons CDN Link -->
    <script src="tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">

    </script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../static/css/style1.css">
    <style>
        .trunc {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

            padding: 20px;
            margin: 0;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 20px;
            display: inline-block
        }
    </style>


</head>

<body>
    <?php include "navbar.php"; ?>
	
	
	<div>
    <section class="home-section ">
        <!-- Begin top nav bar -->
        <div style="padding-top:100px">
            <form method="" action="./" class="container-fluid justify-content-start">
                <a class="btn btn-outline-secondary me-2" type="button" href="unassigned.php" value="Active">Un-Assigned Contents</a>
                <a class="btn btn-outline-success me-2" type="button" href="assigned.php" value="Blocked">Assigned Contents</a>
                <a class="btn btn-outline-secondary me-2" type="button" href="new.php" value="Blocked">New Contents</a>
                <a class="btn btn-outline-secondary me-2" type="button" href="old.php" value="Active">Old Contents</a>
            </form>
        </div>
        <!-- End top nav bar -->
        <div class="card p-3" style="margin-top: 20px;">
            <form method="post" action="assigned.php">
                <div class="filter p-3">
                    <div class="p-2 start">
                        <label for="">Start Time</label>
                        <input class="form-control" style="max-width: 320px;" type="date" name="filter_start" value="<?php if (isset($_POST['filter_start'])) {
                                                                                                                            echo $_POST['filter_start'];
                                                                                                                        } else if (isset($_SESSION['startT'])) {
                                                                                                                            echo $_SESSION['startT'];
                                                                                                                        } else {
                                                                                                                            echo date('Y-m-d', strtotime('-1 week'));
                                                                                                                        } ?>">
                    </div>
                    <div class="p-2 end">
                        <label for="">End Time</label>
                        <input class="form-control" style="max-width: 320px;" type="date" name="filter_end" value="<?php if (isset($_POST['filter_start'])) {
                                                                                                                        echo $_POST['filter_end'];
                                                                                                                    } else if (isset($_SESSION['endT'])) {
                                                                                                                        echo $_SESSION['endT'];
                                                                                                                    } else {
                                                                                                                        echo date('Y-m-d');
                                                                                                                    } ?>">
                    </div>
                    <div class="p-2  filt">
                        <div>
                            <button id="filt_btn" class="btn btn-success">Filter</button>
                        </div>
                    </div>
                </div>
            </form>


            <?php
            $reslt = test();
            // echo json_encode($reslt[0][0]);
            // exit();
            if (empty($reslt[0])) {
            ?>
                <div class="card text-center">
                    Filter to get results
                </div>
            <?php
            } else {
                $i = 0;
                $result_data = $reslt[0];
                $mod_select = $reslt[1];

            ?>
                <div class="table-responsive">
                    <div style="font-size:13px;">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th scope="col">Packs</th>
                                    <th scope="col">Date created</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Contributor</th>
                                    <th scope="col">Reviewer</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                foreach ($result_data[0]['createdDate'] as $ind => $val) {

                                ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td>
                                            <a href="../pack/pack.php?assign&pack_id=<?php echo $result_data[0]["id"][$i] ?>" class="Tdbreck"><?php echo $result_data[0]["packName"][$i]; ?></a>
                                        </td>
                                        <td><?php echo $result_data[0]['createdDate'][$i]; ?></td>
                                        <td><?php echo $result_data[0]['duration'][$i]; ?></td>
                                        <td><?php echo $result_data[0]['Contributor'][$i]; ?></td>
                                        <td style="cursor: pointer;">
                                            <?php

                                            // if ($result_data[0]['status'][$i] == 'accepted' || $result_data[0]['status'][$i] == 'rejected') {
                                            if ($result_data[0]['status'][$i] != 'pending') {
                                                echo $result_data[0]['Reviewer'][$i];
                                            } else if ($result_data[0]['status'][$i] == 'pending') {
                                            ?>
                                                <span style="font-size:13px;" data-toggle="modal" data-target="#exampleModalCenter<?php echo $i; ?>"><span class="dot bg-primary"></span><?php echo $result_data[0]['Reviewer'][$i]; ?></span>
                                                <div class="modal fade" id="exampleModalCenter<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <form action="../../api.php" method="post">
                                                                <input type="hidden" name="lecid" value="<?php echo $result_data[0]['id'][$i]; ?>">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Re-assign to:
                                                                    </h5>
                                                                    <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <select class="form-control" name="reviewer">

                                                                        <?php foreach ($mod_select as $v) {
                                                                            if ($result_data[0]['contributorId'] != $v['id']) {
                                                                        ?>
                                                                                <option value="<?php echo $v['id']; ?>" <?php if ($v['id'] == $result_data[0]['Reviewer'][$i]) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>><?php echo $v['ftname']; ?>
                                                                                </option>
                                                                        <?php }
                                                                        } ?>
                                                                        <option value="<?php echo $_SESSION['id']; ?>" <?php if ($_SESSION['id'] == $result_data[0]['Reviewer'][$i]) {
                                                                                                                            echo 'selected';
                                                                                                                        } ?>>Admin</option>
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="assigned" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $result_data[0]['dueDate'][$i]; ?></td>
                                        <td>
                                            <?php
                                            if ($result_data[0]['status'][$i] == 'accepted') {
                                            ?>
                                                <span style="font-size:13px;"><span class="dot bg-success"></span> Accepted</span>
                                            <?php
                                            } else if ($result_data[0]['status'][$i] == 'pending') {
                                            ?>
                                                <span style="font-size:13px;"><span class="dot bg-primary"></span> Pending</span>
                                            <?php
                                            } else if ($result_data[0]['status'][$i] == 'needs Attention') {
                                            ?>
                                                <span style="font-size:13px;"><span class="dot bg-warning"></span> Needs Attention</span>
                                            <?php
                                            } else {
                                            ?>
                                                <span style="font-size:13px;"><span class="dot bg-danger"></span> Rejected</span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                <?php

                                    $i++;
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            <?php
            }
            ?>


        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="../static/js/script.js"></script>
    <script>
        text_tr("Tdbreck")
    </script>

	</div>
</body>

</html>