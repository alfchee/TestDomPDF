<?php

// import the data
require_once 'data/moviesData.php';
require_once 'lib//models/CommentModel.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instacolin | PDF Generator</title>

     <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Begin of menu -->
    <nav class="navbar navbar-inverse navbar fixed top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand">Instacolin</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/">PDF</a></li>
                    <li class="active"><a href="ajaxtest.php">AJAX Test</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End of menu -->

    <!-- Begin of Content -->
    <section class="container">
        <div class="row">
            <h1>Good Movies</h1>
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th width="20%">Name</th>
                            <th width="30%">Argument</th>
                            <th class="notes" width="25%">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($movies as $movie): ?>
                            <?php 
                                $comment = CommentModel::findByMovieId($movie['id']);
                            ?>
                            <tr data-movie-id="<?php echo $movie['id'] ?>" data-comment-id="<?php echo $comment->id ?>">
                                <td><a href="<?php echo $movie['url'] ?>" target="blank"><?php echo $movie['name'] ?></a></td>
                                <td><?php echo $movie['argument'] ?></td>
                                <td class="notes text-center">
                                    <div class="note-edition form-group">
                                        <textarea class="form-control"><?php echo $comment->comment ?></textarea>
                                        <input class="save-note btn btn-primary btn-xs" value="Save"/>    
                                    </div>
                                    <?php if($comment->id): ?>
                                        <p class="note-text"><?php echo $comment->comment ?></p>
                                    <?php else: ?>
                                        <a href="#" class="add-note btn btn-primary">Add Note</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- End of Content -->

    <!-- Vendor Scripts -->
    <script type="text/javascript" src="js/jquery.min.js" ></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>