<!DOCTYPE html>
<html lang="en">
      <head>
      <title>Wink</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Photo Gallery">
      <meta name="keywords" content="PHP, Photography">
      <meta name="author" content="Fenimore">
      <link rel="icon" href="favicon.ico">
      <link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
      <!-- jQuery library -->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
      <!-- Latest compiled JavaScript -->
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
      <style>
      </style>
      </head>
      <body>


<div class="container">
  <div class="row text-center">
<?php
$gallery = null;
$spaces = array("-", "_");
if ( !empty($_GET['gallery'])) {
    $gallery = $_REQUEST['gallery'];
    $category = $_REQUEST['category'];
}
$gallerytitle = str_replace($spaces, " ", $gallery);
echo '<h1 class="title"> <a href=index.php>'. ucfirst($gallerytitle) .'</a></h1>';
echo '<div class="col-md-1 col-md-offset-1">';
echo '<a href=# onclick="prev()"';
echo ' class="nav-control"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>';
echo '<br><br>';
// Navigation
// TODO: add zoom/fullscreen
echo '<a href="index.php" class="nav-control"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
echo '<br>';
$download = 'zip.php?category='. $category .'&gallery='.$gallery;
echo '<a href='.$download.' class="nav-control"><span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span></a>';
echo '</div>';
//if(null==$gallery or null==$category) {
//    echo 'ERROR: gallery and/or category parameters not specified.';
// }
$path = 'media/'. $category . '/' . $gallery . '/';
$images = glob($path."*.{[jJ][pP][gG],gif,jpeg,svg,bmp,png}", GLOB_BRACE);
$thumbnails = glob($path.'thumbnails/'."*.{[jJ][pP][gG],gif,jpeg,svg,bmp,png}", GLOB_BRACE);
//z$info = pathinfo($image);
$src = 'view.php?category=' . $category . '&gallery=' . $gallery . '&index=';
$title = str_replace($spaces, " ", $info['filename']);
$size = sizeof($images);
echo '<div class="col-md-8">';
echo '<img src="#" id="image" alt="" width="auto" height="auto" class="img-responsive center-block" >';
echo '<br><div class="image-title">' . $title . '</div>';
echo '</div>'; // Col
// Next Picture
echo '<div class="col-md-1 text-right" style="z-index:100">';
echo '<a href=# onclick="next()"';
echo ' class="nav-control"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>';
echo '<br><br>';
echo '<img src="loading.gif" style="display:none;"; id="loading" alt="" width="auto" height="auto" >';
echo '  </div>'; // Col
echo '</div>'; //<!-- Row -->
echo '<div id="slider" class="row">';
foreach($thumbnails as $key=>$val) {
    echo '<div class="col-md-1"';
    if ($key > 11) { // bootstrap column
        echo ' style="display:none" ';
    }
    echo '>';
    // Thumbnail should only be visible if...
    echo '  <img src="'.$val.'" class="thmb" id="thumbnail-'.$key;
    echo '" alt="" width="auto" height="auto"';
    echo ' class="img-responsive center-block" >';
    echo '</div>';// col
}

echo '</div>'; // Row
?>

</div><!-- Container -->

<script type="text/javascript">
      var index = 0;
      var size = "<?php echo $size ?>";
      var thumbs = $('thmb');

      function next() {
          console.log('next');
          index = checkBounds(index, size, 1);
          getImage();
          var $active = $('div#slider IMG.active');
          var $next = $active.next();
          if($active.is(':last-child')){
              $next = $('#slider img:first-child');
          }
          $next.addClass('active');
          // Change attribute to zoom
          $active.removeClass('active');
      }
      function prev() {
          console.log('prev');
          index = checkBounds(index, size, -1);
          getImage();
          var $active = $('div#slider IMG.active');
          var $prev = $active.prev();
          if($active.is(':first-child')){
              $prev = $('#slider img:last-child');
          }
          $prev.addClass('active');
          // do something
          $active.removeClass('active');
      }

function checkBounds(idx, sze, inc) {
    idx += inc;
    if (idx == sze) {
        return 0;
    } else if (idx == -2) {
        return sze - 2;
    } else {
        return idx;
    }
}


function getImage() {
    // TODO: add loading
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("image").src = this.responseText;
                document.getElementById("loading").display = "none";
                var $active = $('div#slider IMG.active');
                $active.removeClass('active');
                thumbs[index].addClass('active');
            }
        };
        xmlhttp.open("GET","<?php echo $src ?>" + index, true);
        xmlhttp.send();
        document.getElementById("loading").display = "block";

}

getImage();

</script>
</body>

</html>
