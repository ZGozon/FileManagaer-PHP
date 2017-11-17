<div id="modal3" class="modal">
        <div class="modal-content" style="background: #26a69a;">


              <h4 class="modal-title" style="color: white; font-weight: 100">New Folder</h4>
            </div>
              <div class="modal-body">

            <h5><p class="break-word" style="margin-left: 26px;">Destination folder: <?php echo fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH) ?></p></h5>
            <form action="" method="post" name="myForm" onsubmit="return validateForm()" enctype="multipart/form-data">
                <input type="hidden" name="" value="save">
                <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
       <div class="row">
    
        <div class="input-field col s6">
          <i class="material-icons prefix">folder</i>
          <input id="icon_prefix" type="text" name="newfold" class="validate" required="" aria-required="true">
          <label for="icon_prefix">Folder Name</label>
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
    