<?php



    // Upload
    if (isset($_POST['upl'])) {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }

        $errors = 0;
        $uploads = 0;
        $total = count($_FILES['upload']['name']);

        for ($i = 0; $i < $total; $i++) {
            $tmp_name = $_FILES['upload']['tmp_name'][$i];
            if (empty($_FILES['upload']['error'][$i]) && !empty($tmp_name) && $tmp_name != 'none') {
                if (move_uploaded_file($tmp_name, $path . '/' . $_FILES['upload']['name'][$i])) {
                    $uploads++;
                } else {
                    $errors++;
                }
            }
        }

        if ($errors == 0 && $uploads > 0) {
            fm_set_msg(sprintf('<div class="swal2-container swal2-fade swal2-shown" style="overflow-y: auto;"><div role="dialog" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-modal swal2-show" tabindex="-1" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 318px;"><ul class="swal2-progresssteps" style="display: none;"></ul><div class="swal2-icon swal2-error" style="display: none;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div><div class="swal2-icon swal2-question" style="display: none;">?</div><div class="swal2-icon swal2-warning" style="display: none;">!</div><div class="swal2-icon swal2-info" style="display: none;">i</div><div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: block;"><div class="swal2-success-circular-line-left" style="background: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip swal2-animate-success-line-tip"></span> <span class="swal2-success-line-long swal2-animate-success-line-long"></span><div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background: rgb(255, 255, 255);"></div></div><img class="swal2-image" style="display: none;"><h2 class="swal2-title" id="swal2-title">Uploaded !</h2><div id="swal2-content" class="swal2-content" style="display: block;">successfully uploaded in <b>%s</b>!</div><input class="swal2-input" style="display: none;"><input type="file" class="swal2-file" style="display: none;"><div class="swal2-range" style="display: none;"><output></output><input type="range"></div><select class="swal2-select" style="display: none;"></select><div class="swal2-radio" style="display: none;"></div><label for="swal2-checkbox" class="swal2-checkbox" style="display: none;"><input type="checkbox"></label><textarea class="swal2-textarea" style="display: none;"></textarea><div class="swal2-validationerror" id="swal2-validationerror" style="display: none;"></div><div class="swal2-buttonswrapper" style="display: block;"><a href="filemanager.php" class="swal2-confirm swal2-styled" aria-label="" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">OK</a><button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: none; background-color: rgb(170, 170, 170);">Cancel</button></div><button type="button" class="swal2-close" style="display: none;">×</button></div></div>', $path ));
        } elseif ($errors == 0 && $uploads == 0) {
            fm_set_msg('<div class="swal2-container swal2-fade swal2-shown" style="overflow-y: auto;"><div role="dialog" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-modal swal2-show" tabindex="-1" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 317px;"><ul class="swal2-progresssteps" style="display: none;"></ul><div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: block;"><span class="swal2-x-mark swal2-animate-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div><div class="swal2-icon swal2-question" style="display: none;">?</div><div class="swal2-icon swal2-warning" style="display: none;">!</div><div class="swal2-icon swal2-info" style="display: none;">i</div><div class="swal2-icon swal2-success" style="display: none;"><div class="swal2-success-circular-line-left" style="background: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span><div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background: rgb(255, 255, 255);"></div></div><img class="swal2-image" style="display: none;"><h2 class="swal2-title" id="swal2-title">Oops...</h2><div id="swal2-content" class="swal2-content" style="display: block;">Nothing Uploaded!</div><input class="swal2-input" style="display: none;"><input type="file" class="swal2-file" style="display: none;"><div class="swal2-range" style="display: none;"><output></output><input type="range"></div><select class="swal2-select" style="display: none;"></select><div class="swal2-radio" style="display: none;"></div><label for="swal2-checkbox" class="swal2-checkbox" style="display: none;"><input type="checkbox"></label><textarea class="swal2-textarea" style="display: none;"></textarea><div class="swal2-validationerror" id="swal2-validationerror" style="display: none;"></div><div class="swal2-buttonswrapper" style="display: block;"><a href="filemanager.php" class="swal2-confirm swal2-styled" aria-label="" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">OK</a><button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: none; background-color: rgb(170, 170, 170);">Cancel</button></div><button type="button" class="swal2-close" style="display: none;">×</button></div></div>');
        } else {
            fm_set_msg(sprintf('Error while uploading files. Uploaded files: %s', $uploads), 'error');
        }

        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
       










?>