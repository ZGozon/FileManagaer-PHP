     

     <div id="modal2" class="modal">
     <div class="modal-content" style="background: #26a69a;">
     <h4 class="modal-title" style="color: white; font-weight: 100">Add Content</h4>
     </div>
     <div class="modal-body">
     <h5><p class="break-word" style="margin-left: 26px;">Destination folder: <?php echo fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH) ?></p></h5>
     <form action="" method="post" enctype="multipart/form-data">
     <input type="hidden" name="action" value="save">
     <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
     <div class="row">

      <div class="col s12">
         
      <div class="input-field col s6">
      <i class="material-icons prefix">title</i>
      <input name="namecon" id="icon_prefix" type="text" class="validate" required="" aria-required="true">
      <label for="icon_prefix">Title</label>
      </div>
      <div class="input-field col s6">
      <i class="material-icons prefix">insert_link</i>
      <input name="urlcon" id="icon_telephone" type="text" class="validate" required="" aria-required="true">
      <label for="icon_telephone">Url</label>
      </div>
      <div class="row">
      <div class="input-field col s6">
      <i class="material-icons prefix">movie</i>
      <select name="choose" required="" aria-required="true">
      <option value=""  disabled selected>Choose file type</option>
      <option value="html">html</option>
      <option value="mp4">mp4</option>
      <option value="swf">swf</option>
      </select>
      <label>Select</label>
      </div>
      <div class="input-field col s6">
      <i class="material-icons prefix">timelapse</i>
      <input name="timecon" id="icon_telephone" type="number" class="validate" required="" aria-required="true" min="1" max="50">
      <label for="icon_telephone">Preloader Duration</label>
      </div>
      <div class="file-field input-field">
      <div class="input-field col s6" style="margin-left: 1px;">
      <i class="material-icons prefix">image</i>
      <div class="btn" style="margin-left:44px;">
      <span>Browse</span>
      <input type="file" name="imgcon" value="lodi">
      </div>
      <div class="file-path-wrapper">
      <input class="file-path validate" type="text"  placeholder="Image preloader">
      </div>
      </div></div><div class="input-field col s6" style="margin-top: -56px; margin-left: 460px;"><h7>or select from available images below</h7></div>
      <div>
      <p>
   <!--    <input name="group1" value="google.png" type="radio" id="test1" />
      <label for="test1" style="margin-left: 15px;"><img src="http://localhost/test2storage/Assets/google.png" style="width:20%; "></label>
      <div style="margin-left: 260px; margin-top: -71px;">
      <input name="group1" value="inquirer.png" type="radio" id="test2" />
      <label for="test2"><img src="http://localhost/test2storage/Assets/inquirer.png" style="width:30%; "></label></div></div> -->
      <input name="grp1" value="google.png" type="radio" id="r1">
  <label for="r1" style="margin-left: 15px;"><img src="http://localhost/test2storage/Assets/google.png" style="width:20%; "></label>
  <div style="margin-left: 260px; margin-top: -71px;">
  <input name="grp1" value="inquirer.png" type="radio" id="r2">
  <label for="r2"><img src="http://localhost/test2storage/Assets/inquirer.png" style="width:30%; "></label>
</div>

 
      </p>
      </div>
      </div>
      <br>
      <p>
                <div class="modal-footer">
                   <!--  <a href="landingpage.php?p=<?php echo urlencode(FM_PATH) ?>"> --><button class="btn" ><i class="icon-apply"></i> Upload</button></a> &nbsp;
                    <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
                </p>

           

    </div>
    
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