<?php
    /* Lowlevel Ajax response creation backend script */


    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(isset($_POST['query_str'])) {

            if($_POST['query_str'] == "array") {

                $data = ['WordPress', 'Joomla!', 'Jango', 'MoinMoin',
                         'OpenCMS', 'Jahia', 'dotCms', 'Umbraco',
                         'FosWiki', 'Composer CMS', 'Drupal', 'Magento',
                         'ProcessWire', 'Microweber', 'XOOPS'];
            }
            elseif($_POST['query_str'] == "object") {

                $data = [
                        'name' => 'Joomla!',
                        'creation_year' => '2005',
                        'language' => 'PHP',
                        'webpage' => 'www.joomla.org',
                        'type' => 'content management system'
                        ];
            }
            else {
                $data = "Caller error: Invalid query string received";
            }

        } // end if(isset($_POST['query_str']...
        else {
            $data = "Caller error: No query string received";
        }

        echo json_encode($data);
        die();
    } // end if($_SERVER['REQUEST_METHOD']...

    echo json_encode(["Caller error: Invalid request method received. Expecting POST"]);

?>
