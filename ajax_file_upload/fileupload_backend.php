<?php
    /*
        Test ajax file upload backend PHP script

        advice:
            for quick setup when using PHP builtin server - in linux, set file
            upload temp directory using the following command
            `export TMPDIR=/path/to/tmp/dir`

        for more research on file uploads see:
            https://secure.php.net/manual/en/features.file-upload.php
        for session upload progress see:
            https://secure.php.net/manual/en/session.upload-progress.php
        youtube php-js image imagecrop
            https://www.youtube.com/watch?v=1lrwLc-5UXs
    */

    define('UPLOAD_DIR', '/loadfile/');

    // simbolizes input attribute `name` value => <input type="file" name="file">
    define('FILE_NAME_ATTR', 'file');



    /* respond to request */
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        $filesKeys = ['status' => 'error', 'status_message' => '$_FILES is not set'];
        $postKeys = ['status' => 'no keys set in the POST'];

        if(isset($_FILES)) {

            if(sizeof($_FILES) > 0) {

                $uf_error = $_FILES[FILE_NAME_ATTR]['error'];

                if($uf_error) { // list of errors codes => https://secure.php.net/manual/en/features.file-upload.errors.php
                    $filesKeys['status_message'] = 'file loaded to server, but with errors';
                    $filesKeys['error_code'] = $uf_error;

                } else { // UPLOAD_ERR_OK

                    $imagename = basename($_FILES[FILE_NAME_ATTR]['name']);
                    $filesKeys['saved_image_absolute_path'] = __DIR__ . UPLOAD_DIR . $imagename;
                    $filesKeys['saved_image_relative_path'] = UPLOAD_DIR . $imagename;
                    //if absolute file path is needed => realpath(UPLOAD_DIR . $imagename);
                    $filesKeys['image_disksize'] = $_FILES[FILE_NAME_ATTR]['size'];

                    $image_stat = getimagesize($_FILES[FILE_NAME_ATTR]['tmp_name']);
                    if($image_stat) {
                        $filesKeys['width_height'] = $image_stat[3];
                        $filesKeys['image_type'] = $image_stat[2];
                        $filesKeys['image_mime'] = $image_stat['mime'];
                    } else {
                        // TODO: here push failed operations
                        //array_push(...);
                    }

                    // move file from tmpdir and save
		    // TODO: check user permissions
                    move_uploaded_file($_FILES[FILE_NAME_ATTR]['tmp_name'],
                                       $filesKeys['saved_image_absolute_path']);

                   $filesKeys['status'] = 'success';
                   $filesKeys['status_message'] = 'successfull file upload';
                }

            } else {
                $filesKeys['status_message'] = '$_FILES size == 0';
            }
        }

        $postKeys = ['received_post_keys' => array_keys($_POST)];

        echo json_encode(['server_status' => 'OK',
                          'files' => $filesKeys,
                          'post' => $postKeys]);
        die();
    }

    /* invalid request method received */
    echo json_encode(['server_status' => 'error',
                      'error_message' => 'invalid request method received']);
?>
