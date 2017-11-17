

<div id="modal4" class="modal">

  <div class="modal-content" style="background: #26a69a;">
    <h4 class="modal-title" style="color: white; font-weight: 100">Add Batch Content</h4>

  		</div>




  <div class="row">
    <div class="col s12">
      <ul class="tabs" style="width: 103%; margin-left: -10px;">
        <li class="tab col s3"><a class="active" href="#test1" style="color: #229d9e;">Test 1</a></li>
        <li class="tab col s3"><a  href="#test2" style="color: #229d9e;">Test 2</a></li>
        <li class="tab col s3 disabled"><a href="#test3" style="color: #229d9e;">Disabled Tab</a></li>
        <li class="tab col s3"><a href="#test4" style="color: #229d9e;">Test 4</a></li>
      </ul>
    </div>

    <div id="test2" class="col s12"><strong>Files</strong>
    <!--   <ul id="names_list">
        <li>None</li> -->
         <p id="msg"></p>

      </ul></div>
      <div id="test3" class="col s12">Test 3</div>
      <div id="test4" class="col s12">Test 4</div>
    </div>

    <div class="modal-body">
      <div id="test1" class="col s12" class="active">
        <h5><p class="break-word" style="margin-left: 26px;">Destination folder: <?php echo fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH) ?></p></h5>
        <form action="" method="get" enctype="multipart/form-data">
          <input type="hidden" name="" value="save">
          <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">

          <p>
           <div class="col s12">


           </div>
           <div class="input-field col s6">
            <i class="material-icons prefix">insert_link</i>
            <input name="urlcon" id="icon_telephone" type="text" class="validate" required="" aria-required="true">
            <label for="icon_telephone">Url</label>
          </div>
          <div class="input-field col s6">
            <i class="material-icons prefix">timelapse</i>
            <input name="timebcon" id="icon_telephone" type="number" class="validate" required="" aria-required="true" min="1" max="50">
            <label for="icon_telephone">Preloader Duration</label>
            </div>
            <input type="file" id="file" name="file" />
       
           <input name="grp1" value="google.png" type="radio" id="r1">
  <label for="r1" style="margin-left: 15px;"><img src="http://localhost/test2storage/Assets/google.png" style="width:20%; "></label>
  <div style="margin-left: 260px; margin-top: -71px;">
  <input name="grp1" value="inquirer.png" type="radio" id="r2">
  <label for="r2"><img src="http://localhost/test2storage/Assets/inquirer.png" style="width:30%; "></label>





              <br>
              <p>
                <div class="modal-footer">
                   <button id="btn_next">Generate</button>
               

                 <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
               </p>



             </div>
             
           </div>
         </div>
       </div>
     </div>
   </div>
 </form>
 <script>  $(document).ready(function() {
  $('select').material_select();

  // for HTML5 "required" attribute
  $("select[required]").css({
   display: "inline",
   height: 0,
   padding: 0,
   width: 0
 });
});</script>


<script type="text/javascript">
  $("#btn_next").click(function(){

   $.ajax({
    url: "addbatchcontentprocess.php?generate_batch",
    method: "GET",
    data: {
     directory: $("input[name=urlcon]").val(),
     duration: $("input[name=timebcon]").val(),
     image: $("input[name=file]").val(),
     radio:  $("input[name=grp1]").val(),
     p: $("input[name=p]").val(),

   }
 }).done(function(msg){
			// $("#description").html(msg);
			$("#names_list").html(msg);
		})
});
</script>
 <script type="text/javascript">
            $(document).ready(function (e) {
                $('#btn_next').on('click', function () {
                    var file_data = $('#file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        url: 'addbatchcontentprocess.php', // point to server-side PHP script 
                        dataType: 'text', // what to expect back from the PHP script
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (response) {
                            $('#msg').html(response); // display success response from the PHP script
                        },
                        error: function (response) {
                            $('#msg').html(response); // display error response from the PHP script
                        }
                    });
                }); 
            });
        </script>

<script>
  $(document).ready(function() {
    $('ul.tabs').tabs();
    $("#btn_next").click(function() {
      $('ul.tabs').tabs('select_tab', 'test2');
    });
  });
</script>









