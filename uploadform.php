
    <div id="modal1" class="modal">
    <div class="modal-content" style="background: #26a69a;"">


    <h4 class="modal-title" style="color: white; font-weight: 100" >Upload Files</h4>
    </div>
    <div class="modal-body">

    <h5><p class="break-word" style="margin-left: 10px;">Destination folder: <?php echo fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH) ?></p></h5>
    <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
    <input type="hidden" name="upl" value="1">
                
    <div class="file-field input-field" style="width: 60%; margin-left: 10px;">
    <div class="btn">
    <span>File</span>
    <input type="file" id="files" multiple="" name="upload[]"" onChange="makeFileList();"  required="" aria-required="true"/><br>
    </div>
    <div class="file-path-wrapper">
    <input class="file-path validate" type="text" placeholder="Upload one or more files">
    </div>
    </div>
    <div style="margin-left: 10px;">
    <p>
    <strong>Files You Selected:</strong>
    </p>
    <ul id="fileList"><li>No Files Selected</li></ul>
    </div>
    <p>
    <div class="modal-footer">
    <button class="btn"><i class="icon-apply"></i> Upload</button> &nbsp;
    <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
    </p>
    </div>
    </div>
    </div>
    </div>
    </div>
    </form>