<?php

session_start();
include "dbConnection.php";

$email = $_SESSION['email'];
if (!(isset($_SESSION['email'])))
	header("location:index.php");

$res = mysqli_query($con, "SELECT * FROM admin where email='$email'");
$rowcount = mysqli_num_rows($res);
if ($rowcount < 1) {
	echo "alert('You don't have access to this page!')";
	header("location:index.php");
}


$path=$_GET["path"];
$candiname = $_GET["candiname"];

$folder = "captures/".substr($path,0,-4)."/";
// echo $folder;
$dirname = $folder;
$images = glob($dirname."*.png");
$audio = "captures/audio/".($path);


?>

 <!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin-Show Captures</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
	
<style>
* {
  box-sizing: border-box;
}
body{
	background: whitesmoke;
}
.btn:focus, .btn:active, button:focus, button:active {
  outline: none !important;
  box-shadow: none !important;
}

#image-gallery .modal-footer{
  display: block;
}

.thumb{
  margin-top: 15px;
  margin-bottom: 15px;
}

</style>
</head>
<body>

<h1 align="center">Captured Images of :  <?php echo $candiname; ?></h1>
<h3></h3>
<br>
<br>
<br>
<div class="container">
			&nbsp;&nbsp;<h4 align="center">Here is Audio Recording,</h4><br>

	<div class="row">
		<div class="col-md-2 col-md-offset-4">
			<audio controls preload="auto">
			<source src="<?php echo $audio ?>" type="audio/mpeg">
			</audio>
	</div>
	</div>



		<div class="row">
	  	      
      <?php  foreach($images as $image): ?>

            <div class="col-lg-3 col-md-6 col-xs-6 col-lg-offset-1 thumb">
                <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-title="" data-image="<?php echo $image ?>"
                   data-target="#image-gallery">
                    <img class="img-thumbnail" src="<?php echo $image ?>" alt="Another alt text">
                </a>
            </div>
    	<?php endforeach; ?>

	        </div>


        <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="image-gallery-title"></h4>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img id="image-gallery-image" class="img-responsive col-xs-12" src="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary float-left" id="show-previous-image"><i class="fa fa-arrow-left">PREVIOUS</i>
                        </button>

                        <button type="button" id="show-next-image" class="btn btn-primary float-right"><i class="fa fa-arrow-right">NEXT</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>	

<script type="text/javascript">
	
	let modalId = $('#image-gallery');

$(document)
  .ready(function () {

    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#show-previous-image, #show-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#show-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#show-previous-image')
          .hide();
      }
    }



    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#show-next-image, #show-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
          .text($sel.data('title'));
        $('#image-gallery-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }
  });

// build key actions
$(document)
  .keydown(function (e) {
    switch (e.which) {
      case 37: // left
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-previous-image').is(":visible")) {
          $('#show-previous-image')
            .click();
        }
        break;

      case 39: // right
        if ((modalId.data('bs.modal') || {})._isShown && $('#show-next-image').is(":visible")) {
          $('#show-next-image')
            .click();
        }
        break;

      default:
        return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
  });

</script>

</body>
</html>
