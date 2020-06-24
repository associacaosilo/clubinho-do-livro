<?php
/**
 * Template Name: Book-Page layout
 * Template Post Type: book, page
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		}
	}

	?>

</main><!-- #site-content -->
	<div id="gravador">
		<button id="startRecord"><img src="assets/img/botao_gravar.png" alt="GRAVAR"></button>
		<button id="stopRecord"> <img src="assets/img/botao_pare.png" alt="PARAR"> </button>
	</div>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>

<script>  
  var recordButton = document.getElementById('startRecord');
  var stopButton = document.getElementById('stopRecord');
  recordButton.addEventListener("click", startRecording);
  stopButton.addEventListener("click", stopRecording);
  debug = document.getElementById("debugText");
  var recording = false;

    
  recordButton.disabled = false;
  stopButton.disabled = true;
  
  var handleSuccess = function(stream) {

    console.log("Sucess");

    var context = new AudioContext();
    var input = context.createMediaStreamSource(stream);
    var processor = context.createScriptProcessor(1024,1,1);
    
    //input.connect(processor);
    processor.connect(context.destination);
    
    const options = {
      mimeType: "video/webm;codecs=vp9" //ESCOLHER FORMATO IDEAL
    };
    var recordedChunks = [];
    
    
    recorder = new MediaRecorder(stream, options);
    
    recorder.onstart = function(e) {
      console.log("Started");
    }

    recorder.ondataavailable = function(e) {
      if (e.data.size > 0) {
        recordedChunks.push(e.data);
      }
    };

    recorder.onstop = function() {
      debug.innerHTML = "STOPED";
      recording = false;
      //new Blob(recordedChunks);
      //FAZER O QUE FOR NECESSÁRIO COM OS AUDIOS
    };
    
    if(recording) {
      console.log("Recording...");
      recorder.start();
    }
  };
  
  function startRecording() {
    recordButton.disabled = true;
    stopButton.disabled = false;

    recording = true;
    
    navigator.mediaDevices.getUserMedia({ audio: true, video: false })
        .then(handleSuccess)
  }
  
  function stopRecording() {
    stopButton.disabled = true;
    recordButton.disabled = false;
    console.log("Asked to stop");
    
    recorder.stop();
  }
</script>
