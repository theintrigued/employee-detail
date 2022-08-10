<?php
    require_once('wp-load.php'); // add wordpress functionality
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    if(isset($_POST['description'])){ 

      $user_login = $_POST['username'];
      $user_password = $_POST['pwd'];
      $jobValue = $_POST['job'];
      $shortDecription = $_POST['description'];
      $firstName = $_POST['firstname'];
      $lastName = $_POST['lastname'];
      $gitHub = $_POST['github'];
      $linkedin = $_POST['linkedin'];
      $xing = $_POST['xing'];
      $facebook = $_POST['facebook'];

      //echo $user_login . " + " . $user_password . " + " . $jobValue ;
      
        global $wpdb;
        $upload_dir = wp_upload_dir();
            $path = $upload_dir['path'] . "/07"; // upload directory
            $files = $_FILES['image'];
            //print_r ($files);
            
            $valid_extensions = array('jpeg', 'jpg', 'png');
            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];
            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            // can upload same image using rand function
            $final_image = rand(1000,1000000).$img;
            // check's valid format
            if(in_array($ext, $valid_extensions)) 
            { 
            $path = $path.strtolower($final_image); 
            if(move_uploaded_file($tmp,$path)) 

            {
            //file uploaded success
            //echo "<img src='$path' />";
            $attachment = array(
                'post_mime_type' => $ext,
                'post_title' => $img,
                'post_content' => '',
                'post_status' => 'inherit'
              );

            $attach_id = wp_insert_attachment( $attachment, $path );
            echo $attach_id;
            $attach_data = wp_generate_attachment_metadata( $attach_id, $path );
            wp_update_attachment_metadata( $attach_id, $attach_data );

                  }
                  else{
                    echo "Not Uploaded" ;
                  }
                }
                else{
                    echo "Not Uploaded" ;
                  }



                  $credentials = array();
                  $credentials['user_login'] = $user_login;
                  $credentials['user_password'] = $user_password;
                  $credentials['remember'] = true;           
                    
                          //check if username exists
                          $doesUsernameExist = username_exists( $user_login );
                          
                          //if does not exist  (returned false if user not found)
                          if ($doesUsernameExist == false) {
                              
                              $newUser  = wp_create_user( $user_login, $user_password, $email = '' ) ;
                              $currentUserId = $newUser;
                              if($currentUserId != 0) {
                                update_user_meta( $currentUserId, "first_name",  $firstName) ;
                                update_user_meta( $currentUserId, "last_name",  $lastName) ;
                                update_user_meta( $currentUserId, "description",  $shortDecription) ;

                                  if(metadata_exists( 'user', $currentUserId, 'AvatarURL' )) {
                                     
                                      $user = wp_signon($credentials);
                                      wp_set_auth_cookie($user->ID, true);
                                  }
                                  else {
                                      add_user_meta( $currentUserId, 'avatarImageId', $attach_id);
                                      add_user_meta( $currentUserId, 'positionAtCompany', $jobValue);
                                      add_user_meta( $currentUserId, 'githubLink', $gitHub);
                                      add_user_meta( $currentUserId, 'linkedinLink', $linkedin);
                                      add_user_meta( $currentUserId, 'facebookLink', $facebook);
                                      add_user_meta( $currentUserId, 'xingLink', $xing);
                                      $user = wp_signon($credentials);
                                      wp_set_auth_cookie($user->ID, true);
                                      echo "";
                                  }
                              }
                             
                              if ( is_wp_error($newUser) ) {
                                  echo $newUser->get_error_message();
                                  die();
                               }
                               else {
                                  
                                  exit();
                               }
                          }
                        
            }
          





