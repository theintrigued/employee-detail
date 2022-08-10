

jQuery(document).ready(function($) {


     $('.submitButton12').on('click', function() {
        var uname = $('#usernameInput101').val();
        var pass = $('#passwordInput101').val();
        var job2 = $('#jobInput101').val();
        var description2 = $('#shortDescription').val();
        var firstname = $('#firstName').val();
        var lastname = $('#lastName').val();
        var github = $('#gitHub').val();
        var linkedin = $('#linkedIn').val();
        var xing = $('#xing').val();
        var facebook = $('#faceBook').val();

        var urlWithVar = 'http://localhost/employeeRegistrationBackend.php';
        var file_data = $('#avatarImage').prop('files')[0];  

        var form_data = new FormData();                  
        form_data.append('image', file_data);
        form_data.append('username', uname);
        form_data.append('pwd', pass);
        form_data.append('job', job2);
        form_data.append('description', description2);
        form_data.append('firstname', firstname);
        form_data.append('lastname', lastname);
        form_data.append('github', github);
        form_data.append('linkedin', linkedin);
        form_data.append('xing', xing);
        form_data.append('facebook', facebook);
        alert(description2);

                                  
        $.ajax({
            url: urlWithVar , // <-- point to server-side PHP script 
            dataType: 'text',  // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(php_script_response){
                alert(php_script_response); // <-- display response from the PHP script, if any
            }
         });
    });







});


